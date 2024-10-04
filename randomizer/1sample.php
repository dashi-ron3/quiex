<?php
session_start();

// Define a set of questions and their options
$questions = [
    "Question 1?" => ["A. Yes", "B. No", "C. Maybe", "D. Never"],
    "Question 2?" => ["A. Yes", "B. No", "C. Maybe", "D. Never"],
    "Question 3?" => ["A. Yes", "B. No", "C. Maybe", "D. Never"],
];

// Function to randomize questions and their choices
function randomizeQuiz($questions) {
    $randomizedQuestions = array_keys($questions);
    shuffle($randomizedQuestions); // Randomize question order

    $randomizedQuiz = [];
    foreach ($randomizedQuestions as $question) {
        $options = $questions[$question];
        shuffle($options); // Randomize answer choices
        $randomizedQuiz[$question] = $options;
    }

    return $randomizedQuiz;
}

// Randomize questions for both students when the button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Randomize questions and options
    $_SESSION['quiz_student1'] = randomizeQuiz($questions);
    $_SESSION['quiz_student2'] = randomizeQuiz($questions);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Randomizer</title>
</head>
<body>
    <h1>Teacher - Randomize Questions</h1>
    <form method="POST">
        <button type="submit">Randomize Questions</button>
    </form>
</body>
</html>
