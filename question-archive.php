<?php
session_start();

if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
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
            <h1 id="subject-title">Subject 1</h1>
            <div class="questions">
                <div class="question">
                    <p><strong>Question 1:</strong> Lorem ipsum.</p>
                    <label><input type="radio" name="q1" disabled> A</label><br>
                    <label><input type="radio" name="q1" disabled> B</label><br>
                    <label><input type="radio" name="q1" disabled> C</label><br>
                    <label><input type="radio" name="q1" disabled> D</label>
                </div>
                <div class="question">
                    <p><strong>Question 2:</strong> Lorem ipsum.</p>
                    <label><input type="radio" name="q2" disabled> True</label><br>
                    <label><input type="radio" name="q2" disabled> False</label><br>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
