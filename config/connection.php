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

echo "<style>
    body {
        background-color: #CBD5E0;
    }
</style>";

if ($access_denied) {
    echo "
    <div style='
        color: #ffffff;
        background-color: #e74c3c;
        padding: 15px;
        border-radius: 5px;
        margin: 20px auto;
        text-align: center;
        max-width: 500px;
        font-size: 18px;
        font-weight: bold;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    '>
        Access Denied: You do not have permission to view this page.
    </div>";
    exit();
}
?>
