<?php
$conn = new mysqli("localhost", "root", "", "your_database");

if (isset($_GET['id'])) {
    $transactionId = $_GET['id'];
    $sql = "SELECT * FROM certificate_indigency WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $transactionId);
    $stmt->execute();
    $result = $stmt->get_result();
    $transactionDetails = $result->fetch_assoc();
    
    echo json_encode($transactionDetails);
}

$conn->close();
?>
