<?php
session_start();

// Database credentials
$servername = "localhost";
$db_username = "root";
$db_password = "pochita12";
$dbname = "quiex";

// Establish a database connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check user authentication
if (!isset($_SESSION['user_id'])) {
    // For testing, set user_id to 1 explicitly if not already set
    $_SESSION['user_id'] = 1;  // Remove this line in production
}
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quiz_id'], $_POST['answer'])) {
    $quizId = filter_var($_POST['quiz_id'], FILTER_VALIDATE_INT);
    $answers = $_POST['answer'];

    // Validate the quiz ID and answers
    if (!$quizId || empty($answers)) {
        echo "<script>alert('Quiz ID or answers are invalid.');</script>";
        exit;
    }

    $totalScore = 0;
    $maxScore = 0;
    $results = [];

    // Fetch the quiz title
    $quizTitleStmt = $pdo->prepare("SELECT title FROM quizzes WHERE id = :quiz_id");
    $quizTitleStmt->execute([':quiz_id' => $quizId]);
    $quizTitleData = $quizTitleStmt->fetch(PDO::FETCH_ASSOC);
    if (!$quizTitleData) {
        die("Quiz not found.");
    }
    $quizTitle = htmlspecialchars($quizTitleData['title']);

    // Calculate the total score and gather question results
    foreach ($answers as $questionId => $studentAnswerId) {
        // Get the correct answer and point value
        $correctAnswerStmt = $pdo->prepare("SELECT correct_answer, points FROM questions WHERE id = :questionId");
        $correctAnswerStmt->execute([':questionId' => $questionId]);
        $correctAnswerData = $correctAnswerStmt->fetch(PDO::FETCH_ASSOC);

        if ($correctAnswerData) {
            $correctAnswer = $correctAnswerData['correct_answer'];
            $pointValue = (int)$correctAnswerData['points'];
            $maxScore += $pointValue;

            // Fetch the actual text of the student's answer
            $optionStmt = $pdo->prepare("SELECT option_text FROM options WHERE id = :optionId");
            $optionStmt->execute([':optionId' => $studentAnswerId]);
            $studentAnswerData = $optionStmt->fetch(PDO::FETCH_ASSOC);

            $studentAnswerText = $studentAnswerData ? htmlspecialchars($studentAnswerData['option_text']) : '';

            if ($studentAnswerText == $correctAnswer) {
                $totalScore += $pointValue;
                $results[$questionId] = [
                    'correct' => true,
                    'points_awarded' => $pointValue,
                    'student_answer' => $studentAnswerText,
                    'correct_answer' => htmlspecialchars($correctAnswer)
                ];
            } else {
                $results[$questionId] = [
                    'correct' => false,
                    'points_awarded' => 0,
                    'student_answer' => $studentAnswerText,
                    'correct_answer' => htmlspecialchars($correctAnswer)
                ];
            }
        } else {
            echo "<script>alert('Question ID $questionId not found.');</script>";
            exit;
        }
    }

    // Insert the attempt data into the database
    try {
        $stmt = $pdo->prepare("INSERT INTO attempts (user_id, quiz_id, quiz_title, score, max_score) VALUES (:user_id, :quiz_id, :quiz_title, :score, :max_score)");
        $stmt->execute([
            ':user_id' => $userId,
            ':quiz_id' => $quizId,
            ':quiz_title' => $quizTitle,
            ':score' => $totalScore,
            ':max_score' => $maxScore
        ]);
        $attemptId = $pdo->lastInsertId();

        // Insert each answer result for the attempt
        foreach ($results as $questionId => $result) {
            $stmt = $pdo->prepare("INSERT INTO answers (attempt_id, question_id, quiz_id, student_answer, points_awarded, correct) VALUES (:attempt_id, :question_id, :quiz_id, :student_answer, :points_awarded, :correct)");
            $stmt->execute([
                ':attempt_id' => $attemptId,
                ':question_id' => $questionId,
                ':quiz_id' => $quizId,
                ':student_answer' => $result['student_answer'],
                ':points_awarded' => $result['points_awarded'],
                ':correct' => $result['correct'] ? 1 : 0
            ]);
        }

        echo "<script>alert('Quiz submitted successfully.'); window.location.href = 'leaderboard.php';</script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Error saving attempt: " . addslashes($e->getMessage()) . "');</script>";
    }
} else {
    echo "<script>alert('Invalid request method or missing data.');</script>";
}
?>
