<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Assessment">
    <link rel="icon" href="assets/logo-quiex.ico"/>
    <title>Take Assessment | QuiEx</title>
    <link rel="stylesheet" href="css/take-assessment.css">
    <script src="javascript/take-assessment.js" defer></script>
</head>
<body>
    <header>
        <div class="logo">
            <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50">
        </div>
    </header>

    <div class="container">
        <h1>Assessment: <span id="assessment-title"></span></h1>
        
        <div id="question-container">
            <h2 id="question-text"></h2>
            
            <div id="choices-container"></div>
            
            <div id="error-message" style="color: red; display: none;">Please select an answer before proceeding.</div>
            
            <button id="prev-button" onclick="prevQuestion()" disabled>← Previous</button>
            <button id="next-button" onclick="nextQuestion()" disabled>Next →</button>
        </div>

        <button id="submit-button" onclick="submitAssessment()" style="display: none;">Submit Assessment</button>
    </div>

</body>
</html>

<!--<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Reader</title>
    <script src="javascript/take-assessment.js" defer></script>
</head>
<body>
    <div id="text-container">Sample text</div>
    <button id="read-button">Read Question</button>
</body>
</html>-->