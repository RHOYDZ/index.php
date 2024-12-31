<?php
session_start(); // Start the session to access the logged-in user

// Database connection
$conn = new mysqli("localhost", "root", "", "your_database");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data from the session (assuming the username is stored in session)
$user_username = $_SESSION['username']; // Replace with actual session variable

// Query to get user data, including the email
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_username);
$stmt->execute();
$result = $stmt->get_result();

// If user data is found
if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $name = $user_data['name'];
    $zone = $user_data['zone'];
    $street = $user_data['street'];
    $house_number = $user_data['house_number'];
    $birthday = $user_data['birthday'];
    $email = $user_data['username']; // Fetch the email from the database

    // Calculate age based on birthday
    $age = date_diff(date_create($birthday), date_create('today'))->y;
} else {
    echo "<script>alert('User not found.'); window.location.href='login.php';</script>";
    exit;
}

// Generate reference code
function generateReferenceCode() {
    return strtoupper(substr(md5(time()), 0, 10));
}
$reference_code = generateReferenceCode(); // Generate it here

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form values
    $payment_method = $_POST['payment_method'];
    $purpose = $_POST['purpose'];

    // Determine reference code based on payment method
    if ($payment_method == 'gcash') {
        $reference_code = generateReferenceCode(); // Generate reference code if GCash is selected
    } elseif ($payment_method == 'barangay_hall') {
        $reference_code = $_POST['reference_code']; // Use the reference code provided by the user
    }

    // Insert the data into the certificate_indigency table, including the email
    $stmt = $conn->prepare("INSERT INTO certificate_indigency (name, zone, street, house_number, age, purpose, payment_method, reference_code, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssissss", $name, $zone, $street, $house_number, $age, $purpose, $payment_method, $reference_code, $email);

    if ($stmt->execute()) {
        // Show appropriate message based on payment method
        if ($payment_method == 'gcash') {
            echo "<script>alert('Print the form and go to the barangay hall to authenticate.'); window.location.href='download_pdf.php';</script>";
        } else {
            echo "<script>alert('Your reference code is: $reference_code. Go to the barangay hall to get the form and prepare 140 PHP.'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Indigency</title>
    <link rel="stylesheet" href="certificate_indigency.css">
    <script>
        function toggleReferenceCode() {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const referenceCodeInput = document.getElementById('reference_code');
            const autoGeneratedCode = document.getElementById('auto_generated_code');

            if (paymentMethod === 'barangay_hall') {
                referenceCodeInput.value = "<?php echo $reference_code; ?>";
                autoGeneratedCode.style.display = 'block';
                referenceCodeInput.readOnly = true;
            } else {
                referenceCodeInput.value = "";
                autoGeneratedCode.style.display = 'none';
                referenceCodeInput.readOnly = false;
            }
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <img src="../pic/logo2.png" alt="Logo">
        <p>Barangay Indigency</p>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
            </ul>
        </nav>
    </div>
    <div class="content">
        <div class="form-container">
            <h1>Certificate of Indigency</h1>
            <form method="POST">
                <p>
                    <input type="radio" id="paygcash" name="payment_method" value="gcash" onchange="toggleReferenceCode()" required>
                    <label for="paygcash">Pay on GCash (₱100)</label>
                </p>
                <p>
                    <input type="radio" id="paybarangay" name="payment_method" value="barangay_hall" onchange="toggleReferenceCode()" required>
                    <label for="paybarangay">Pay in Barangay Hall (₱140)</label>
                </p>

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>

                <label for="zone">Zone:</label>
                <input type="text" id="zone" name="zone" value="<?php echo $zone; ?>" required>

                <label for="street">Street:</label>
                <input type="text" id="street" name="street" value="<?php echo $street; ?>" required>

                <label for="house_number">House Number:</label>
                <input type="text" id="house_number" name="house_number" value="<?php echo $house_number; ?>" required>

                <label for="age">Age:</label>
                <input type="number" id="age" name="age" value="<?php echo $age; ?>" readonly>

                <label for="purpose">Purpose:</label>
                <input type="text" id="purpose" name="purpose" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" readonly>

                <label for="reference_code">Reference Code:</label>
                <input type="text" id="reference_code" name="reference_code" readonly>
                <div id="auto_generated_code">This is your reference code. Copy this.</div>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>

