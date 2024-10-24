<?php
session_start();

$servername = "localhost";
$db_username = "root";
$db_password = "15a5m249ph";
$dbname = "quiex";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// if (isset($_SESSION['user_id'])) {
//     $user_id = $_SESSION['user_id'];
// } else {
//     header("Location: login.php");
//     exit();
// }

// Sample user ID
$user_id = 1;

if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];

    $quiz = $conn->query("SELECT * FROM quizzes WHERE id = $quiz_id AND user_id = $user_id")->fetch_assoc();
    $questions = $conn->query("SELECT * FROM questions WHERE quiz_id = $quiz_id");

    if (!$quiz) {
        die("Quiz not found.");
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $quiz['title']; ?> Review</title>
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
                        <img src="assets/studycompanion.png" alt="study companion">
                    </div>
                </div>
            </nav>
        </header>

        <div class="container">
            <!-- left container -->
            <div class="left-container">
                <h1><?php echo $quiz['title']; ?></h1>
                <hr class="divider">
                <button class="pdf-button">Download as PDF</button>
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
                            <td>Points:</td>
                            <td><?php echo $quiz['points']; ?></td>
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
                        while ($question = $questions->fetch_assoc()) {
                            $user_answer_query = "
                        SELECT ua.answer_id, ua.is_correct
                        FROM user_answers ua
                        WHERE ua.quiz_id = $quiz_id AND ua.question_id = {$question['id']}
                    ";
                            $user_answer = $conn->query($user_answer_query)->fetch_assoc();
                            $correct_answer = $conn->query("SELECT * FROM choices WHERE question_id = {$question['id']} AND is_correct = 1")->fetch_assoc();

                            // check if user's answer is correct
                            $is_correct = ($user_answer && $user_answer['answer_id'] == $correct_answer['id']);
                            $correct_class = $is_correct ? 'nav-correct' : 'nav-wrong';
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

            <!-- right container -->
            <div class="right-container">
                <?php
                // fetch user's answers from the database
                $userAnswersQuery = "
        SELECT ua.question_id, ua.answer_id, ua.is_correct, c.text AS answer_text
        FROM user_answers ua
        JOIN choices c ON ua.answer_id = c.id
        WHERE ua.quiz_id = $quiz_id
    ";

                $userAnswers = $conn->query($userAnswersQuery);
                $userAnswerMap = [];
                while ($answer = $userAnswers->fetch_assoc()) {
                    $userAnswerMap[$answer['question_id']] = $answer;
                }

                $i = 1;
                $questions->data_seek(0);
                while ($question = $questions->fetch_assoc()) {
                    $correct_answer = $conn->query("SELECT * FROM choices WHERE question_id = {$question['id']} AND is_correct = 1")->fetch_assoc();
                    $user_answer = isset($userAnswerMap[$question['id']]) ? $userAnswerMap[$question['id']] : null;

                    $user_answer_id = $user_answer ? $user_answer['answer_id'] : null; // user's selected answer

                    // check if user's answer is correct
                    $is_user_answer_correct = ($user_answer && $user_answer['is_correct'] == 1);
                    $is_correct_answer_id = ($correct_answer['id'] == $user_answer_id);
                ?>
                    <div class="individual-container" id="question-<?php echo $i; ?>">
                        <h3>Question <?php echo $question['id']; ?>: <?php echo $question['type']; ?></h3>
                        <p><?php echo $question['text']; ?></p>

                        <?php if ($question['type'] == 'Multiple Choice') { ?>
                            <ul>
                                <?php
                                $choices = $conn->query("SELECT * FROM choices WHERE question_id = {$question['id']}");
                                while ($choice = $choices->fetch_assoc()) {
                                    $is_user_answer = ($choice['id'] == $user_answer_id);
                                    $is_correct = ($choice['id'] == $correct_answer['id']);
                                    $choice_class = '';

                                    // Set the class based on user's answer and correctness for answer correction feature
                                    if ($is_user_answer) {
                                        $choice_class = $is_user_answer_correct ? 'correct' : 'wrong';
                                    } elseif ($is_correct) {
                                        $choice_class = 'correct';
                                    }
                                ?>
                                    <li class="<?php echo $choice_class; ?>">
                                        <div class="radio-choice">
                                            <input type="radio" name="question-<?php echo $i; ?>" value="<?php echo $choice['id']; ?>" <?php echo $is_user_answer ? 'checked' : ''; ?> disabled>
                                            <?php echo $choice['text']; ?>
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
    // if no quiz_id is passed, list all quizzes for the user
    $quizzes = $conn->query("SELECT * FROM quizzes WHERE user_id = $user_id");

    if ($quizzes->num_rows > 0) {
    ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Study Companion</title>
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
                            <img src="assets/studycompanion.png" alt="study companion">
                        </div>
                    </div>
                </nav>
            </header>
            <div class="container">
                <h2>Your Quizzes</h2>
                <ul>
                    <?php while ($quiz = $quizzes->fetch_assoc()) { ?>
                        <li>
                            <a href="?quiz_id=<?php echo $quiz['id']; ?>"><?php echo $quiz['title']; ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </body>

        </html>
<?php
    } else {
        echo "<p>No quizzes found.</p>";
    }
}

$conn->close();
?>