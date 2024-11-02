<?php
session_start();
$DBASE = "localhost";
$DB_USER = "root";
$DB_PASS = "aventurine";
$DB_NAME = "quiex";

$conn = new mysqli($DBASE, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['marks_obtained'])) {
    $user_id = $_POST['user_id'];
    $marks_obtained = $_POST['marks_obtained'];

    $total_marks_query = "SELECT total_marks FROM quizzes WHERE user_id = ?";
    $total_marks_stmt = $conn->prepare($total_marks_query);
    $total_marks_stmt->bind_param("i", $user_id);
    $total_marks_stmt->execute();
    $total_marks_stmt->bind_result($total_marks);
    $total_marks_stmt->fetch();
    $total_marks_stmt->close();

    if ($total_marks > 0) { 
        $points = ($marks_obtained / $total_marks) * 100;
    } else {
        $points = 0; 
    }

    $update_sql = "UPDATE quizzes SET marks = ?, points = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_sql);
    
    if ($stmt) {
        $stmt->bind_param("ddi", $marks_obtained, $points, $user_id);
        
        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit(); 
        } else {
            echo "<div style='color: red;'>Error updating marks: " . $stmt->error . "</div>";
        }
    } else {
        echo "<div style='color: red;'>Prepare failed: " . $conn->error . "</div>";
    }
}

$sql = "SELECT u.id, u.username, u.email, q.total_marks, q.title AS subject_name, q.marks AS obtained_marks, q.points 
        FROM quizzes q 
        INNER JOIN users u ON q.user_id = u.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($_SESSION['theme'] ?? 'light'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/logo-quiex.ico"/>
    <title>QuiEx</title>
    <link rel="stylesheet" href="css/grades.css">
    <script src="javascript/student-appearance.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <style>
        .editable-field {
            display: none; 
        }
        .edit-mode .editable-field {
            display: inline-block; 
        }
        .edit-mode .display-field {
            display: none; 
        }
        .pencil-icon {
            cursor: pointer;
            background: none;
            border: none;
            color: #333;
        }
    </style>
    <script>
        function editMarks(rowId) {
            const row = document.getElementById('row-' + rowId);
            row.classList.toggle('edit-mode'); 
        }
    </script>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <img class="main-logo" src="<?php echo htmlspecialchars($_SESSION['theme'] === 'dark' ? 'assets/Dark_QuiEx-Logo.png' : 'assets/QuiEx-Logo.png'); ?>" alt="QuiEx Logo" width="140" height="50">
            </div>
            <div class="nav">
                <a href="teacher-page.php">HOME</a>
                <div class="dropdown">
                    <a href="#create" class="dropbtn">CREATE</a>
                    <div class="dropdown-content">
                        <a href="create-assessment.php">Create Assessment</a>
                        <a href="#">Questions Archive</a>
                        <a href="teacher-assessments.php">Assessments</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="#grade" class="drpbtn">GRADE VIEWING</a>
                    <div class="dropdown-content">
                        <a href="grade-viewing.php">View Grades</a>
                    </div>
                </div>
                <a href="teacher-settings.php">SETTINGS</a>
            </div>
            <div class="menu-icon" onclick="toggleMenu()">☰</div>
        </nav>
    </header>
    
    <div class="title">
        <h1>View Grades</h1> <img src="assets/student-or-teacher.png" alt="Student and Teacher" class="st" width="250px" height="200px">
    </div>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Items</th>
                    <th>Total Marks</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr id='row-" . $row['id'] . "'>";
                        echo "<td>" . htmlspecialchars($row['subject_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";

                        echo "<td>" . htmlspecialchars($row['total_marks']) . "</td>";
                        
                        echo "<td>";
                        echo "<span class='display-field'>" . htmlspecialchars($row['obtained_marks']) . "</span>";
                        echo "<form method='POST' action='' class='editable-field'>";
                        echo "<input type='number' name='marks_obtained' value='" . htmlspecialchars($row['obtained_marks']) . "' required min='0'>";
                        echo "<input type='hidden' name='user_id' value='" . htmlspecialchars($row['id']) . "'>";
                        echo "<button type='submit'>Save</button>";
                        echo "</form>";
                        echo "<button class='pencil-icon' onclick='editMarks(" . htmlspecialchars($row['id']) . ")'><i class='fas fa-pencil-alt'></i></button>"; 
                        echo "</td>";

                        echo "<td>" . htmlspecialchars($row['points']) . "</td>"; 
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <form method="POST" action="download-grade.php" class="download">
            <button type="submit" class="dl">Download</button>
        </form>

    </div>
    <script>
        function toggleMenu() {
            const nav = document.querySelector('.nav');
            nav.style.display = (nav.style.display === 'flex') ? 'none' : 'flex';
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
