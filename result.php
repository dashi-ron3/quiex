<?php
include 'config/connection.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_GET['attempt_id']) && isset($_GET['score']) && isset($_GET['max_score'])) {
    $attemptId = filter_var($_GET['attempt_id'], FILTER_VALIDATE_INT);
    $score = filter_var($_GET['score'], FILTER_VALIDATE_INT);
    $maxScore = filter_var($_GET['max_score'], FILTER_VALIDATE_INT);
    
    $answersStmt = $pdo->prepare("SELECT question_id, student_answer, points_awarded, correct FROM answers WHERE attempt_id = :attempt_id");
    $answersStmt->execute([':attempt_id' => $attemptId]);
    $answers = $answersStmt->fetchAll(PDO::FETCH_ASSOC);

    $quizStmt = $pdo->prepare("SELECT quiz_id FROM attempts WHERE id = :attempt_id");
    $quizStmt->execute([':attempt_id' => $attemptId]);
    $quizId = $quizStmt->fetchColumn();

    echo "<h1>Quiz Results</h1>";
    echo "<p>Your Score: $score / $maxScore</p>";
    echo "<h2>Your Answers</h2>";

    foreach ($answers as $answer) {
        echo "<div>";
        echo "<p>Question ID: " . htmlspecialchars($answer['question_id']) . "</p>";
        echo "<p>Your Answer: " . htmlspecialchars($answer['student_answer']) . "</p>";
        echo "<p>Points Awarded: " . htmlspecialchars($answer['points_awarded']) . "</p>";
        echo "<p>Correct: " . ($answer['correct'] ? 'Yes' : 'No') . "</p>";
        echo "</div><hr>";
    }
} else {
    echo "<script>alert('Invalid attempt. Please try again.');</script>";
    exit;
}
?>