<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Maker</title>
    <link rel="stylesheet" type="text/css" href="qstyle.css">
</head>
<body>
    <form action="process-q.php" method="POST" id="quizForm" enctype="multipart/form-data">
        <div>
            <h1>Assessment Maker</h1>
            <p>Number of Questions: <span id="questionCounter">0</span></p>
        </div>

        <div id="titleDescription">
            <label for="quiz-subject">Subject:</label>
            <input type="text" id="quiz-subject" name="quiz-subject" required>
            <br>
            <label for="quiz-title">Title:</label>
            <input type="text" id="quiz-title" name="quiz-title" required>
            <br>
            <label for="quiz-description">Description:</label>
            <textarea id="quiz-description" name="quiz-description" rows="4"></textarea>
        </div>

        <div id="quizContainer">
            <!-- Questions will be added here -->
        </div>

        <button id="addQuestion" type="button">Add Question</button>

        <div id="quizSettings">
            <label for="openDate">Open Date:</label>
            <input type="datetime-local" id="openDate" name="openDate">
            <br>
            <label for="closeDate">Close Date:</label>
            <input type="datetime-local" id="closeDate" name="closeDate">
            <br>
            <label for="timerHours">Timer Hours:</label>
            <input type="number" id="timerHours" name="timerHours">
            <br>
            <label for="timerMinutes">Timer Minutes:</label>
            <input type="number" id="timerMinutes" name="timerMinutes">
            <br>
            <label for="maxAttempts">Max Attempts:</label>
            <input type="number" id="maxAttempts" name="maxAttempts">
            <br>
            <label for="randomizeOrder">Randomize Order:</label>
            <input type="checkbox" id="randomizeOrder" name="randomizeOrder">
        </div>

        <button id="done" type="submit">Done</button>
    </form>

    <script>
        let questionCounter = 0;

        function updateQuestionCounter() {
            document.getElementById("questionCounter").textContent = questionCounter;
        }

        function addQuestion() {
            questionCounter++;
            updateQuestionCounter();

            const questionDiv = document.createElement("div");
            questionDiv.classList.add("question");

            questionDiv.innerHTML = `
                <h3>Question ${questionCounter}</h3>
                <h4>Question Type:</h4>
                <select class="question-type" name="question_type[]">
                    <option value="short_answer">Short Answer</option>
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="essay">Essay</option>
                    <option value="true_false">True/False</option>
                </select>
                <h4>Question:</h4>
                <input type="text" name="question_text[]" placeholder="Question Text" required>
                
                <div class="answer-fields"></div>

                <div class="correct-answer" style="display: none;">
                    <h4>Correct Answer:</h4>
                    <input type="text" name="correct_answer[]" placeholder="Type correct answer here">
                </div>

                <div class="scorePoints">
                    <label for="points">Points:</label>
                    <input type="number" name="points[]" placeholder="0" required>
                </div>
                Image: <input type="file" accept="image/*" name="imagePath[]" onchange="showImage(this)"><br>
                <img class="image-preview" style="max-width: 200px; display: none;"><br>
                Feedback: <textarea name="feedback[]"></textarea>
                <button class="deleteQuestion" type="button">Delete</button>
            `;

            questionDiv.querySelector(".question-type").addEventListener("change", function() {
                const answerFields = questionDiv.querySelector(".answer-fields");
                const correctAnswerField = questionDiv.querySelector(".correct-answer");
                answerFields.innerHTML = ''; // Clear previous answer fields
                const selectedType = this.value;

                // Display correct answer input field for all question types
                correctAnswerField.style.display = 'block';

                switch (selectedType) {
                    case "multiple_choice":
                        addMultipleChoiceFields(answerFields);
                        break;
                    case "essay":
                        addEssayField(answerFields);
                        break;
                    case "true_false":
                        addTrueFalseFields(answerFields);
                        break;
                }
            });

            document.getElementById("quizContainer").appendChild(questionDiv);

            questionDiv.querySelector(".deleteQuestion").addEventListener("click", function() {
                questionCounter--;
                updateQuestionCounter();
                questionDiv.remove();
            });
        }

        function addMultipleChoiceFields(container) {
            for (let i = 0; i < 4; i++) {
                container.innerHTML += `
                    <div>
                        <label>Option ${i + 1}:</label>
                        <input type="text" name="option_text[${questionCounter - 1}][]" placeholder="Option Text" required>
                    </div>
                `;
            }
        }

        function addEssayField(container) {
            container.innerHTML += `<textarea name="essay_response[]"></textarea>`;
        }

        function addTrueFalseFields(container) {
            container.innerHTML += `
                <div>
                    <label>True/False (Correct Answer: Type 'true' or 'false')</label>
                </div>
            `;
        }

        function showImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgPreview = input.nextElementSibling;
                    imgPreview.src = e.target.result;
                    imgPreview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById("addQuestion").addEventListener("click", addQuestion);
    </script>
</body>
</html>
