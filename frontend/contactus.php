<?php
session_start();

// Assuming the logged-in user ID is stored in the session
$logged_in_user_id = $_SESSION['user_id'] ?? null;

require_once 'config.php'; // Include the database configuration file

// Initialize variables for form
$user_name = "";
$user_email = "";
$message_feedback = "";

// Fetch logged-in user details if user is logged in
if ($logged_in_user_id) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT name, username FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $logged_in_user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $user_name = htmlspecialchars($row['name']);
            $user_email = htmlspecialchars($row['username']);
        }
    } catch (PDOException $e) {
        die("Error fetching user details: " . $e->getMessage());
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Validate form fields
    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO contact_messages (name, email, message, message_status) VALUES (:name, :email, :message, :status)";
            $stmt = $pdo->prepare($sql);
            $message_status = 0; // Initially set as unread

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':status', $message_status, PDO::PARAM_INT);

            $stmt->execute();

            $message_feedback = "<div style='margin-bottom: 20px; color: green;'>Thank you, $name! Your message has been received.</div>";
        } catch (PDOException $e) {
            $message_feedback = "<div style='margin-bottom: 20px; color: red;'>Error: " . $e->getMessage() . "</div>";
        }
    } else {
        $message_feedback = "<div style='margin-bottom: 20px; color: red;'>All fields are required!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="icon" href="../pic/logo2.png" type="image/x-icon">
    <link rel="stylesheet" href="contactus2.css">
</head>

<body>
    <!-- nav bar start -->
    <nav class="navbar" id="navbar">
        <div class="logo">
            <a href="index.php">
                <img src="../pic/logo2.png" alt="Logo" class="logo-img" />
            </a>
        </div>

        <div class="hamburger" id="hamburger">☰</div>
        <ul class="nav-links" id="nav-links">
            <li><a href="index.php" class="nav-link">Home</a></li>
            <li><a href="about.php" class="nav-link">About</a></li>
            <li><a href="contactus.php" class="nav-link">Contact Us</a></li>

            <?php if (isset($_SESSION['username'])): ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle nav-link" id="dropdownToggle"><?php echo $_SESSION['username']; ?> <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown-menu" id="dropdownMenu">
                        <li><a href="myaccount.php" class="dropdown-item">My Account</a></li>
                        <li><a href="logout.php" class="dropdown-item">Log Out</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="login.php" class="nav-link">Log In</a></li>
            <?php endif; ?>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle nav-link" id="dropdownCertificates">Certificates</a>
                <ul class="dropdown-menu" id="dropdownMenuCertificates">
                    <li><a href="#" class="dropdown-item">INDIGENCY</a></li>
                    <li><a href="barangay_clearance_form.php" class="dropdown-item">BARANGAY CLEARANCE</a></li>
                    <li><a href="#" class="dropdown-item">BUSINESS PERMIT</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- nav bar end -->

    <section class="google-map-section">
        <h2 class="map-section-title">Our Location</h2>
        <div class="map-container">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7604.959903012885!2d121.69541864496185!3d17.627413158305313!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x338586613bb8c765%3A0xe3400dd5dff004f0!2sPallua%20Norte%2C%20Tuguegarao%2C%20Cagayan!5e0!3m2!1sfil!2sph!4v1735194104703!5m2!1sfil!2sph"
                class="google-map"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </section>

    <div class="contact-form">
        <h2 class="contact-form-title">Contact Us</h2>
        <?php echo $message_feedback; ?>
        <form action="contactus.php" method="post" class="form-container">
            <input type="text" name="name" placeholder="Your Name" value="<?php echo $user_name; ?>" class="form-input" required>
            <input type="email" name="email" placeholder="Your Email" value="<?php echo $user_email; ?>" class="form-input" required>
            <textarea name="message" rows="5" placeholder="Your Message" class="form-textarea" required></textarea>
            <button type="submit" class="form-button">Send Message</button>
        </form>
    </div>

    

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-left">
                <p class="footer-info"><i class="fas fa-map-marker-alt"></i> Address: <br> PALLUA NORTE,<br> TUGUEGARAO CITY, <br>CAGAYAN REGION II</p>
                <p class="footer-info"><i class="fas fa-phone"></i> Contact: 09911838151</p>
            </div>

            <div class="footer-center">
                <a href="https://www.facebook.com/skabataanpalluanorte/" target="_blank" class="social-link">
                    <i class="fab fa-facebook-f"></i> Facebook
                </a>
                <a href="mailto:rhoydeleonpablo01@gmai.com" class="social-link">
                    <i class="fas fa-envelope"></i> Gmail
                </a>
            </div>

            <div class="footer-right">
                <a href="privacy-policy.html" class="footer-link"><i class="fas fa-user-shield"></i> Privacy Policy</a>
                <a href="terms-and-conditions.html" class="footer-link"><i class="fas fa-file-contract"></i> Terms & Conditions</a>
                <a href="contact-us.html" class="footer-link"><i class="fas fa-envelope-open-text"></i> Contact Us</a>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="footer-bottom-text">All rights reserved &copy; Welcome to Pallua Norte</p>
        </div>
    </footer>

    <script src="style.js"></script>

</body>
