let currentQuestion = 0;
const totalQuestions = 10;
let timer;
let timeLeft = 10;

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

questions.sort(() => Math.random() - 0.5);

function updateQuestionNumber() {
    document.getElementById('questionNumber').innerText = `${currentQuestion + 1}/${totalQuestions}`;
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
            currentQuestion++;
            nextQuestion();
        }
    }, 1000);
}

function nextQuestion() {
    if (currentQuestion < totalQuestions) {
        const questionData = questions[currentQuestion];
        document.querySelector('.container p').innerText = questionData.question;

        document.querySelector('.answer-box').innerHTML = '';

        if (questionData.type === 'multiple-choice') {
            questionData.answers.forEach(answer => {
                const button = document.createElement('button');
                button.classList.add('choice');
                button.innerText = answer;
                button.onclick = handleAnswerClick;
                document.querySelector('.answer-box').appendChild(button);
            });
        } else if (questionData.type === 'identification') {
            const input = document.createElement('input');
            input.type = 'text';
            input.placeholder = 'Your answer...';
            input.id = 'identificationAnswer';
            input.classList.add('identification-input');

            const submitButton = document.createElement('button');
            submitButton.innerText = 'Submit';
            submitButton.classList.add('identification-submit');
            submitButton.onclick = handleIdentificationSubmit;

            document.querySelector('.answer-box').appendChild(input);
            document.querySelector('.answer-box').appendChild(submitButton);
        }

        updateQuestionNumber();
        startTimer();
    } else {
        clearInterval(timer);
        alert('Quiz Complete!');
    }
}

function handleAnswerClick() {
    const questionData = questions[currentQuestion];
    const selectedAnswer = this.innerText;
    const isCorrect = selectedAnswer === questionData.correct;

    this.style.backgroundColor = isCorrect ? 'green' : 'red';
    this.style.color = 'white';

    setTimeout(() => {
        alert(isCorrect ? 'Correct!' : `Incorrect! The correct answer was ${questionData.correct}.`);
        currentQuestion++;
        nextQuestion();
    }, 1000); 
}

function handleIdentificationSubmit() {
    const input = document.getElementById('identificationAnswer');
    const answer = input.value.trim().toUpperCase();
    const correctAnswer = questions[currentQuestion].answer;
    const isCorrect = answer === correctAnswer; 

    input.style.backgroundColor = isCorrect ? 'green' : 'red';
    input.style.color = 'white'; 

    setTimeout(() => {
        alert(isCorrect ? 'Correct!' : `Incorrect! The correct answer was ${correctAnswer}.`);
        currentQuestion++; 
        nextQuestion();
    }, 1000);
}

function startQuiz() {
    document.querySelector('.start-screen').style.display = 'none';
    document.querySelector('.quiz-screen').style.display = 'flex';
    updateQuestionNumber();
    nextQuestion();
}

document.querySelector('.start-button').addEventListener('click', startQuiz);
