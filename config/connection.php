<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// replace what needs to be replaced
$dsn = 'mysql:host=localhost;dbname=quiex';
$servername = "localhost";
$db_username = "root";
$db_password = "yannigonzales";
$dbname = "quiex";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
