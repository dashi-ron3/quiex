<?php
    $DBASE = "localhost";
    $DB_USER = "root";
    $DB_PASS = "admin!!!";
    $DB_NAME = "quiex";

    // Create connection
    $conn = new mysqli($DBASE, $DB_USER, $DB_PASS, $DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to get username and total marks only
    $sql = "SELECT u.username, q.total_marks 
            FROM quizzes q 
            INNER JOIN users u ON q.user_id = u.id";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuiEx - View Grades</title>
    <link rel="stylesheet" href="css/grades.css">
</head>
<body>
    <div class="logo">
        <img src="assets/QuiEx-Logo.png" alt="QuiEx Logo" width="140" height="50">
    </div>
    <div class="title">
        <h1>View Grades</h1>
    </div>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Total Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['total_marks'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>