<?php
session_start();
require 'config/connection.php';

$percentage = null;

if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];

    $countableQuestionsQuery = "
    SELECT q.id, q.question_type, q.correct_answer, a.student_answer
    FROM questions q
    LEFT JOIN answers a ON q.id = a.question_id
    JOIN attempts at ON a.attempt_id = at.id
    WHERE q.quiz_id = $quiz_id 
    AND q.question_type IN ('multiple_choice', 'true_false', 'short_answer')
    AND at.quiz_id = $quiz_id 
    AND at.user_id = $user_id
    ";
    $countableQuestions = $conn->query($countableQuestionsQuery);

    $marks = 0;
    $total_marks = 0;
    
    while ($question = $countableQuestions->fetch_assoc()) {
        $user_answer_normalized = strtolower(trim($question['student_answer']));
        $correct_answer_normalized = strtolower(trim($question['correct_answer']));
    
        $total_marks++;
    
        if ($user_answer_normalized === $correct_answer_normalized) {
            $marks++;
        }
    }
    
    if ($total_marks > 0) {
        $percentage = ($marks / $total_marks) * 100;
    } else {
        $percentage = 0;
    }
    
    error_log("Marks: $marks, Total Marks: $total_marks, Percentage: $percentage");
    

    $questionTypeMap = [
        'short_answer' => 'Short Answer',
        'multiple_choice' => 'Multiple Choice',
        'essay' => 'Essay',
        'true_false' => 'True or False'
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
    <title><?php echo isset($quiz['title']) ? htmlspecialchars($quiz['title'], ENT_QUOTES, 'UTF-8') : ' Review'; ?> | QuiEx</title>
    <link rel="stylesheet" href="css/study-companion.css">
    <link rel="icon" href="assets/logo-quiex.ico"/>

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
        <h1><?php echo htmlspecialchars($quiz['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
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
                        <td><?php echo htmlspecialchars($quiz['marks'], ENT_QUOTES, 'UTF-8'); ?> out of <?php echo htmlspecialchars($quiz['total_marks'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <tr>
                        <td>Score:</td>
                        <td><?php echo htmlspecialchars(number_format($percentage, 0), ENT_QUOTES, 'UTF-8'); ?>/100</td>
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

    $countableQuestionsQuery = "
    SELECT q.id, q.question_type, q.correct_answer, a.student_answer
    FROM questions q
    LEFT JOIN answers a ON q.id = a.question_id
    JOIN attempts at ON a.attempt_id = at.id
    WHERE q.quiz_id = $quiz_id 
    AND q.question_type IN ('multiple_choice', 'true_false', 'short_answer', 'essay')
    AND at.quiz_id = $quiz_id 
    AND at.user_id = $user_id
    AND a.attempt_id = (SELECT MAX(id) FROM attempts WHERE quiz_id = $quiz_id AND user_id = $user_id)
    ";
    
    $countableQuestions = $conn->query($countableQuestionsQuery);                    

    while ($question = $countableQuestions->fetch_assoc()) {
        $user_answer = strtolower(trim($question['student_answer'] ?? ''));
        $correct_answer = strtolower(trim($question['correct_answer']));

        $is_correct = ($user_answer === $correct_answer);
    
        if ($question['question_type'] === 'multiple_choice' || $question['question_type'] === 'true_false' || $question['question_type'] === 'short_answer') {
            $correct_class = $is_correct ? 'nav-correct' : 'nav-wrong';
        } else {
            $correct_class = 'nav-neutral';
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
LEFT JOIN options o ON a.student_answer = o.id 
WHERE a.attempt_id = (SELECT MAX(id) FROM attempts WHERE quiz_id = $quiz_id AND user_id = $user_id)
";

$userAnswers = $conn->query($userAnswersQuery);
$userAnswerMap = [];
while ($answer = $userAnswers->fetch_assoc()) {
    $userAnswerMap[$answer['question_id']] = $answer;
}

$i = 1;
$questions->data_seek(0);
while ($question = $questions->fetch_assoc()) {
    $correct_answer = $question['correct_answer'];
    $user_answer = isset($userAnswerMap[$question['id']]) ? $userAnswerMap[$question['id']] : null;

    $user_answer_text = $user_answer ? strtolower(trim($user_answer['student_answer'])) : 'No answer';
    $correct_answer_normalized = strtolower(trim($correct_answer));

    $is_correct_answer = ($question['question_type'] != 'essay' && $user_answer && $user_answer_text === $correct_answer_normalized);

    $user_answer_class = ($question['question_type'] != 'essay') ? ($is_correct_answer ? 'correct' : 'wrong') : 'neutral';
?>
    <div class="individual-container" id="question-<?php echo $i; ?>">
        <h3>Question <?php echo $i; ?>: <?php echo $questionTypeMap[$question['question_type']]; ?></h3>
        <p><?php echo $question['question_text']; ?></p>

        <?php if ($question['question_type'] == 'multiple_choice') { ?>
            <ul>
                <?php
                $choices = $conn->query("SELECT id, option_text FROM options WHERE question_id = {$question['id']}");
                while ($choice = $choices->fetch_assoc()) {
                    $is_user_answer = ($user_answer && $user_answer['student_answer'] == $choice['id']);
                    $is_correct = ($choice['option_text'] == $correct_answer);

                    $choice_class = '';
                    if ($is_user_answer && $is_correct) {
                        $choice_class = 'correct';
                    } elseif ($is_user_answer && !$is_correct) {
                        $choice_class = 'wrong';
                    } elseif ($is_correct) {
                        $choice_class = 'correct';
                    }
                ?>
                    <li class="<?php echo $choice_class; ?>">
                        <label>
                            <input type="radio" name="question-<?php echo $question['id']; ?>" value="<?php echo $choice['option_text']; ?>" <?php echo $is_user_answer ? 'checked' : ''; ?> disabled>
                            <?php echo htmlspecialchars($choice['option_text']); ?>
                        </label>
                    </li>
                <?php } ?>
    </ul>
            <?php } elseif ($question['question_type'] == 'true_false') { ?>
                <p class="<?php echo $user_answer_class; ?>">Your Answer: <?php echo $user_answer ? htmlspecialchars($user_answer['student_answer']) : 'No answer'; ?></p>
                <?php if (!$is_correct_answer) { ?>
                    <p class="correct">Correct Answer: <?php echo htmlspecialchars($correct_answer); ?></p>
                <?php } ?>
                <?php } elseif ($question['question_type'] == 'short_answer') { ?>
                    <p class="<?php echo $user_answer_class; ?>">Your Answer: <?php echo $user_answer ? htmlspecialchars($user_answer['student_answer']) : 'No answer'; ?></p>
                    <?php if (!$is_correct_answer) { ?>
                        <p class="correct">Correct Answer: <?php echo nl2br(htmlspecialchars($correct_answer)); ?></p>
                    <?php } ?>
                    <?php } elseif ($question['question_type'] == 'essay') { ?>
                        <h4 class="answer-spacing">Your Answer:</h4>
                        <div class="essay-answer neutral" style="border: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                            <?php echo nl2br(htmlspecialchars($user_answer_text)); ?>
                        </div>
                        <div class="answer-spacing"></div>
                        <?php if (!$is_correct_answer) { ?>
                            <p class="correct">Correct answer: <?php echo nl2br(htmlspecialchars($correct_answer)); ?></p>
                        <?php } ?>
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
    SELECT 
        quizzes.id, 
        quizzes.title, 
        MAX(attempts.score) AS marks, 
        MAX(attempts.max_score) AS total_marks
    FROM quizzes
    JOIN attempts ON quizzes.id = attempts.quiz_id
    WHERE attempts.user_id = $user_id
    GROUP BY quizzes.id

    ";
    
    $quizzes = $conn->query($quizzes_query);

    if ($quizzes->num_rows > 0) {
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($_SESSION['theme'] ?? 'light'); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Storage | QuiEx</title>
    <link rel="icon" href="assets/logo-quiex.ico"/>
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
        if ($quiz['total_marks'] > 0) {
            $percentage = ($quiz['marks'] / $quiz['total_marks']) * 100;
        } else {
            $percentage = 0;
        }
        
        echo "<div class='quiz-container'>";
        echo "<h3>" . htmlspecialchars($quiz['title'], ENT_QUOTES, 'UTF-8') . "</h3>";
        
        echo "<div class='quiz-info'>";
        echo "<p>Score: " . number_format($percentage, 0) . "/100</p>";
        echo "</div>";
        
        echo "<a href='?quiz_id=" . htmlspecialchars($quiz['id'], ENT_QUOTES, 'UTF-8') . "' class='quiz-link'>View Quiz</a>";
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
