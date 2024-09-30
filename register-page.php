<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Register Page">
    <title>QuiEx</title>
    <link rel="stylesheet" href="css/register-page.css">
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
                <h1>REGISTER</h1>
                <form action="register.php" method="POST">
                    <label for="username">USERNAME</label>
                    <input type="text" id="username" name="username" required>
                    <label for="email">EMAIL</label>
                    <input type="email" id="email" name="email" required>
                    <label for="password">PASSWORD</label>
                    <input type="password" id="password" name="password" required>
                    <button type="submit">REGISTER</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
