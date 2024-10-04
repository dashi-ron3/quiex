<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Create Assessment Feature">
    <title>New Assessment</title>
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
                <div class="panel-header">
                    <button class="btn-icon">+</button>
                    <button class="btn-icon">Play</button>
                    <button class="btn-icon">Stop</button>
                    <button class="btn-icon">Settings</button>
                    <button class="btn-icon">Help</button>
                    <button class="btn-icon">Volume</button>
                </div>
                
                <div class="panel-body">
                    <h2>Assessment Title</h2>
                    <input type="text" placeholder="Enter title">

                    <div class="choices">
                        <h3>Choice</h3>
                        <ul>
                            <li><input type="radio" name="question_type"> Multiple Choice</li>
                            <li><input type="radio" name="question_type"> True or False</li>
                            <li><input type="radio" name="question_type"> Checkbox</li>
                            <li><input type="radio" name="question_type"> Identification</li>
                            <li><input type="radio" name="question_type"> Essay</li>
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

                    <div class="text-media">
                        <h3>Text & Media</h3>
                        <ul>
                            <li>Long Text</li>
                            <li>Video</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="right-panel">
                <div class="header-row">
                    <h2>Question 1: <select>
                        <option>Multiple Choice</option>
                        <option>True or False</option>
                    </select>
                    
                    </h2>

                    <h4>Read Question:
                        <button class="btn-volume" onclick="readQuestion()">
                        <img src="assets/volume-icon.png" alt="Read Question">
                    </h4>
                </div>

                <div class="question-input">
                    <label for="question">Question:</label>
                    <input type="text" id="question">

                    <button class="btn">Add Choice</button>
                    <button class="btn">Add Choice</button>
                </div>

                <div class="file-upload">
                    <button class="btn">Choose file</button>
                </div>

                <div class="points-section">
                    <label for="points">Points:</label>
                    <input type="number" id="points">
                </div>
            </div>
        </div>

        <button class="btn-submit">Add Questions</button>
    </div>
</body>
</html>