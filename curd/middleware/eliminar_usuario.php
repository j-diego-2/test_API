<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'])) {
    $userId = $_POST['userId'];
    $deleteUrl = "https://localhost:7240/user/delete/$userId";

    // echo "URL generada: $deleteUrl<br>";
    // echo "UserId recibido: " . htmlspecialchars($_POST['userId']) . "<br>";


    // Aquí podrías hacer la petición DELETE si deseas
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $deleteUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        //echo "<div class='alert alert-danger'>Error al eliminar usuario: " . curl_error($ch) . "</div>";
        echo json_encode([
            'success' => false, 
            'message' => 'Error al tratar de eliminar el usuario',
            'error' => curl_error($ch)]);
    } else {
        // echo "<div class='alert alert-success'>Respuesta de la API: $response</div>";
        echo json_encode([
            'success' => true, 
            'message' => 'Usuario eliminado correctamente',
            'response' => $response]);
    }

    curl_close($ch);
    } else {
        // echo "<div class='alert alert-warning'>No se proporcionó un userId válido.</div>";
        echo json_encode([
            'success' => false, 
            'message' => 'No se proporcionó un userId válido']);
    }
?>
