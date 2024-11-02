<?php
include 'config/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quiz_subject = mysqli_real_escape_string($conn, $_POST['quiz-subject']);
    $quiz_title = mysqli_real_escape_string($conn, $_POST['quiz-title']);
    $quiz_description = mysqli_real_escape_string($conn, $_POST['quiz-description']);
    $open_date = mysqli_real_escape_string($conn, $_POST['openDate']);
    $close_date = mysqli_real_escape_string($conn, $_POST['closeDate']);
    $max_attempts = mysqli_real_escape_string($conn, $_POST['maxAttempts']);
    $randomize_order = isset($_POST['randomizeOrder']) ? 1 : 0;

    // $quiz_code = 'QZ-' . strtoupper(dechex(time())); // or generating a code

    $quiz_sql = "INSERT INTO quizzes (subject, title, description, open_date, close_date, max_attempts, randomize_order) 
                 VALUES ('$quiz_subject', '$quiz_title', '$quiz_description', '$open_date', '$close_date', '$max_attempts', '$randomize_order')";

    if (mysqli_query($conn, $quiz_sql)) {
        $quiz_id = mysqli_insert_id($conn);

        $question_texts = $_POST['question_text'];
        $question_types = $_POST['question_type'];
        $correct_answers = $_POST['correct_answer'];
        $points = $_POST['points'];
        $feedback = $_POST['feedback'];
        
        foreach ($question_texts as $index => $question_text) {
            $question_text = mysqli_real_escape_string($conn, $question_text);
            $question_type = mysqli_real_escape_string($conn, $question_types[$index]);
            $correct_answer = mysqli_real_escape_string($conn, $correct_answers[$index]);
            $points_value = mysqli_real_escape_string($conn, $points[$index]);
            $feedback_value = mysqli_real_escape_string($conn, $feedback[$index]);

            $question_sql = "INSERT INTO questions (quiz_id, question_text, question_type, correct_answer, points, feedback) 
                             VALUES ('$quiz_id', '$question_text', '$question_type', '$correct_answer', '$points_value', '$feedback_value')";
            if (mysqli_query($conn, $question_sql)) {
                $question_id = mysqli_insert_id($conn); 

                if ($question_type === 'multiple_choice') {
                    $option_texts = $_POST['option_text'][$index]; 
                    foreach ($option_texts as $option_text) {
                        $option_text = mysqli_real_escape_string($conn, $option_text);
                        $option_sql = "INSERT INTO options (question_id, option_text) VALUES ('$question_id', '$option_text')";
                        mysqli_query($conn, $option_sql);
                    }
                }
            } else {
                echo "Error inserting question: " . mysqli_error($conn);
            }

            if (isset($_FILES['imagePath']) && $_FILES['imagePath']['error'][$index] == UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['imagePath']['tmp_name'][$index];
                $file_name = basename($_FILES['imagePath']['name'][$index]);
                $upload_dir = 'upload-assessment/'; 
                $target_file = $upload_dir . $file_name;
                if (move_uploaded_file($file_tmp, $target_file)) {
                    $image_sql = "UPDATE questions SET image_path = '$target_file' WHERE id = '$question_id'";
                    mysqli_query($conn, $image_sql);
                }
            }
        }

        header("Location: qtesting.php?quiz_id=" . $quiz_id);
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request method.";
}

mysqli_close($conn);
?>