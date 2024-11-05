<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Login Page">
    <title>QuiEx | Login</title>
    <link rel="stylesheet" href="css/login-page.css">
    <link rel="icon" href="assets/logo-quiex.ico"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script src="javascript/login-page.js"></script>
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
                <h1>LOGIN</h1>
                <form action="login-page.php" method="POST">
                    <label for="username">USERNAME</label>
                    <input type="text" id="username" name="username" required>

                    <label for="password">PASSWORD</label>
                    <input type="password" id="password" name="password" required>

                    <button type="submit">LOG IN</button>
                </form>
                <p>Don't have an account? <a href="classification.php">Sign up here!</a></p>
                <p class="terms">By proceeding, you agree to our <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.</p>
            </div>
        </div>
    </div>
</body>
</html>

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $servername = "localhost";
    $db_username = "root";
    $db_password = "pochita12";
    $dbname = "quiex";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, username, password, user_type FROM users WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);

    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password']; 

    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $fetched_username, $fetched_hashed_password, $user_type);
        $stmt->fetch();

        if (password_verify($password, $fetched_hashed_password)) {
            $_SESSION['username'] = $fetched_username;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_type'] = $user_type;

            // Redirect based on user type
            if ($user_type === 'teacher') {
                header("Location: teacher-page.php");
            } elseif ($user_type === 'student') {
                header("Location: student-page.php");
            }
            exit();
        } else {
            echo "<script>showError('Invalid username or password.');</script>";
        }
    } else {
        echo "<script>showError('Invalid username or password.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>