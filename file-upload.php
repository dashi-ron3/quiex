<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileType = $_FILES['file']['type'];
        $uploadFileDir = 'uploads-assessments/';

        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }

        $dest_path = $uploadFileDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            echo json_encode([
                'status' => 'success',
                'fileName' => $fileName,
                'filePath' => $dest_path,
                'fileType' => $fileType
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error moving the file.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No file uploaded or there was an upload error.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>