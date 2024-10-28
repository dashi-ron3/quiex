<?php
session_start();
include 'config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $assessment_code = $_POST['assessment_code'];

    $sql = "SELECT id FROM assessments WHERE unique_code = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$assessment_code]);

    $assessment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($assessment) {
        header("Location: take-assessment.php?id=" . $assessment['id']);
        exit();
    } else {
        header("Location: index.php?error=1");
        exit();
    }
}
?>