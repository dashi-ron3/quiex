<?php
session_start();

$servername = "localhost";
$db_username = "root";
$db_password = "yannigonzales";
$dbname = "quiex";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quiz_id'], $_POST['answer'])) {
    $quizId = filter_var($_POST['quiz_id'], FILTER_VALIDATE_INT);
    $answers = $_POST['answer'];

    if (empty($answers)) {
        echo "<script>alert('Please answer all questions.');</script>";
        exit;
    }

    $totalScore = 0;
    $maxScore = 0;
    $results = [];

    foreach ($answers as $questionId => $studentAnswer) {
        $correctAnswerStmt = $pdo->prepare("SELECT correct_answer, points FROM questions WHERE id = :questionId");
        $correctAnswerStmt->execute([':questionId' => $questionId]);
        $correctAnswerData = $correctAnswerStmt->fetch(PDO::FETCH_ASSOC);

        if ($correctAnswerData) {
            $correctAnswer = $correctAnswerData['correct_answer'];
            $pointValue = (int)$correctAnswerData['points'];
            $maxScore += $pointValue;

            if ($studentAnswer == $correctAnswer) {
                $totalScore += $pointValue;
                $results[$questionId] = [
                    'correct' => true,
                    'points_awarded' => $pointValue,
                    'student_answer' => htmlspecialchars($studentAnswer),
                    'correct_answer' => htmlspecialchars($correctAnswer)
                ];
            } else {
                $results[$questionId] = [
                    'correct' => false,
                    'points_awarded' => 0,
                    'student_answer' => htmlspecialchars($studentAnswer),
                    'correct_answer' => htmlspecialchars($correctAnswer)
                ];
            }
        }
    }

    $stmt = $pdo->prepare("INSERT INTO attempts (user_id, quiz_id, score, max_score) VALUES (:user_id, :quiz_id, :score, :max_score)");
    $stmt->execute([
        ':user_id' => $userId,
        ':quiz_id' => $quizId,
        ':score' => $totalScore,
        ':max_score' => $maxScore
    ]);
    $attemptId = $pdo->lastInsertId();

    foreach ($results as $questionId => $result) {
        $stmt = $pdo->prepare("INSERT INTO answers (attempt_id, question_id, student_answer, points_awarded, correct) VALUES (:attempt_id, :question_id, :student_answer, :points_awarded, :correct)");
        $stmt->execute([
            ':attempt_id' => $attemptId,
            ':question_id' => $questionId,
            ':student_answer' => $result['student_answer'],
            ':points_awarded' => $result['points_awarded'],
            ':correct' => $result['correct'] ? 1 : 0
        ]);
    }

    header("Location: leaderboard.php");
    exit;
} else {
    echo "<script>alert('Quiz submission failed. Please answer all questions.');</script>";
}
?>
