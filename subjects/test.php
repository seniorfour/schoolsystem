<?php
$conn = new mysqli('localhost', 'root', '', 'school_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "✅ Connection successful!";
?>
