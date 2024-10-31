<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Register Page">
    <title>Register | QuiEx</title>
    <link rel="stylesheet" href="css/register-page.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
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
                <h1>REGISTER</h1>
                <form action="register-page.php" method="POST">
                    <!-- Hidden field for user type -->
                    <input type="hidden" name="user_type" value="<?php echo htmlspecialchars($_GET['user_type'] ?? ''); ?>">

                    <label for="username">USERNAME</label>
                    <input type="text" id="username" name="username" required>

                    <label for="email">EMAIL</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">PASSWORD</label>
                    <input type="password" id="password" name="password" required>

                    <button type="submit">REGISTER</button>
                </form>
                <p class="login-link">
                    Already have an account? <a href="login-page.php">Log in here!</a>
                    <p class="terms">By proceeding, you agree to our <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.</p>
                </p>
            </div>
        </div>
    </div>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $servername = "localhost";
    $db_username = "root";
    $db_password = "pochita12";
    $dbname = "quiex";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $username, $email, $hashed_password, $user_type);
    $username = htmlspecialchars($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];

    if ($stmt->execute()) {
        header("Location: login-page.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
