<?php
include('rutas.php');

header('Content-Type: application/json');

$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['user']['UserId'])) {
    $userId = $data['user']['UserId'];

    // Llamada a la API externa
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, Rutas::$urls . Rutas::$eliminarUsuario . $userId);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Validar si hubo error en la conexión CURL
    if ($error) {
        echo json_encode([
            'success' => false, 
            'message' => 'Error al tratar de eliminar el usuario',
            'error' => $error
        ]);
        exit;
    }

    // Decodificar respuesta de la API
    $apiResponse = json_decode($response, true);

    if ($httpCode >= 200 && $httpCode < 300 && isset($apiResponse['success']) && $apiResponse['success']) {
        // Si la API eliminó el usuario con éxito, entonces eliminamos la imagen
        $imagePath = __DIR__ . '/../assets/img/' . $userId . '.jpg';  // Asegúrate que esta ruta coincida con la del script de subida
        $imageDeleted = false;
        $imageMessage = '';

        if (file_exists($imagePath)) {
            if (unlink($imagePath)) {
                $imageDeleted = true;
                $imageMessage = 'Imagen eliminada correctamente.';
            } else {
                $imageMessage = 'Error al eliminar la imagen.';
            }
        } else {
            $imageMessage = 'La imagen no existe.';
        }

        echo json_encode([
            'success' => true,
            'message' => 'Usuario eliminado correctamente.',
            'imageDeleted' => $imageDeleted,
            'imageMessage' => $imageMessage,
            'apiResponse' => $apiResponse
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'La API no confirmó la eliminación del usuario.',
            'apiResponse' => is_string($response) ? substr($response, 0, 500) : 'No se pudo interpretar',
            'statusCode' => $httpCode
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No se proporcionó un userId válido'
    ]);
}
