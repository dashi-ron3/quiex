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

// Fetch all students, ordered by points, highest first
$sql = "SELECT name, profile_pic, points FROM leaderboard ORDER BY points DESC";
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

    <div class="leaderboard-container">
        <!-- Top 3 players -->
        <div class="top-three">
            <?php foreach ($top_three as $index => $student) { ?>
                <div class="player-card player-<?= $index + 1 ?>"> <!-- Add class to identify player position -->
                    <?php if ($index == 0) { ?>
                        <div class="star gold-star"></div>
                    <?php } elseif ($index == 1) { ?>
                        <div class="star silver-star"></div>
                    <?php } else { ?>
                        <div class="star bronze-star"></div>
                    <?php } ?>
                    <img src="<?= $student['profile_pic'] ?>" alt="<?= $student['name'] ?>">
                    <h3><?= $student['name'] ?></h3>
                    <div class="points"><?= $student['points'] ?> points</div>
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
                foreach ($other_students as $student) { ?>
                    <tr>
                        <td><?= $rank ?></td>
                        <td><?= $student['name'] ?></td>
                        <td><?= $student['points'] ?> points</td>
                    </tr>
                <?php $rank++;
                } ?>
            </tbody>
        </table>
    </div>

</body>

</html>