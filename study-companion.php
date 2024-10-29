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
                            <td colspan="2">
                                <?php 
                                if ($quiz['is_graded']) {
                                    echo $quiz['points'];
                                } else {
                                    echo "N/A";
                                }
                                ?>
                            </td>
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
                
                    $user_answer_id = $user_answer ? $user_answer['answer_id'] : null;
                
                    // Check if user's answer is correct
                    $is_user_answer_correct = ($user_answer && $user_answer['is_correct'] == 1);
                    $is_correct_answer_id = ($correct_answer['id'] == $user_answer_id);

                    //echo "DEBUG: question id: {$question['id']}, user answer id: {$user_answer_id}, is correct: {$is_user_answer_correct}, correct answer id: {$correct_answer['id']}";

                ?>
                    <div class="individual-container" id="question-<?php echo $i; ?>">
                        <div class="button-container">
                            <button class="button" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-flag" viewBox="0 0 16 16">
                                    <path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12 12 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A20 20 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a20 20 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21 21 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21 21 0 0 0 14 7.655V1.222z"/>
                                </svg>
                            </button>
                            <button class="button" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-square-dots" viewBox="0 0 16 16">
                                    <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                    <path d="M5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                                </svg>
                            </button>
                        </div>
                        <h3>Question <?php echo $i; ?>: <?php echo $question['type']; ?></h3>
                        <p><?php echo $question['text']; ?></p>
                
                        <?php if ($question['type'] == 'Multiple Choice') { ?>
                            <ul>
                                <?php
                                $choices = $conn->query("SELECT * FROM choices WHERE question_id = {$question['id']}");
                                while ($choice = $choices->fetch_assoc()) {
                                    // Check if this choice is the user's answer
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
                                            <input type="radio" name="question-<?php echo $question['id']; ?>" value="<?php echo $choice['id']; ?>" <?php echo $is_user_answer ? 'checked' : ''; ?> disabled>
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
                <h1>My Quizzes</h1>
                <ul>
                    <?php
                    while ($quiz = $quizzes->fetch_assoc()) {
                        echo "<li><a href='?quiz_id=" . $quiz['id'] . "'>" . $quiz['title'] . "</a></li>";
                    }
                    ?>
                </ul>
            </div>
        </body>

        </html>

<?php
    } else {
        echo "You have no quizzes.";
    }
}

$conn->close();
?>