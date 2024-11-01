<?php
// Database configuration
$host = 'localhost';
$dbname = 'qtest';
$username = 'root';
$password = '15a5m249ph';

// Establish a database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if quiz_id is provided
$quizId = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : null;

if ($quizId) {
    // Retrieve quiz details
    $quizStmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = :quizId");
    $quizStmt->execute([':quizId' => $quizId]);
    $quiz = $quizStmt->fetch(PDO::FETCH_ASSOC);

    if (!$quiz) {
        die("Quiz not found.");
    }

    // Retrieve quiz questions
    $questionStmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = :quizId");
    $questionStmt->execute([':quizId' => $quizId]);
    $questions = $questionStmt->fetchAll(PDO::FETCH_ASSOC);

    // Retrieve options for all questions in a single query
    $optionStmt = $pdo->prepare("SELECT * FROM options WHERE question_id IN (SELECT id FROM questions WHERE quiz_id = :quizId)");
    $optionStmt->execute([':quizId' => $quizId]);
    $options = [];
    foreach ($optionStmt->fetchAll(PDO::FETCH_ASSOC) as $option) {
        $options[$option['question_id']][] = $option;
    }
} else {
    echo "<p style='color: red;'>Quiz ID is required to display the quiz.</p>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo htmlspecialchars($quiz['title'] ?? 'Quiz'); ?></title>
    <link rel="stylesheet" type="text/css" href="styles2.css">
</head>

<body>
    <!-- Form to input quiz ID -->
    <div class="quiz-id-form">
        <form action="display-q.php" method="GET">
            <label for="quiz_id">Enter Quiz ID:</label>
            <input type="number" id="quiz_id" name="quiz_id" required>
            <button type="submit">Load Quiz</button>
        </form>
    </div>

    <?php if ($quizId && $quiz): ?>
        <div class="quiz-container">
            <h1><?php echo htmlspecialchars($quiz['title']); ?></h1>
            <p><strong>Subject:</strong> <?php echo htmlspecialchars($quiz['subject']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($quiz['description']); ?></p>
            <p><strong>Open Date:</strong> <?php echo htmlspecialchars($quiz['open_date']); ?></p>
            <p><strong>Close Date:</strong> <?php echo htmlspecialchars($quiz['close_date']); ?></p>
            <p><strong>Timer:</strong> <?php echo htmlspecialchars($quiz['timer_hours'] ?? 0) . ' hours ' . htmlspecialchars($quiz['timer_minutes'] ?? 0) . ' minutes'; ?></p>
            <p><strong>Max Attempts:</strong> <?php echo htmlspecialchars($quiz['max_attempts']); ?></p>
            <p><strong>Randomize Order:</strong> <?php echo $quiz['randomize_order'] ? 'Yes' : 'No'; ?></p>

            <!-- Countdown Timer -->
            <h2>Time Left: <span id="timer"></span></h2>

            <form id="quizForm" action="submit-quiz.php" method="POST">
                <input type="hidden" name="quiz_id" value="<?php echo htmlspecialchars($quizId); ?>">
                
                <?php if (empty($questions)): ?>
                    <p>No questions found for this quiz.</p>
                <?php else: ?>
                    <ol>
                        <?php foreach ($questions as $question): ?>
                            <li>
                                <p><strong>Question:</strong> <?php echo htmlspecialchars($question['question_text']); ?></p>
                                <label>Your Answer:</label>

                                <?php
                                // Display answer input based on question type
                                switch ($question['question_type']) {
                                    case 'short_answer':
                                        echo '<input type="text" name="answers[' . $question['id'] . ']" required>';
                                        break;

                                    case 'multiple_choice':
                                        foreach ($options[$question['id']] as $option) {
                                            echo '<div><input type="radio" name="answers[' . $question['id'] . ']" value="' . htmlspecialchars($option['option_text']) . '" required>';
                                            echo htmlspecialchars($option['option_text']) . '</div>';
                                        }
                                        break;

                                    case 'essay':
                                        echo '<textarea name="answers[' . $question['id'] . ']" required></textarea>';
                                        break;

                                    case 'true_false':
                                        echo '<div><input type="radio" name="answers[' . $question['id'] . ']" value="True" required> True</div>';
                                        echo '<div><input type="radio" name="answers[' . $question['id'] . ']" value="False" required> False</div>';
                                        break;
                                }
                                ?>
                                <input type="hidden" name="correct_answer[<?php echo $question['id']; ?>]" value="<?php echo htmlspecialchars($question['correct_answer']); ?>">
                                <input type="hidden" name="points[<?php echo $question['id']; ?>]" value="<?php echo htmlspecialchars($question['points']); ?>">
                            </li>
                        <?php endforeach; ?>
                    </ol>
                    <button type="submit" id="submitBtn">Submit Quiz</button>
                <?php endif; ?>
            </form>
        </div>
    <?php endif; ?>

    <script>
        // Timer functionality
        let timerHours = <?php echo (int)($quiz['timer_hours'] ?? 0); ?>;
        let timerMinutes = <?php echo (int)($quiz['timer_minutes'] ?? 0); ?>;
        let timeLeft = (timerHours * 60 * 60) + (timerMinutes * 60);

        function startTimer() {
            const timerDisplay = document.getElementById('timer');
            const submitBtn = document.getElementById('submitBtn');
            const quizForm = document.getElementById('quizForm');

            const timerInterval = setInterval(() => {
                let hours = Math.floor(timeLeft / 3600);
                let minutes = Math.floor((timeLeft % 3600) / 60);
                let seconds = timeLeft % 60;

                timerDisplay.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    alert("Time's up! Your quiz will be submitted automatically.");
                    submitBtn.disabled = true;
                    quizForm.submit();
                } else {
                    timeLeft--;
                }
            }, 1000);
        }

        window.onload = startTimer;
    </script>
</body>

</html>
