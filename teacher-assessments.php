<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Assessments</title>
    <link rel="stylesheet" href="css/assessment-style.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50">
            </div>
        </nav>
    </header>

    <div class="page-name">
        <img src="assets/assessment.png" alt="page title">
    </div>

    <div class="container">

        <div class="sidebar">
            <div class="subject">
                <img src="assets/sub-folder.png" alt="biology folder">
                <h3>BIOLOGY</h3>
            </div>
            <div class="subject">
                <img src="assets/sub-folder.png" alt="biology folder">
                <h3>CHEMISTRY</h3>
            </div>
            <div class="subject">
                <img src="assets/sub-folder.png" alt="biology folder">
                <h3>CALCULUS</h3>
            </div>
        </div>

        <div class="content">
            <h1>BIOLOGY</h1>

            <?php
            $conn = mysqli_connect("localhost", "root", "15a5m249ph", "testing");
            if (mysqli_connect_errno()) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "SELECT title, status, lastUsed, descrip FROM assessments";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                    <div class="assessment">
                        <div class="last-used">LAST USED ON: <?php echo htmlspecialchars($row['lastUsed']); ?></div>
                        <div class="header">
                            <div class="title-status">
                                <div class="title"><strong>Assessment Title:</strong> <?php echo htmlspecialchars($row['title']); ?></div>
                                <div class="status"><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></div>
                            </div>
                            <a href="#" class="edit"><strong>Edit</strong></a>
                        </div>
                        <p class="details"><?php echo htmlspecialchars($row['descrip']); ?></p>
                        <div class="share">
                            <label for="share-<?php echo $row['title']; ?>">SHARE TO PUBLIC: </label>
                            <input type="checkbox" id="share-<?php echo $row['title']; ?>">
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>No assessments found.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>

</html>