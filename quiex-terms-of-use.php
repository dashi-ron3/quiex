<?php
session_start();
require 'config/connection.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($_SESSION['theme'] ?? 'light'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Terms of Use">
    <link rel="icon" href="assets/logo-quiex.ico"/>
    <title>Terms of Use | QuiEx</title>
    <link rel="stylesheet" href="css/terms-of-use.css">
    <script src="javascript/student-appearance.js" defer></script>
</head>
<body>

    <header>
    <img id="logo" src="<?php echo htmlspecialchars($_SESSION['theme'] === 'dark' ? 'assets/Dark-QuiEx-Logo.png' : 'assets/QuiEx-Logo.png'); ?>" alt="QuiEx Logo" width="140" height="50">
    </header>

    <div class="container">
        <h2>Terms of Use</h2>
        <p>Welcome to QuiEx, a platform designed to provide students with a secure and interactive environment for taking assessments, quizzes, and exams. By accessing and using this website, you agree to comply with and be bound by the following terms and conditions.</p>

        <h3>1. Acceptance of Terms</h3>
        <p>By using the QuiEx platform, you agree to these Terms of Use, our Privacy Policy, and any other terms and conditions that may be implemented on the website. If you do not agree to these terms, please refrain from using the site.</p>

        <h3>2. Eligibility</h3>
        <p>The platform is intended for students who are currently enrolled in educational institutions or registered users with proper credentials provided by their institution. You must be at least 13 years of age to use this platform.</p>

        <h3>3. User Accounts</h3>
        <p>To access certain features of QuiEx, including taking assessments, you will be required to create an account. You are responsible for maintaining the confidentiality of your account login credentials.</p>

        <h3>4. User Conduct</h3>
        <ul>
            <li>Use the website only for its intended educational purpose.</li>
            <li>Do not engage in cheating or unauthorized collaboration.</li>
            <li>Do not share your account details with others.</li>
        </ul>

        <h3>5. Intellectual Property</h3>
        <p>All content on the platform, including text, images, quizzes, assessments, and software, is the property of QuiEx and its licensors.</p>

        <h3>6. Assessments and Grading</h3>
        <p>Your participation in assessments is subject to the rules provided by your educational institution. QuiEx is not responsible for grading or evaluation.</p>

        <h3>7. Privacy and Data Security</h3>
        <p>QuiEx values your privacy. By using the platform, you agree to our <a href="quiex-terms-of-use.php">Privacy Policy</a>, which outlines how we collect, use, and protect your personal information.</p>

        <h3>8. Termination</h3>
        <p>QuiEx may terminate or suspend your access to the platform without notice if you violate any of these Terms of Use.</p>

        <p><strong>Contact Us:</strong> If you have any questions or concerns about these Terms of Use, please contact us at quiexteam@gmail.com.</p>
    </div>

    <footer>
        <p>&copy; 2024 QuiEx. All rights reserved.</p>
    </footer>

</body>
</html>