<?php
header('Content-Type: application/json');

include('rutas.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Leer JSON enviado por fetch()
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);


// Verificar estructura
if (!isset($input) && !is_array($input)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}


$name = $input['name'] ?? '';
$lastName = $input['lastName'] ?? '';
$phone = $input['phone'] ?? '';
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';


// Validaciones
if (empty($name) || empty($lastName) || empty($phone) || empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido']);
    exit;
}

if (!preg_match('/^\d{7,15}$/', $phone)) {
    echo json_encode(['success' => false, 'message' => 'Número de teléfono no válido']);
    exit;
}

if (strlen($password) < 8) {
    echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres']);
    exit;
}

// Preparar datos para enviar a la API externa
$dataToSend = json_encode([
    'Name' => $name,
    'LastName' => $lastName,
    'Phone' => $phone,
    'Email' => $email,
    'Password' => $password
]);


// Enviar datos a la API externa
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, Rutas::$urls . Rutas::$agregarUsuario);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $dataToSend);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($dataToSend)
]);


$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

$responseData = json_decode($response, true);


// Respuesta limpia para el frontend
if ($httpCode >= 200 && $httpCode < 300 && isset($responseData['success']) && $responseData['success']) {
    echo json_encode([
        'success' => true, 
        'message' => 'Usuario registrado'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error desde la API externa',
        'apiResponse' => is_string($response) ? substr($response, 0, 500) : 'No se pudo interpretar',
        'statusCode' => $httpCode
    ]);
}