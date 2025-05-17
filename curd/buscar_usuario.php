<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Buscar Usuario</h1>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="userId" class="form-label">ID de Usuario</label>
                <input type="text" class="form-control" id="userId" name="userId" required>
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="listar_usuarios.php" class="btn btn-primary" role="button">Regresar</a>
        </form>
        

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'])) {
            $userId = $_POST['userId'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://localhost:7240/user/data/$userId");
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $respuesta = curl_exec($ch);
            /* echo '<pre>';
                    print_r($respuesta);
                echo '</pre>'; */

            if (curl_errno($ch)) {
                echo "<div class='alert alert-danger' role='alert'>Error: " . curl_error($ch) . "</div>";
            } else {
                $info = json_decode($respuesta, true);

                /* echo "<pre>";
                print_r($info);
                echo "</pre>"; */

                if (isset($info['data']) && is_array($info['data']) && count($info['data']) > 0) {
                    $usuario = $info['data'][0];
                    /* echo "<pre>";
                    print_r($usuario);
                    echo "</pre>"; */


                    echo "<p class='mt-3'>ID: " . htmlspecialchars($usuario['userId'] ?? '')  . "</p>";
                    echo "<p>Nombre: " . htmlspecialchars($usuario['name'] ?? '') . "</p>";
                    echo "<p>Apellido: " . htmlspecialchars($usuario['lastName'] ?? '') . "</p>";
                    echo "<p>Teléfono: " . htmlspecialchars($usuario['phone'] ?? '') . "</p>";
                    echo "<p>Fecha de Registro: " . htmlspecialchars($usuario['created'] ?? '') . "</p>";
                    echo "<p>Correo: " . htmlspecialchars($usuario['email'] ?? '') . "</p>";
                    echo "<p>Imagen: " . htmlspecialchars($usuario['image'] ?? '') . "</p>";

                    /* if (!empty($usuario['image'])) {
                        echo "<p>Imagen de perfil:</p>";
                        echo "<img src='" . htmlspecialchars($usuario['image']) . "' alt='Imagen del usuario' style='max-width:200px; height:auto;'/>";
                    } else {
                        echo "<img src='assets\img\perfil.jpg' style='max-width:80px; height:auto;'/>";
                    } */
                } else {
                    echo "<div class='alert alert-warning mt-3' role='alert'>Usuario no encontrado.</div>";
                }
            }



            curl_close($ch);
        }
        ?>

        <form action="middleware\eliminar_usuario.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
            <input type="hidden" name="userId" value="<?php echo htmlspecialchars($usuario['userId']); ?>">
            <button type="submit" class="btn btn-danger mt-3">Eliminar Usuario</button>
        </form>
    </div>
</body>

</html>