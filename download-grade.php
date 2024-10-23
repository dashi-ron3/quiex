<?php
$DBASE = "localhost";
$DB_USER = "root";
$DB_PASS = "admin!!!";
$DB_NAME = "quiex";

$conn = new mysqli($DBASE, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT u.username, u.email, q.title AS subject_name, q.total_marks, q.marks AS obtained_marks, q.points 
        FROM quizzes q 
        INNER JOIN users u ON q.user_id = u.id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=grades.csv');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Subject', 'Username', 'Email', 'Total Marks', 'Obtained Marks', 'Points']);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
} else {
    echo "No records found.";
}

$conn->close();
?>
