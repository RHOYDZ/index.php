
<?php
// Start session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PALLUA NORTE</title>
    <link rel="stylesheet" href="index.css">
    <link rel="icon" href="../pic/logo2.png" type="image/x-icon">
    <style>
    /* About Section */
        .bout-section {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 20px auto;
            background-color: #e6f3ff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Section Header */
        .bout-header h1 {
            color: #002244;
            font-size: 2.5em;
            margin-bottom: 20px;
            text-align: center;
        }

        .bout-header p {
            font-size: 1.2em;
            text-align: center;
            margin: 0 auto;
            max-width: 800px;
        }

        /* Subsections */
        .bout-section h2 {
            color: #004080;
            font-size: 1.8em;
            margin-top: 30px;
            border-bottom: 2px solid #004080;
            padding-bottom: 5px;
        }

        .bout-section p {
            margin: 15px 0;
            font-size: 1em;
        }

        /* List styling */
        .bout-section ul {
            margin: 15px 0;
            padding: 0 20px;
        }

        .bout-section li {
            margin-bottom: 10px;
        }

        /* Add responsiveness */
        @media (max-width: 768px) {
            .bout-header h1 {
                font-size: 2em;
            }

            .bout-section h2 {
                font-size: 1.5em;
            }

            .bout-header p, 
            .bout-section p {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
<!-- nav bar start -->
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
                <li><a href="barangay_clearance_form.php">BARANGGAY CLERANCE</a></li>
                <li><a href="#">BUSINESS PERMIT</a></li>
            </ul>
        </li>
    </ul>
</nav>
<!-- nav bar end -->


<!-- About Section -->
<section class="bout-section">
    <div class="bout-header">
        <h1>Welcome to Barangay Pallua Norte</h1>
        <p>
            Barangay <strong>Pallua Norte</strong> is a peaceful and vibrant community located in Tuguegarao City, Cagayan. 
            Known for its scenic beauty and strong sense of unity, it’s a place where tradition meets the quiet charm of countryside living.
        </p>
    </div>

    <div class="bout-history">
        <h2>A Brief History</h2>
        <p>
            Pallua Norte has its roots in the early days of Tuguegarao’s growth. It started as a small settlement of farmers and fishermen, thanks to its proximity to the Cagayan River. The fertile land and abundant water supply made it an ideal location for farming, especially rice and corn.
        </p>
        <p>
            Over the years, as the city of Tuguegarao expanded, so did Pallua Norte. New roads were built, connecting the barangay to other areas, and families began to diversify their livelihoods. Despite these changes, Pallua Norte has kept its rural charm and remains close-knit, with a strong sense of community.
        </p>
    </div>

    <div class="bout-community-life">
        <h2>Community Life</h2>
        <p>
            One of the things that makes Pallua Norte special is the strong sense of togetherness among its residents. They share a deep bond, whether it’s through celebrating local festivals, helping one another during tough times, or just enjoying the simple pleasures of daily life.
        </p>
        <p>
            The annual fiesta, in honor of the barangay’s patron saint, is a highlight. The streets are filled with lively parades, vibrant decorations, traditional dances, and music. It’s a time for the community to come together and celebrate their shared heritage and values.
        </p>
    </div>

    <div class="bout-economy">
        <h2>Economy</h2>
        <p>
            The economy of Pallua Norte has traditionally been centered on agriculture. Many residents still engage in farming, growing crops like rice and corn. The Cagayan River also supports local fishing activities, providing fresh fish for both personal consumption and sale.
        </p>
        <p>
            In recent years, small businesses have been growing, including shops, food stalls, and other local enterprises. These businesses not only support the barangay’s economy but also help provide jobs and services to the residents.
        </p>
    </div>

    <div class="bout-culture-traditions">
        <h2>Culture and Traditions</h2>
        <p>
            Pallua Norte takes great pride in its rich cultural heritage. The community values traditions that have been passed down through generations, such as respect for elders and a strong sense of hospitality. Local festivals and celebrations are a key part of the barangay’s cultural identity, and they help bring everyone together.
        </p>
        <p>
            The elders of the barangay play an important role in teaching younger generations about the community's history, traditions, and stories, ensuring that Pallua Norte’s culture remains alive for years to come.
        </p>
    </div>

    <div class="bout-landmarks">
        <h2>Landmarks and Natural Beauty</h2>
        <p>
            One of the most beautiful aspects of Pallua Norte is its natural environment. The barangay is located along the Cagayan River, which not only provides scenic views but also serves as a relaxing spot for the residents. The surrounding countryside is lush with greenery, making it a peaceful escape from the busy city life.
        </p>
    </div>

    <div class="bout-future">
        <h2>Looking Ahead</h2>
        <p>
            As Pallua Norte continues to grow, the community is focused on balancing progress with its deep-rooted traditions. The barangay aims to improve its infrastructure and offer more opportunities for its residents while preserving the natural beauty and cultural values that make it unique.
        </p>
        <p>
            With the collective effort of its people, Barangay Pallua Norte looks forward to a bright future—one where harmony, growth, and tradition thrive together.
        </p>
    </div>
</section>


<!-- Footer -->
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
            <a href="privacy-policy.php"><i class="fas fa-user-shield"></i> Privacy Policy</a>
            <a href="terms-and-conditions.php"><i class="fas fa-file-contract"></i> Terms & Conditions</a>
            <a href="contact-us.php"><i class="fas fa-envelope-open-text"></i> Contact Us</a>
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
