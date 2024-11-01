const setStudentTheme = (theme) => {
    console.log("Setting theme to:", theme);
    
    document.documentElement.classList.remove('light', 'dark', 'purple');
    document.documentElement.classList.add(theme);

    const logo = document.getElementById('logo');
    if (theme === 'dark') {
        logo.src = 'assets/Dark_QuiEx-Logo.png';
    } else {
        logo.src = 'assets/QuiEx-Logo.png';
    }

    fetch('update-theme.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ theme: theme })
    })
    .then(response => response.json())
    .then(data => {
        console.log("Server response:", data);
        if (data.status !== 'success') {
            console.error('Failed to save theme:', data.message);
        } else {
            console.log("Theme saved successfully!");
        }
    })
    .catch(error => console.error('Error saving theme:', error));
};

document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = document.documentElement.dataset.theme;
    console.log("Loaded saved theme:", savedTheme);

    if (savedTheme) {
        setStudentTheme(savedTheme);
        document.getElementById('theme-select').value = savedTheme;
    }

    document.getElementById('theme-select').addEventListener('change', function() {
        setStudentTheme(this.value);
    });
});