<?php
// db.php - Database connection file

$servername = "localhost";
$username = "root";
$password = ""; // Your MySQL password
$dbname = "school_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>