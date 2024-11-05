<?php
session_start();
require 'config/connection.php';

// Fetch the assessments (if needed)
$sql = "SELECT * FROM Assessments";
$stmt = $conn->prepare($sql);
$stmt->execute();
$assessments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Assume this is set when you click on a subject
$selectedSubject = isset($_GET['subject']) ? $_GET['subject'] : null;

// Fetch questions based on selected subject
$sqlQuestions = $selectedSubject 
    ? "SELECT * FROM Questions WHERE subject = ?"
    : "SELECT * FROM Questions"; // Fetch all questions if no subject is selected

$stmtQuestions = $conn->prepare($sqlQuestions);
if ($selectedSubject) {
    $stmtQuestions->bind_param("s", $selectedSubject);
}
$stmtQuestions->execute();
$questions = $stmtQuestions->get_result();
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($_SESSION['theme'] ?? 'light'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/logo-quiex.ico"/>
    <title>Question Archive | QuiEx</title>
    <link rel="stylesheet" href="css/question-archive.css">
    <script src="javascript/student-appearance.js" defer></script>
    <script src="javascript/question-archive.js" defer></script>
</head>
<body>

<header>
            <nav class="navbar">
                <div class="logo">
                    <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50">
                </div>
                <div class="menu-icon" onclick="toggleMenu()">☰</div>
                <div class="nav">
                    <a href="teacher-page.php">HOME</a>
                    <div class="dropdown">
                        <a href="#create" class="dropbtn">CREATE</a>
                        <div class="dropdown-content">
                            <a href="qtesting.php">Create Assessment</a>
                            <a href="#">Questions Archive</a>
                            <a href="teacher-assessments.php">Assessments</a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="#grade" class="drpbtn">GRADE VIEWING</a>
                        <div class="dropdown-content">
                            <a href="grade-viewing.php">View Grades</a>
                        </div>
                    </div>
                    <a href="teacher-settings.php">SETTINGS</a>
                </div>
                <div class="menu-icon" onclick="toggleMenu()">☰</div>
            </nav>
            <div class="page-name">
                <img src="assets/questionarchive.png" alt="page title">
            </div>
        </header>

<main class="main-container">
    <div class="container">
        <div class="folder-list">
            <?php
            // Define an array of subjects for dynamic rendering
            $subjects = ['Biology', 'Calculus', 'Chemistry', 'English', 'Ethics', 'General', 'Math', 'Science'];
            foreach ($subjects as $subject) {
                echo '<div class="subject" onclick="showQuestionsPage(\'' . htmlspecialchars($subject) . '\')">';
                echo '<img src="assets/sub-folder.png" alt="' . htmlspecialchars($subject) . '">';
                echo '<h3>' . htmlspecialchars($subject) . '</h3>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</main>

<div class="container" id="questions-page" style="display: 'none'; <?php echo $selectedSubject ?>;">
    <div class="sidebar">
        <button class="back-button" onclick="goBack()">Back</button>
        <?php
        // Render the subject list again for the sidebar
        foreach ($subjects as $subject) {
            echo '<div class="subject" onclick="showQuestionsPage(\'' . htmlspecialchars($subject) . '\')">';
            echo '<img src="assets/sub-folder.png" alt="' . htmlspecialchars($subject) . '" class="subject-image">';
            echo '<h3>' . htmlspecialchars($subject) . '</h3>';
            echo '</div>';
        }
        ?>
    </div>

    <div class="content">
        <h1 id="subject-title"><?php echo htmlspecialchars($selectedSubject); ?></h1>
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
</div>


</body>
</html>

<?php
$stmt->close();
$stmtQuestions->close();
$conn->close();
?>
