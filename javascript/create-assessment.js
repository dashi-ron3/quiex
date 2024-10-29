let currentQuestionIndex = 0;

document.getElementById('save-btn').addEventListener('click', function() {
    saveAssessment('draft');
});

document.getElementById('publish-btn').addEventListener('click', function() {
    saveAssessment('published');
});

function saveAssessment(status) {
    let title = document.querySelector('input[placeholder="Enter title"]').value;

    if (!title) {
        alert('Please enter an assessment title.');
        return;
    }

    let questions = [];
    let hasQuestions = false; 

    document.querySelectorAll('.question-block').forEach((questionBlock, index) => {
        let questionType = questionBlock.querySelector('select').value;
        let questionText = questionBlock.querySelector('textarea').value;
        let points = questionBlock.querySelector('input[type="number"]').value;

        if (!questionType || !questionText) {
            alert(`Please enter a question type and text for question ${index + 1}.`);
            return;
        }

        if (!points) {
            alert(`Please enter points for question ${index + 1}.`);
            return;
        }

        let choices = [];
        if (questionType === 'multiple-choice' || questionType === 'checkboxes') {
            questionBlock.querySelectorAll('.choices-container li').forEach(choiceItem => {
                const choiceText = choiceItem.querySelector('input').value.trim();
                if (choiceText) {
                    choices.push(choiceText);
                }
            });

            if (choices.length === 0) {
                alert(`Please add at least one choice for question ${index + 1}.`);
                return;
            }
        }

        questions.push({
            questionType,
            questionText,
            points,
            choices
        });
        hasQuestions = true;
    });

    if (!hasQuestions) {
        alert('Please add at least one question to the assessment.');
        return;
    }

    localStorage.setItem('assessmentTitle', title);
    localStorage.setItem('assessmentQuestions', JSON.stringify(questions));

    fetch('save-assessment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            title: title,
            questions: questions,
            status: status
        }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('message').textContent = 'Assessment saved successfully!';
            if (data.unique_code) {
                document.getElementById('message').textContent += ' Unique Code: ' + data.unique_code;
            }
        } else {
            document.getElementById('message').textContent = 'Error: ' + data.message;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('message').textContent = 'An unexpected error occurred: ' + error.message;
    });
}

function loadAssessment() {
    const savedTitle = localStorage.getItem('assessmentTitle');
    const savedQuestions = localStorage.getItem('assessmentQuestions');

    if (savedTitle) {
        document.querySelector('input[placeholder="Enter title"]').value = savedTitle;
    }

    if (savedQuestions) {
        const questions = JSON.parse(savedQuestions);
        questions.forEach((question, index) => {
            addQuestionBlock(question, index);
        });
    }
}

function addQuestionBlock(question, index) {
    const questionsContainer = document.getElementById('questions-container');
    const questionCount = questionsContainer.children.length + 1;

    const newQuestionBlock = document.createElement('div');
    newQuestionBlock.classList.add('question-block');

    newQuestionBlock.innerHTML = `
        <div class="question-type">
            <label for="question-type-${questionCount}">Question ${questionCount}:</label>
            <select id="question-type-${questionCount}" onchange="handleQuestionTypeChange(this)">
                <option value="multiple-choice" ${question.questionType === 'multiple-choice' ? 'selected' : ''}>Multiple Choice</option>
                <option value="true-false" ${question.questionType === 'true-false' ? 'selected' : ''}>True or False</option>
                <option value="long-answer" ${question.questionType === 'long-answer' ? 'selected' : ''}>Long Answer</option>
                <option value="short-answer" ${question.questionType === 'short-answer' ? 'selected' : ''}>Short Answer</option>
                <option value="checkboxes" ${question.questionType === 'checkboxes' ? 'selected' : ''}>Checkboxes</option>
            </select>
        </div>

        <div class="question-input">
            <label for="question-${questionCount}">Question:</label><br>
            <textarea id="question-${questionCount}" rows="3" required>${question.questionText}</textarea>
        </div>

        <div class="add-choice-section hidden">
            <button class="btn" onclick="addChoice(${questionCount})">Add Choice</button>
        </div>

        <div class="choices-container hidden">
            <ul id="choices-list-${questionCount}"></ul>
        </div>

        <form id="uploadForm-${questionCount}" enctype="multipart/form-data" action="file-upload.php" method="POST">
            <div class="file-upload-box">
                <div class="file-upload-header">
                    <span class="remove-icon">&times;</span>
                </div>
                <div class="file-upload">
                    <input type="file" id="file-upload-${questionCount}" accept="image/*,video/*" name="file">
                    <button class="btn" type="submit">Upload File</button>
                </div>
            </div>
        </form>

        <div class="points-section">
            <label for="points-${questionCount}">Points:</label><br>
            <input type="number" id="points-${questionCount}" value="${question.points}" required>
        </div>
    `;

    questionsContainer.appendChild(newQuestionBlock);

    if (question.choices.length > 0) {
        const choicesList = document.getElementById(`choices-list-${questionCount}`);
        question.choices.forEach(choice => {
            const newChoice = document.createElement("li");
            const choiceInput = document.createElement("input");
            choiceInput.type = "text";
            choiceInput.value = choice;
            newChoice.appendChild(choiceInput);
            choicesList.appendChild(newChoice);
        });
    }

    showQuestion(questionCount - 1);
    currentQuestionIndex = questionCount - 1;
}

window.onload = loadAssessment;

function handleQuestionTypeChange(selectElement) {
    const selectedType = selectElement.value;
    const questionBlock = selectElement.closest('.question-block');
    const addChoiceSection = questionBlock.querySelector(".add-choice-section");
    const choicesContainer = questionBlock.querySelector(".choices-container");

    addChoiceSection.classList.add("hidden");
    choicesContainer.classList.add("hidden");

    if (selectedType === "multiple-choice" || selectedType === "true-false" || selectedType === "short-answer" || selectedType === "long-answer" || selectedType === "checkboxes") {
        addChoiceSection.classList.remove("hidden");
        choicesContainer.classList.remove("hidden");
    }
}

document.getElementById('add_question').addEventListener('click', function() {
    const questionsContainer = document.getElementById('questions-container');
    const questionCount = questionsContainer.children.length + 1;

    addQuestionBlock({ questionType: 'multiple-choice', questionText: '', points: '', choices: [] }, questionCount - 1);
});

function addChoice(questionIndex) {
    const choicesList = document.getElementById(`choices-list-${questionIndex}`);
    const newChoice = document.createElement("li");

    const choiceInput = document.createElement("input");
    choiceInput.type = "text";
    choiceInput.placeholder = "Enter choice";

    newChoice.appendChild(choiceInput);
    choicesList.appendChild(newChoice);
}

function showQuestion(index) {
    const questions = document.querySelectorAll('.question-block');
    
    if (index < 0 || index >= questions.length) {
        return;
    }

    questions.forEach((question) => {
        question.style.display = 'none';
    });

    questions[index].style.display = 'block';
    currentQuestionIndex = index;
    updateNavigationButtons();
}

function updateNavigationButtons() {
    const questions = document.querySelectorAll('.question-block');
    const prevBtn = document.getElementById('prev-question');
    const nextBtn = document.getElementById('next-question');

    prevBtn.style.display = currentQuestionIndex === 0 ? 'none' : 'inline-block';
    nextBtn.style.display = currentQuestionIndex === questions.length - 1 ? 'none' : 'inline-block';
}

document.getElementById('prev-question').addEventListener('click', function() {
    showQuestion(currentQuestionIndex - 1);
});

document.getElementById('next-question').addEventListener('click', function() {
    showQuestion(currentQuestionIndex + 1);
});

showQuestion(0);
