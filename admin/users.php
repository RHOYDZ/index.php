<?php
// Database connection
$host = 'localhost';
$dbname = 'your_database';
$username = 'root';  // Default XAMPP username
$password = '';  // Default XAMPP password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle user update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $contact_number = $_POST['contact_number'];
    $new_password = $_POST['new_password'];  // New password field
    $zone = $_POST['zone'];
    $mothers_name = $_POST['mothers_name'];
    $fathers_name = $_POST['fathers_name'];
    $birthday = $_POST['birthday'];

    // Prepare query to update user info
    if (!empty($new_password)) {
        // If a new password is provided, hash it
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_query = "UPDATE users SET name=?, username=?, contact_number=?, password=?, zone=?, mothers_name=?, fathers_name=?, birthday=? WHERE id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssssssi", $name, $username, $contact_number, $hashed_password, $zone, $mothers_name, $fathers_name, $birthday, $id);
    } else {
        // If no new password is provided, update the info without changing the password
        $update_query = "UPDATE users SET name=?, username=?, contact_number=?, zone=?, mothers_name=?, fathers_name=?, birthday=? WHERE id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssssssi", $name, $username, $contact_number, $zone, $mothers_name, $fathers_name, $birthday, $id);
    }

    // Execute the query
    if ($stmt->execute()) {
        // Do not echo success message anymore
        // echo "User information updated successfully!";
    } else {
        // You can handle the error here if needed
        // echo "Error: " . $stmt->error;
    }
}


// Handle search functionality
$search = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $search_query = "SELECT * FROM users WHERE name LIKE ? OR username LIKE ?";
    $stmt = $conn->prepare($search_query);
    $search_term = "%" . $search . "%";
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Default query if no search is performed
    $result = $conn->query("SELECT * FROM users");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Search and Edit</title>
    <link rel="stylesheet" href="users.css">
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="../pic/logo2.png" alt="Logo" class="sidebar-logo">
        <ul class="sidebar-menu">
            <li><a href="mesages.php" class="sidebar-link">Messages</a></li>
            <li><a href="index.php" class="sidebar-link">Dashboard</a></li>
            <li><a href="users.php" class="sidebar-link">Users</a></li>
        </ul>
        <div class="logout">
            <a href="../frontend/login.php" class="logout-link">Logout</a>
        </div>
    </div>

    <div class="main-content">
        <h1 class="page-title">Search Users</h1>

        <!-- Search form -->
        <form method="post" action="users.php" class="search-form">
            <input type="text" name="search" placeholder="Search by name or username" value="<?php echo htmlspecialchars($search); ?>" class="search-input" required>
            <button type="submit" class="search-button">Search</button>
        </form>

        <!-- User Table -->
        <table class="user-table" border="1">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['name']}</td>
                                <td>{$row['username']}</td>
                                <td>
                                    <button class='edit-button' onclick=\"openEditModal({$row['id']}, '{$row['name']}', '{$row['username']}', '{$row['contact_number']}', '{$row['password']}', '{$row['zone']}', '{$row['mothers_name']}', '{$row['fathers_name']}', '{$row['birthday']}')\">Edit</button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No results found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Edit Modal -->
        <div id="editModal" class="edit-modal" style="display:none;">
            <h2>Edit User</h2>
            <form method="post" action="users.php" class="edit-form" onsubmit="return validateForm()">
                <input type="hidden" name="id" id="id">
                
                <!-- Name Input with Uppercase -->
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required class="edit-input uppercase" oninput="capitalizeInput(this)"><br>

                <!-- Username Input (Email Validation) -->
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required class="edit-input"><br>

                <!-- Contact Number Input -->
                <label for="contact_number">Contact Number:</label>
                <input type="text" name="contact_number" id="contact_number" required class="edit-input"><br>

                <!-- New Password Input -->
                <label for="new_password">New Password (optional):</label>
                <input type="password" name="new_password" id="new_password" class="edit-input"><br>

                <!-- Zone Input -->
                <label for="zone">Zone:</label>
                <input type="text" name="zone" id="zone" required class="edit-input"><br>

                <!-- Mother's Name Input with Uppercase -->
                <label for="mothers_name">Mother's Name:</label>
                <input type="text" name="mothers_name" id="mothers_name" required class="edit-input uppercase" oninput="capitalizeInput(this)"><br>

                <!-- Father's Name Input with Uppercase -->
                <label for="fathers_name">Father's Name:</label>
                <input type="text" name="fathers_name" id="fathers_name" required class="edit-input uppercase" oninput="capitalizeInput(this)"><br>

                <!-- Birthday Input -->
                <label for="birthday">Birthday:</label>
                <input type="date" name="birthday" id="birthday" required class="edit-input"><br>

                <!-- Submit Button -->
                <button type="submit" name="update" class="edit-button">Update</button>
                <button type="button" onclick="closeEditModal()" class="edit-cancel-button">Cancel</button>
            </form>
        </div>

    </div>

    <script>
        // Function to automatically capitalize input for Name, Mother's Name, Father's Name
        function capitalizeInput(input) {
            input.value = input.value.toUpperCase();  // Make all letters uppercase
        }

        // Function to validate email format for the username
        function validateForm() {
            const email = document.getElementById('username').value;
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

            if (!emailPattern.test(email)) {
                alert('Please input a valid email address');
                return false;  // Prevent form submission if invalid
            }
            return true;  // Allow form submission if valid
        }

        // Function to open the edit modal and pre-fill the form fields
        function openEditModal(id, name, username, contact_number, password, zone, mothers_name, fathers_name, birthday) {
            document.getElementById('id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('username').value = username;
            document.getElementById('contact_number').value = contact_number;
            document.getElementById('new_password').value = ''; // Clear the password field for a new password
            document.getElementById('zone').value = zone;
            document.getElementById('mothers_name').value = mothers_name;
            document.getElementById('fathers_name').value = fathers_name;
            document.getElementById('birthday').value = birthday;

            document.getElementById('editModal').style.display = 'block';
        }

        // Function to close the edit modal
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>

    <style>
        /* CSS to make text uppercase */
        .uppercase {
            text-transform: uppercase;  /* Transform to uppercase */
        }
    </style>
</body>

</html>
