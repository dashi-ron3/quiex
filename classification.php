<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Classification Page">
    <title>QuiEx</title>
    <link rel="stylesheet" href="css/classification.css">
    <script src="javascript/classification.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50">
            </div>
        </nav>
    </header>

    <div class="content">
        <div class="container">
            <div class="left-section">
                <h1>
                    CREATE.<br>
                    LEARN.<br>
                    HAVE FUN.
                </h1>
            </div>

            <div class="right-section">
                <img src="assets/student-or-teacher.png" alt="Illustration of a teacher and a student" width="400" height="600">
                <p>ARE YOU A</p>
                <button id="studentBtn">STUDENT</button> &nbsp; or &nbsp; <button id="teacherBtn">TEACHER</button>
                <br><button id="confirmBtn" class="hidden">CONFIRM</button>
            </div>

        </div>
    </div>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['role'])) {
        $role = $_POST['role'];

        if ($role === 'student') {
            header("Location: online-quiz&exam-maker/home-page/student-page.php");
        } elseif ($role === 'teacher') {
            header("Location: online-quiz&exam-maker/home-page/teacher-page.php");
        } else {
            echo "Invalid role selected.";
        }
        exit();
    }
}
?>