<?php
session_start();
require 'config/connection.php';

$subject = isset($_GET['subject']) ? mysqli_real_escape_string($conn, $_GET['subject']) : '';

if ($subject) {
    // Query to get all quizzes from assessments for this subject
    $getQuizzesQuery = "
        SELECT id, title, open_date AS lastUsed, description AS descrip
        FROM quizzes
        WHERE subject = '$subject'
    ";
    $quizzesResult = $conn->query($getQuizzesQuery);

    if ($quizzesResult->num_rows > 0) {
        while ($quiz = $quizzesResult->fetch_assoc()) {
            $quizId = $quiz['id'];

            // Check and insert if this quiz does not exist in uploadedAss
            $checkQuizQuery = "SELECT COUNT(*) AS count FROM uploadedAss WHERE quizId = $quizId";
            $checkQuizResult = $conn->query($checkQuizQuery);
            $row = $checkQuizResult->fetch_assoc();

            if ($row['count'] == 0) {
                $insertQuizQuery = "
                    INSERT INTO uploadedAss (quizId, subject, title, lastUsed, descrip)
                    VALUES ($quizId, '$subject', '{$quiz['title']}', '{$quiz['lastUsed']}', '{$quiz['descrip']}')
                ";
                if ($conn->query($insertQuizQuery) !== TRUE) {
                    echo "Error inserting quiz: " . $conn->error;
                }
            }
        }
    }
}

// Check if the checkbox was checked or not
$isShared = isset($_POST['share']) ? 1 : 0;

// Update the 'shared' status based on the checkbox state
if (isset($_POST['assessment_title'])) {
    $assessmentTitle = mysqli_real_escape_string($conn, $_POST['assessment_title']);
    $updateQuery = "UPDATE uploadedAss SET shared = $isShared WHERE title = '$assessmentTitle'";

    if ($conn->query($updateQuery) === FALSE) {
        echo "Error updating shared status: " . $conn->error;
    }
}

// Fetching subjects
$subjectsQuery = "SELECT DISTINCT subject FROM quizzes";
$subjectsResult = $conn->query($subjectsQuery);

// Fetching assessments from uploadedAss
$sql = "SELECT title, status, lastUsed, descrip, shared FROM uploadedAss WHERE subject = '$subject'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($_SESSION['theme'] ?? 'light'); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessments | QuiEx</title>
    <link rel="icon" href="assets/logo-quiex.ico"/>
    <link rel="stylesheet" href="css/assessment-style.css">
    <script src="javascript/student-appearance.js" defer></script>
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
            <img src="<?php echo htmlspecialchars($_SESSION['theme'] === 'dark' ? 'assets/darkassessments.png' : 'assets/assessment.png'); ?>" alt="page title">
        </div>
    </header>

    <div class="container">
        <div class="sidebar">
            <?php
            if ($subjectsResult->num_rows > 0) {
                while ($row = $subjectsResult->fetch_assoc()) {
                    $subjectName = htmlspecialchars($row['subject']);
            ?>
                    <div class="subject">
                        <form method="GET" action="">
                            <input type="hidden" name="subject" value="<?php echo $subjectName; ?>">
                            <button type="submit">
                                <img src="<?php 
                                    echo htmlspecialchars(
                                        $_SESSION['theme'] === 'dark' ? 'assets/darksubfolder.png' : 
                                        ($_SESSION['theme'] === 'purple' ? 'assets/purplesubfolder.png' : 'assets/sub-folder.png')
                                    ); 
                                ?>" alt="<?php echo $subjectName; ?> folder">
                                <h3><?php echo strtoupper($subjectName); ?></h3>
                            </button>
                        </form>
                    </div>
            <?php
                }
            } else {
                echo "<p>No subjects found.</p>";
            }
            ?>
        </div>

        <div class="content">
            <h1><?php echo htmlspecialchars(strtoupper($subject)); ?></h1>

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                    <div class="assessment">
                        <div class="last-used">LAST USED ON: <?php echo htmlspecialchars($row['lastUsed']); ?></div>
                        <div class="header">
                            <div class="title-status">
                                <div class="title"><strong>Assessment Title:</strong> <?php echo htmlspecialchars($row['title']); ?></div>
                                <div class="status"><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></div>
                            </div>
                            <button type="button" class="score" onclick="fetchScores('<?php echo htmlspecialchars($row['title']); ?>')">Score</button>
                        </div>
                        <p class="details"><?php echo htmlspecialchars($row['descrip']); ?></p>
                    </div>
            <?php
                }
            } else {
                echo "<p>No assessments found.</p>";
            }

            // Fetching questions and options related to the selected subject
            if ($subject) {
                $getQuestionsAndOptionsQuery = "
                SELECT 
                    q.id AS question_id, 
                    q.question_text, 
                    o.id AS option_id, 
                    o.option_text
                FROM 
                    questions q
                LEFT JOIN 
                    options o ON q.id = o.question_id
                WHERE 
                    q.quiz_id = (SELECT id FROM quizzes WHERE subject = '$subject' LIMIT 1) -- Modify if you want to fetch from multiple quizzes
                ";
                $questionsAndOptionsResult = $conn->query($getQuestionsAndOptionsQuery);
            
                if ($questionsAndOptionsResult->num_rows > 0) {
                    echo '<h2>Questions for ' . htmlspecialchars($subject) . ':</h2>';
                    $currentQuestionId = null;
                    
                    while ($row = $questionsAndOptionsResult->fetch_assoc()) {
                        // Check if this is a new question
                        if ($currentQuestionId !== $row['question_id']) {
                            // Close the previous question block if it exists
                            if ($currentQuestionId !== null) {
                                echo '</div>'; // Closing the previous question block
                            }
                            
                            // New question block
                            echo '<div class="question">';
                            echo '<p class="question-text">' . htmlspecialchars($row['question_text']) . '</p>';
                            $currentQuestionId = $row['question_id'];
                        }
            
                        // Output options if they exist
                        if ($row['option_id'] !== null) {
                            echo '<div class="radio-choice">';
                            echo '<input type="radio" id="option_' . $row['option_id'] . '" name="question_' . $row['question_id'] . '" value="' . $row['option_id'] . '">';
                            echo '<label for="option_' . $row['option_id'] . '">' . htmlspecialchars($row['option_text']) . '</label>';
                            echo '</div>'; // Close radio choice
                        }
                    }
                    
                    // Close the last question block
                    if ($currentQuestionId !== null) {
                        echo '</div>'; // Closing the last question block
                    }
                } else {
                    echo "<p>No published questions found for this subject.</p>";
                }
            }
            

            $conn->close();
            ?>
        </div>
    </div>

    <div id="score-modal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal()">&times;</span>
            <h2>Scores</h2>
            <div id="modal-score-content">
                <!-- Scores will be loaded here via AJAX -->
            </div>
        </div>
    </div>

    <script>
        function fetchScores(title) {
            const modal = document.getElementById('score-modal');
            const modalContent = document.getElementById('modal-score-content');

            // Show the modal
            modal.style.display = 'block';

            // Clear previous content
            modalContent.innerHTML = '<p>Loading...</p>';

            // Fetch scores
            fetch('fetch-scores.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'title=' + encodeURIComponent(title),
            })
            .then(response => response.text())
            .then(data => {
                modalContent.innerHTML = data;
            })
            .catch(error => {
                console.error('Error fetching scores:', error);
                modalContent.innerHTML = '<p>Error loading scores.</p>';
            });
        }

        function closeModal() {
            const modal = document.getElementById('score-modal');
            modal.style.display = 'none';
        }

        function toggleMenu() {
            const nav = document.querySelector('.nav');
            nav.classList.toggle('show');
        }
    </script>
</body>
</html>
