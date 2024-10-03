<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuiEx</title>
    <link rel="stylesheet" href="css/rapidquiz3.css">
    <script src="javascript/rapid-quiz.js"></script>
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
        <h2>Rapid Quiz</h2>
        <button class="start-button" onclick="startQuiz()">Start</button>
    </div>

    <div class="quiz-screen">
        <div class="logo">
            <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50">
        </div>
        <div class="title">
            <h1>Rapid Quiz</h1> <img src="assets/timer.png" alt="Timer" width="100" height="80">
        </div>
        <div class="container">
            <div class="timer">
                00:10
            </div>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
            incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
            exercitation ullamco laboris nisi ut aliquip ex ea com...</p>
        </div>
        <div class="answer-box">
            <div class="choice answer1">CHOICE 1</div>
            <div class="choice answer2">CHOICE 2</div>
            <div class="choice answer3">CHOICE 3</div>
            <div class="choice answer4">CHOICE 4</div>
        </div>
        <img src="assets/think.png" alt="Man Thinking" class="think-img" width="200" height="300">
        <img src="assets/lightning.png" alt="Lightning" class="bolt-img" width="250" height="250">
        <img src="assets/brain.png" alt="Brain" class="brain-img" width="120" height="100">
    </div>

    <script>
        function startQuiz() {
            document.querySelector('.start-screen').style.display = 'none';
            document.querySelector('.quiz-screen').style.display = 'flex';
        }
    </script>
</body>
</html>
