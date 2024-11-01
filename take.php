<?php
$servername = "localhost"; 
$username = "root";   
$password = "weneklek";     
$dbname = "quiez";         

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the assessment and its questions
$assessmentId = $_GET['id']; // Assessment ID as a query parameter
$sql = "SELECT * FROM Assessments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $assessmentId);
$stmt->execute();
$assessment = $stmt->get_result()->fetch_assoc();

$sqlQuestions = "SELECT * FROM Questions WHERE assessment_id = ?";
$stmtQuestions = $conn->prepare($sqlQuestions);
$stmtQuestions->bind_param("i", $assessmentId);
$stmtQuestions->execute();
$questions = $stmtQuestions->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $assessment['title']; ?></title>
</head>
<body>
    <h1>Title: <?php echo $assessment['title']; ?></h1>
    <p>Description: <?php echo $assessment['description']; ?></p>

    <form action="submit_assessment.php" method="POST">
        <input type="hidden" name="assessment_id" value="<?php echo $assessmentId; ?>">
        
        <?php while ($question = $questions->fetch_assoc()): ?>
            <div>
                <p>Question:  <?php echo $question['question_text']; ?></p>
                <?php if ($question['type'] == 'multiple_choice'): ?>
                    <?php
                    // Fetch options for this question
                    $sqlOptions = "SELECT * FROM Options WHERE question_id = ?";
                    $stmtOptions = $conn->prepare($sqlOptions);
                    $stmtOptions->bind_param("i", $question['id']);
                    $stmtOptions->execute();
                    $options = $stmtOptions->get_result();
                    ?>
                    <?php while ($option = $options->fetch_assoc()): ?>
                        <label>
                            <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo $option['id']; ?>">
                            <?php echo $option['option_text']; ?>
                        </label><br>
                    <?php endwhile; ?>
                <?php elseif ($question['type'] == 'true_false'): ?>
                    <label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="true"> True</label><br>
                    <label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="false"> False</label><br>
                <?php elseif ($question['type'] == 'short_answer' || $question['type'] == 'long_answer'): ?>
                    <textarea name="answers[<?php echo $question['id']; ?>]" required></textarea>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>

        <button type="submit">Submit Assessment</button>
    </form>
</body>
</html>

<?php
$stmt->close();
$stmtQuestions->close();
$conn->close();
?>
