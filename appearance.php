<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Student Settings">
    <title>QuiEx</title>
    <link rel="stylesheet" href="css/student-settings.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body>
    <header>
        <div class="logo">
            <img id="logo" src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50">
        </div>
    </header>

    <div class="container">
        <div class="sidebar">
            <h1>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16" onclick="goBack()">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                </svg>
                SETTINGS
            </h1>
            <ul>
                <li><a href="student-settings.php">PROFILE</a></li>
                <li><a href="appearance.php">APPEARANCE</a></li>
                <li><a href="#">HELP</a></li>
            </ul>
            <div class="illustration">
                <img src="https://oaidalleapiprodscus.blob.core.windows.net/private/org-RcpoXHkzChYnDbFAyeQ8tamr/user-ehrvabJ3DufsCu8YJ7PqY5gl/img-apZ2yZFjHOhOkGnLpMDBBAFB.png" 
                     alt="Illustration of a person sitting with a laptop and books" width="100" height="100"/>
            </div>
        </div>

        <div class="content">
            <h2>Appearance</h2>
            <label for="theme-select">Choose a theme:</label>
            <select name="theme-select" id="theme-select">
                <option value="light">Light Theme</option>
                <option value="dark">Dark Theme</option>
                <option value="purple">Purple Theme</option>
            </select>
            <button id="save-theme-btn">Save Changes</button>
        </div>
    </div>

    <script src="javascript/appearance.js" defer></script>
</body>
</html>
