<?php
session_start();
include 'config/connection.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$unique_code = $input['unique_code'] ?? null;
$assessmentId = $input['assessmentId'] ?? null;

if (!$unique_code && !$assessmentId) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid unique code or assessment ID']);
    exit();
}

try {
    if ($unique_code) {
        $sql = "SELECT id, title FROM assessments WHERE unique_code = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$unique_code]);
    } else {
        $sql = "SELECT id, title FROM assessments WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$assessmentId]);
    }
    
    $assessment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$assessment) {
        echo json_encode(['status' => 'error', 'message' => 'Assessment not found']);
        exit();
    }

    $assessmentId = $assessment['id'];
    $title = $assessment['title'];

    $sqlQuestions = "SELECT id, question_type, question_text FROM quiex_questions WHERE assessment_id = ?";
    $stmtQuestions = $pdo->prepare($sqlQuestions);
    $stmtQuestions->execute([$assessmentId]);
    $questions = $stmtQuestions->fetchAll(PDO::FETCH_ASSOC);

    foreach ($questions as &$question) {
        $questionId = $question['id'];
        $sqlChoices = "SELECT id, choice_text, is_correct FROM quiex_choices WHERE question_id = ?";
        $stmtChoices = $pdo->prepare($sqlChoices);
        $stmtChoices->execute([$questionId]);
        $question['choices'] = $stmtChoices->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode([
        'status' => 'success',
        'assessment' => [
            'title' => $title,
            'questions' => $questions
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
