<?php
require_once('tcpdf/tcpdf.php');
session_start();

$servername = "localhost";
$db_username = "root";
$db_password = "pochita12";
$dbname = "quiex";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header("Location: login.php");
    exit();
}

// Sample user ID
//$user_id = 1;

if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];

    $quiz = $conn->query("SELECT * FROM quizzes WHERE id = $quiz_id AND user_id = $user_id")->fetch_assoc();
    $questions = $conn->query("SELECT * FROM questions WHERE quiz_id = $quiz_id");

    if (!$quiz) {
        die("Quiz not found.");
    }

    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('QuiEx');
    $pdf->SetTitle($quiz['title']);
    $pdf->AddPage();

    // quiz info
    $pdf->Cell(0, 10, $quiz['title'] . " Review", 0, 1, 'C');
    $pdf->Ln(8);
    $pdf->Cell(0, 10, "Started on: " . date('m/d/y h:i A', strtotime($quiz['started_at'])), 0, 1);
    $pdf->Cell(0, 10, "Finished on: " . date('m/d/y h:i A', strtotime($quiz['finished_at'])), 0, 1);
    $pdf->Cell(0, 10, "Time taken: " . gmdate('H:i:s', strtotime($quiz['finished_at']) - strtotime($quiz['started_at'])), 0, 1);
    $pdf->Cell(0, 10, "Marks: {$quiz['marks']} out of {$quiz['total_marks']}", 0, 1);
    $pdf->Cell(0, 10, "Score: " . ($quiz['is_graded'] ? "{$quiz['points']}" : "N/A"), 0, 1);

    // questions and answers
    $pdf->Ln(10);
    $i = 1;
    while ($question = $questions->fetch_assoc()) {
        $pdf->Cell(0, 10, "Question $i: {$question['text']}", 0, 1);

        $defaultTextColor = [0, 0, 0]; // black
        $pdf->SetTextColor(...$defaultTextColor);

        $choices = $conn->query("SELECT * FROM choices WHERE question_id = {$question['id']}");
        while ($choice = $choices->fetch_assoc()) {
            $user_answer_query = "
                SELECT ua.answer_id
                FROM user_answers ua
                WHERE ua.quiz_id = $quiz_id AND ua.question_id = {$question['id']}
            ";
            $user_answer = $conn->query($user_answer_query)->fetch_assoc();
            $user_answer_id = $user_answer ? $user_answer['answer_id'] : null;

            if ($choice['is_correct']) {
                $pdf->SetTextColor(111, 180, 110); // green
                $pdf->Cell(0, 10, "- {$choice['text']}", 0, 1);
            } else {
                if ($user_answer_id == $choice['id']) {
                    $pdf->SetTextColor(255, 0, 0); // red
                    $pdf->Cell(0, 10, "- {$choice['text']} (Your Answer)", 0, 1);
                } else {
                    $pdf->SetTextColor(0, 0, 0); // black
                    $pdf->Cell(0, 10, "- {$choice['text']}", 0, 1);
                }
            }
        }
        $pdf->SetTextColor(...$defaultTextColor);
        $pdf->Ln(5);
        $i++;
    }


    $pdf->Output("{$quiz['title']}_Review.pdf", 'D');
}

$conn->close();
?>
