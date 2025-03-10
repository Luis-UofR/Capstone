<?php
// Database connection settings
$host = 'localhost';
$db = 'accounting_app';
$user = 'root'; // Should be root (checked on phpMyAdmin)
$password = ''; // There is no password

// Create connection
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";
?>
