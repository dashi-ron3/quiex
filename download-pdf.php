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

/*if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header("Location: index.php");
    exit();
}*/

// Sample user ID
$user_id = 3;

if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];

        // Fetch quiz and attempt details
    $attempt_query = "
    SELECT attempts.started_at, attempts.submitted_at AS finished_at, 
        attempts.score AS marks, attempts.max_score AS total_marks, 
        attempts.score AS points, quizzes.title
    FROM attempts
    JOIN quizzes ON attempts.quiz_id = quizzes.id
    WHERE attempts.user_id = $user_id AND attempts.quiz_id = $quiz_id
    ";
    $quiz = $conn->query($attempt_query)->fetch_assoc();

    $questions = $conn->query("SELECT * FROM questions WHERE quiz_id = $quiz_id");

    if (!$quiz) {
        die("Quiz not found.");
    }

    $pdf = new TCPDF();
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('QuiEx');
    $pdf->SetTitle($quiz['title']);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, $quiz['title'] . " Review", 0, 1, 'C');
    $pdf->Ln(5);

$containerWidth = $pdf->getPageWidth() - $pdf->getMargins()['left'] - $pdf->getMargins()['right'];
$pdf->SetFont('helvetica', '', 12);
$pdf->MultiCell($containerWidth, 12, "Started on: " . date('m/d/y h:i A', strtotime($quiz['started_at'])) . "\n" .
                                       "Finished on: " . date('m/d/y h:i A', strtotime($quiz['finished_at'])) . "\n" .
                                       "Time taken: " . gmdate('H:i:s', strtotime($quiz['finished_at']) - strtotime($quiz['started_at'])) . "\n" .
                                       "Marks: {$quiz['marks']} out of {$quiz['total_marks']}\n" .
                                       "Score: " . $quiz['points'], 
              0, 'C', 0, 1, '', '', true);
$pdf->Ln(5);



    $i = 1;
    while ($question = $questions->fetch_assoc()) {
        $containerWidth = 180;
        $baseContainerHeight = 50;
        $spacing = 15;
        $startY = $pdf->GetY();

        if ($startY + $baseContainerHeight + $spacing > $pdf->getPageHeight() - $pdf->getMargins()['bottom']) {
            $pdf->AddPage();
            $startY = $pdf->GetY();
        }

        $questionHeight = $pdf->getStringHeight($containerWidth, "Question $i: {$question['question_text']}") + 5;
        $choicesHeight = 0;
        
        $choices = $conn->query("SELECT * FROM options WHERE question_id = {$question['id']}");
        while ($choice = $choices->fetch_assoc()) {
            $choicesHeight += $pdf->getStringHeight($containerWidth, "- {$choice['option_text']}") + 5;
        }

        $totalHeight = $questionHeight + $choicesHeight + 10;
        $containerHeight = max($baseContainerHeight, $totalHeight);

        $pdf->SetFillColor(245, 245, 245);
        $pdf->Rect(15, $startY, $containerWidth, $containerHeight, 'DF');
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Rect(15, $startY, $containerWidth, $containerHeight, 'D');

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->MultiCell($containerWidth, 10, "Question $i: {$question['question_text']}", 0, 'L', 0, 1, '15', $startY + 5);

        $userAnswersQuery = "
            SELECT a.question_id, a.student_answer, a.correct, o.id AS option_id, o.option_text AS answer_text
            FROM answers a
            JOIN options o ON a.student_answer = o.id 
            WHERE a.attempt_id = (SELECT id FROM attempts WHERE quiz_id = $quiz_id AND user_id = $user_id)
        ";
        $user_answer = $conn->query($userAnswersQuery)->fetch_assoc();
        $user_answer_id = $user_answer ? $user_answer['student_answer'] : null;

        $pdf->SetY($startY + $questionHeight + 10);
        $choices = $conn->query("SELECT * FROM options WHERE question_id = {$question['id']}");
        while ($choice = $choices->fetch_assoc()) {
            $is_user_answer = ($choice['id'] == $user_answer_id);
            $is_correct_answer = ($user_answer && $user_answer['correct'] == 1);
            $choice_text = "- {$choice['option_text']}";

            if ($is_correct_answer && $is_user_answer) {
                $pdf->SetTextColor(111, 180, 110);
                $choice_text .= " (Your Answer)";
            } elseif ($is_correct_answer) {
                $pdf->SetTextColor(111, 180, 110);
                $choice_text .= " (Correct Answer)";
            } elseif ($is_user_answer) {
                $pdf->SetTextColor(255, 0, 0);
                $choice_text .= " (Your Answer)";
            } else {
                $pdf->SetTextColor(0, 0, 0);
            }

            $pdf->MultiCell($containerWidth - 10, 8, $choice_text, 0, 'L', 0, 1, '25');
        }

        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln($spacing);
        $i++;
    }

    $pdf->Output("{$quiz['title']} Review.pdf", 'D');
}

$conn->close();
?>
