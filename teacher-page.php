<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Main Page Teacher's View">
    <title>QuiEx</title>
    <link rel="stylesheet" href="css/teacher-page.css">
    <script src="javascript/teacher-page.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
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
                    <a href="#create" class="dropbtn">CREATE</a>
                    <div class="dropdown-content">
                        <a href="#">Create Assessment</a>
                        <a href="#">Questions Archive</a>
                        <a href="teacher-assessments.php">Assessments</a>
                    </div>
                </div>
                <a href="teacher-settings.php">SETTINGS</a>
            </div>
        </nav>
    </header>

    <section id="home">
        <div class="slideshow-container">
            <div class="slide active">
                <div class="container">
                    <div class="left-section">
                        <img src="assets/teacher-homepage.png" alt="Illustration of a teacher lecturing two students" class="illustration" width="650" height="450">
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
                        <img src="assets/teacher-homepage.png" alt="Illustration of a teacher teaching" class="illustration" width="600" height="500">
                    </div>
                </div>
            </div>
        </div>

        <div class="dots-container">
            <span class="dot active" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
        </div>
    </section>

    <section id="create">
        <div class="create">
            <h1>CREATE</h1>
            <div class="create-container">
                <div class="create-options">
                    <a href="#">Create Assessment</a>
                    <div class="hover-content">
                        <p>Create an assessment for your students!</p>
                        <button>Enter ></button>
                    </div>
                </div>

                <div class="create-options">
                    <a href="#">Questions Archive</a>
                    <div class="hover-content">
                        <p>Have access on your previous test questions for possible usage on future tests.</p>
                        <button>Enter ></button>
                    </div>
                </div>

                <div class="create-options">
                    <a href="#">Assessments</a>
                    <div class="hover-content">
                        <p>A test storage.</p>
                        <button onclick="location.href='teacher-assessments.php'">Enter ></button>
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