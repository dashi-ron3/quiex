<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Classification Page">
    <title>Classification | QuiEx</title>
    <link rel="stylesheet" href="css/classification.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script src="javascript/classification.js" defer></script>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="index.php"><img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50"></a>
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
                
                <form action="register-page.php" method="GET">
                    <button type="button" id="studentBtn" onclick="setUserType('student')">STUDENT</button> 
                    &nbsp; or &nbsp; 
                    <button type="button" id="teacherBtn" onclick="setUserType('teacher')">TEACHER</button>
                    
                    <br><button id="confirmBtn" class="hidden" type="submit">CONFIRM</button>
                    <input type="hidden" name="user_type" id="userTypeInput">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
