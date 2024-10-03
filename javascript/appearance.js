function goBack() {
    window.location.href = 'student-page.php';
}

const setTheme = (theme) => {
    document.documentElement.classList.remove('light', 'dark', 'purple');
    document.documentElement.classList.add(theme);
    localStorage.setItem('selectedTheme', theme);

    const logo = document.getElementById('logo');
    if (theme === 'dark') {
        logo.src = 'assets/Dark_QuiEx-Logo.png';
    } else {
        logo.src = 'assets/QuiEx-Logo.png';
    }
};

const loadTheme = () => {
    const savedTheme = localStorage.getItem('selectedTheme');
    if (savedTheme) {
        setTheme(savedTheme);
        document.getElementById('theme-select').value = savedTheme;
    } else {
        setTheme('light');
    }
};

document.addEventListener('DOMContentLoaded', function() {
    loadTheme(); 

    document.getElementById('theme-select').addEventListener('change', function() {
        setTheme(this.value);
    });

    document.getElementById('save-theme-btn').addEventListener('click', function() {
        const currentTheme = document.getElementById('theme-select').value;
        setTheme(currentTheme);
        alert(`Theme "${currentTheme}" has been saved!`);
    });
});
