// back to top button
const backToTopButton = document.getElementById('backToTop');

window.onscroll = function() {
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        backToTopButton.style.display = 'block';
    } else {
        backToTopButton.style.display = 'none';
    }
};

backToTopButton.onclick = function() {
    window.scrollTo({top: 0, behavior: 'smooth'});
};

function checkAnswerCorrectness($student_answer, $correct_answer) {
    // Normalize the answers for comparison
    $user_answer_normalized = strtolower(trim($student_answer));
    $correct_answer_normalized = strtolower(trim($correct_answer));

    // Return true if answers match, false otherwise
    return $user_answer_normalized === $correct_answer_normalized;
}
