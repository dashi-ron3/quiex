<?php
session_start();

$servername = "localhost";
$db_username = "root";
$db_password = "pochita12";
$dbname = "quiex";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sample user ID
$user_id = 3;

$percentage = null;

if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];

    $countableQuestionsQuery = "
    SELECT q.id, q.question_type, q.correct_answer, a.student_answer
    FROM questions q
    LEFT JOIN answers a ON q.id = a.question_id
    WHERE q.quiz_id = $quiz_id 
    AND q.question_type IN ('multiple-choice', 'true-false', 'short-answer')
    AND a.attempt_id = (SELECT id FROM attempts WHERE quiz_id = $quiz_id AND user_id = $user_id)
    ";
    $countableQuestions = $conn->query($countableQuestionsQuery);

    $marks = 0;
    $total_marks = 0;
    
    while ($question = $countableQuestions->fetch_assoc()) {
        // Check if the student's answer matches the correct answer
        if (trim($question['student_answer']) === trim($question['correct_answer'])) {
            $marks++;
        }
        $total_marks++;
    }    

    if ($total_marks > 0) {
        $percentage = ($marks / $total_marks) * 100;
    } else {
        $percentage = 0;
    }

    $questionTypeMap = [
        'short-answer' => 'Short Answer',
        'multiple-choice' => 'Multiple Choice',
        'long-answer' => 'Essay',
        'true-false' => 'True or False'
    ];

    $attempt_query = "
    SELECT attempts.started_at, attempts.submitted_at AS finished_at, 
        attempts.score AS marks, attempts.max_score AS total_marks, 
        attempts.score AS points, quizzes.title
    FROM attempts
    JOIN quizzes ON attempts.quiz_id = quizzes.id
    WHERE attempts.user_id = $user_id AND attempts.quiz_id = $quiz_id
    ";
    $quiz = $conn->query($attempt_query)->fetch_assoc();

    if ($quiz) { 
        $marks = $quiz['marks'];
        $total_marks = $quiz['total_marks'];

        if ($total_marks > 0) {
            $percentage = ($marks / $total_marks) * 100;
        } else {
            $percentage = 0;
        }

    $questions = $conn->query("SELECT * FROM questions WHERE quiz_id = $quiz_id");
    } else {
        die("Quiz not found.");
    }
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($_SESSION['theme'] ?? 'light'); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($quiz['title']) ? htmlspecialchars($quiz['title']) : ' Review'; ?></title>
    <link rel="stylesheet" href="css/study-companion.css">
    <script src="javascript/student-appearance.js" defer></script>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <img class="main-logo" src="<?php echo htmlspecialchars($_SESSION['theme'] === 'dark' ? 'assets/Dark_QuiEx-Logo.png' : 'assets/QuiEx-Logo.png'); ?>" alt="QuiEx Logo" width="140" height="50">
            </div>
            <div class="header-content">
                <div class="header-image">
                    <img src="<?php echo htmlspecialchars($_SESSION['theme'] === 'dark' ? 'assets/darkstudycompanion.png' : 'assets/studycompanion.png'); ?>" alt="study companion">
                </div>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="left-container">
            <h1><?php echo $quiz['title']; ?></h1>
            <hr class="divider">
            <a href="download-pdf.php?quiz_id=<?php echo $quiz_id; ?>" target="_blank">
                <button class="pdf-button">Download as PDF</button>
            </a>
            <hr class="divider">
            <div class="quiz-info">
                <table>
                    <tr>
                        <td>Started on:</td>
                        <td><?php echo date('m/d/y', strtotime($quiz['started_at'])); ?> at <?php echo date('h:i A', strtotime($quiz['started_at'])); ?></td>
                    </tr>
                    <tr>
                        <td>Finished on:</td>
                        <td><?php echo date('m/d/y', strtotime($quiz['finished_at'])); ?> at <?php echo date('h:i A', strtotime($quiz['finished_at'])); ?></td>
                    </tr>
                    <tr>
                        <td>Time taken:</td>
                        <td>
                            <?php
                            $time_taken = strtotime($quiz['finished_at']) - strtotime($quiz['started_at']);
                            echo gmdate('H:i:s', $time_taken);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Marks:</td>
                        <td><?php echo $quiz['marks']; ?> out of <?php echo $quiz['total_marks']; ?></td>
                    </tr>
                    <tr>
                        <td>Score:</td>
                        <td><?php echo number_format($percentage, 0); ?>/100</td>
                    </tr>
                </table>
                <hr class="divider">
            </div>
            <div class="quiz-navigation">
                <h3>
                    <img src="assets/nav-icon.png" alt="Navigation Icon">
                    Quiz Navigation
                </h3>
                <div class="nav-buttons">
                    <?php
                    $i = 1;

                    // Fetch all questions and their user answers, including long answer questions
                    $countableQuestionsQuery = "
                        SELECT q.id, q.question_type, q.correct_answer, a.student_answer
                        FROM questions q
                        LEFT JOIN answers a ON q.id = a.question_id AND a.attempt_id = (SELECT id FROM attempts WHERE quiz_id = $quiz_id AND user_id = $user_id)
                        WHERE q.quiz_id = $quiz_id 
                    ";
                    $countableQuestions = $conn->query($countableQuestionsQuery);

                    while ($question = $countableQuestions->fetch_assoc()) {
                        $user_answer = $question['student_answer'] ?? null;
                        $correct_answer = $question['correct_answer'];
                        
                        // Determine button class
                        if ($question['question_type'] === 'long-answer' || $question['question_type'] === 'essay') {
                            $correct_class = 'nav-neutral'; // Class for blue button
                        } else {
                            // For other types, determine correctness
                            $is_user_answer_correct = ($user_answer == $correct_answer);
                            $correct_class = $is_user_answer_correct ? 'nav-correct' : 'nav-wrong';
                        }
                    ?>
                        <a href="#question-<?php echo $i; ?>" class="nav-btn-link">
                            <button class="nav-btn <?php echo $correct_class; ?>"><?php echo $i; ?></button>
                        </a>
                    <?php
                        $i++;
                    }
                    ?>
                </div>
                <hr class="divider">
                <a href="study-companion.php">
                    <button class="finish-review">Finish Review</button>
                </a>
            </div>
        </div>

        <div class="right-container">
            <?php
                $userAnswersQuery = "
                SELECT a.question_id, a.student_answer, a.correct, o.option_text AS answer_text
                FROM answers a
                JOIN options o ON a.student_answer = o.id 
                WHERE a.attempt_id = (SELECT id FROM attempts WHERE quiz_id = $quiz_id AND user_id = $user_id)
                ";

            $userAnswers = $conn->query($userAnswersQuery);
            $userAnswerMap = [];
            while ($answer = $userAnswers->fetch_assoc()) {
                $userAnswerMap[$answer['question_id']] = $answer;
            }          

            $i = 1;
            $questions->data_seek(0);
            while ($question = $questions->fetch_assoc()) {
                $correct_answer = $question['correct_answer']; // Assuming 'correct_answer' is the column name in the questions table
                $user_answer = isset($userAnswerMap[$question['id']]) ? $userAnswerMap[$question['id']] : null;
            
                $user_answer_text = $user_answer ? $user_answer['student_answer'] : 'No answer';
                // Compare user answer with the correct answer
                $is_correct_answer = (trim($correct_answer) === trim($user_answer_text));
            ?>
            <div class="individual-container" id="question-<?php echo $i; ?>">
                <h3>Question <?php echo $i; ?>: <?php echo $questionTypeMap[$question['question_type']]; ?></h3>
                <p><?php echo $question['question_text']; ?></p>

                <?php if ($question['question_type'] == 'multiple-choice') { ?>
                    <ul>
                        <?php
                        // Get the correct answer from the questions table
                        $correct_answer_text = $question['correct_answer'];

                        // Fetch all choices for the current question
                        $choices = $conn->query("SELECT * FROM options WHERE question_id = {$question['id']}");
                        while ($choice = $choices->fetch_assoc()) {
                            $is_user_answer = ($choice['id'] == $user_answer_text);
                            $is_correct = ($choice['id'] == $correct_answer_text);
                            $choice_class = '';

                            if ($is_user_answer) {
                                $choice_class = $is_correct ? 'correct' : 'wrong'; // Mark as correct or wrong based on user answer
                            } else if ($is_correct) {
                                $choice_class = 'correct'; // Mark the correct answer
                            }
                        ?>
                        <li class="<?php echo $choice_class; ?>">
                            <div class="radio-choice">
                            <input type="radio" name="question-<?php echo $i; ?>" id="choice-<?php echo $choice['id']; ?>" disabled <?php echo $is_user_answer ? 'checked' : ''; ?>>
                                <label for="choice-<?php echo $choice['id']; ?>"><?php echo $choice['option_text']; ?></label>
                            </div>
                        </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>

            <?php
                $i++;
            }                 
            ?>
        </div>

    </div>
    <button id="backToTop" class="back-to-top">
        <span class="arrow up"></span>
    </button>
    <script src="javascript/study-companion.js"></script>
</body>

</html>
<?php
} else {
    $quizzes_query = "
    SELECT quizzes.id, quizzes.title, attempts.score AS marks, attempts.max_score AS total_marks
    FROM quizzes
    LEFT JOIN attempts ON quizzes.id = attempts.quiz_id AND attempts.user_id = $user_id
    ";
    $quizzes = $conn->query($quizzes_query);

    if ($quizzes->num_rows > 0) {
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($_SESSION['theme'] ?? 'light'); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Storage</title>
    <link rel="stylesheet" href="css/study-companion.css">
    <script src="javascript/student-appearance.js" defer></script>
</head>

<body>
<header>
    <nav class="navbar">
        <div class="logo">
            <img class="main-logo" src="<?php echo htmlspecialchars($_SESSION['theme'] === 'dark' ? 'assets/Dark_QuiEx-Logo.png' : 'assets/QuiEx-Logo.png'); ?>" alt="QuiEx Logo" width="140" height="50">
        </div>
        <div class="header-content">
            <div class="header-image">
                <img src="<?php echo htmlspecialchars($_SESSION['theme'] === 'dark' ? 'assets/darkassessmentstorage.png' : 'assets/assessment-storage.png'); ?>" alt="assessment storage">
            </div>
        </div>
    </nav>
</header>

    <div class="quizzes-list">
    <?php
    while ($quiz = $quizzes->fetch_assoc()) {
        // Calculate the percentage only if total_marks is greater than 0
        if ($quiz['total_marks'] > 0) {
            $percentage = ($quiz['marks'] / $quiz['total_marks']) * 100;
        } else {
            $percentage = 0; // Prevent division by zero
        }
        
        echo "<div class='quiz-container'>";
        echo "<h3>" . htmlspecialchars($quiz['title']) . "</h3>";
        
        echo "<div class='quiz-info'>";
        echo "<p>Score: " . number_format($percentage, 0) . "/100</p>"; // Displaying percentage
        echo "</div>";
        
        echo "<a href='?quiz_id=" . $quiz['id'] . "' class='quiz-link'>View Quiz</a>";
        echo "</div>";
    }
    ?>
</div>
</body>

</html>

<?php
    } else {
        echo '
        <!DOCTYPE html>
        <html lang="en">
        
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Assessment Storage</title>
            <link rel="stylesheet" href="css/study-companion.css">
        </head>
        
        <body>
        <header>
            <nav class="navbar">
                <div class="logo">
                    <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50">
                </div>
                <div class="header-content">
                    <div class="header-image">
                        <img src="assets/assessment-storage.png" alt="assessment storage">
                    </div>
                </div>
            </nav>
        </header>
        
        <div class="container">
            <div class="quizzes-list">
                <div class="no-quizzes">
                    <h3>No quizzes have been taken.</h3>
                </div>
            </div>
        </div>
        
        </body>
        
        </html>
        ';
    }
}

$conn->close();
?>
