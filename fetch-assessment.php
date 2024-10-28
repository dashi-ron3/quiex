<?php
include 'config/connection.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$unique_code = $input['unique_code'];

if (!$unique_code) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid unique code']);
    exit();
}

try {
    $sql = "SELECT id, title FROM assessments WHERE unique_code = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$unique_code]);
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
        $sqlChoices = "SELECT id, choice_text FROM quiex_choices WHERE question_id = ?";
        $stmtChoices = $pdo->prepare($sqlChoices);
        $stmtChoices->execute([$questionId]);
        $question['choices'] = $stmtChoices->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode([
        'status' => 'success',
        'title' => $title,
        'questions' => $questions
    ]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
