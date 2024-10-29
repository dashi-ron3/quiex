<?php
session_start();

$servername = "localhost";
$db_username = "root";
$db_password = "pochita12";
$dbname = "quiex";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $username);

$username = htmlspecialchars($_POST['username']);
$password = $_POST['password']; 

$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id, $fetched_username, $fetched_hashed_password);
    $stmt->fetch();

    if (password_verify($password, $fetched_hashed_password)) {
        $_SESSION['username'] = $fetched_username;
        $_SESSION['user_id'] = $user_id;

        header("Location: classification.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }
} else {
    echo "Invalid username or password.";
}

$stmt->close();
$conn->close();
?>