<?php
include('rutas.php');
// Leer JSON enviado por fetch()
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);


// Verificamos que el objeto "user" exista
if (!isset($input['user'])) {
    echo json_encode(['success' => false, 'message' => 'No se recibió información del usuario']);
    exit;
}

$userData = $input['user'] ?? '';

$userId = isset($userData['UserId']) ? strip_tags($userData['UserId']) : '';
$name = isset($userData['Name']) ? strip_tags($userData['Name']) : '';
$lastName = isset($userData['LastName']) ? strip_tags($userData['LastName']) : '';
$phone = isset($userData['Phone']) ? strip_tags($userData['Phone']) : '';
$email = isset($userData['Email']) ? strip_tags($userData['Email']) : '';


// Validaciones
if (empty($userId)) {
    echo json_encode(['success' => false, 'message' => 'ID de usuario es obligatorio']);
    exit;
}

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido']);
    exit;
}

if (!empty($phone) && !preg_match('/^\d{7,15}$/', $phone)) {
    echo json_encode(['success' => false, 'message' => 'Número de teléfono no válido']);
    exit;
}

// Construimos los datos mínimos requeridos por la API
$data = [
    "userId" => (int)$userId
];

if (!empty($name)) $data["name"] = $name;
if (!empty($lastName)) $data["lastName"] = $lastName;
if (!empty($phone)) $data["phone"] = $phone;
if (!empty($email)) $data["email"] = $email;


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, Rutas::$urls . Rutas::$editarUsuario);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Para evitar problemas con HTTPS local
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Solo para desarrollo
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Mostrar resultado
if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode(['success' => true, 
                    'message' => 'Datos actualizados correctamente',
                    'data' => $data]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error desde la API externa',
        'apiResponse' => $response,
        'statusCode' => $httpCode
    ]);
}