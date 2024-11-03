<?php
session_start();
include 'config/connection.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch top 3 and the rest of the students from the attempts table
$sql = "
    SELECT u.name, SUM(a.score) AS total_points 
    FROM users u 
    JOIN attempts a ON u.id = a.user_id 
    GROUP BY u.id 
    ORDER BY total_points DESC
";
$stmt = $pdo->query($sql);

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
                    <div class="points"><?= htmlspecialchars($student['total_points']) ?> points</div>
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
                </tr>
            </thead>
            <tbody>
                <?php
                $rank = 4;
                foreach ($other_students as $student) {
                    echo "<tr>";
                    echo "<td>{$rank}</td>";
                    echo "<td>" . htmlspecialchars($student['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($student['total_points']) . " points</td>";
                    echo "</tr>";
                    $rank++;
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
