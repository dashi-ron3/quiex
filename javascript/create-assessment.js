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

let currentQuestionIndex = 0;

function handleQuestionTypeChange(selectElement) {
    const selectedType = selectElement.value;
    const addChoiceSection = selectElement.closest('.question-block').querySelector("#add-choice-section");
    const choicesContainer = selectElement.closest('.question-block').querySelector("#choices-container");

    addChoiceSection.classList.add("hidden");
    choicesContainer.classList.add("hidden");

    if (selectedType === "multiple-choice" || selectedType === "checkboxes") {
        addChoiceSection.classList.remove("hidden");
        choicesContainer.classList.remove("hidden");
    }
}

function addChoice(questionIndex) {
    const choicesList = document.getElementById(`choices-list${questionIndex}`);
    const newChoice = document.createElement("li");
    const choiceInput = document.createElement("input");
    choiceInput.type = "text";
    choiceInput.placeholder = "Enter choice";
    newChoice.appendChild(choiceInput);
    choicesList.appendChild(newChoice);
}

function showQuestion(index) {
    const questions = document.querySelectorAll('.question-block');
    questions.forEach((question, idx) => {
        question.style.transform = `translateX(${(idx - index) * 100}%)`;
    });

    const prevBtn = document.getElementById('prev-question');
    const nextBtn = document.getElementById('next-question');

    if (index === 0) {
        prevBtn.classList.add('hidden');
    } else {
        prevBtn.classList.remove('hidden');
    }

    if (index === questions.length - 1) {
        nextBtn.classList.add('hidden');
    } else {
        nextBtn.classList.remove('hidden');
    }
}

document.getElementById('add-question').addEventListener('click', function () {
    const questionsContainer = document.getElementById('questions-container');
    const questionCount = questionsContainer.children.length + 1;

    const newQuestionBlock = document.createElement('div');
    newQuestionBlock.classList.add('question-block');

    newQuestionBlock.innerHTML = `
        <h2>Question ${questionCount}:</h2>
        <div class="question-type">
            <label for="question-type${questionCount}">Select Question Type:</label>
            <select id="question-type${questionCount}" onchange="handleQuestionTypeChange(this)">
                <option value="multiple-choice">Multiple Choice</option>
                <option value="true-false">True or False</option>
                <option value="long-answer">Long Answer (Essay)</option>
                <option value="short-answer">Short Answer (Identification)</option>
                <option value="checkboxes">Checkboxes (Multiple Answers)</option>
            </select>
        </div>

        <div class="question-input">
            <label for="question${questionCount}">Question:</label>
            <textarea id="question${questionCount}" rows="3" required></textarea>
        </div>

        <div id="add-choice-section" class="hidden">
            <button class="btn" onclick="addChoice(${questionCount})">Add Choice</button>
        </div>

        <div id="choices-container" class="hidden">
            <ul id="choices-list${questionCount}"></ul>
        </div>

        <div class="file-upload">
            <button class="btn">Choose file</button>
        </div>

        <div class="points-section">
            <label for="points${questionCount}">Points:</label>
            <input type="number" id="points${questionCount}" required>
        </div>
    `;

    questionsContainer.appendChild(newQuestionBlock);

    showQuestion(questionCount - 1);
    currentQuestionIndex = questionCount - 1;
});

document.getElementById('next-question').addEventListener('click', function () {
    const questions = document.querySelectorAll('.question-block');
    if (currentQuestionIndex < questions.length - 1) {
        currentQuestionIndex++;
        showQuestion(currentQuestionIndex);
    }
});

document.getElementById('prev-question').addEventListener('click', function () {
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        showQuestion(currentQuestionIndex);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    showQuestion(0);
});

function readQuestion() {
    const questionText = document.getElementById('question').value;
    if (questionText.trim() === "") {
        alert("Please enter a question first");
        return;
    }

    const utterance = new SpeechSynthesisUtterance(questionText);
    speechSynthesis.speak(utterance);
}
