<?php
session_start();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_pic = isset($_SESSION['profile_pic']) ? $_SESSION['profile_pic'] : 'assets/user-icon.svg';
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QuiEx Student Settings">
    <title>QuiEx</title>
    <link rel="stylesheet" href="css/student-settings.css">
    <script src="javascript/student-settings.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

<body>
    <header>
        <div class="logo">
            <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50">
        </div>
    </header>

    <div class="container">
        <div class="sidebar"></div>
        <h1>
            <svg
                xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16" onclick="goBack()">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
            </svg>
            SETTINGS
        </h1>

        <ul>
            <li><a href="#">PROFILE</a></li>
            <li><a href="appearance.php">APPEARANCE</a></li>
            <li><a href="#">HELP</a></li>
        </ul>

        <div class="illustration">
            <img src="https://oaidalleapiprodscus.blob.core.windows.net/private/org-RcpoXHkzChYnDbFAyeQ8tamr/user-ehrvabJ3DufsCu8YJ7PqY5gl/img-apZ2yZFjHOhOkGnLpMDBBAFB.png"
                alt="Illustration of a person sitting with a laptop and books" width="100" height="100" />
        </div>
    </div>

    <div class="content">
        <h2>USER PROFILE</h2>
        <div class="profile-header">
            <div class="profile-pic">
                <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture" width="80" height="80">
            </div>
            <div class="username">
                <h3><?php echo htmlspecialchars($username); ?></h3>
                <p>n/a</p>
            </div>
        </div>

        <div class="profile-upload">
            <form action="pfp-upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="fileToUpload" id="fileToUpload">
                <button type="submit" id="saveButton" name="submit" style="display:none" ;>Save</button>
            </form>
        </div>

        <div class="basic-info">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg>
                Basic Information
            </h3>

            <div class="info-item">
                <label for="username">Username:</label>
                <input id="username" type="text" value="LOREM IPSUM" readonly />
            </div>
            <div class="info-item">
                <label for="name">Name:</label>
                <input id="name" type="text" value="LOREM IPSUM" readonly />
            </div>
            <div class="info-item">
                <label for="age">Age:</label>
                <input id="age" type="text" value="LOREM IPSUM" readonly />
            </div>
        </div>
    </div>
    </div>

    <script>
        document.getElementById('fileToUpload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById('saveButton').style.display = 'inline-block';
            }
            reader.readAsDataURL(file);
        });

        document.getElementById('saveButton').addEventListener('click', function() {
            // You can hide the save button after "saving"
            document.getElementById('saveButton').style.display = 'none';
        });
    </script>
</body>

</html>