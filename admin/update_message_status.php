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

// Get the message ID from the POST request (JSON format)
$data = json_decode(file_get_contents("php://input"), true);
if (isset($data['id'])) {
    $message_id = $data['id'];

    // Update message status to 'read' (1)
    $sql = "UPDATE contact_messages SET message_status = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $message_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error']);
}

$conn->close();
?>
