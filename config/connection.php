<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// replace what needs to be replaced
$servername = "localhost";
$db_username = "root";
$db_password = "yannigonzales";
$dbname = "quiex";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>