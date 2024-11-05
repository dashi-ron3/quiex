<?php
include 'config/connection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    
    $to = "quiexteam@gmail.com";
    $subject = "Feedback from " . $name;
    $headers = "From: " . $email;
    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
    
    if (mail($to, $subject, $body, $headers)) {
        echo "Feedback sent successfully!";
    } else {
        echo "Failed to send feedback.";
    }
}
?>