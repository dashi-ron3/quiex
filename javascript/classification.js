document.addEventListener('DOMContentLoaded', function () {
    let selectedRole = ''; 

    document.getElementById('studentBtn').addEventListener('click', function() {
        selectRole('student');
    });

    document.getElementById('teacherBtn').addEventListener('click', function() {
        selectRole('teacher');
    });

    function selectRole(role) {
        const confirmBtn = document.getElementById('confirmBtn');
        confirmBtn.classList.remove('hidden');
        confirmBtn.classList.add('visible');
        selectedRole = role; 
    }

    document.getElementById('confirmBtn').addEventListener('click', function() {
        if (selectedRole === 'student') {
            window.location.href = 'student-page.php';
        } else if (selectedRole === 'teacher') {
            window.location.href = 'teacher-page.php'; 
        }
    });
});
