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
    header("Location: index.php");
    exit();
}

if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];

    $attempt_query = "
    SELECT attempts.started_at, attempts.submitted_at AS finished_at, 
        attempts.score AS marks, attempts.max_score AS total_marks, 
        quizzes.title
    FROM attempts
    JOIN quizzes ON attempts.quiz_id = quizzes.id
    WHERE attempts.user_id = $user_id AND attempts.quiz_id = $quiz_id
    ";
    $quiz = $conn->query($attempt_query)->fetch_assoc();

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
                                       "Marks: {$quiz['marks']} out of {$quiz['total_marks']}\n", 
              0, 'C', 0, 1, '', '', true);
    $pdf->Ln(5);

    $questions = $conn->query("SELECT * FROM questions WHERE quiz_id = $quiz_id");
    $userAnswersQuery = "
        SELECT a.question_id, a.student_answer
        FROM answers a
        WHERE a.attempt_id = (SELECT MAX(id) FROM attempts WHERE quiz_id = $quiz_id AND user_id = $user_id)
    ";
    $userAnswers = $conn->query($userAnswersQuery);
    $userAnswerMap = [];
    while ($answer = $userAnswers->fetch_assoc()) {
        $userAnswerMap[$answer['question_id']] = $answer;
    }

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
    
        $pdf->SetFillColor(245, 245, 245);
        $pdf->Rect(15, $startY, $containerWidth, $baseContainerHeight, 'DF');
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Rect(15, $startY, $containerWidth, $baseContainerHeight, 'D');
    
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->MultiCell($containerWidth, 10, "Question $i: {$question['question_text']}", 0, 'L', 0, 1, '15', $startY + 5);
    
        $user_answer = isset($userAnswerMap[$question['id']]) ? $userAnswerMap[$question['id']] : null;
        $user_answer_text = $user_answer ? $user_answer['student_answer'] : 'No Answer';

        $questionHeight = $pdf->getStringHeight($containerWidth, "Question $i: {$question['question_text']}") + 5;
    
        if ($question['question_type'] == 'multiple_choice') {
            $correct_answer_text = $question['correct_answer'];
    
            $pdf->SetY($startY + $questionHeight + 10);
            $choices = $conn->query("SELECT * FROM options WHERE question_id = {$question['id']}");
    
            while ($choice = $choices->fetch_assoc()) {
                $is_user_answer = ($choice['option_text'] == $user_answer_text);
                $is_correct_answer = ($correct_answer_text == $choice['option_text']);
                $choice_text = "- {$choice['option_text']}";
    
                $pdf->SetTextColor(0, 0, 0);
    
                if ($is_user_answer) {
                    if (!$is_correct_answer) {
                        $pdf->SetTextColor(203, 91, 91);
                        $choice_text .= " (Your Answer)";
                    }
                } 
    
                if ($is_correct_answer) {
                    $pdf->SetTextColor(111, 180, 110);
                    $choice_text .= " (Correct Answer)";
                }
    
                $pdf->MultiCell($containerWidth - 10, 8, $choice_text, 0, 'L', 0, 1, '25');
            }
    
            if ($user_answer_text !== $correct_answer_text) {
                $pdf->SetTextColor(203, 91, 91);
                $pdf->MultiCell($containerWidth - 10, 8, "Your Answer: $user_answer_text", 0, 'L', 0, 1, '25');
                $pdf->SetTextColor(0, 0, 0);
            }
        } elseif (in_array($question['question_type'], ['short_answer', 'true_false', 'essay'])) {
            $correct_answer = $question['correct_answer'];
    
            if ($user_answer_text == $correct_answer) {
                $pdf->SetTextColor(111, 180, 110);
            } else {
                $pdf->SetTextColor(203, 91, 91);
            }
    
            $pdf->MultiCell($containerWidth - 10, 8, "Your Answer: $user_answer_text", 0, 'L', 0, 1, '25');
    
            $pdf->SetTextColor(111, 180, 110);
            $pdf->MultiCell($containerWidth - 10, 8, "Correct Answer: $correct_answer", 0, 'L', 0, 1, '25');
    
            $pdf->SetTextColor(0, 0, 0);
        }
    
        $pdf->SetY($startY + $baseContainerHeight + $spacing);
        $i++;
    }
    
    $pdf->Output("{$quiz['title']} Review.pdf", 'D');
} else {
    die("No quiz ID specified.");
}

$conn->close();
?>
