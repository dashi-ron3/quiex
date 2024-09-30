<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Main Page Student's View">
    <title>QuiEx</title>
    <link rel="stylesheet" href="css/student-page.css">
    <script src="javascript/student-page.js" defer></script>
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
                            <h1>About QuiEx</h1>
                            <p>QuiEx is an Online Quiz & Exam Maker made for ... intro basta</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="slide">
                <div class="container">
                    <div class="left-section">
                    <div class="text-content">
                            <h1>QuiEx Features</h1>
                            <p>Our platform offers a wide range of features to enhance your learning experience...</p>
                        </div>
                    </div>
                    <div class="right-section">
                        <img src="assets/student-homepage1.png" alt="Illustration of a student studying with books" class="illustration" width="600" height="500">     
                    </div>
                </div>
            </div>
        </div>

        <div class="dots-container">
            <span class="dot active" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
        </div>
    </section>

    <section id="assessment">
    <div class="assessment">
        <h1>ASSESSMENT</h1>
        <div class="assessment-container">
            <div class="assessment-options">
                <a href="#">Practice Assessment</a>
                <div class="hover-content">
                    <p>Practice your knowledge with public assessments to ready yourself on future tests!</p>
                    <button>Enter ></button>
                </div>
            </div>

            <div class="assessment-options">
                <a href="#">Take Assessment</a>
                <div class="hover-content">
                    <p>Join a private class assessment!</p>
                    <button>Enter ></button>
                </div>
            </div>

            <div class="assessment-options">
                <a href="#">Rapid Quiz</a>
                <div class="hover-content">
                    <p>Fun and thrill in studying? Test your comprehension speed with this!</p>
                    <button>Enter ></button>
                </div>
            </div>
        </div>
    </div>
</section>

    <footer>
        &copy; Copyright 2024 QuiEx
    </footer>
</body>
</html>
