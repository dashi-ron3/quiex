<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/connection.css">
    <link rel="icon" href="assets/logo-quiex.ico"/>
    <title>Access Denied</title>
</head>
<body>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$dsn = 'mysql:host=localhost;dbname=quiex';

$servername = "localhost";
$db_username = "root";
$db_password = "pochita12";
$dbname = "quiex";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header("Location: index.php");
    exit();
}

$user_type = null;
$sql = "SELECT user_type FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_type);
$stmt->fetch();
$stmt->close();

if (!$user_type) {
    echo "User type not found.";
    exit();
}

$student_pages = ['student-page.php', 'display-q.php', 'rapid-quiz.php', 'study-companion.php', 'student-settings.php', 'download-pdf.php', 'fetch-scores.php', 'fetch-theme.php', 'leaderboard.php', 'pfp-upload.php', 'process-q.php', 'result.php', 'save-profile.php', 'send-feedback.php', 'submit-quiz.php', 'update-theme.php'];
$teacher_pages = ['teacher-page.php', 'qtesting.php', 'question-archive.php', 'teacher-assessments.php', 'grade-viewing.php', 'teacher-settings.php', 'download-grade.php', 'fetch-scores.php', 'fetch-theme.php', 't-pfp-upload.php', 'process-q.php', 'result.php', 'save-profile.php', 'send-feedback.php', 'submit-quiz.php', 'update-theme.php'];

$current_page = basename($_SERVER['PHP_SELF']);
$access_denied = false;

// Restrict access based on user type
if (($user_type == 'student' && !in_array($current_page, $student_pages)) ||
    ($user_type == 'teacher' && !in_array($current_page, $teacher_pages))) {
    $access_denied = true;
}

if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $db_username, $db_password, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($access_denied) {
    echo "
    <div class='access-denied'>
        Access Denied: You do not have permission to view this page.
    </div>";
    exit();
}
?>

</body>
</html>
