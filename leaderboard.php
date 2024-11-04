<?php
session_start();
include 'config/connection.php';

$quizId = isset($_GET['quiz_id']) ? filter_var($_GET['quiz_id'], FILTER_VALIDATE_INT) : null;

if (!$quizId) {
    die("Invalid quiz ID.");
}

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch top 3 students along with quiz_id and max_score from attempts, quizzes, and questions tables
$sql = "
    SELECT u.id AS user_id, u.name, SUM(a.score) AS points, q.id AS quiz_id, max_scores.max_score 
    FROM users u 
    JOIN attempts a ON u.id = a.user_id 
    JOIN quizzes q ON a.quiz_id = q.id 
    JOIN (
        SELECT quiz_id, SUM(points) AS max_score 
        FROM questions 
        GROUP BY quiz_id
    ) AS max_scores ON q.id = max_scores.quiz_id 
    WHERE a.quiz_id = :quiz_id
    GROUP BY u.id, q.id 
    ORDER BY points DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':quiz_id' => $quizId]);

$top_three = [];
$other_students = [];

$rank = 1;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($rank <= 3) {
        $top_three[] = $row;
    } else {
        $other_students[] = $row;
    }
    $rank++;
}

// Insert top students into the leaderboard table
foreach ($top_three as $student) {
    $insert_sql = "INSERT INTO leaderboard (user_id, quiz_id, name, points, max_score) VALUES (:user_id, :quiz_id, :name, :points, :max_score)";
    $insert_stmt = $pdo->prepare($insert_sql);
    $insert_stmt->execute([
        ':user_id' => $student['user_id'],
        ':quiz_id' => $student['quiz_id'],
        ':name' => $student['name'],
        ':points' => $student['points'],
        ':max_score' => $student['max_score'],
    ]);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="css/leaderboard.css">
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
                    <img src="assets/leaderboard.png" alt="page title">
                </div>
            </div>
        </nav>
        <div class="mobile-page-name">
            <img src="assets/leaderboard.png" alt="page title">
        </div>
    </header>

    <div class="leaderboard-container">
        <!-- Top 3 players -->
        <div class="top-three">
            <?php foreach ($top_three as $index => $student) { ?>
                <div class="player-card player-<?= $index + 1 ?>"> <!-- Add class to identify player position -->
                    <?php if ($index == 0) { ?>
                        <div class="star gold-star"></div>
                        <img src="assets/gold_profile.jpg" alt="<?= htmlspecialchars($student['name']) ?>">
                    <?php } elseif ($index == 1) { ?>
                        <div class="star silver-star"></div>
                        <img src="assets/silver_profile.jpg" alt="<?= htmlspecialchars($student['name']) ?>">
                    <?php } else { ?>
                        <div class="star bronze-star"></div>
                        <img src="assets/bronze_profile.jpg" alt="<?= htmlspecialchars($student['name']) ?>">
                    <?php } ?>
                    <h3><?= htmlspecialchars($student['name']) ?></h3>
                    <div class="points"><?= htmlspecialchars($student['points']) ?> points</div>
                    <div class="max-score">Max Score: <?= htmlspecialchars($student['max_score']) ?></div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- 4th to nth players -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Player</th>
                    <th>Points</th>
                    <th>Max Score</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rank = 4;
                foreach ($other_students as $student) {
                    echo "<tr>";
                    echo "<td>{$rank}</td>";
                    echo "<td>" . htmlspecialchars($student['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($student['points']) . " points</td>";
                    echo "<td>" . htmlspecialchars($student['max_score']) . "</td>";
                    echo "</tr>";
                    $rank++;
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>