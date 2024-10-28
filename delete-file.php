<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['fileName'])) {
        $fileName = $_POST['fileName'];
        $filePath = 'uploads-assessments/' . $fileName;

        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error deleting the file.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'File not found.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No file specified.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>