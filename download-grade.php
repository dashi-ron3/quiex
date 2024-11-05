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

$sql = "SELECT u.username, u.email, a.quiz_title AS subject_name, a.max_score AS total_marks, a.score AS obtained_marks, 
               (a.score / a.max_score * 100) AS points 
        FROM attempts a
        INNER JOIN users u ON a.user_id = u.id";
$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
}

if ($result->num_rows > 0) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="grades.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Username', 'Email', 'Subject', 'Total Marks', 'Obtained Marks', 'Points (%)']);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['username'],
            $row['email'],
            $row['subject_name'],
            $row['total_marks'],
            $row['obtained_marks'],
            round($row['points'], 2) 
        ]);
    }

    fclose($output);
    exit();
} else {
    echo "No data available to download.";
}

$conn->close();
?>
