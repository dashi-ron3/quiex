<?php
session_start();
include 'config/connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log(print_r($_POST, true)); 
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $name = $_POST['name'] ?? '';
    $age = $_POST['age'] ?? '';
    $gr_level = $_POST['gr_level'] ?? '';
    $password = $_POST['password'] ?? '';

    error_log("Username: $username, Email: $email, Name: $name, Age: $age, Education Level: $gr_level");

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    if (empty($username) || empty($email)|| empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill out all required fields.']);
        exit;
    }

    $sql = "INSERT INTO quiex.users (id, username, email, name, age, gr_level, password)
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                email = VALUES(email),
                name = VALUES(name), 
                age = VALUES(age),
                gr_level = VALUES(gr_level)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("issis", $id, $username, $email, $name, $age, $gr_level, $hashed_password);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['name'] = $name;
                $_SESSION['age'] = $age > 0 ? $age : 'n/a'; 
                $_SESSION['gr_level'] = $gr_level;

                echo json_encode(['status' => 'success', 'message' => 'Basic information updated successfully.']);
            } else {
                echo json_encode(['status' => 'info', 'message' => 'No changes made.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error executing statement: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error preparing statement: ' . $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
