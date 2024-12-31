<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Database connection
$host = 'localhost';
$dbname = 'your_database';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data based on logged-in user ID
$user_id = $_SESSION['user_id'];  // Get user ID from session
$user_data = [];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
}
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $contact_number = $_POST['contact_number'];
    $zone = $_POST['zone'];
    $mothers_name = $_POST['mothers_name'];
    $fathers_name = $_POST['fathers_name'];
    $birthday = $_POST['birthday'];
    $gender = $_POST['gender'];
    $spouse_name = $_POST['spouse_name'] ?? null;
    $citizenship = $_POST['citizenship'];
    $house_number = $_POST['house_number'];
    $street = $_POST['street'];
    $work = $_POST['work'] ?? null;
    $is_student = isset($_POST['is_student']) ? 'Yes' : 'No';
    $school = $_POST['school'] ?? null;
    $degree = $_POST['degree'] ?? null;
    $married = $_POST['married'];

    // Ensure the upload directory exists
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Create the directory if it doesn't exist
    }

    // Handle profile picture upload
    $profile_picture = $user_data['profile_picture'] ?? null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $upload_file = $upload_dir . basename($_FILES['profile_picture']['name']);
        // Check if the file is an image
        $file_type = mime_content_type($_FILES['profile_picture']['tmp_name']);
        if (strpos($file_type, 'image') === false) {
            echo "Please upload a valid image file.";
        } else {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_file)) {
                $profile_picture = $upload_file;
            } else {
                echo "Error uploading file.";
            }
        }
    }

    // Update user data in the database
    $update_query = "UPDATE users SET name=?, username=?, contact_number=?, zone=?, mothers_name=?, fathers_name=?, birthday=?, profile_picture=?, gender=?, spouse_name=?, citizenship=?, house_number=?, street=?, work=?, is_student=?, school=?, degree=?, married=? WHERE id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param(
        "ssssssssssssssssssi",
        $name,
        $username,
        $contact_number,
        $zone,
        $mothers_name,
        $fathers_name,
        $birthday,
        $profile_picture,
        $gender,
        $spouse_name,
        $citizenship,
        $house_number,
        $street,
        $work,
        $is_student,
        $school,
        $degree,
        $married,
        $user_id
    );

    if ($stmt->execute()) {
        // Redirect to the account page after successful form submission
        header('Location: myaccount.php');
        exit;
    } else {
        echo "Error updating user: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fill Up Form</title>
    <link rel="stylesheet" href="fillupform.css">
    <script>
    function toggleMarriedFields() {
        const civilStatus = document.getElementById('civil_status').value;
        const spouseField = document.getElementById('spouse_name_field');
        spouseField.style.display = (civilStatus === 'Married') ? 'block' : 'none';
    }

    function toggleStudentFields() {
        const isStudent = document.getElementById('is_student').value;
        const studentFields = document.querySelector('.student-fields');
        const workField = document.querySelector('.work-field');

        if (isStudent === 'Yes') {
            studentFields.style.display = 'block';
            workField.style.display = 'none';
        } else {
            studentFields.style.display = 'none';
            workField.style.display = 'block';
        }
    }

    // Initialize the form based on saved data or default state
    window.onload = function () {
        toggleStudentFields(); // Set student/work fields visibility
        toggleMarriedFields(); // Set spouse field visibility (if needed)
    };
</script>

</head>

<body>
<h1 class="form-header">Fill Up Form</h1>
<form class="fill-up-form" method="POST" action="fillupform.php" enctype="multipart/form-data">
    <div class="form-group">
        <label class="form-label" for="name">Name:</label>
        <input class="form-input" type="text" id="name" name="name" 
            value="<?php echo htmlspecialchars($user_data['name'] ?? ''); ?>" 
            <?php echo !empty($user_data['name']) ? 'readonly' : ''; ?> required>
    </div>

    <div class="form-group">
        <label class="form-label" for="username">Username:</label>
        <input class="form-input" type="text" id="username" name="username" 
            value="<?php echo htmlspecialchars($user_data['username'] ?? ''); ?>" 
            <?php echo !empty($user_data['username']) ? 'readonly' : ''; ?> required>
    </div>

    <div class="form-group">
        <label class="form-label" for="contact_number">Contact Number:</label>
        <input class="form-input" type="text" id="contact_number" name="contact_number" 
            value="<?php echo htmlspecialchars($user_data['contact_number'] ?? ''); ?>" 
            <?php echo !empty($user_data['contact_number']) ? 'readonly' : ''; ?> required>
    </div>

    <div class="form-group">
        <label class="form-label" for="zone">Zone:</label>
        <select class="form-input" id="zone" name="zone" required>
            <?php
            for ($i = 1; $i <= 10; $i++) {
                $selected = (isset($user_data['zone']) && $user_data['zone'] == $i) ? 'selected' : '';
                echo "<option value=\"$i\" $selected>$i</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label class="form-label" for="mothers_name">Mother's Name:</label>
        <input class="form-input" type="text" id="mothers_name" name="mothers_name" 
            value="<?php echo htmlspecialchars($user_data['mothers_name'] ?? ''); ?>" 
            <?php echo !empty($user_data['mothers_name']) ? 'readonly' : ''; ?> required>
    </div>

    <div class="form-group">
        <label class="form-label" for="fathers_name">Father's Name:</label>
        <input class="form-input" type="text" id="fathers_name" name="fathers_name" 
            value="<?php echo htmlspecialchars($user_data['fathers_name'] ?? ''); ?>" 
            <?php echo !empty($user_data['fathers_name']) ? 'readonly' : ''; ?> required>
    </div>

    <div class="form-group">
        <label class="form-label" for="birthday">Birthday:</label>
        <input class="form-input" type="date" id="birthday" name="birthday" 
            value="<?php echo htmlspecialchars($user_data['birthday'] ?? ''); ?>" 
            <?php echo !empty($user_data['birthday']) ? 'readonly' : ''; ?> required>
    </div>

    <div class="form-group">
        <label class="form-label" for="profile_picture">Profile Picture:</label>
        <?php if (!empty($user_data['profile_picture'])): ?>
            <img src="<?php echo htmlspecialchars($user_data['profile_picture']); ?>" alt="Profile Picture" style="max-width: 100px; display: block;">
        <?php endif; ?>
        <input class="form-input" type="file" id="profile_picture" name="profile_picture">
    </div>

    <div class="form-group">
        <label class="form-label" for="civil_status">Civil Status:</label>
        <select class="form-input" id="civil_status" name="civil_status" onchange="toggleMarriedFields()" required>
            <option value="Single" <?php echo (isset($user_data['civil_status']) && $user_data['civil_status'] === 'Single') ? 'selected' : ''; ?>>Single</option>
            <option value="Married" <?php echo (isset($user_data['civil_status']) && $user_data['civil_status'] === 'Married') ? 'selected' : ''; ?>>Married</option>
        </select>
    </div>

    <div class="form-group" id="spouse_name_field" style="display: <?php echo (isset($user_data['civil_status']) && $user_data['civil_status'] === 'Married') ? 'block' : 'none'; ?>;">
        <label class="form-label" for="spouse_name">Spouse Name:</label>
        <input class="form-input" type="text" name="spouse_name" 
            value="<?php echo htmlspecialchars($user_data['spouse_name'] ?? ''); ?>" 
            <?php echo !empty($user_data['spouse_name']) ? 'readonly' : ''; ?>>
    </div>

    <div class="form-group">
        <label class="form-label" for="is_student">Are you a student?</label>
        <select class="form-input" id="is_student" name="is_student" onchange="toggleStudentFields()">
            <option value="Yes" <?php echo (isset($user_data['is_student']) && $user_data['is_student'] === 'Yes') ? 'selected' : ''; ?>>Yes</option>
            <option value="No" <?php echo (isset($user_data['is_student']) && $user_data['is_student'] === 'No') ? 'selected' : ''; ?>>No</option>
        </select>
    </div>

    <div class="form-group student-fields" style="display: <?php echo (isset($user_data['is_student']) && $user_data['is_student'] === 'Yes') ? 'block' : 'none'; ?>;">
        <label class="form-label" for="school">School:</label>
        <input class="form-input" type="text" id="school" name="school" 
            value="<?php echo htmlspecialchars($user_data['school'] ?? ''); ?>">
        <label class="form-label" for="degree">Degree/Course:</label>
        <input class="form-input" type="text" id="degree" name="degree" 
            value="<?php echo htmlspecialchars($user_data['degree'] ?? ''); ?>">
    </div>

    <div class="form-group work-field" style="display: <?php echo (isset($user_data['is_student']) && $user_data['is_student'] === 'No') ? 'block' : 'none'; ?>;">
        <label class="form-label" for="work">Work:</label>
        <input class="form-input" type="text" id="work" name="work" 
            value="<?php echo htmlspecialchars($user_data['work'] ?? ''); ?>">
    </div>

    <div class="form-group">
        <label class="form-label" for="gender">Gender:</label>
        <select class="form-input" name="gender" id="gender" required 
            <?php echo !empty($user_data['gender']) ? 'disabled' : ''; ?>>
            <option value="Male" <?php echo (isset($user_data['gender']) && $user_data['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo (isset($user_data['gender']) && $user_data['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
            <option value="Other" <?php echo (isset($user_data['gender']) && $user_data['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
        </select>
    </div>

    <div class="form-group">
        <label class="form-label" for="citizenship">Citizenship:</label>
        <select class="form-input" name="citizenship" id="citizenship" required 
            <?php echo !empty($user_data['citizenship']) ? 'disabled' : ''; ?>>
            <option value="Filipino" <?php echo (isset($user_data['citizenship']) && $user_data['citizenship'] === 'Filipino') ? 'selected' : ''; ?>>Filipino</option>
            <option value="Dual" <?php echo (isset($user_data['citizenship']) && $user_data['citizenship'] === 'Dual') ? 'selected' : ''; ?>>Dual</option>
            <option value="Other" <?php echo (isset($user_data['citizenship']) && $user_data['citizenship'] === 'Other') ? 'selected' : ''; ?>>Other</option>
        </select>
    </div>

    <div class="form-group">
        <label class="form-label" for="house_number">House Number:</label>
        <input class="form-input" type="text" id="house_number" name="house_number" 
            value="<?php echo htmlspecialchars($user_data['house_number'] ?? ''); ?>" 
            <?php echo !empty($user_data['house_number']) ? 'readonly' : ''; ?> required>
    </div>

    <div class="form-group">
        <label class="form-label" for="street">Street:</label>
        <input class="form-input" type="text" id="street" name="street" 
            value="<?php echo htmlspecialchars($user_data['street'] ?? ''); ?>" 
            <?php echo !empty($user_data['street']) ? 'readonly' : ''; ?> required>
    </div>

    <button class="form-button" type="submit">Submit</button>
</form>

</body>


</html>