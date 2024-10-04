document.addEventListener("DOMContentLoaded", function() {
    const addChoiceButtons = document.querySelectorAll('.btn');
    
    addChoiceButtons.forEach(button => {
        button.addEventListener('click', function() {
            alert('Add choice functionality coming soon!');
        });
    });
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
