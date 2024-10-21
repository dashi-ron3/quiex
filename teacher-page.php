<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Main Page Teacher's View">
    <link rel="icon" href="assets/logo-quiex.ico"/>
    <title>QuiEx</title>
    <link rel="stylesheet" href="css/teacher-page.css">
    <link rel="stylesheet" href="css/loading-screen.css">
    <script src="javascript/teacher-page.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body>
    
    <div id="loading-screen">
        <img src="assets/QuiEx-Logo.png" alt="Logo" id="logo" width="100" height="200">
    </div>

    <div id="main-content" style="display: none;">

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
                            <a href="create-assessment.php">Create Assessment</a>
                            <a href="#">Questions Archive</a>
                            <a href="#">Assessments</a>
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
                                <p>QuiEx is an online quiz and exam maker designed to streamline the process of creating, managing, and administering quizzes and exams.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="slide">
                    <div class="container">
                        <div class="left-section">
                        <div class="text-content">
                                <h1>QuiEx Features</h1>
                                <p>Create, publish, and many more! Our platform offers a wide range of features for efficiency on your part!</p>
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
                            <button onclick="window.location.href='create-assessment.php'">Enter</button>
                        </div>
                    </div>

                    <div class="create-options">
                        <a href="#">Questions Archive</a>
                        <div class="hover-content">
                            <p>Have access on your previous test questions for possible usage on future tests.</p>
                            <button>Enter</button>
                        </div>
                    </div>

                    <div class="create-options">
                        <a href="#">Assessments</a>
                        <div class="hover-content">
                            <p>A test storage.</p>
                            <button>Enter</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer>
            <div class="footer-content">
                <div class="team-info">
                    <p>QuiEx Team:</p>
                    <p>Gonzales, Josephe Rianne</p>
                    <p>Belen, Hannah Veronica</p>
                    <p>Carcer, Ronwell Marc</p>
                    <p>Dalosa, Karsheena Maye</p>
                    <p>Miralles, Ronwela Clarisse</p>
                </div>
                <div class="contact-info">
                    Contact Us:
                    <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                    </svg>
                    quiexteam@gmail.com<p>
                    <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
                    </svg>    
                    Brgy. Milagrosa, Calamba, Laguna</p>
                    <p class="copyright">&copy; Copyright 2024 QuiEx</p>
                </div>
            </div>
        </footer>

    </div>
    <script src="javascript/loading-screen.js"></script>
</body>
</html>
