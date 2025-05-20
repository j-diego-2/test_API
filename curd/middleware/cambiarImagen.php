<?php
include('rutas.php');
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

// var_dump($input);

if(!isset($input['user'])) {
    echo json_encode(['success' => false, 'message' => 'No se recibió información del usuario']);
    exit;
}

$userData = $input['user'] ?? '';
$userId = isset($userData['UserId']) ? strip_tags($userData['UserId']) : '';
$imagen = isset($userData['ImagePath']) ? strip_tags($userData['ImagePath']) : '';

$dataToSend = json_encode([
    'UserId' => $userId,
    'Image' => $imagen
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
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$responseData = json_decode($response, true);

if ($httpCode >= 200 && $httpCode < 300 && isset($responseData['success']) && $responseData['success']) {
    echo json_encode([
        'success' => true, 
        'message' => 'Se ha actualizado la imagen de perfil correctamente',
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error desde la API externa',
        'apiResponse' => is_string($response) ? substr($response, 0, 500) : 'No se pudo interpretar',
        'statusCode' => $httpCode
    ]);
}