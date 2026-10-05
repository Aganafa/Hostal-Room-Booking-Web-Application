<?php
// config/database.php

$host = 'localhost';
$db_name = 'hostel_booking';
$username = 'root';
$password = '';

// Define base URL to avoid routing issues on different setups
define('BASE_URL', '/hostal room booking web application');

try {
    $conn = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
    exit();
}
?>
