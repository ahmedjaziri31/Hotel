<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "hotel_complaint_system";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set to utf8
$conn->set_charset("utf8");
?>
