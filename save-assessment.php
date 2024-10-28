<?php
session_start();
include 'config/connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

try {
    $pdo = new PDO($dsn, $db_username, $db_password, $options);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
error_log(print_r($input, true));

if (isset($input['title']) && isset($input['questions']) && isset($input['status'])) {
    $title = $input['title'];
    $questions = $input['questions'];
    $status = $input['status'];
    $timeLimit = isset($input['timeLimit']) ? (int)$input['timeLimit'] : 0;

    $unique_code = null;
    if ($status === 'published') {
        $unique_code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM assessments WHERE unique_code = ?");
        do {
            $stmt->execute([$unique_code]);
        } while ($stmt->fetchColumn() > 0);
    }

    try {
        $sql = "INSERT INTO assessments (title, time_limit, status, unique_code) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $timeLimit, $status, $unique_code]);

        $assessmentId = $pdo->lastInsertId();
        if (!$assessmentId) {
            throw new Exception('Failed to retrieve last assessment ID');
        }

        foreach ($questions as $question) {
            $questionType = $question['questionType'];
            $questionText = $question['questionText'];
            $points = isset($question['points']) && is_numeric($question['points']) ? (int)$question['points'] : 0;  // Ensure points is an integer
        
            $sqlQuestion = "INSERT INTO quiex_questions (assessment_id, question_type, question_text, points) VALUES (?, ?, ?, ?)";
            $stmtQuestion = $pdo->prepare($sqlQuestion);
            $stmtQuestion->execute([$assessmentId, $questionType, $questionText, $points]);
        
            $questionId = $pdo->lastInsertId();
            if (!empty($question['quiex_choices']) && is_array($question['quiex_choices'])) {
                foreach ($question['quiex_choices'] as $choice) {
                    $sqlChoice = "INSERT INTO quiex_choices (question_id, choice_text) VALUES (?, ?)";
                    $stmtChoice = $pdo->prepare($sqlChoice);
                    $stmtChoice->execute([$questionId, $choice]);
                }
            }
        }
        
        echo json_encode([
            'status' => 'success',
            'unique_code' => $unique_code 
        ]);
        exit();

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit(); 
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit(); 
    }

} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid input data'
    ]);
    exit();
}
?>