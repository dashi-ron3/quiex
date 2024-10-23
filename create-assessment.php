<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Create Assessment Feature">
    <link rel="icon" href="assets/logo-quiex.ico"/>
    <title>New Assessment | QuiEx</title>
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
                    <img src="assets/randomizer.png" alt="Randomize Items" width="30" height="30"/>
                </button>

                <div class="panel-body">
                    <h2>Assessment Title</h2>
                    <input type="text" placeholder="Enter title">
                </div>

                <div class="timer-section">
                    <h4>Time Remaining: <span id="timer-display">00:00:00</span></h4>
                    <label for="total-time-input">Set Timer:</label>
                    <input type="number" id="total-time-input" placeholder="Enter time in minutes..." min="0">
                    <br>
                    <button id="start-timer">Start Timer</button>
                </div>

                <div class="q-bank">
                    <h4>Access Question Archive</h4>
                    <button class="archive">Archive</button>
                </div>

                <div class="instructions">
                    <h4>Creating an Assessment</h4>
                    <li></li>
                </div>

                <div id="save-assessment">
                    <button id="save-btn">Save Assessment</button>
                </div>

                <div id="publish-assessment">
                    <button id="publish-btn">Publish Assessment</button>
                </div>

                <div id="message"></div>

            </div>

            <div class="right-panel">
                <div class="questions-wrapper" id="questions-container">
                    <div class="question-block">
                        <div class="question-type">
                            <label for="question-type1">Question 1:</label>
                            <select id="question-type1" onchange="handleQuestionTypeChange(this)">
                                <option value="multiple-choice">Multiple Choice</option>
                                <option value="true-false">True or False</option>
                                <option value="long-answer">Long Answer</option>
                                <option value="short-answer">Short Answer</option>
                                <option value="checkboxes">Checkboxes</option>
                            </select>
                        </div>

                        <div class="question-input">    
                            <label for="question">Question:</label> <br>
                            <textarea id="question" rows="3" required></textarea>
                        </div>

                        <div class="add-choice-section hidden">
                            <button class="btn" onclick="addChoice(1)">Add Choice</button>
                        </div>

                        <div class="choices-container hidden">
                            <ul id="choices-list-1"></ul>
                        </div>

                        <form id="uploadForm" enctype="multipart/form-data" action="file-upload.php" method="POST">
                            <div class="file-upload-box">
                                <div class="file-upload-header">
                                    <span class="remove-icon">&times;</span>
                                </div>
                                <div class="file-upload">
                                    <input type="file" id="file-upload" accept="image/*,video/*" name="file" required>
                                    <button class="btn" type="submit">Upload File</button>
                                </div>
                            </div>
                        </form>

                        <div id="preview">
                            <?php
                            $uploadFileDir = 'uploads-assessments/';
                            $files = array_diff(scandir($uploadFileDir), array('.', '..'));

                            foreach ($files as $file) {
                                $filePath = $uploadFileDir . $file;
                                $fileType = mime_content_type($filePath);

                                if (strpos($fileType, 'image') !== false) {
                                    echo "<div class='preview-item' data-file-name='$file'><img src='$filePath' alt='$file' width='200' height='150' /><span class='remove-preview'>&times;</span></div>";
                                } elseif (strpos($fileType, 'video') !== false) {
                                    echo "<div class='preview-item' data-file-name='$file'><video src='$filePath' controls width='200' height='150'></video><span class='remove-preview'>&times;</span></div>";
                                }
                            }
                            ?>
                        </div>

                        <div id="upload-error" style="color: red;"></div>

                        <div class="points-section">
                            <label for="points1">Points:</label> <br>
                            <input type="number" id="points1" required>
                        </div>
                    </div>
                </div>

                <div class="navigation-arrows">
                    <button class="prev" id="prev-question">← Previous</button>
                    <button class="next" id="next-question">Next →</button>
                </div> 
            </div>
        </div>
        <button class="btn-submit" id="add_question">Add Question</button>
    </div>

</body>     
</html>
