<?php
session_start();
$conn = mysqli_connect("localhost", "root", "15a5m249ph", "quiex");
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

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

            // Check if this specific quizId already exists in uploadedAss
            $checkQuizQuery = "SELECT COUNT(*) AS count FROM uploadedAss WHERE quizId = $quizId";
            $checkQuizResult = $conn->query($checkQuizQuery);
            $row = $checkQuizResult->fetch_assoc();

            // Insert only if this quiz does not exist
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
$isShared = isset($_POST['share']) ? 1 : 0;  // 1 if checked, 0 if unchecked

// Update the 'shared' status based on the checkbox state.
if (isset($_POST['assessment_title'])) {
    $assessmentTitle = mysqli_real_escape_string($conn, $_POST['assessment_title']);
    $updateQuery = "UPDATE uploadedAss SET shared = $isShared WHERE title = '$assessmentTitle'";

    if ($conn->query($updateQuery) !== FALSE) {
        $conn->error;
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
    <title>Teacher Assessments</title>
    <link rel="stylesheet" href="css/assessment-style.css">
    <script src="javascript/student-appearance.js" defer></script>
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
                    <img src="<?php echo htmlspecialchars($_SESSION['theme'] === 'dark' ? 'assets/darkassessments.png' : 'assets/assessment.png'); ?>" alt="page title">
                </div>
            </div>
        </nav>
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
                            <!-- <a href="#" class="edit"><strong>Edit</strong></a> -->
                            <button type="button" class="score" onclick="fetchScores('<?php echo htmlspecialchars($row['title']); ?>')">Score</button>
                            
                        </div>
                        <p class="details"><?php echo htmlspecialchars($row['descrip']); ?></p>
                        <!-- <div class="share">
                            <form method="POST" action="">
                                <input type="hidden" name="assessment_title" value="<?php echo htmlspecialchars($row['title']); ?>">
                                <label for="share-<?php echo htmlspecialchars($row['title']); ?>">SHARE TO PUBLIC: </label>
                                <input
                                    type="checkbox"
                                    id="share-<?php echo htmlspecialchars($row['title']); ?>"
                                    name="share"
                                    <?php echo $row['shared'] ? 'checked' : ''; ?>
                                    onchange="this.form.submit()">
                            </form>
                        </div> -->
                    </div>
            <?php
                }
            } else {
                echo "<p>No assessments found.</p>";
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
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'assessment_title=' + encodeURIComponent(title)
                })
                .then(response => response.text())
                .then(data => {
                    modalContent.innerHTML = data;
                })
                .catch(error => {
                    console.error('Error fetching scores:', error);
                    modalContent.innerHTML = '<p>Error loading scores. Please try again.</p>';
                });
        }

        function closeModal() {
            const modal = document.getElementById('score-modal');
            modal.style.display = 'none';
        }
    </script>

</body>

</html>