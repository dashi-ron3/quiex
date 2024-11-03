<?php
$conn = mysqli_connect("localhost", "root", "15a5m249ph", "quiex");
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['assessment_title'])) {
    $assessmentTitle = mysqli_real_escape_string($conn, $_POST['assessment_title']);

    // Updated query to join quizzes, assessments, users, and user_answers tables
    $query = "SELECT 
                users.name AS studentName,
                attempts.score AS score,
                attempts.max_score AS totalPoints,
                GROUP_CONCAT(options.id) AS incorrectQuestions
              FROM attempts
              JOIN quizzes ON quizzes.title = attempts.title
              JOIN users ON users.id = attempts.user_id
              LEFT JOIN answers ON answers.quiz_id = attempts.id
              LEFT JOIN options ON options.id = answers.id AND answers.is_correct = 0
              WHERE quizzes.title = '$assessmentTitle'
              GROUP BY users.name, attempts.score, attempts.max_score";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<table class='score-table'>";
        echo "<tr>
                <th>Student Name</th>
                <th>Score</th>
                <th>Total Points</th>
                <th>Percentage</th>
                <th>Incorrect Questions</th>
              </tr>";

        while ($row = $result->fetch_assoc()) {
            $studentName = htmlspecialchars($row['studentName']);
            $score = (int)$row['score'];
            $totalPoints = (int)$row['totalPoints'];
            $percentage = ($totalPoints > 0) ? round(($score / $totalPoints) * 100, 2) : 0;
            $incorrectQuestions = htmlspecialchars($row['incorrectQuestions']);

            echo "<tr>
                    <td>{$studentName}</td>
                    <td>{$score}</td>
                    <td>{$totalPoints}</td>
                    <td>{$percentage}%</td>
                    <td>{$incorrectQuestions}</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No scores found for this assessment.</p>";
    }

    $conn->close();
} else {
    echo "<p>Assessment title not specified.</p>";
}
