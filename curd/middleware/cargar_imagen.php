<?php
include 'rutas.php';
header('Content-Type: application/json');

$response = [];

if (!isset($_POST['userId'])) {
    echo json_encode(['success' => false, 'message' => 'No se recibió información del usuario']);
    exit;
}

$userId = $_POST['userId'];
$uploadDir = '../assets/img/';
$finalFileName = $userId . '.jpg'; // Cambia la extensión según el tipo de imagen que esperas
$destPath = $uploadDir . $finalFileName;

if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profileImage']['tmp_name'];
    $fileName = mime_content_type($fileTmpPath);

    if (!in_array($fileName, ['image/jpeg', 'image/png', 'image/gif'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Solo se permiten imágenes JPG.'
        ]);
        exit;
    }

    // Verifica si la carpeta existe y tiene permisos
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    // Mueve el archivo
    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $response = [
            'status' => 'success',
            'message' => 'Imagen subida correctamente.',
            'filePath' => $destPath
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Error al mover el archivo al directorio destino.'
        ];
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'No se recibió un archivo válido.',
        'debug' => $_FILES
    ];
}


// var_dump($_POST['userId']);


if ($response['status'] === 'success' && isset($response['filePath'])) {
    // Llmar a la API para guardar la  ruta
        $userId = $_POST['userId'] ?? '';
        $destPath = str_replace('../', '', $response['filePath']); // Elimina '../' para que la ruta sea relativa al servidor
        $dataToSend = json_encode([
            'UserId' => $userId,
            'Image' => 'http://localhost/curd/' . $destPath
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Rutas::$urls . Rutas::$cambiarImagen);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataToSend);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($dataToSend)
        ]);
        $responseApi = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $responseData = json_decode($responseApi, true);

        if ($httpCode >= 200 && $httpCode < 300 && isset($responseData['success']) && $responseData['success']) {
            $response['apiResponse'] = [
                'success' => true,
                'message' => 'Se ha actualizado la imagen de perfil correctamente',
            ];
        } else {
            $response['apiResponse'] = [
                'success' => false,
                'message' => 'Error desde la API externa',
                'apiResponse' => is_string($responseApi) ? substr($responseApi, 0, 500) : 'No se pudo interpretar',
                'statusCode' => $httpCode
            ];
        }
} else {
    $responseAPI = [
        'success' => false,
        'message' => 'Error al subir la imagen',
    ];
}

echo json_encode($response);
