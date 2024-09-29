<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Main Page Student's View">
    <title>QuiEx</title>
    <link rel="stylesheet" href="css/student-page.css">
    <script src="javascript/student-page.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50">
            </div>
            <div class="nav">
                <a href="#home">HOME</a>

                <div class="dropdown">
                    <a href="#assessment" class="dropbtn">ASSESSMENT</a>
                    <div class="dropdown-content">
                        <a href="#">Practice Assessment</a>
                        <a href="#">Take Assessment</a>
                        <a href="#">Rapid Quiz</a>
                    </div>
                </div>

                <a href="student-settings.php">SETTINGS</a>
            </div>
        </nav>
    </header>

    <section id="home">
        <div class="slideshow-container">
            <div class="slide active">
                <div class="container">
                    <div class="left-section">
                        <img src="assets/student-homepage.png" alt="Illustration of three students studying" class="illustration" width="650" height="450">
                    </div>
                    <div class="right-section">
                        <div class="text-content">
                            <h1>QuiEx</h1>
                            <p>QuiEx is an Online Quiz & Exam Maker made for ... intro basta</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="slide">
                <div class="container">
                    <div class="left-section">
                        <img src="assets/student-homepage.png" alt="Illustration of students in a classroom" class="illustration" width="650" height="450">
                    </div>
                    <div class="right-section">
                        <div class="text-content">
                            <h1>Features</h1>
                            <p>Our platform offers a wide range of features to enhance your learning experience...</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="slide">
                <div class="container">
                    <div class="left-section">
                        <img src="assets/student-homepage.png" alt="Illustration of student taking an online quiz" class="illustration" width="650" height="450">
                    </div>
                    <div class="right-section">
                        <div class="text-content">
                            <h1>Get Started</h1>
                            <p>Join us today and revolutionize the way you create and take quizzes and exams...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dots-container">
            <span class="dot active" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
        </div>

    </section>


    <section id="assessment">
        <div class="assessment">
            <h1>Assessment</h1>
            <p></p>
            <a href=""></a>
        </div>
    </section>

</body>

<footer>

    &copy; Copyright 2024 QuiEx
</footer>

</html>
