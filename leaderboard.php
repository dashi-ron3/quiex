<?php
$servername = "localhost";
$username = "root";
$password = "15a5m249ph";
$dbname = "quiex";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch names from users and scores from attempts
$sql = "
    SELECT u.name, SUM(a.score) AS total_points 
    FROM users u 
    JOIN attempts a ON u.id = a.user_id 
    GROUP BY u.id 
    ORDER BY total_points DESC
";
$result = $conn->query($sql);

// Create arrays for top 3 and rest of the students
$top_three = [];
$other_students = [];

if ($result->num_rows > 0) {
    // Fetch top 3 students
    for ($i = 0; $i < 3 && $row = $result->fetch_assoc(); $i++) {
        $top_three[] = $row;
    }
    // Fetch the rest of the students
    while ($row = $result->fetch_assoc()) {
        $other_students[] = $row;
    }
}

// Store the fetched data into the leaderboard table
foreach ($top_three as $student) {
    $name = $conn->real_escape_string($student['name']);
    $points = $student['total_points'];
    
    // Check if the entry already exists
    $checkSql = "SELECT * FROM leaderboard WHERE name = '$name'";
    $checkResult = $conn->query($checkSql);
    
    if ($checkResult->num_rows == 0) {
        // Insert into leaderboard
        $insertSql = "INSERT INTO leaderboard (name, points) VALUES ('$name', $points)";
        $conn->query($insertSql);
    }
}

// Close the connection
$conn->close();
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
                        <img src="assets/gold_profile.jpg" alt="<?= $student['name'] ?>">
                    <?php } elseif ($index == 1) { ?>
                        <div class="star silver-star"></div>
                        <img src="assets/silver_profile.jpg" alt="<?= $student['name'] ?>">
                    <?php } else { ?>
                        <div class="star bronze-star"></div>
                        <img src="assets/bronze_profile.jpg" alt="<?= $student['name'] ?>">
                    <?php } ?>
                    <h3><?= $student['name'] ?></h3>
                    <div class="points"><?= $student['total_points'] ?> points</div>
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