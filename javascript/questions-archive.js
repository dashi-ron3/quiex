function showQuestionsPage(subject) {
    document.querySelector('.main-container').style.display = 'none';
    document.getElementById('questions-page').style.display = 'flex';
    document.getElementById('subject-title').textContent = subject;
}

function showQuestions(subject) {
    document.getElementById('subject-title').textContent = subject;
}

function goBack() {
    document.getElementById('questions-page').style.display = 'none';
    document.querySelector('.main-container').style.display = 'flex';
}
