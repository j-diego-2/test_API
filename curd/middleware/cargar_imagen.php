<?php
// var_dump($_POST);
header('Content-Type: application/json');



$uploadDir = realpath(__DIR__ . '/../assets/img/') . DIRECTORY_SEPARATOR;
$relPath = '/assets/img/';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profileImage']['tmp_name'];
        $fileName = basename($_FILES['profileImage']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $uniqueFileName = uniqid('img_', true) . '.' . $fileExtension;
            $destination = $uploadDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $destination)) {
                $response['status'] = 'success';
                $response['message'] = 'Imagen subida correctamente.';
                $response['filePath'] = $relPath . $uniqueFileName;
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error al mover el archivo.';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Extensión de archivo no permitida.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error al subir el archivo.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);