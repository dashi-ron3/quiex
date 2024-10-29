<?php
session_start();
include 'config/connection.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$assessmentId = $input['assessmentId'];
$answers = $input['answers'];

if (empty($assessmentId) || empty($answers)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
    exit();
}

try {
    $sql = "SELECT id, title FROM assessments WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$assessmentId]);
    $assessment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$assessment) {
        echo json_encode(['status' => 'error', 'message' => 'Assessment not found']);
        exit();
    }

    foreach ($answers as $questionId => $answerId) {
        $sqlAnswer = "INSERT INTO assessment_results (assessment_id, question_id, answer_id) VALUES (?, ?, ?)";
        $stmtAnswer = $pdo->prepare($sqlAnswer);
        $stmtAnswer->execute([$assessmentId, $questionId, $answerId]);
    }

    echo json_encode(['status' => 'success', 'message' => 'Assessment submitted successfully!']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
