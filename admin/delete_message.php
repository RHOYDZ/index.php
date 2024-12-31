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

// Get the message ID from the request
$data = json_decode(file_get_contents("php://input"));
$message_id = $data->id;

// Delete message query
$sql = "DELETE FROM contact_messages WHERE id = ?";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $message_id);

if ($stmt->execute()) {
    // Success
    echo json_encode(['status' => 'success']);
} else {
    // Error
    echo json_encode(['status' => 'error']);
}

$stmt->close();
$conn->close();
?>
