<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Create Assessment Feature">
        <title>QuiEx</title>
    <link rel="stylesheet" href="css/create-assessment.css">
    <script src="javascript/create-assessment.js" defer></script>
</head>
<body>
    <header>
        <div class="logo">
            <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50">
        </div>
    </header>

    <div class="container">
        <h1>New Assessment</h1>
        <div class="assessment-section">
            <div class="left-panel">
                <button id="randomizeButton" class="btn-volume" onclick="randomizeQuestions()">
                    <img src="assets/randomizer.png" alt="Randomize Items">
                </button>
                <div class="timer-section">
                    <h2>Time Remaining: <span id="timer-display">00:00</span></h2>
                    <label for="timer">Set Timer:</label>
                    <input type="number" id="timer-input" placeholder="Enter minutes">
                    <button id="start-timer">Start Timer</button>
                </div>
                
                <div class="panel-body">
                    <h2>Assessment Title</h2>
                    <input type="text" placeholder="Enter title">

                    <div class="choices">
                        <h3>Choice</h3>
                        <ul>
                            <li>Multiple Choice</li>
                            <li>True or False</li>
                            <li>Checkbox</li>
                            <li>Identification</li>
                            <li>Essay</li>
                        </ul>
                    </div>

                    <div class="contact-info">
                        <h3>Contact Info</h3>
                        <ul>
                            <li>Email</li>
                            <li>Phone Number</li>
                            <li>Address</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="right-panel">
                <div class="questions-wrapper" id="questions-container">
                    <div class="question-block">
                        <h2>Question 1:</h2>
                        
                        <div class="question-type">
                            <label for="question-type1">Select Question Type:</label>
                            <select id="question-type1">
                                <option value="multiple-choice">Multiple Choice</option>
                                <option value="true-false">True or False</option>
                                <option value="long-answer">Long Answer (Essay)</option>
                                <option value="short-answer">Short Answer (Identification)</option>
                                <option value="checkboxes">Checkboxes (Multiple Answers)</option>
                            </select>
                        </div>

                        <div class="question-input">
                            <label for="question">Question:</label>
                            <textarea id="question" rows="3" required></textarea>
                        </div>

                        <div id="add-choice-section" class="hidden">
                            <button class="btn" id="add-choice-btn" onclick="addChoice()">Add Choice</button>
                        </div>

                        <div id="choices-container" class="hidden">
                            <ul id="choices-list"></ul>
                        </div>

                        <div class="file-upload">
                            <button class="btn">Choose file</button>
                        </div>

                        <div class="points-section">
                            <label for="points1">Points:</label>
                            <input type="number" id="points1" required>
                        </div>
                    </div>
                </div>

                <div class="navigation-arrows">
                    <button class="arrow" id="prev-question" class="hidden">← Previous</button>
                    <button class="arrow" id="next-question">Next →</button>
                </div>

                <button class="btn-submit" id="add-question">Add Question</button>
            </div>
        </div>
    </div>
</body>
</html>
