function goBack() {
    window.location.href = 'student-page.php';
}

const setStudentTheme = (theme) => {
    document.documentElement.classList.remove('light', 'dark', 'purple');
    document.documentElement.classList.add(theme);
    localStorage.setItem('studentTheme', theme);

    const logo = document.getElementById('logo');
    if (theme === 'dark') {
        logo.src = 'assets/Dark_QuiEx-Logo.png';
    } else {
        logo.src = 'assets/QuiEx-Logo.png';
    }
};

const loadStudentTheme = () => {
    const savedTheme = localStorage.getItem('studentTheme');
    if (savedTheme) {
        setStudentTheme(savedTheme);
        document.getElementById('theme-select').value = savedTheme;
    } else {
        setStudentTheme('light'); // default theme
    }
};

document.addEventListener('DOMContentLoaded', function() {
    loadStudentTheme();

    document.getElementById('theme-select').addEventListener('change', function() {
        setStudentTheme(this.value);
    });

    document.getElementById('save-theme-btn').addEventListener('click', function() {
        const currentTheme = document.getElementById('theme-select').value;
        setStudentTheme(currentTheme);
        alert(`Theme "${currentTheme}" has been saved for student!`);
    });
});
