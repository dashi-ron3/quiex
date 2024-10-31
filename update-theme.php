<?php
session_start();
header('Content-Type: application/json');

$servername = "localhost";
$db_username = "root";
$db_password = "pochita12";
$dbname = "quiex";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed']));
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$theme = $data['theme'] ?? '';
$user_id = $_SESSION['user_id'] ?? '';

if (!isset($data['theme'])) {
    echo json_encode(['status' => 'error', 'message' => 'Theme not provided']);
    exit();
}

$_SESSION['theme'] = $theme;

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("UPDATE users SET theme = ? WHERE id = ?");
$stmt->bind_param("si", $theme, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'theme' => $theme]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update theme']);
}

$stmt->close();
$conn->close();
?>