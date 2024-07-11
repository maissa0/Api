<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test1"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming you receive JSON data via POST
$data = json_decode(file_get_contents('php://input'), true);

// Validate JSON data
if (empty($data['num']) || empty($data['nom'])) {
    die("Error: Required fields missing");
}

// Prepare SQL statement using prepared statement
$stmt = $conn->prepare("UPDATE personne SET nom = ? WHERE num = ?");

// Bind parameters and execute SQL statement
$stmt->bind_param("si", $data['nom'], $data['num']); // 's' for string, 'i' for integer
if ($stmt->execute()) {
    echo "Record updated successfully";
} else {
    echo "Error: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
