<?php
// Database Configuration
$host = 'localhost';      // Database Host (e.g., localhost or IP address)
$user = 'root';           // Database Username
$password = '';           // Database Password
$dbname = 'db_k3'; // Replace with your database name

// Create Connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Base URL Configuration
function base_url($path = '') {
    $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
                "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
    return rtrim($base_url, '/') . '/' . ltrim($path, '/');
}
?>
