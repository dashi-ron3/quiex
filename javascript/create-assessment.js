let currentQuestionIndex = 0;
let timerInterval;

document.getElementById('start-timer').addEventListener('click', function() {
    const totalMinutesInput = parseInt(document.getElementById('total-time-input').value) || 0;

    const totalSeconds = totalMinutesInput * 60;

    if (totalSeconds > 0) {
        startTimer(totalSeconds); 
    } else {
        alert("Please enter a valid time.");
    }
});

function startTimer(duration) {
    let timeRemaining = duration;
    clearInterval(timerInterval);

    timerInterval = setInterval(function() {
        const hours = Math.floor(timeRemaining / 3600);
        const minutes = Math.floor((timeRemaining % 3600) / 60);
        const seconds = timeRemaining % 60;

        document.getElementById('timer-display').textContent = 
            `${hours < 10 ? '0' + hours : hours}:${minutes < 10 ? '0' + minutes : minutes}:${seconds < 10 ? '0' + seconds : seconds}`;

        if (--timeRemaining < 0) {
            clearInterval(timerInterval);
            alert("Time is up!");
        }
    }, 1000);
}

document.getElementById('save-btn').addEventListener('click', function() {
    saveAssessment('draft');
});

document.getElementById('publish-btn').addEventListener('click', function() {
    saveAssessment('published');
});

function saveAssessment(status) {
    let title = document.querySelector('input[placeholder="Enter title"]').value;
    let timeLimit = document.getElementById('total-time-input').value;

    if (!title) {
        alert('Please enter an assessment title.');
        return;
    }

    let questions = [];
    document.querySelectorAll('.question-block').forEach((questionBlock, index) => {
        let questionType = questionBlock.querySelector('select').value;
        let questionText = questionBlock.querySelector('textarea').value;
        let points = questionBlock.querySelector('input[type="number"]').value;

        let choices = [];
        if (questionType === 'multiple-choice' || questionType === 'checkboxes') {
            questionBlock.querySelectorAll('.choices-container li').forEach(choiceItem => {
                const choiceText = choiceItem.textContent.trim();
                if (choiceText) {
                    choices.push(choiceText);
                }
            });
        }

        questions.push({
            questionType,
            questionText,
            points,
            choices
        });
    });

    console.log({ title, timeLimit, questions, status });

    fetch('save-assessment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            title: title,
            timeLimit: timeLimit,
            questions: questions,
            status: status
        }),
    })
    .then(response => {
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (err) {
                throw new Error('Invalid JSON: ' + text);
            }
        });
    })
    .then(data => {
        console.log(data);
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

    const newQuestionBlock = document.createElement('div');
    newQuestionBlock.classList.add('question-block');

    newQuestionBlock.innerHTML = `
        <div class="question-type">
            <label for="question-type-${questionCount}">Question ${questionCount}:</label>
            <select id="question-type-${questionCount}" onchange="handleQuestionTypeChange(this)">
                <option value="multiple-choice">Multiple Choice</option>
                <option value="true-false">True or False</option>
                <option value="long-answer">Long Answer</option>
                <option value="short-answer">Short Answer</option>
                <option value="checkboxes">Checkboxes</option>
            </select>
        </div>

        <div class="question-input">
            <label for="question-${questionCount}">Question:</label><br>
            <textarea id="question-${questionCount}" rows="3" required></textarea>
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
                    <input type="file" id="file-upload-${questionCount}" accept="image/*,video/*" name="file" required>
                    <button class="btn" type="submit">Upload File</button>
                </div>
            </div>
        </form>

        <div class="points-section">
            <label for="points-${questionCount}">Points:</label><br>
            <input type="number" id="points-${questionCount}" required>
        </div>
    `;

    questionsContainer.appendChild(newQuestionBlock);

    showQuestion(questionCount - 1);
    currentQuestionIndex = questionCount - 1;
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
    if (currentQuestionIndex > 0) {
        showQuestion(currentQuestionIndex - 1);
    }
});

document.getElementById('next-question').addEventListener('click', function() {
    const questions = document.querySelectorAll('.question-block');
    if (currentQuestionIndex < questions.length - 1) {
        showQuestion(currentQuestionIndex + 1);
    }
});

document.getElementById('upload-btn').addEventListener('click', function() {
    document.getElementById('file-upload').click();
});

document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    fetch('file-upload.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('upload-error').textContent = '';

            let previewDiv = document.getElementById('preview');
            let fileType = data.fileType;
            let filePath = data.filePath;
            let fileName = data.fileName;

            let fileElement = document.createElement('div');
            fileElement.classList.add('preview-item');
            fileElement.setAttribute('data-file-name', fileName);

            if (fileType.startsWith('image')) {
                fileElement.innerHTML = `<img src="${filePath}" alt="${fileName}" width="200" height="150" /><span class="remove-preview">&times;</span>`;
            } else if (fileType.startsWith('video')) {
                fileElement.innerHTML = `<video src="${filePath}" controls width="200" height="150"></video><span class="remove-preview">&times;</span>`;
            }

            previewDiv.appendChild(fileElement);
            addRemoveListeners(); 
        } else {
            document.getElementById('upload-error').textContent = data.message;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('upload-error').textContent = 'An unexpected error occurred during file upload.';
    });
});

function addRemoveListeners() {
    document.querySelectorAll('.remove-preview').forEach(function (icon) {
        icon.addEventListener('click', function () {
            const previewItem = this.parentElement;
            const fileName = previewItem.getAttribute('data-file-name');

            if (confirm('Are you sure you want to delete this file?')) {
                fetch('delete-file.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ fileName: fileName }),
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        previewItem.remove();
                    } else {
                        alert('Error deleting the file.');
                    }
                });
            }
        });
    });
}

addRemoveListeners();
