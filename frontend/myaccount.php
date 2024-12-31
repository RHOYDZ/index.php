<?php
// Start the session
session_start();

// Database credentials
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'your_database';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$message = '';

// Fetch user details
$sql = "SELECT id, name, username, contact_number, password, zone, fathers_name, mothers_name, birthday, profile_picture, gender, spouse_name, citizenship, house_number, street, work, is_student, school, degree, married FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="myacc.css">
</head>
<body>
    <div class="container">
        <h1>My Account</h1>
        <div class="account-info">
            <!-- Display Profile Picture -->
            <div class="profile-section">
                <?php if (!empty($userData['profile_picture'])): ?>
                    <img src="<?php echo htmlspecialchars($userData['profile_picture']); ?>" alt="Profile Picture" class="profile-picture">
                <?php else: ?>
                    <p>No profile picture available.</p>
                <?php endif; ?>
            </div>

            <div class="personal-info">
                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($userData['name']); ?></p>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($userData['username']); ?></p>
                <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($userData['contact_number']); ?></p>
                <p><strong>Zone:</strong> <?php echo htmlspecialchars($userData['zone']); ?></p>
                <p><strong>Father's Name:</strong> <?php echo htmlspecialchars($userData['fathers_name']); ?></p>
                <p><strong>Mother's Name:</strong> <?php echo htmlspecialchars($userData['mothers_name']); ?></p>
                <p><strong>Birthday:</strong> <?php echo htmlspecialchars($userData['birthday']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($userData['gender']); ?></p>
                <p><strong>Citizenship:</strong> <?php echo htmlspecialchars($userData['citizenship']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($userData['house_number']) . ' ' . htmlspecialchars($userData['street']); ?></p>

                <!-- Work/Student Section -->
                <?php if (strtolower($userData['is_student']) === 'yes'): ?>
                    <p><strong>Status:</strong> Student</p>
                    <p><strong>School:</strong> <?php echo !empty($userData['school']) ? htmlspecialchars($userData['school']) : 'N/A'; ?></p>
                    <p><strong>Degree:</strong> <?php echo !empty($userData['degree']) ? htmlspecialchars($userData['degree']) : 'N/A'; ?></p>
                <?php elseif (!empty($userData['work'])): ?>
                    <p><strong>Work:</strong> <?php echo htmlspecialchars($userData['work']); ?></p>
                <?php else: ?>
                    <p><strong>Work:</strong> None</p>
                <?php endif; ?>

                <p><strong>Marital Status:</strong> <?php echo strtolower($userData['married']) === 'yes' ? 'Married' : 'Single'; ?></p>
                <?php if (strtolower($userData['married']) === 'yes'): ?>
                    <p><strong>Spouse Name:</strong> <?php echo htmlspecialchars($userData['spouse_name']); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="account-actions">
            <a href="index.php" class="btn">Home</a>
            <button class="btn" onclick="window.location.href='fillupform.php'">Edit</button>
            <!-- New Transaction Button -->
            <button class="btn" onclick="window.location.href='transaction.php'">Transaction</button>
        </div>
    </div>

    <!-- Debugging: Uncomment to check user data -->
    <!-- <pre><?php print_r($userData); ?></pre> -->
</body>

</html>
