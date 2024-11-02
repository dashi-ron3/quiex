let questionCounter = 0;

function copyQuizID() {
    const quizIDElement = document.getElementById("quizID");
    const quizID = quizIDElement.textContent;

    const tempInput = document.createElement("input");
    tempInput.value = quizID;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);

    alert("Quiz ID copied to clipboard: " + quizID);
}

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
        
        <h4>Upload Image/Video:</h4>
        <input type="file" accept="image/*,video/*" onchange="showImage(this)" />
        <div class="preview" style="margin-top: 10px;"></div>
        
        <div id="upload-error" style="color: red;"></div>

        Feedback: <textarea name="feedback[]"></textarea>
        <button class="deleteQuestion" type="button">Delete</button>
    `;

    questionDiv.querySelector(".question-type").addEventListener("change", function() {
        const answerFields = questionDiv.querySelector(".answer-fields");
        const correctAnswerField = questionDiv.querySelector(".correct-answer");
        answerFields.innerHTML = ''; 
        const selectedType = this.value;

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
            </div>`;
    }
}

function addEssayField(container) {
    container.innerHTML += `<textarea name="essay_response[]"></textarea>`;
}

function addTrueFalseFields(container) {
    container.innerHTML += `
        <div>
            <label>True/False (Correct Answer: Type 'true' or 'false')</label>
        </div>`;
}

function showImage(input) {
    const previewContainer = input.nextElementSibling; 
    previewContainer.innerHTML = ''; 

    if (input.files && input.files.length) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const filePreview = document.createElement(file.type.startsWith('image/') ? 'img' : 'video');
                filePreview.src = e.target.result;
                filePreview.controls = file.type.startsWith('video/');
                filePreview.style.width = '200px';
                filePreview.style.height = '150px';
                const removeButton = document.createElement('span');
                removeButton.textContent = 'X';
                removeButton.style.cursor = 'pointer';
                removeButton.style.color = 'red';
                removeButton.onclick = function() {
                    previewContainer.innerHTML = ''; 
                    input.value = ''; 
                };

                previewContainer.appendChild(filePreview);
                previewContainer.appendChild(removeButton);
            };
            reader.readAsDataURL(file);
        });
    }
}

document.getElementById("addQuestion").addEventListener("click", addQuestion);