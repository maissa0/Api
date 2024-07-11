<?php
// db.php

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

// Check the request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you receive JSON data via POST
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate JSON data
    if (empty($data['value1']) || empty($data['value2'])) {
        die("Error: Required fields missing");
    }

    // Prepare SQL statement using prepared statement
    $stmt = $conn->prepare("INSERT INTO personne (nom, num) VALUES (?, ?)");

    // Bind parameters and execute SQL statement
    $stmt->bind_param("si", $data['value1'], $data['value2']); // 's' for string, 'i' for integer
    if ($stmt->execute()) {
        echo "Data inserted successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if num parameter is provided
    if (isset($_GET['num'])) {
        $num = $_GET['num'];

        // Prepare SQL statement using prepared statement
        $stmt = $conn->prepare("SELECT nom, num FROM personne WHERE num = ?");
        
        // Bind parameters and execute SQL statement
        $stmt->bind_param("i", $num); // 'i' for integer
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            echo json_encode($rows);
        } else {
            echo json_encode(array("message" => "No records found"));
        }

        // Close statement
        $stmt->close();
    } else {
        // Fetch all data from the table
        $sql = "SELECT * FROM personne";
        $result = $conn->query($sql);

        $rows = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            echo json_encode($rows);
        } else {
            echo json_encode(array());
        }
    }
}

// Close connection
$conn->close();
?>

