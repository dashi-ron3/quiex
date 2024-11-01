<?php
$servername = "localhost"; 
$username = "root";   
$password = "weneklek";     
$dbname = "quiez";         

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user has submitted a remove request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $removeId = intval($_POST['remove_id']);
    
    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM Assessments WHERE id = ?");
    $stmt->bind_param("i", $removeId);
    
    if ($stmt->execute()) {
        $successMessage = "Assessment removed successfully.";
    } else {
        $errorMessage = "Error removing assessment: " . $conn->error;
    }

    $stmt->close();
}

// Check if the user has submitted an ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assessment_id'])) {
    $assessmentId = intval($_POST['assessment_id']);
    header("Location: take.php?id=" . $assessmentId);
    exit();
}

$sql = "SELECT id, code, title, description FROM Assessments";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assessment Selector</title>
</head>
<body>
    <h1>Quiez</h1>
    <h3>Enter Assessment ID</h3>
    <form method="POST" action="">
        <input type="number" name="assessment_id" required placeholder="Enter Assessment ID">
        <button type="submit">Go</button>
    </form>

    <div class="assessments">
        <h2>Assessments Data</h2>
    
        <?php if (isset($errorMessage)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php elseif (isset($successMessage)): ?>
        <p style="color:green;"><?php echo htmlspecialchars($successMessage); ?></p>
        <?php endif; ?>
        
        <table border="1">
        <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Title</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php
        // Check if there are results
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['code']}</td>
                        <td>{$row['title']}</td>
                        <td>{$row['description']}</td>
                        <td>
                            <form method='POST' action='' style='display:inline;'>
                                <input type='hidden' name='remove_id' value='{$row['id']}'>
                                <button type='submit' onclick='return confirm(\"Are you sure you want to remove this assessment?\");'>Remove</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No assessments found</td></tr>";
        }

        // Close connection
        $conn->close();
        ?>
    </table>
    </div>
</body>
</html>
