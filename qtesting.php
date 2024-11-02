<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Assessment Maker">
    <link rel="icon" href="assets/logo-quiex.ico"/>
    <title>Create Assessment | QuiEx</title>
    <link rel="stylesheet" type="text/css" href="css/create-assessment.css">
    <script src="javascript/qtesting.js" defer></script>
</head>
<body>
    <header>
        <div class="logo">
            <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50">
        </div>
        <h1>New Assessment</h1>
    </header>

    <div class="container">
        <?php if (isset($_GET['quiz_id'])): ?>
            <p>Your Quiz ID:</p>
            <div class="quiz-id" id="quizID"><?php echo htmlspecialchars($_GET['quiz_id']); ?></div>
            <button class="copy-btn" onclick="copyQuizID()">Copy Quiz ID</button>
        <?php else: ?>
            <p>Error: Quiz ID not found.</p>
        <?php endif; ?>
        <p>Number of Questions: <span id="questionCounter">0</span></p>
    </div>

    <form action="process-q.php" method="POST" id="quizForm" enctype="multipart/form-data">

        <div id="titleDescription">
            <label for="quiz-subject">Subject:</label>
            <input type="text" id="quiz-subject" name="quiz-subject" required>
            <br>
            <label for="quiz-title">Title:</label>
            <input type="text" id="quiz-title" name="quiz-title" required>
            <br>
            <label for="quiz-description">Description:</label>
            <textarea id="quiz-description" name="quiz-description" rows="4"></textarea>
        </div>

        <div id="quizContainer">
            <!-- Questions will be added here -->
        </div>

        <button id="addQuestion" type="button">Add Question</button>

        <div id="quizSettings">
            <label for="openDate">Open Date:</label>
            <input type="datetime-local" id="openDate" name="openDate">
            <br>
            <label for="closeDate">Close Date:</label>
            <input type="datetime-local" id="closeDate" name="closeDate">
            <br>
            <label for="maxAttempts">Max Attempts:</label>
            <input type="number" id="maxAttempts" name="maxAttempts">
            <br>
            <label for="randomizeOrder">Randomize Order:</label>
            <input type="checkbox" id="randomizeOrder" name="randomizeOrder">
        </div>

        <button id="done" type="submit">Done</button>
    </form>

</body>
</html>