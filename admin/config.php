<?php
// config.php

// Database connection settings
$host = 'localhost';
$dbname = 'your_database';
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Helper function to fetch all rows from a table
function fetchAllFromTable($tableName, $pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM $tableName");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

// Helper function to insert data into a table
function insertIntoTable($tableName, $data, $pdo) {
    try {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), '?'));
        $stmt = $pdo->prepare("INSERT INTO $tableName ($columns) VALUES ($placeholders)");
        $stmt->execute(array_values($data));
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        die("Insert failed: " . $e->getMessage());
    }
}

// Example tables: certificate_indigency, contact_messages, users
// Usage example:
// $data = fetchAllFromTable('certificate_indigency', $pdo);
// print_r($data);
?>
