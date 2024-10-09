function goBack() {
    window.location.href = 'teacher-page.php';
}

const setTeacherTheme = (theme) => {
    document.documentElement.classList.remove('light', 'dark', 'purple');
    document.documentElement.classList.add(theme);
    localStorage.setItem('teacherTheme', theme);

    const logo = document.getElementById('logo');
    if (theme === 'dark') {
        logo.src = 'assets/Dark_QuiEx-Logo.png';
    } else {
        logo.src = 'assets/QuiEx-Logo.png';
    }
};

const loadTeacherTheme = () => {
    const savedTheme = localStorage.getItem('teacherTheme');
    if (savedTheme) {
        setTeacherTheme(savedTheme);
        document.getElementById('theme-select').value = savedTheme;
    } else {
        setTeacherTheme('light'); // default theme
    }
};

document.addEventListener('DOMContentLoaded', function() {
    loadTeacherTheme(); 

    document.getElementById('theme-select').addEventListener('change', function() {
        setTeacherTheme(this.value);
    });

    document.getElementById('save-theme-btn').addEventListener('click', function() {
        const currentTheme = document.getElementById('theme-select').value;
        setTeacherTheme(currentTheme);
        alert(`Theme "${currentTheme}" has been saved for teacher!`);
    });
});
