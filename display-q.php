<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Take Assessment">
    <link rel="icon" href="assets/logo-quiex.ico"/>
    <title>Take Assessment | QuiEx</title>
    <link rel="stylesheet" href="css/assessment-display.css">
</head>
<body>

    <header>
        <div class="logo">
            <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50">
        </div>
    </header>

    <?php
    require 'config/connection.php'; 

    $quizId = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : null;

    if ($quizId) {
        try {
            // Change here: Selecting quiz by quiz ID instead of quiz code
            $quizStmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = :quizId");
            $quizStmt->execute([':quizId' => $quizId]);
            $quiz = $quizStmt->fetch(PDO::FETCH_ASSOC);

            if ($quiz) {
                echo "<h1>" . htmlspecialchars($quiz['title']) . "</h1>";
                echo "<div class='quiz-details'>";
                echo "<p><strong>Subject:</strong> " . htmlspecialchars($quiz['subject']) . "</p>";
                echo "<p><strong>Description:</strong> " . htmlspecialchars($quiz['description']) . "</p>";
                echo "<p><strong>Close Date:</strong> " . htmlspecialchars($quiz['close_date']) . "</p>";
                echo "<p><strong>Max Attempt(s):</strong> " . htmlspecialchars($quiz['max_attempts']) . "</p>";
                echo "</div>";

                // Use the quiz ID to fetch the questions
                $questionStmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = :quizId");
                $questionStmt->execute([':quizId' => $quizId]);
                $questions = $questionStmt->fetchAll(PDO::FETCH_ASSOC);

                if ($questions) {
                    echo "<form method='POST' action='submit-quiz.php'>";

                    foreach ($questions as $question) {
                        echo "<div class='question'>";
                        echo "<h3>" . htmlspecialchars($question['question_text']) . "</h3>";

                        $questionType = $question['question_type'];
                        $questionId = $question['id'];

                        if ($questionType === 'multiple_choice') {
                            $optionStmt = $pdo->prepare("SELECT * FROM options WHERE question_id = :questionId");
                            $optionStmt->execute([':questionId' => $questionId]);
                            $options = $optionStmt->fetchAll(PDO::FETCH_ASSOC);

                            echo "<div class='options-container'>";
                            foreach ($options as $option) {
                                echo "<div class='option'>";
                                echo "<label>";
                                echo "<input type='radio' name='answer[$questionId]' value='" . $option['id'] . "'>";
                                echo htmlspecialchars($option['option_text']);
                                echo "</label>";
                                echo "</div>";
                            }
                            echo "</div>";
                        } elseif ($questionType === 'essay' || $questionType === 'short_answer') {
                            echo "<textarea name='answer[$questionId]' rows='4' cols='50'></textarea>";
                        } elseif ($questionType === 'true_false') {
                            echo "<label><input type='radio' name='answer[$questionId]' value='true'> True</label>";
                            echo "<label><input type='radio' name='answer[$questionId]' value='false'> False</label>";
                        }
                        echo "</div>";
                    }

                    echo "<button type='submit'>Submit Quiz</button>";
                    echo "</form>";
                } else {
                    echo "No questions found for this quiz.";
                }
            } else {
                echo "Quiz not found.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "No quiz ID provided.";
    }
    ?>
</body>
</html>