<?php
// Start the session to track the logged-in user
session_start();

// Include the database connection file
include('config.php');

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Fetch the logged-in user's details
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $loggedInUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($loggedInUser) {
        // Display the user's name (or other information)
        $username = $loggedInUser['username'];
    } else {
        // User not found in the database
        $username = 'Unknown User';
    }
} else {
    // No user is logged in
    $username = 'Guest';
}

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
    <title>Staff Hierarchy</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 0;
        }

        a {
    text-decoration: none;
    color: inherit;
}

ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}

/* Navbar */
.navbar {
    display: flex;
    justify-content: space-between; /* Space between logo and nav links */
    align-items: center;
    padding: 15px 30px; /* Adjusted padding for better balance */
    background-color: #0066cc; /* Blue background */
    border-radius: 25px; /* Rounded corners */
    margin: 0 auto; /* Center navbar */
    max-width: 1200px; /* Limit maximum width */
    position: fixed; /* Fixed at the top */
    top: 10px; /* Position at the top */
    left: 0; /* Span full width */
    right: 0; /* Span full width */
    z-index: 1000; /* Above other content */
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1); /* Optional shadow */
}

/* Hide hamburger icon by default (on larger screens) */
.hamburger {
    display: none; /* Hidden by default */
    font-size: 30px;
    color: white;
}

/* Add padding-top to body or next section to prevent content from being hidden behind the navbar */
body {
    padding-top: 80px; /* Adjust for navbar height */
}

/* Logo and Hamburger container */
.navbar-content {
    display: flex;
    justify-content: space-between; /* Space out logo and hamburger */
    align-items: center;
    width: 100%;
}

/* Logo */
.navbar .logo {
    display: flex;
    align-items: center; /* Vertically center the logo */
    flex: 1; /* Allow the logo container to take space on the left */
}

.navbar .logo img {
    height: 50px; /* Logo size */
}

/* Navigation Links */
.nav-links {
    display: flex;
    gap: 20px;
    align-items: center; /* Center nav links vertically */
    flex: 2; /* Allow nav links to take more space */
    justify-content: flex-end; /* Align nav links to the right */
}

.nav-links li a {
    color: white;
    font-size: 18px;
    padding: 10px 15px; /* Adjust padding for better balance */
    border-radius: 5px;
    transition: background-color 0.3s ease, transform 0.3s ease; /* Add transform for scaling */
    text-align: center;
    text-decoration: none; /* Remove underline */
}

.nav-links li a:hover {
    background-color: #023e8a; /* Darker blue on hover */
    color: #f0f0f0; /* Light hover text color */
    transform: scale(1.1); /* Slightly enlarge the text */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15); /* Add shadow effect */
}

/* Dropdown Menu */
.dropdown-menu {
    display: none;
    position: absolute;
    background-color: white;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
}

.dropdown:hover .dropdown-menu {
    display: block;
}

.dropdown-menu li a {
    color: #0066cc;
    padding: 8px;
    display: block;
    transition: background-color 0.3s ease, transform 0.3s ease; /* Add transform for scaling */
    text-decoration: none;
}

.dropdown-menu li a:hover {
    background-color: #f1f1f1;
    transform: scale(1.1); /* Slightly enlarge the text */
    color: #005bb5; /* Match the hover effect */
}

/* Mobile responsiveness */
@media screen and (max-width: 768px) {
    .navbar {
        flex-direction: column; /* Stack logo and nav links vertically */
        padding: 10px; /* Adjust padding for mobile */
    }

    /* Align logo and hamburger menu horizontally */
    .navbar-content {
        display: flex;
        justify-content: space-between;
        width: 100%;
    }

    .nav-links {
        flex-direction: column; /* Stack nav links vertically */
        gap: 15px; /* Increase gap between nav links */
        display: none; /* Hide nav links by default */
    }

    /* Show hamburger icon only on small screens */
    .hamburger {
        display: block; /* Show on small screens */
    }

    .nav-links.show {
        display: flex; /* Show nav links when hamburger is clicked */
    }
}


        /* Layout container */
        .container {
            margin: 20px auto;
            max-width: 1200px;
            text-align: center;
            position: relative;
        }

        .staff-member {
            text-align: center;
            margin: 10px;
        }

        .staff-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #007bff;
        }

        .staff-name, .staff-position {
            margin-top: 5px;
            font-size: 14px;
        }

        /* Hierarchy groups */
        .kapitan {
            position: relative;
            margin-bottom: 20px;
        }

        .secretary-treasurer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px auto 40px;
            width: 300px;
            position: relative;
        }

        .kagawad {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 40px;
            position: relative;
        }

        .nested-group {
            display: flex;
            justify-content: space-between;
        }

        .horizontal-group {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .tanod {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
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

    <!-- Hierarchy Container -->
    <div class="container">
        <!-- Kapitan -->
        <div class="kapitan">
            <?php if (!empty($groupedStaff['Kapitan'])): ?>
                <?php foreach ($groupedStaff['Kapitan'] as $member): ?>
                    <div class="staff-member">
                        <img src="/barrangay pallua norte/pic/<?php echo htmlspecialchars($member['image_name']); ?>" 
                             alt="<?php echo htmlspecialchars($member['name']); ?>" class="staff-image">
                        <p class="staff-name"><?php echo htmlspecialchars($member['name']); ?></p>
                        <p class="staff-position">Kapitan</p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Secretary and Treasurer -->
        <div class="secretary-treasurer">
            <div class="staff-member">
                <?php if (!empty($groupedStaff['Secretary'])): ?>
                    <?php foreach ($groupedStaff['Secretary'] as $member): ?>
                        <img src="/barrangay pallua norte/pic/<?php echo htmlspecialchars($member['image_name']); ?>" 
                             alt="<?php echo htmlspecialchars($member['name']); ?>" class="staff-image">
                        <p class="staff-name"><?php echo htmlspecialchars($member['name']); ?></p>
                        <p class="staff-position">Secretary</p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="staff-member">
                <?php if (!empty($groupedStaff['Treasurer'])): ?>
                    <?php foreach ($groupedStaff['Treasurer'] as $member): ?>
                        <img src="/barrangay pallua norte/pic/<?php echo htmlspecialchars($member['image_name']); ?>" 
                             alt="<?php echo htmlspecialchars($member['name']); ?>" class="staff-image">
                        <p class="staff-name"><?php echo htmlspecialchars($member['name']); ?></p>
                        <p class="staff-position">Treasurer</p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Kagawad -->
        <div class="kagawad">
            <?php if (!empty($groupedStaff['Kagawad'])): ?>
                <?php foreach ($groupedStaff['Kagawad'] as $member): ?>
                    <div class="staff-member">
                        <img src="/barrangay pallua norte/pic/<?php echo htmlspecialchars($member['image_name']); ?>" 
                             alt="<?php echo htmlspecialchars($member['name']); ?>" class="staff-image">
                        <p class="staff-name"><?php echo htmlspecialchars($member['name']); ?></p>
                        <p class="staff-position">Kagawad</p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Nested Groups -->
        <div class="nested-group">
            <!-- Left Group -->
            <div class="horizontal-group">
                <?php foreach (['Clinic', 'Daycare', 'Utility', 'Street Sweeper'] as $position): ?>
                    <?php if (!empty($groupedStaff[$position])): ?>
                        <?php foreach ($groupedStaff[$position] as $member): ?>
                            <div class="staff-member">
                                <img src="/barrangay pallua norte/pic/<?php echo htmlspecialchars($member['image_name']); ?>" 
                                     alt="<?php echo htmlspecialchars($member['name']); ?>" class="staff-image">
                                <p class="staff-name"><?php echo htmlspecialchars($member['name']); ?></p>
                                <p class="staff-position"><?php echo $position; ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Right Group -->
            <div>
                <?php if (!empty($groupedStaff['Chief Tanod'])): ?>
                    <?php foreach ($groupedStaff['Chief Tanod'] as $member): ?>
                        <div class="staff-member">
                            <img src="/barrangay pallua norte/pic/<?php echo htmlspecialchars($member['image_name']); ?>" 
                                 alt="<?php echo htmlspecialchars($member['name']); ?>" class="staff-image">
                            <p class="staff-name"><?php echo htmlspecialchars($member['name']); ?></p>
                            <p class="staff-position">Chief Tanod</p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="tanod">
                    <?php if (!empty($groupedStaff['Tanod'])): ?>
                        <?php foreach ($groupedStaff['Tanod'] as $member): ?>
                            <div class="staff-member">
                                <img src="/barrangay pallua norte/pic/<?php echo htmlspecialchars($member['image_name']); ?>" 
                                     alt="<?php echo htmlspecialchars($member['name']); ?>" class="staff-image">
                                <p class="staff-name"><?php echo htmlspecialchars($member['name']); ?></p>
                                <p class="staff-position">Barangay Police</p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
