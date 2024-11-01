<?php
// process-q.php

// Include your database connection file
$host = 'localhost'; // or your database host
$user = 'root'; // your database username
$password = '15a5m249ph'; // your database password
$database = 'qtest'; // your database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve the quiz information
    $quiz_subject = mysqli_real_escape_string($conn, $_POST['quiz-subject']);
    $quiz_title = mysqli_real_escape_string($conn, $_POST['quiz-title']);
    $quiz_description = mysqli_real_escape_string($conn, $_POST['quiz-description']);
    $open_date = mysqli_real_escape_string($conn, $_POST['openDate']);
    $close_date = mysqli_real_escape_string($conn, $_POST['closeDate']);
    $timer_hours = mysqli_real_escape_string($conn, $_POST['timerHours']);
    $timer_minutes = mysqli_real_escape_string($conn, $_POST['timerMinutes']);
    $max_attempts = mysqli_real_escape_string($conn, $_POST['maxAttempts']);
    $randomize_order = isset($_POST['randomizeOrder']) ? 1 : 0;

    // Insert quiz info into the quizzes table
    $quiz_sql = "INSERT INTO quizzes (subject, title, description, open_date, close_date, timer_hours, timer_minutes, max_attempts, randomize_order) VALUES ('$quiz_subject', '$quiz_title', '$quiz_description', '$open_date', '$close_date', '$timer_hours', '$timer_minutes', '$max_attempts', '$randomize_order')";
    if (mysqli_query($conn, $quiz_sql)) {
        $quiz_id = mysqli_insert_id($conn); // Get the last inserted quiz ID

        // Prepare to handle questions
        $question_texts = $_POST['question_text'];
        $question_types = $_POST['question_type'];
        $correct_answers = $_POST['correct_answer'];
        $points = $_POST['points'];
        $feedback = $_POST['feedback'];
        
        foreach ($question_texts as $index => $question_text) {
            // Sanitize question data
            $question_text = mysqli_real_escape_string($conn, $question_text);
            $question_type = mysqli_real_escape_string($conn, $question_types[$index]);
            $correct_answer = mysqli_real_escape_string($conn, $correct_answers[$index]);
            $points_value = mysqli_real_escape_string($conn, $points[$index]);
            $feedback_value = mysqli_real_escape_string($conn, $feedback[$index]);

            // Insert the question into the questions table
            $question_sql = "INSERT INTO questions (quiz_id, question_text, question_type, correct_answer, points, feedback) VALUES ('$quiz_id', '$question_text', '$question_type', '$correct_answer', '$points_value', '$feedback_value')";
            if (mysqli_query($conn, $question_sql)) {
                $question_id = mysqli_insert_id($conn); // Get the last inserted question ID

                // If it's a multiple choice question, add options
                if ($question_type === 'multiple_choice') {
                    $option_texts = $_POST['option_text'][$index]; // Options for this question
                    foreach ($option_texts as $option_text) {
                        $option_text = mysqli_real_escape_string($conn, $option_text);
                        // Insert options for the multiple choice question
                        $option_sql = "INSERT INTO options (question_id, option_text) VALUES ('$question_id', '$option_text')";
                        mysqli_query($conn, $option_sql);
                    }
                }
            } else {
                echo "Error inserting question: " . mysqli_error($conn);
            }

            // Handle image uploads, if applicable
            if (isset($_FILES['imagePath']) && $_FILES['imagePath']['error'][$index] == UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['imagePath']['tmp_name'][$index];
                $file_name = basename($_FILES['imagePath']['name'][$index]);
                $upload_dir = 'uploads/'; // Define your upload directory
                $target_file = $upload_dir . $file_name;
                if (move_uploaded_file($file_tmp, $target_file)) {
                    // Save the image path in the database, if needed
                    // For example, you might add a field for the image path in the questions table
                    $image_sql = "UPDATE questions SET image_path = '$target_file' WHERE id = '$question_id'";
                    mysqli_query($conn, $image_sql);
                }
            }
        }

        // Redirect to a success page or display a success message
        header("Location: display-q.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
mysqli_close($conn);
?>
