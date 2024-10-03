<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuiEx</title>
    <link rel="stylesheet" href="css/rapidquiz2.css">
    <script src="javascript/rapid-quiz.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        /* Start Screen Styles */
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
            background-color: #567cbd;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .start-button:hover {
            background-color: #203f69;
        }

        /* Quiz Screen Styles */
        .quiz-screen {
            display: none;
            position: relative; /* Set to relative to allow absolute positioning of logo */
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Logo positioned in the top-left */
        .quiz-screen .logo {
            position: absolute;
            top: 20px;
            left: 20px;
        }
    </style>
</head>
<body>
    <!-- Start Screen -->
    <div class="start-screen">
        <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="200" height="80">
        <button class="start-button" onclick="startQuiz()">Start</button>
    </div>

    <!-- Quiz Screen -->
    <div class="quiz-screen">
        <div class="logo">
            <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50">
        </div>
        <h1>Rapid Quiz</h1>
        <div class="container">
            <div class="timer">
                00:10
            </div>
        </div>
        <div class="answer-box">
            <div class="choice answer1">CHOICE 1</div>
            <div class="choice answer2">CHOICE 2</div>
            <div class="choice answer3">CHOICE 3</div>
            <div class="choice answer4">CHOICE 4</div>
        </div>
    </div>

    <script>
        function startQuiz() {
            document.querySelector('.start-screen').style.display = 'none';
            document.querySelector('.quiz-screen').style.display = 'flex';
        }
    </script>
</body>
</html>
