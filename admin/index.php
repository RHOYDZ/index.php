<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "your_database";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch count of unread messages where message_status is 0 (unread)
$sql = "SELECT COUNT(*) AS unread_count FROM contact_messages WHERE message_status = 0";
$result = $conn->query($sql);
$unread_count = 0;

if ($result && $row = $result->fetch_assoc()) {
    $unread_count = $row['unread_count'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHqT7BMLJkoFw8UaT2NvhSNNOV9zO1RT/4yZTc7H2W/DdbfrrZg1eMcME5k5dJrJw99KIuLg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Basic styles for the sidebar */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #333;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar img {
            width: 80%;
            margin: 20px auto;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 15px;
            text-align: center;
            background-color: #444;
            margin: 5px 0;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            position: relative;
        }
        .sidebar ul li a:hover {
            background-color: #555;
        }
        .sidebar ul li a .unread-count {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            background-color: red;
            color: white;
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 50%;
            font-weight: bold;
        }

        /* Logout section */
        .logout {
            padding: 15px;
            background-color: #444;
            text-align: center;
            margin: 5px 0;
        }
        .logout a {
            color: white;
            text-decoration: none;
        }
        .logout:hover {
            background-color: #555;
        }

        /* Main content area */
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <img src="../pic/logo2.png" alt="Logo">
        <ul>
            <li>
                <a href="mesages.php">
                    Messages
                    <?php if ($unread_count > 0): ?>
                        <span class="unread-count"><?php echo $unread_count; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="transaction.php">Transactions</a></li>
            <li><a href="uploadstaff.php">staffupload</a></li>
        </ul>
        <div class="logout">
            <a href="../frontend/login.php">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Welcome to the Admin Dashboard</h1>
        <p>This is where you can manage your system.</p>
    </div>
</body>
</html>
