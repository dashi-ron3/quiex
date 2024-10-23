<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['fileName'])) {
        $fileName = $input['fileName'];
        $filePath = 'uploads-assessments/' . $fileName;

        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                echo 'success';
            } else {
                echo 'error';
            }
        } else {
            echo 'file not found';
        }
    } else {
        echo 'no file specified';
    }
}
?>