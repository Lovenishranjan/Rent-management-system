<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'rent_management';
$port = 3307; // Change if your MySQL runs on another port

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
