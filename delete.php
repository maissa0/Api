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

// Get the 'num' parameter from the query string
$num = isset($_GET['num']) ? intval($_GET['num']) : 0;

// Check if 'num' is valid
if ($num <= 0) {
    die("Error: Invalid 'num' parameter");
}

// Prepare SQL statement using prepared statement
$stmt = $conn->prepare("DELETE FROM personne WHERE num = ?");

// Bind parameter and execute SQL statement
$stmt->bind_param("i", $num); // 'i' for integer
if ($stmt->execute()) {
    echo "Record deleted successfully";
} else {
    echo "Error: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
