<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Include the database connection file (Assuming it's named 'config.php')
include('config.php');

// Fetch staff members from the database
$stmt = $pdo->query("SELECT * FROM tbl_staff ORDER BY position");
$staff = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group staff members by position for layout purposes
$groupedStaff = [];
foreach ($staff as $member) {
    $groupedStaff[$member['position']][] = $member;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PALLUA NORTE</title>
    <link rel="stylesheet" href="index.css">
    <link rel="icon" href="../pic/logo2.png" type="image/x-icon">


</head>

<body>
    <nav class="navbar" id="navbar">
        <div class="logo">
            <a href="index.php">
                <img src="../pic/logo2.png" alt="Logo" />
            </a>
        </div>

        <div class="hamburger" id="hamburger">☰</div>
        <ul class="nav-links" id="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contactus.php">Contact Us</a></li>


            <?php if (isset($_SESSION['username'])): ?>
                <!-- If user is logged in, show username and log out option -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" id="dropdownToggle"><?php echo $_SESSION['username']; ?> <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown-menu" id="dropdownMenu">
                        <!-- Add "My Account" link before "Log Out" -->
                        <li><a href="myaccount.php">My Account</a></li>
                        <li><a href="logout.php">Log Out</a></li>
                        <li><a href="staff.php">Chart</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <!-- If user is not logged in, show login link -->
                <li><a href="login.php">Log In</a></li>
            <?php endif; ?>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" id="dropdownToggle">Certificates</a>
                <ul class="dropdown-menu" id="dropdownMenu">
                    <li><a href="certificate_indigency.php">INDIGENCY</a></li>
                    <li><a href="#">BARANGGAY CLERANCE</a></li>
                    <li><a href="#">BUSINESS PERMIT</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- nav bar end -->





    <section class="pal-welcome-section">
        <div class="pal-header">
            <img src="../pic/logo2.png" alt="Logo" class="pal-logo">
            <h1 class="pal-welcome-heading">PALLUA NORTE</h1>
        </div>

        <p class="pal-about-text">
            Barangay <strong>Pallua Norte</strong> is a peaceful and vibrant community located in Tuguegarao City, Cagayan.
            Known for its scenic beauty and strong sense of unity, it’s a place where tradition meets the quiet charm of countryside living.
        </p>

        <p class="pal-about-text">
            Pallua Norte has its roots in the early days of Tuguegarao’s growth. It started as a small settlement of farmers and fishermen, thanks to its proximity to the Cagayan River. The fertile land and abundant water supply made it an ideal location for farming, especially rice and corn.
            Over the years, as the city of Tuguegarao expanded, so did Pallua Norte. New roads were built, connecting the barangay to other areas, and families began to diversify their livelihoods. Despite these changes, Pallua Norte has kept its rural charm and remains close-knit, with a strong sense of community.
        </p>

        <p class="pal-about-text">
            One of the things that makes Pallua Norte special is the strong sense of togetherness among its residents. They share a deep bond, whether it’s through celebrating local festivals, helping one another during tough times, or just enjoying the simple pleasures of daily life.
        </p>

        <div class="pal-read-more-container">
            <a href="about.php" class="pal-read-more-btn">Read More</a>
        </div>
    </section>







    <section class="google-map-section">
        <h2>Our Location</h2>
        <div class="map-container">
            <!-- Google Maps Embed iframe -->
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7604.959903012885!2d121.69541864496185!3d17.627413158305313!2m
                3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x338586613bb8c765%3A0xe3400dd5dff004f0!2sPallua%20Norte%2C%20Tuguegarao%2C%20Cagayan!5
                e0!3m2!1sfil!2sph!4v1735194104703!5m2!1sfil!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-
                referrer-when-downgrade"
                width="100%"
                height="450"
                style="border:0;"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </div>
    </section>
    
    
    

    <div class="documents-container">
    <div class="document barangay-clearance">
        <h3 class="document-title">Barangay Clearance</h3>
        <a href="barangay-clearance.php">
            <button class="get-button">Kumuha Barangay Clearance</button>
        </a>
    </div>

    <div class="document business-permit">
        <h3 class="document-title">Business Permit</h3>
        <a href="business-permit.php">
            <button class="get-button">Kumuha Business <BR>Permit</BR></button>
        </a>
    </div>

    <div class="document indigency-clearance">
        <h3 class="document-title">Indigency Clearance</h3>
        <a href="certificate_indigency.php">
            <button class="get-button">Kumuha Indigency Clearance</button>
        </a>
    </div>
</div>

<div class="container">
    <!-- Slideshow -->
    <div class="slideshow-container">
        <div class="slides">
            <?php foreach ($staff as $member): ?>
                <div class="slide">
                    <img src="/barrangay pallua norte/pic/<?php echo htmlspecialchars($member['image_name']); ?>" 
                         alt="<?php echo htmlspecialchars($member['name']); ?>" class="staff-image">
                    <p class="staff-name"><?php echo htmlspecialchars($member['name']); ?></p>
                    <p class="staff-position"><?php echo htmlspecialchars($member['position']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="nav-buttons">
            <button class="prev">&#10094;</button>
            <button class="next">&#10095;</button>
        </div>
    </div>
</div>

<style>
    /* Slideshow Container */
    .slideshow-container {
        position: relative;
        width: 320px; /* Adjust based on slide width */
        height: 400px; /* Adjust based on slide height */
        overflow: hidden;
    }

    .slides {
        display: flex;
        transition: transform 0.5s ease;
    }

    .slide {
        min-width: 320px; /* Adjust based on slide width */
        height: 400px; /* Adjust based on slide height */
        opacity: 0.7;
        transition: opacity 0.5s ease, transform 0.5s ease;
        position: relative;
    }

    .slide.active {
        opacity: 1;
        transform: scale(1.1); /* Makes the current slide a bit bigger */
        z-index: 3;
    }

    .slide.previous {
        opacity: 0.5;
        transform: scale(0.9); /* Makes the previous slide smaller */
        z-index: 1;
    }

    .slide.next {
        opacity: 0.5;
        transform: scale(0.9); /* Makes the next slide smaller */
        z-index: 2;
    }

    .nav-buttons {
        position: absolute;
        top: 50%;
        width: 100%;
        display: flex;
        justify-content: space-between;
        transform: translateY(-50%);
    }

    .prev, .next {
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
    }
</style>

<script>
    // JavaScript for the slideshow functionality
    let currentIndex = 0;
    const slides = document.querySelectorAll('.slide');
    const totalSlides = slides.length;

    const showSlide = (index) => {
        // Remove classes from all slides
        slides.forEach(slide => slide.classList.remove('active', 'previous', 'next'));

        // Add classes to the current, previous, and next slides
        const prevIndex = (index - 1 + totalSlides) % totalSlides;
        const nextIndex = (index + 1) % totalSlides;

        slides[prevIndex].classList.add('previous');
        slides[index].classList.add('active');
        slides[nextIndex].classList.add('next');
    };

    // Show next slide
    const nextSlide = () => {
        currentIndex = (currentIndex + 1) % totalSlides;
        showSlide(currentIndex);
    };

    // Show previous slide
    const prevSlide = () => {
        currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        showSlide(currentIndex);
    };

    // Auto slideshow to transition one by one
    const autoSlide = () => {
        currentIndex = (currentIndex + 1) % totalSlides;
        showSlide(currentIndex);
    };

    // Set interval for auto sliding
    setInterval(autoSlide, 3000); // Change slide every 3 seconds

    // Add event listeners for the navigation buttons
    document.querySelector('.next').addEventListener('click', nextSlide);
    document.querySelector('.prev').addEventListener('click', prevSlide);

    // Show the first slide initially
    showSlide(currentIndex);
</script>






    <footer class="footer">
        <div class="footer-container">
            <!-- Left Column -->
            <div class="footer-left">
                <p><i class="fas fa-map-marker-alt"></i> Address: <br> PALLUA NORTE,<br> TUGUEGARAO CITY, <br>CAGAYAN REGION II</p>
                <p><i class="fas fa-phone"></i> Contact: 09911838151</p>
            </div>

            <!-- Center Column -->
            <div class="footer-center">
                <a href="https://www.facebook.com/skabataanpalluanorte/" target="_blank" class="social-link">
                    <i class="fab fa-facebook-f"></i> Facebook
                </a>
                <a href="mailto:rhoydeleonpablo01@gmai.com" class="social-link">
                    <i class="fas fa-envelope"></i> Gmail
                </a>
            </div>

            <!-- Right Column -->
            <div class="footer-right">
                <a href="privacy-policy.html"><i class="fas fa-user-shield"></i> Privacy Policy</a>
                <a href="terms-and-conditions.html"><i class="fas fa-file-contract"></i> Terms & Conditions</a>
                <a href="contact-us.html"><i class="fas fa-envelope-open-text"></i> Contact Us</a>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p>All rights reserved &copy; Welcome to Pallua Norte</p>
        </div>
    </footer>

    <script src="style.js"></script>
</body>

</html>