<?php
session_start(); // Start the session to access the logged-in user

// Database connection settings
$host = 'localhost'; // Your database host
$dbname = 'your_database'; // Your database name
$username = 'root'; // Default XAMPP username is 'root'
$password = ''; // Default password is empty for 'root' user in XAMPP

// Create the connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming the logged-in user's username is stored in the session
// You can replace 'username' with 'user_id' if you're using user_id in the session
$user_username = $_SESSION['username']; // Store the logged-in user's username in session

// Query the database for the logged-in user's data
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_username); // Bind the username to the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch the user's data
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $age = date_diff(date_create($row['birthday']), date_create('today'))->y; // Calculate age from birthday
    $zone = $row['zone'];
    $street = $row['street'];
    $house_number = $row['house_number'];
    $created_at = date("F d, Y"); // Current date
} else {
    echo "No user found.";
    exit;
}

// Retrieve the most recent purpose based on the latest created_at from the certificate_indigency table
$sql_purpose = "SELECT purpose FROM certificate_indigency WHERE name = ? AND zone = ? AND street = ? AND house_number = ? ORDER BY created_at DESC LIMIT 1";
$stmt_purpose = $conn->prepare($sql_purpose);
$stmt_purpose->bind_param("ssss", $name, $zone, $street, $house_number); // Bind user details for the purpose
$stmt_purpose->execute();
$result_purpose = $stmt_purpose->get_result();

// Fetch the latest purpose
if ($result_purpose->num_rows > 0) {
    $row_purpose = $result_purpose->fetch_assoc();
    $purpose = $row_purpose['purpose']; // Get the latest purpose from the database
} else {
    echo "Purpose not found.";
    exit;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Indigency</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            padding: 40px;
            box-sizing: border-box;
            background: white;
            border: 2px solid #0288d1;
            position: relative;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 120px;
            margin-bottom: 10px;
        }
        .header h1,
        .header h2,
        .header h3 {
            margin: 5px 0;
        }
        .content {
            text-align: justify;
            line-height: 2;
            font-size: 18px;
        }
        .content p {
            margin: 20px 0;
        }
        .footer {
            position: absolute;
            bottom: 40px;
            text-align: center;
            width: 100%;
            font-size: 14px;
            color: #555;
        }
        .punong-barangay {
            margin-top: 50px;
            text-align: right;
            margin-right: 60px;
            font-size: 18px;
        }
        .actions {
            text-align: center;
            margin-top: 20px;
        }
        .actions button {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #0288d1;
            color: white;
            cursor: pointer;
        }
        .actions button:hover {
            background-color: #01579b;
        }
    </style>
</head>
<body>
    <div class="container" id="certificate">
        <div class="header">
            <img src="../pic/logo2.png" alt="Logo">
            <h3>Republic of the Philippines</h3>
            <h3>Province of Cagayan</h3>
            <h3>Municipality of Tuguegarao City</h3>
            <h1>BARRANGAY PALLUA NORTE</h1>
            <h3>Office of the Punong Barangay</h3>
            <h2>CERTIFICATION</h2>
        </div>
        <div class="content">
            <p>TO WHOM IT MAY CONCERN:</p>
            <p>
                This is to certify that <strong><?php echo $name; ?></strong>,
                <strong><?php echo $age; ?></strong> years old, is a resident of
                <strong><?php echo $zone; ?></strong>, Pallua Norte,
                <strong><?php echo $street; ?></strong>, House Number
                <strong><?php echo $house_number; ?></strong>, Tuguegarao City.
            </p>
            <p>
                This is to certify further that the said person mentioned above belongs to
                an indigent family in the barangay.
            </p>
            <p>
                This certification is being issued upon the request of the person named
                above for <strong><?php echo $purpose; ?></strong>.
            </p>
            <p>
                Given this <strong><?php echo $created_at; ?></strong> at Pallua Norte,
                Tuguegarao City.
            </p>
            <div class="punong-barangay">
                <strong>PUNONG BARANGAY</strong><br>
                Pallua Norte
            </div>
        </div>
        <div class="footer">
            <em>Do not accept if not authenticated.</em>
        </div>
    </div>
    <div class="actions">
        <button onclick="downloadPDF()">Download Form</button>
        <button onclick="window.print()">Print Form</button>
    </div>
    <script>
        function downloadPDF() {
            const element = document.getElementById('certificate');
            const opt = {
                margin: 0,
                filename: 'Certificate_of_Indigency.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</body>
</html>
