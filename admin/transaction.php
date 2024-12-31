<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "your_database");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search functionality
$searchQuery = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $searchQuery = "WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
}

// Fetch transactions based on search, ordered by created_at DESC
$sql = "SELECT id, name, age, email, paid, created_at FROM certificate_indigency $searchQuery ORDER BY created_at DESC";
$result = $conn->query($sql);

// Handle Update Paid Status
if (isset($_POST['update_paid'])) {
    $transactionId = intval($_POST['transaction_id']);
    $paidStatus = $_POST['paid_status'];

    // Validate inputs
    if (!empty($transactionId) && ($paidStatus === 'yes' || $paidStatus === 'no')) {
        // Update the paid status in the database
        $updateSql = "UPDATE certificate_indigency SET paid = ? WHERE id = ?";
        $stmt = $conn->prepare($updateSql);
        if ($stmt) {
            $stmt->bind_param("si", $paidStatus, $transactionId);
            if ($stmt->execute()) {
                // Redirect to transactions.php after updating the status
                header("Location: transaction.php");
                exit;
            } else {
                echo "Failed to update paid status. Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Failed to prepare statement. Error: " . $conn->error;
        }
    } else {
        echo "Invalid input. Please check the data.";
    }
}

// Fetch transaction details for display in modal (if needed)
$transactionDetails = [];
if (isset($_GET['view'])) {
    $transactionId = $_GET['view'];
    $detailSql = "SELECT * FROM certificate_indigency WHERE id = ?";
    $stmt = $conn->prepare($detailSql);
    $stmt->bind_param("i", $transactionId);
    $stmt->execute();
    $resultDetails = $stmt->get_result();
    $transactionDetails = $resultDetails->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Your CSS styling for layout */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 200px;
            height: 100vh;
            position: fixed;
            background-color: #2c3e50;
            padding-top: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 10px;
            text-align: center;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #ecf0f1;
            display: block;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
        }

        .content {
            margin-left: 220px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #2c3e50;
            color: white;
        }

        h1 {
            color: #34495e;
        }

        .table-row:hover {
            cursor: pointer;
            background-color: #f2f2f2;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            max-width: 600px;
            width: 100%;
        }

        .modal-content h2 {
            margin-top: 0;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li><a href="index.php">Home</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Transactions</h1>
        <form method="post">
            <input type="text" name="search" placeholder="Search by Name or Email" value="<?php echo isset($search) ? $search : ''; ?>">
            <button type="submit">Search</button>
        </form>
        <p>Below is the list of transactions:</p>

        <!-- Display the transaction table -->
        <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Email</th>
                    <th>Paid Status</th>
                    <th>Date</th> <!-- New column for Date -->
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): 
                    // Format the date
                    $formattedDate = date("F j, Y h:i A", strtotime($row['created_at'])); // Month as word, Time in 12-hour format
                ?>
                <tr class="table-row">
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['age']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <form method="post" action="transaction.php" style="display:inline;">
                            <input type="hidden" name="transaction_id" value="<?php echo $row['id']; ?>">
                            <select name="paid_status" onchange="this.form.submit()">
                                <option value="yes" <?php echo $row['paid'] == 'yes' ? 'selected' : ''; ?>>Paid</option>
                                <option value="no" <?php echo $row['paid'] == 'no' ? 'selected' : ''; ?>>Not Paid</option>
                            </select>
                            <input type="hidden" name="update_paid" value="1">
                        </form>
                    </td>
                    <td><?php echo htmlspecialchars($formattedDate); ?></td> <!-- Display formatted date -->
                    <td>
                        <button onclick="viewDetails(<?php echo $row['id']; ?>)">View Details</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>No transactions found.</p>
        <?php endif; ?>

        <!-- Modal for viewing transaction details -->
        <div class="modal" id="transactionModal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Transaction Details</h2>
                <p><strong>Name:</strong> <span id="modalName"></span></p>
                <p><strong>Zone:</strong> <span id="modalZone"></span></p>
                <p><strong>Street:</strong> <span id="modalStreet"></span></p>
                <p><strong>House Number:</strong> <span id="modalHouseNumber"></span></p>
                <p><strong>Age:</strong> <span id="modalAge"></span></p>
                <p><strong>Purpose:</strong> <span id="modalPurpose"></span></p>
                <p><strong>Payment Method:</strong> <span id="modalPaymentMethod"></span></p>
                <p><strong>Reference Code:</strong> <span id="modalReferenceCode"></span></p>
                <p><strong>Date Created:</strong> <span id="modalCreatedAt"></span></p> <!-- Display Date in modal -->
                <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                <p><strong>Paid Status:</strong> <span id="modalPaid"></span></p>
            </div>
        </div>
    </div>

    <script>
        function viewDetails(transactionId) {
            // Make an AJAX request to fetch the transaction details
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_transaction_details.php?id=' + transactionId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    document.getElementById('modalName').textContent = data.name;
                    document.getElementById('modalZone').textContent = data.zone;
                    document.getElementById('modalStreet').textContent = data.street;
                    document.getElementById('modalHouseNumber').textContent = data.house_number;
                    document.getElementById('modalAge').textContent = data.age;
                    document.getElementById('modalPurpose').textContent = data.purpose;
                    document.getElementById('modalPaymentMethod').textContent = data.payment_method;
                    document.getElementById('modalReferenceCode').textContent = data.reference_code;
                    document.getElementById('modalCreatedAt').textContent = data.created_at; // Date in modal
                    document.getElementById('modalEmail').textContent = data.email;
                    document.getElementById('modalPaid').textContent = data.paid;
                    document.getElementById('transactionModal').style.display = 'flex';
                }
            };
            xhr.send();
        }

        function closeModal() {
            document.getElementById('transactionModal').style.display = 'none';
        }
    </script>
</body>
</html>

<?php 
$conn->close();
?>
