<?php
// Database configuration
$host = 'localhost';
$dbname = 'qtest';
$username = 'root';
$password = '15a5m249ph';

// Establish a database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if form is submitted with necessary data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quiz_id'], $_POST['answers'])) {
    $quizId = filter_var($_POST['quiz_id'], FILTER_VALIDATE_INT);
    $answers = $_POST['answers'];
    $correctAnswers = $_POST['correct_answer'];
    $points = $_POST['points'];
    
    $totalScore = 0;
    $maxScore = 0;
    $results = [];

    // Iterate over each question to calculate score
    foreach ($answers as $questionId => $studentAnswer) {
        $correctAnswer = trim($correctAnswers[$questionId]);
        $pointValue = (int)$points[$questionId];
        $maxScore += $pointValue;

        // Check the answer based on the question type
        $questionTypeStmt = $pdo->prepare("SELECT question_type FROM questions WHERE id = :questionId");
        $questionTypeStmt->execute([':questionId' => $questionId]);
        $questionType = $questionTypeStmt->fetchColumn();

        $isCorrect = false;

        switch ($questionType) {
            case 'short_answer':
            case 'multiple_choice':
            case 'true_false':
                $isCorrect = strcasecmp(trim($studentAnswer), $correctAnswer) === 0;
                break;
            case 'essay':
                $isCorrect = false; // To be graded manually
                break;
        }

        // Calculate total score
        if ($isCorrect) {
            $totalScore += $pointValue;
            $results[$questionId] = [
                'correct' => true,
                'points_awarded' => $pointValue,
                'student_answer' => $studentAnswer,
                'correct_answer' => $correctAnswer
            ];
        } else {
            $results[$questionId] = [
                'correct' => false,
                'points_awarded' => 0,
                'student_answer' => $studentAnswer,
                'correct_answer' => $correctAnswer
            ];
        }
    }

    // Save attempt details
    $stmt = $pdo->prepare("INSERT INTO attempts (quiz_id, score, max_score) VALUES (:quiz_id, :score, :max_score)");
    $stmt->execute([
        ':quiz_id' => $quizId,
        ':score' => $totalScore,
        ':max_score' => $maxScore
    ]);
    $attemptId = $pdo->lastInsertId(); // Get the ID of the last inserted attempt

    // Save individual answers
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

    // Display the results
    echo "<h2>Your Score: $totalScore / $maxScore</h2>";
    foreach ($results as $questionId => $result) {
        echo "<p>Question ID: $questionId | Correct: " . ($result['correct'] ? 'Yes' : 'No') . " | Points Awarded: {$result['points_awarded']}</p>";
    }
} else {
    die("Quiz submission failed. Please answer all questions.");
}
?>
