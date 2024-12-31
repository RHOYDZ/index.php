<?php
require_once 'config.php';  // Include the config file for DB connection

// Start session
session_start();

// Registration handler
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $contact_number = $_POST['contact_number'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $zone = $_POST['zone'];
    $mothers_name = $_POST['mothers_name'];
    $fathers_name = $_POST['fathers_name'];
    $birthday = $_POST['birthday'];
    $citizenship = $_POST['citizenship'];  // Get the selected citizenship
    $gender = $_POST['gender'];  // Get the selected gender

    // Password confirmation check
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    } else {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Check for duplicate username
        $check_sql = "SELECT COUNT(*) FROM users WHERE username = ?";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([$username]);
        $count = $check_stmt->fetchColumn();

        if ($count > 0) {
            // Username already exists
            echo "<script>alert('Email is already in use. Please choose a different one.');</script>";
        } else {
            // Insert new user with citizenship and gender
            $sql = "INSERT INTO users (name, username, contact_number, password, zone, mothers_name, fathers_name, birthday, citizenship, gender) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $username, $contact_number, $password_hash, $zone, $mothers_name, $fathers_name, $birthday, $citizenship, $gender]);

            // Check if insertion was successful
            if ($stmt) {
                echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Error during registration. Please try again later.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="login.css">
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const toggleCheckbox = document.getElementById('togglePassword');
            passwordField.type = toggleCheckbox.checked ? 'text' : 'password';
        }
    </script>
</head>

<body>
    <div class="register-container">
        <h2 class="register-title">Register</h2>
        <form method="POST" class="register-form">
            <div class="form-group">
                <input type="text" name="name" class="form-input" placeholder="Lastname, Givenname Middlename" required oninput="this.value = this.value.toUpperCase()">
            </div>
            <div class="form-group">
                <input type="text" id="username" name="username" class="form-input" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <input type="text" id="contact_number" name="contact_number" class="form-input" placeholder="Contact Number" maxlength="11" required>
            </div>
            <div class="form-group">
                <input type="text" name="mothers_name" class="form-input" placeholder="Mother's Name" required oninput="this.value = this.value.toUpperCase()">
            </div>
            <div class="form-group">
                <input type="text" name="fathers_name" class="form-input" placeholder="Father's Name" required oninput="this.value = this.value.toUpperCase()">
            </div>
            <div class="form-group">
                <label for="zone" class="form-label">Zone:</label>
                <select name="zone" class="form-select" required>
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <option value="<?php echo $i; ?>">Zone <?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="date" name="birthday" class="form-input" placeholder="Birthday" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" id="password" class="form-input" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" id="confirm_password" class="form-input" placeholder="Confirm Password" required>
                <small id="password_match_message" style="color: red; display: none;">Passwords do not match</small>
                <small id="password_match_success" style="color: green; display: none;">Passwords match</small>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const passwordField = document.getElementById('password');
                    const confirmPasswordField = document.getElementById('confirm_password');
                    const messageError = document.getElementById('password_match_message');
                    const messageSuccess = document.getElementById('password_match_success');

                    function validatePasswords() {
                        if (confirmPasswordField.value === '') {
                            // Hide all messages if confirm field is empty
                            messageError.style.display = 'none';
                            messageSuccess.style.display = 'none';
                            return;
                        }

                        if (passwordField.value === confirmPasswordField.value) {
                            messageError.style.display = 'none';
                            messageSuccess.style.display = 'inline';
                        } else {
                            messageError.style.display = 'inline';
                            messageSuccess.style.display = 'none';
                        }
                    }

                    // Add event listeners for real-time validation
                    passwordField.addEventListener('input', validatePasswords);
                    confirmPasswordField.addEventListener('input', validatePasswords);
                });
            </script>

            <div class="form-group">
                <label for="citizenship" class="form-label">Citizenship:</label>
                <select name="citizenship" class="form-select" required>
                    <option value="Filipino">Filipino</option>
                    <option value="Dual">Dual CitizenShip</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <div class="form-group">
                <label for="gender" class="form-label">Gender:</label>
                <select name="gender" class="form-select" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" id="togglePassword" onclick="togglePasswordVisibility()"> Show Password
                </label>
            </div>
            <div class="form-group">
                <button type="submit" name="register" class="form-button">Register</button>
            </div>
        </form>
        <p class="login-link">
            Already have an account? <a href="login.php">Login here</a>.
        </p>
    </div>
</body>

</html>
