<?php
session_start();

$questions = [
    "Question 1?" => ["A. Yes", "B. No", "C. Maybe", "D. Never"],
    "Question 2?" => ["A. Yes", "B. No", "C. Maybe", "D. Never"],
    "Question 3?" => ["A. Yes", "B. No", "C. Maybe", "D. Never"],
];

function randomizeQuiz($questions) {
    $randomizedQuestions = array_keys($questions);
    shuffle($randomizedQuestions);

    $randomizedQuiz = [];
    foreach ($randomizedQuestions as $question) {
        $options = $questions[$question];
        shuffle($options);
        $randomizedQuiz[$question] = $options;
    }

    return $randomizedQuiz;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    <h1>Randomize Questions</h1>
    <form method="POST">
        <button type="submit">Randomize Questions</button>
    </form>
</body>
</html>
