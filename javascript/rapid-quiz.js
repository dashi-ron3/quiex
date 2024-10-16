let currentQuestion = 0; // Start at 0 to reference the first question correctly
const totalQuestions = 10;
let timer;
let timeLeft = 10;

// Store questions for multiple choice and identification
const questions = [
    { type: 'multiple-choice', question: 'What is the capital of South Korea?', answers: ['BUSAN', 'JEJU', 'SEOUL', 'DAEGU'], correct: 'SEOUL' },
    { type: 'identification', question: 'What is the largest planet in our solar system?', answer: 'JUPITER' },
    { type: 'multiple-choice', question: 'What is the capital of Japan?', answers: ['TOKYO', 'YOKOHAMA', 'NAGOYA', 'OSAKA'], correct: 'TOKYO' },
    { type: 'identification', question: 'Name the author of "No Longer Human".', answer: 'DAZAI OSAMU' },
    { type: 'multiple-choice', question: 'Which element has the chemical symbol O?', answers: ['Gold', 'Oxygen', 'Silver', 'Iron'], correct: 'Oxygen' },
    { type: 'identification', question: 'What is the chemical symbol for water?', answer: 'H2O' },
    { type: 'multiple-choice', question: 'What is the smallest continent by land area?', answers: ['Australia', 'Europe', 'Antarctica', 'South America'], correct: 'Australia' },
    { type: 'multiple-choice', question: 'Which planet is known as the "Red Planet"?', answers: ['Earth', 'Venus', 'Mars', 'Jupiter'], correct: 'Mars' },
    { type: 'identification', question: 'What is the chemical symbol for gold?', answer: 'AU' },
    { type: 'identification', question: 'Who painted the Mona Lisa?', answer: 'LEONARDO DA VINCI' },
];

// Shuffle questions array for randomness
questions.sort(() => Math.random() - 0.5);

function updateQuestionNumber() {
    document.getElementById('questionNumber').innerText = `${currentQuestion + 1}/${totalQuestions}`; // Adjust to show current question correctly
}

function startTimer() {
    clearInterval(timer);
    timeLeft = 10;
    document.querySelector('.timer').innerText = `00:${timeLeft < 10 ? '0' : ''}${timeLeft}`;

    timer = setInterval(() => {
        timeLeft--;
        document.querySelector('.timer').innerText = `00:${timeLeft < 10 ? '0' : ''}${timeLeft}`;

        if (timeLeft <= 0) {
            clearInterval(timer);
            nextQuestion();
        }
    }, 1000);
}

function nextQuestion() {
    if (currentQuestion < totalQuestions) { // Change <= to < to avoid out of bounds
        const questionData = questions[currentQuestion];
        document.querySelector('.container p').innerText = questionData.question;

        // Clear previous answer options
        document.querySelector('.answer-box').innerHTML = '';

        if (questionData.type === 'multiple-choice') {
            // Display multiple-choice answers
            questionData.answers.forEach(answer => {
                const button = document.createElement('button');
                button.classList.add('choice');
                button.innerText = answer;
                button.onclick = handleAnswerClick;
                document.querySelector('.answer-box').appendChild(button);
            });
        } else if (questionData.type === 'identification') {
            // Display input box for identification question
            const input = document.createElement('input');
            input.type = 'text';
            input.placeholder = 'Your answer...';
            input.id = 'identificationAnswer';
            input.classList.add('identification-input'); // Add class for styling

            const submitButton = document.createElement('button');
            submitButton.innerText = 'Submit';
            submitButton.classList.add('identification-submit'); // Add class for styling
            submitButton.onclick = handleIdentificationSubmit;

            document.querySelector('.answer-box').appendChild(input);
            document.querySelector('.answer-box').appendChild(submitButton);
        }

        updateQuestionNumber(); // Update question number after setting question
        startTimer(); // Start timer before moving to the next question
    } else {
        clearInterval(timer);
        alert('Quiz Complete!');
    }
}

function handleAnswerClick() {
    const questionData = questions[currentQuestion]; // Get current question data
    const selectedAnswer = this.innerText; // Get clicked answer text
    if (selectedAnswer === questionData.correct) {
        alert('Correct!');
    } else {
        alert(`Incorrect! The correct answer was ${questionData.correct}.`);
    }
    currentQuestion++; // Move to next question after answer
    nextQuestion();
}

function handleIdentificationSubmit() {
    const input = document.getElementById('identificationAnswer');
    const answer = input.value.trim().toUpperCase();
    const correctAnswer = questions[currentQuestion].answer; // Get the correct answer based on the current question index
    if (answer === correctAnswer) {
        alert('Correct!');
    } else {
        alert(`Incorrect! The correct answer was ${correctAnswer}.`);
    }
    currentQuestion++; // Move to next question after submission
    nextQuestion();
}

function startQuiz() {
    document.querySelector('.start-screen').style.display = 'none';
    document.querySelector('.quiz-screen').style.display = 'flex';
    updateQuestionNumber();
    nextQuestion();
}

document.querySelector('.start-button').addEventListener('click', startQuiz);
