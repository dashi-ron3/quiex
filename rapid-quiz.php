<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/logo-quiex.ico"/>
    <title>Rapid Quiz | QuiEx</title>
    <link rel="stylesheet" href="css/rapid-quiz.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        .start-screen {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .start-screen img {
            margin-bottom: 20px;
        }

        .start-button {
            padding: 10px 20px;
            font-size: 20px;
            font-weight: bold;
            background-color: #567cbd;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            border-radius: 50px;
        }

        .start-button:hover {
            background-color: #203f69;
        }

        .quiz-screen {
            display: none;
            position: relative;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .quiz-screen .logo {
            position: absolute;
            top: 20px;
            left: 20px;
        }
    </style>
</head>
<body>
    <div class="start-screen">
        <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="500" height="200">
        <h2>Are you ready?</h2>
        <button class="start-button" onclick="startQuiz()">Start</button>
    </div>

    <div class="quiz-screen">
        <div class="logo">
            <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50">
        </div>
        <div class="title">
            <h1>Rapid Quiz</h1> <img src="assets/timer.png" alt="Timer" class="clock-img" width="100" height="80">
        </div>
        <div class="question-number" id="questionNumber">
            1/10
        </div>
        <div class="container">
            <div class="timer">
                00:10
            </div>
            <p>What is the capital of South Korea?</p>
        </div>
        <div class="answer-box">
            <button class="choice answer1">BUSAN</button>
            <button class="choice answer2">JEJU</button>
            <button class="choice answer3" id="seoul">SEOUL</button>
            <button class="choice answer4">DAEGU</button>
        </div>

        <img src="assets/think.png" alt="Man Thinking" class="think-img" width="250" height="400">
        <img src="assets/lightning.png" alt="Lightning" class="bolt-img" width="300" height="300">
        <img src="assets/brain.png" alt="Brain" class="brain-img" width="120" height="100">
        <button class="restart-button" onclick="restartQuiz()" style="display: none;">Restart Quiz</button>
        <div class="score">
            <div class="score-box">
                <h2>Score: <span id="score">0</span></h2>
            </div>
        </div>
    </div>

    <script src="javascript/rapid-quiz.js"></script>
</body>
</html>
