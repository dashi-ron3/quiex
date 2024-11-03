<?php
session_start();

if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

$servername = "localhost"; 
$username = "root";   
$password = "";     
$dbname = "quiex";         

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$selectedSubject = isset($_GET['subject']) ? $_GET['subject'] : null;

$sql = "SELECT * FROM Assessments";
$stmt = $conn->prepare($sql);
$stmt->execute();
$assessments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if ($selectedSubject) {
    $sqlQuestions = "SELECT * FROM Questions WHERE subject = ?";
    $stmtQuestions = $conn->prepare($sqlQuestions);
    $stmtQuestions->bind_param("s", $selectedSubject);
    $stmtQuestions->execute();
    $questions = $stmtQuestions->get_result();
} else {
    // Default fetch all questions or handle error
    $sqlQuestions = "SELECT * FROM Questions";
    $stmtQuestions = $conn->prepare($sqlQuestions);
    $stmtQuestions->execute();
    $questions = $stmtQuestions->get_result();
}

?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($_SESSION['theme'] ?? 'light'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions Archive</title>
    <link rel="stylesheet" href="css/question-archive.css">
    <script src="javascript/student-appearance.js" defer></script>
    <script src="javascript/question-archive.js" defer></script>
</head>
<body>

    <header>
        <nav class="navbar">
            <div class="logo-page-name-container">
                <div class="logo">
                    <a href="teacher-page.php">
                        <img class="main-logo" src="<?php echo htmlspecialchars($_SESSION['theme'] === 'dark' ? 'assets/Dark_QuiEx-Logo.png' : 'assets/QuiEx-Logo.png'); ?>" alt="QuiEx Logo" width="140" height="50">
                    </a>
                </div>
                <div class="page-name">
                    <img src="<?php echo htmlspecialchars($_SESSION['theme'] === 'dark' ? 'assets/darkquestionarchive.png' : 'assets/questionarchive.png'); ?>" alt="page title">
                </div>
            </div>
        </nav>
    </header>

    <main class="main-container">
        <div class="container">
            <div class="folder-list">
                <div class="subject" onclick="showQuestionsPage('Biology')">
                    <img src="assets/sub-folder.png" alt="Biology">
                    <h3>Biology</h3>
                </div>
                <div class="subject" onclick="showQuestionsPage('Calculus')">
                    <img src="assets/sub-folder.png" alt="Calculus">
                    <h3>Calculus</h3>
                </div>
                <div class="subject" onclick="showQuestionsPage('Chemistry')">
                    <img src="assets/sub-folder.png" alt="Chemistry">
                    <h3>Chemistry</h3>
                </div>
                <div class="subject" onclick="showQuestionsPage('English')">
                    <img src="assets/sub-folder.png" alt="English">
                    <h3>English</h3>
                </div>
                <div class="subject" onclick="showQuestionsPage('Ethics')">
                    <img src="assets/sub-folder.png" alt="Ethics">
                    <h3>Ethics</h3>
                </div>
                <div class="subject" onclick="showQuestionsPage('General')">
                    <img src="assets/sub-folder.png" alt="General">
                    <h3>General</h3>
                </div>
                <div class="subject" onclick="showQuestionsPage('Math')">
                    <img src="assets/sub-folder.png" alt="Math">
                    <h3>Math</h3>
                </div>
                <div class="subject" onclick="showQuestionsPage('Science')">
                    <img src="assets/sub-folder.png" alt="Science">
                    <h3>Science</h3>
                </div>
                </div>
            </div>
        </div>
    </main>

    <div class="container" id="questions-page" style="display: none;">
        <div class="sidebar">
            <button class="back-button" onclick="goBack()">Back</button>
            <div class="subject" onclick="showQuestions('Biology')">
                <img src="assets/sub-folder.png" alt="Biology" class="subject-image">
                <h3>Biology</h3>
            </div>
            <div class="subject" onclick="showQuestions('Calculus')">
                <img src="assets/sub-folder.png" alt="Calculus" class="subject-image">
                <h3>Calculus</h3>
            </div>
            <div class="subject" onclick="showQuestions('Chemistry')">
                <img src="assets/sub-folder.png" alt="Chemistry" class="subject-image">
                <h3>Chemistry</h3>
            </div>
            <div class="subject" onclick="showQuestions('English')">
                <img src="assets/sub-folder.png" alt="English" class="subject-image">
                <h3>English</h3>
            </div>
            <div class="subject" onclick="showQuestions('Ethics')">
                <img src="assets/sub-folder.png" alt="Ethics" class="subject-image">
                <h3>Ethics</h3>
            </div>
            <div class="subject" onclick="showQuestions('General')">
                <img src="assets/sub-folder.png" alt="General" class="subject-image">
                <h3>General</h3>
            </div>
            <div class="subject" onclick="showQuestions('Math')">
                <img src="assets/sub-folder.png" alt="Math" class="subject-image">
                <h3>Math</h3>
            </div>
            <div class="subject" onclick="showQuestions('Science')">
                <img src="assets/sub-folder.png" alt="Science" class="subject-image">
                <h3>Science</h3>
            </div>
        </div>

        <div class="content">
    <h1 id="subject-title"><?php echo htmlspecialchars($selectedSubject);?></h1>
    <div class="questions archive-list" id="archive-list">
        <div class="questions-container">
            <?php if ($questions->num_rows > 0): ?>
                <?php while ($question = $questions->fetch_assoc()): ?>
                    <div class="question">
                        <p><strong>Question:</strong> <?php echo htmlspecialchars($question['question_text']); ?></p>

                        <?php if ($question['question_type'] == 'multiple_choice'): ?>
                            <?php
                            // Fetch options for this question
                            $sqlOptions = "SELECT * FROM Options WHERE question_id = ?";
                            $stmtOptions = $conn->prepare($sqlOptions);
                            $stmtOptions->bind_param("i", $question['id']);
                            $stmtOptions->execute();
                            $options = $stmtOptions->get_result();
                            ?>
                            <div class="options">
                                <?php while ($option = $options->fetch_assoc()): ?>
                                    <label>
                                        <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo $option['id']; ?>">
                                        <?php echo htmlspecialchars($option['option_text']); ?>
                                    </label><br>
                                <?php endwhile; ?>
                            </div>
                        <?php elseif ($question['question_type'] == 'true_false'): ?>
                            <div class="options">
                                <label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="true"> True</label><br>
                                <label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="false"> False</label><br>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No questions found for this subject.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>

<?php
$stmt->close();
$stmtQuestions->close();
$conn->close();
?>
