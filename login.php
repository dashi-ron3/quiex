<?php
session_start();
header('Content-Type: application/json');

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

    // Fetch the user's theme from the database
    $stmt = $conn->prepare("SELECT theme FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($theme);
    $stmt->fetch();

    if ($theme) {
        $_SESSION['theme'] = $theme;
    } else {
        $_SESSION['theme'] = 'light';
    }

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
