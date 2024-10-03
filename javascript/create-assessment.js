document.addEventListener("DOMContentLoaded", function() {
    const addChoiceButtons = document.querySelectorAll('.btn');
    
    addChoiceButtons.forEach(button => {
        button.addEventListener('click', function() {
            alert('Add choice functionality coming soon!');
        });
    });
});