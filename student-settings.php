<?php
session_start();
require 'config/connection.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<html lang="en" data-theme="<?php echo htmlspecialchars($_SESSION['theme'] ?? 'light'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Student Settings">
    <link rel="icon" href="assets/logo-quiex.ico"/>
    <title>Settings | QuiEx</title>
    <link rel="stylesheet" href="css/student-settings.css">
    <script src="javascript/student-appearance.js" defer></script>
    <script src="javascript/student-settings.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>

</head>
<body>
    <header>
        <div class="logo">
            <img id="logo" src="<?php echo htmlspecialchars($_SESSION['theme'] === 'dark' ? 'assets/Dark-QuiEx-Logo.png' : 'assets/QuiEx-Logo.png'); ?>" alt="QuiEx Logo" width="140" height="50">
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
                <li><a href="#profile">PROFILE</a></li>
                <li><a href="#appearance">APPEARANCE</a></li>
                <li><a href="#help">HELP</a></li>
                <li><a href="logout.php">LOG OUT</a></li>
            </ul>
            <div class="illustration">
                <img src="assets/student-settings.png" alt="Illustration of a person sitting with a laptop and books" width="100" height="100"/>
            </div>
        </div>
        <div class="content">
            <!-- User Profile Section -->
            <section id="profile">
                <h2>User Profile</h2>
                <div class="profile-header">
                    <div class="profile-pic">
                        <img src="<?php echo htmlspecialchars($_SESSION['profile_pic'] ?? 'assets/user-icon.svg'); ?>" alt="Profile Picture" width="80" height="80">
                    </div>
                    <div class="username">
                        <h3><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
                    </div>
                </div>
                <div class="profile-upload">
                    <form action="pfp-upload.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="fileToUpload" id="fileToUpload">
                        <button type="submit" id="saveButton" name="submit">Save</button>
                    </form>
                </div>
                <div class="basic-info">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16" onclick="enableEdit()">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>
                        Basic Information
                        <button id="saveButton" style="display:none;" onclick="saveChanges()">Save</button>
                    </h3>
                    <div class="info-item">
                        <label for="username">Username:</label>
                        <input id="username" name="username" type="text" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly/>
                    </div>
                    <div class="info-item">
                        <label for="name">Name:</label>
                        <input id="name" name="name" type="text" value="<?php echo htmlspecialchars($_SESSION['name'] ?? 'n/a'); ?>" readonly/>
                    </div>
                    <div class="info-item">
                        <label for="age">Age:</label>
                        <input id="age" name="age" type="number" value="<?php echo htmlspecialchars($_SESSION['age'] ?? 'n/a'); ?>" readonly/>
                    </div>
                    <div class="info-item">
                        <label for="gr_level">Education Level:</label>
                        <select id="gr_level" name="gr_level" disabled>
                            <option value="jhs" <?php echo (isset($_SESSION['gr_level']) && $_SESSION['gr_level'] == 'jhs') ? 'selected' : ''; ?>>Junior High School</option>
                            <option value="shs" <?php echo (isset($_SESSION['gr_level']) && $_SESSION['gr_level'] == 'shs') ? 'selected' : ''; ?>>Senior High School</option>
                            <option value="college" <?php echo (isset($_SESSION['gr_level']) && $_SESSION['gr_level'] == 'college') ? 'selected' : ''; ?>>Undergraduate</option>
                        </select>
                    </div>
                    
                </div>
            </section>

            <!-- Appearance Section -->
            <section id="appearance">
                <div class="theme-select">
                    <h2>Appearance</h2>
                    <label for="theme-select">Choose a theme:</label>
                    <select name="theme-select" id="theme-select">
                        <option value="light">Light Theme</option>
                        <option value="dark">Dark Theme</option>
                        <option value="purple">Purple Theme</option>
                    </select>
                </div>
            </section>

            <!-- Help Section -->
            <section id="help">
                <div class="content">
                    <h2>Help</h2>
                    <div class="help-links">
                        <h3>Help Resources</h3>
                        <ul>
                            <li>See <a href="quiex-terms-of-use.php">Terms of Use</a></li>
                            <li>See <a href="quiex-terms-of-use.php">Privacy Policy</a></li>
                        </ul>
                    </div>
                    <div class="feedback-form">
                        <h3>Send a feedback?</h3>
                        <form id="feedbackForm">
                            <label for="name">Your Name:</label>
                            <input type="text" id="name" name="name" placeholder="Enter your name" required>
                            <label for="email">Your Email:</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" required>
                            <label for="message">Your Message:</label>
                            <textarea id="message" name="message" placeholder="Enter your message" rows="4" required></textarea>
                            <button type="submit">Send Feedback</button>
                        </form>
                        <p id="feedbackStatus"></p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
