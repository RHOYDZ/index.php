<?php
// Include the database connection file
include('config.php');

// Initialize variables
$imageUploadError = "";
$name = "";
$position = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $position = $_POST['position'];

    // Validate and handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $imageName = uniqid() . '.' . $imageExtension; // Ensure unique filename
        
        // Set the target directory for images
        $imageDestination = $_SERVER['DOCUMENT_ROOT'] . '/barrangay pallua norte/pic/' . $imageName;

        // Ensure the directory exists
        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/barrangay pallua norte/pic')) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/barrangay pallua norte/pic', 0777, true);
        }

        // Move the uploaded image to the target directory
        if (move_uploaded_file($imageTmpName, $imageDestination)) {
            // Insert the staff information into the database
            $stmt = $pdo->prepare("INSERT INTO tbl_staff (name, image_name, position) VALUES (?, ?, ?)");
            $stmt->execute([$name, $imageName, $position]);
            echo "Staff member added successfully!";
        } else {
            $imageUploadError = "Failed to upload image.";
        }
    } else {
        $imageUploadError = "Please select an image to upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Staff</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f0f8ff;
        }

        h2 {
            text-align: center;
            color: #007bff;
        }

        form {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        label {
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        p {
            color: red;
            text-align: center;
        }

        a {
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
        }

        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>

<h2>Upload Staff Information</h2>

<form action="uploadstaff.php" method="POST" enctype="multipart/form-data">
    <label for="name">Staff Name:</label>
    <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($name); ?>">

    <label for="image">Staff Image:</label>
    <input type="file" id="image" name="image" accept="image/*" required>

    <label for="position">Position:</label>
    <select id="position" name="position" required>
        <option value="Kapitan" <?php echo ($position == 'Kapitan') ? 'selected' : ''; ?>>Kapitan</option>
        <option value="Kagawad" <?php echo ($position == 'Kagawad') ? 'selected' : ''; ?>>Kagawad</option>
        <option value="Secretary" <?php echo ($position == 'Secretary') ? 'selected' : ''; ?>>Secretary</option>
        <option value="Treasurer" <?php echo ($position == 'Treasurer') ? 'selected' : ''; ?>>Treasurer</option>
        <option value="Clinic" <?php echo ($position == 'Clinic') ? 'selected' : ''; ?>>Clinic</option>
        <option value="Daycare" <?php echo ($position == 'Daycare') ? 'selected' : ''; ?>>Daycare</option>
        <option value="Utility" <?php echo ($position == 'Utility') ? 'selected' : ''; ?>>Utility</option>
        <option value="Chief Tanod" <?php echo ($position == 'Chief Tanod') ? 'selected' : ''; ?>>Chief Tanod</option>
        <option value="Tanod" <?php echo ($position == 'Tanod') ? 'selected' : ''; ?>>Barangay Police</option>
        <option value="Street Sweeper" <?php echo ($position == 'Street Sweeper') ? 'selected' : ''; ?>>Street Sweeper</option>
    </select>

    <input type="submit" value="Upload Staff">
</form>

<?php
// Display any upload errors
if ($imageUploadError) {
    echo "<p>$imageUploadError</p>";
}
?>

<a href="index.php">Home</a>

</body>
</html>
