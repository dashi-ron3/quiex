<?php
session_start();

if (!isset($_SESSION['quiz_student1'])) {
    // Redirect to randomizer if questions are not set
    header("Location: randomizer.php");
    exit();
}

// Retrieve randomized questions and options for student 1
$randomizedQuiz = $_SESSION['quiz_student1'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Student 1 Quiz</title>
</head>
<body>
    <h1>Student 1 - Quiz</h1>
    <div>
        <?php foreach ($randomizedQuiz as $question => $options): ?>
            <div class="question">
                <p><?php echo $question; ?></p>
                <?php foreach ($options as $option): ?>
                    <label>
                        <input type="radio" name="<?php echo $question; ?>" value="<?php echo $option; ?>">
                        <?php echo $option; ?>
                    </label><br>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
