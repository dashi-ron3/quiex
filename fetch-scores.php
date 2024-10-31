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
                quizzes.points AS score,
                quizzes.total_marks AS totalPoints,
                GROUP_CONCAT(choices.id) AS incorrectQuestions
              FROM quizzes
              JOIN assessments ON assessments.title = quizzes.title
              JOIN users ON users.id = quizzes.user_id
              LEFT JOIN user_answers ON user_answers.quiz_id = quizzes.id
              LEFT JOIN choices ON choices.id = user_answers.answer_id AND user_answers.is_correct = 0
              WHERE assessments.title = '$assessmentTitle'
              GROUP BY users.name, quizzes.points, quizzes.total_marks";

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
