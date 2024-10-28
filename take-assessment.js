let currentQuestionIndex = 0;
let questions = [];
let answers = {};

function loadAssessment(assessmentCode) {
    fetch('fetch-assessment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            unique_code: assessmentCode
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            questions = data.questions;
            document.getElementById('assessment-title').textContent = data.title;
            showQuestion(0);
        } else {
            alert(data.message || 'Error fetching assessment.');
        }
    })
    .catch(error => console.error('Error:', error));
}

function showQuestion(index) {
    const question = questions[index];
    document.getElementById('question-text').textContent = question.question_text;
    
    const choicesContainer = document.getElementById('choices-container');
    choicesContainer.innerHTML = '';  
    question.choices.forEach(choice => {
        const choiceElement = document.createElement('div');
        choiceElement.innerHTML = `
            <input type="radio" name="answer" value="${choice.id}" onclick="enableNextButton(${index})"> 
            ${choice.choice_text}
        `;
        choicesContainer.appendChild(choiceElement);
    });

    document.getElementById('prev-button').disabled = (index === 0);
    document.getElementById('next-button').disabled = !answers[question.id];
    
    if (index === questions.length - 1) {
        document.getElementById('next-button').style.display = 'none';
        document.getElementById('submit-button').style.display = 'inline';
    } else {
        document.getElementById('next-button').style.display = 'inline';
        document.getElementById('submit-button').style.display = 'none';
    }
}

function enableNextButton(index) {
    answers[questions[index].id] = document.querySelector('input[name="answer"]:checked').value;
    document.getElementById('next-button').disabled = false;
}

function nextQuestion() {
    if (currentQuestionIndex < questions.length - 1) {
        currentQuestionIndex++;
        showQuestion(currentQuestionIndex);
    }
}

function prevQuestion() {
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        showQuestion(currentQuestionIndex);
    }
}

function submitAssessment() {
    fetch('path/to/submit/assessment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            assessmentId: getAssessmentIdFromCode(assessmentCode), 
            answers: answers
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Assessment submitted successfully!');
        } else {
            alert(data.message || 'Error submitting assessment.');
        }
    })
    .catch(error => console.error('Error:', error));
}

const urlParams = new URLSearchParams(window.location.search);
const assessmentCode = urlParams.get('code'); 
loadAssessment(assessmentCode);