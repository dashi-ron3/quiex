<?php
$servername = "localhost";
$db_username = "root";
$db_password = "yannigonzales";
$dbname = "quiex";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("sss", $username, $email, $hashed_password);

$username = htmlspecialchars($_POST['username']);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

if ($stmt->execute()) {
    header("Location: classification.php");
    exit(); 
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
