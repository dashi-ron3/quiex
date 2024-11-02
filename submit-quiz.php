<?php
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

// Check if form is submitted with necessary data
// Inside submit-quiz.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quiz_id'], $_POST['answer'])) {
    $quizId = filter_var($_POST['quiz_id'], FILTER_VALIDATE_INT);
    $answers = $_POST['answer'];

    // Validate answers
    if (empty($answers)) {
        echo "<script>alert('Please answer all questions.');</script>";
        exit;
    }

    $totalScore = 0;
    $maxScore = 0;
    $results = [];

    // Iterate over each question to calculate score
    foreach ($answers as $questionId => $studentAnswer) {
        // Get the correct answer from the database
        $correctAnswerStmt = $pdo->prepare("SELECT correct_answer, points FROM questions WHERE id = :questionId");
        $correctAnswerStmt->execute([':questionId' => $questionId]);
        $correctAnswerData = $correctAnswerStmt->fetch(PDO::FETCH_ASSOC);

        if ($correctAnswerData) {
            $correctAnswer = $correctAnswerData['correct_answer'];
            $pointValue = (int)$correctAnswerData['points'];
            $maxScore += $pointValue;

            // Check if the student's answer matches the correct answer
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
    
    // Save attempt details
    $stmt = $pdo->prepare("INSERT INTO attempts (quiz_id, score, max_score) VALUES (:quiz_id, :score, :max_score)");
    $stmt->execute([
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

    header("Location: results.php?attempt_id=$attemptId&score=$totalScore&max_score=$maxScore");
    exit;

} else {
    echo "<script>alert('Quiz submission failed. Please answer all questions.');</script>";
}

if (isset($_FILES['uploaded_files'])) {
    $uploadDir = 'uploads-assessments/';
    $uploadedFiles = [];

    foreach ($_FILES['uploaded_files']['name'] as $key => $name) {
        if ($_FILES['uploaded_files']['error'][$key] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['uploaded_files']['tmp_name'][$key];
            $filePath = $uploadDir . basename($name);
            if (move_uploaded_file($tmpName, $filePath)) {
                $uploadedFiles[] = $filePath; 
            } else {
                echo "Failed to move uploaded file: $name";
            }
        } else {
            echo "Error uploading file: $name";
        }
    }
}
?>