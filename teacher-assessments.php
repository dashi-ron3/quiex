<?php
$conn = mysqli_connect("localhost", "root", "15a5m249ph", "quiex");
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['assessment_title'])) {
    $assessmentTitle = mysqli_real_escape_string($conn, $_POST['assessment_title']);

    // Check if the checkbox was checked or not
    $isShared = isset($_POST['share']) ? 1 : 0;  // 1 if checked, 0 if unchecked

    // Update the 'shared' status based on the checkbox state.
    $updateQuery = "UPDATE uploadedAss SET shared = $isShared WHERE title = '$assessmentTitle'";

    if ($conn->query($updateQuery) !== TRUE) {
        echo "Error updating record: " . $conn->error;
    }
}

$subjectsQuery = "SELECT DISTINCT subject FROM uploadedAss";
$subjectsResult = $conn->query($subjectsQuery);

$subject = isset($_GET['subject']) ? mysqli_real_escape_string($conn, $_GET['subject']) : '';

$sql = "SELECT title, status, lastUsed, descrip, shared FROM uploadedAss WHERE subject = '$subject'";
$result = $conn->query($sql);
?>

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
            <div class="logo-page-name-container">
                <div class="logo">
                    <a href="student-page.php">
                        <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50">
                    </a>
                </div>
                <div class="page-name">
                    <img src="assets/assessment.png" alt="page title">
                </div>
            </div>
        </nav>
    </header>

    <div class="container">

        <div class="sidebar">
            <?php
            if ($subjectsResult->num_rows > 0) {
                while ($row = $subjectsResult->fetch_assoc()) {
                    $subjectName = htmlspecialchars($row['subject']);
            ?>
                    <div class="subject">
                        <form method="GET" action="">
                            <input type="hidden" name="subject" value="<?php echo $subjectName; ?>">
                            <button type="submit">
                                <img src="assets/sub-folder.png" alt="<?php echo $subjectName; ?> folder">
                                <h3><?php echo strtoupper($subjectName); ?></h3>
                            </button>
                        </form>
                    </div>
            <?php
                }
            } else {
                echo "<p>No subjects found.</p>";
            }
            ?>
        </div>

        <div class="content">
            <h1><?php echo htmlspecialchars(strtoupper($subject)); ?></h1>

            <?php
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
                            <form method="POST" action="">
                                <input type="hidden" name="assessment_title" value="<?php echo htmlspecialchars($row['title']); ?>">
                                <label for="share-<?php echo htmlspecialchars($row['title']); ?>">SHARE TO PUBLIC: </label>
                                <input
                                    type="checkbox"
                                    id="share-<?php echo htmlspecialchars($row['title']); ?>"
                                    name="share"
                                    <?php echo $row['shared'] ? 'checked' : ''; ?>
                                    onchange="this.form.submit()">
                            </form>
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