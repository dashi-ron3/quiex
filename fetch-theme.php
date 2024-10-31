<?php
session_start();

$theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light';
echo json_encode(['theme' => $theme]);
?>
