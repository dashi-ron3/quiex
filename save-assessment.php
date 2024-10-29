<?php
session_start();
include 'config/connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $db_username, $db_password, $options);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
error_log(print_r($input, true));

if (isset($input['title'], $input['questions'], $input['status'])) {
    $title = $input['title'];
    $questions = $input['questions'];
    $status = $input['status'];

    $unique_code = null;
    if ($status === 'published') {
        do {
            $unique_code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM assessments WHERE unique_code = ?");
            $stmt->execute([$unique_code]);
        } while ($stmt->fetchColumn() > 0);
    }

    try {
        $sql = "INSERT INTO assessments (title, status, unique_code) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $status, $unique_code]);

        $assessmentId = $pdo->lastInsertId();
        if (!$assessmentId) {
            throw new Exception('Failed to retrieve last assessment ID');
        }

        foreach ($questions as $question) {
            $questionType = $question['questionType'];
            $questionText = $question['questionText'];
            $points = isset($question['points']) && is_numeric($question['points']) ? (int)$question['points'] : 0;

            $sqlQuestion = "INSERT INTO quiex_questions (assessment_id, question_type, question_text, points) VALUES (?, ?, ?, ?)";
            $stmtQuestion = $pdo->prepare($sqlQuestion);
            $stmtQuestion->execute([$assessmentId, $questionType, $questionText, $points]);

            $questionId = $pdo->lastInsertId();
            if (!empty($question['quiex_choices']) && is_array($question['quiex_choices'])) {
                foreach ($question['quiex_choices'] as $choice) {
                    error_log("Choice: " . print_r($choice, true));
                    if (!empty($choice)) {
                        try {
                            $sqlChoice = "INSERT INTO quiex_choices (question_id, choice_text) VALUES (?, ?)";
                            $stmtChoice = $pdo->prepare($sqlChoice);
                            $stmtChoice->execute([$questionId, $choice]);
                        } catch (PDOException $e) {
                            error_log("Error inserting choice: " . $e->getMessage());
                        }
                    }
                }
            }
        }

        echo json_encode(['status' => 'success', 'unique_code' => $unique_code]);
        exit();

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit();
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
    exit();
}
?>
