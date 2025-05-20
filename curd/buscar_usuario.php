<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
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
        include('middleware/rutas.php');
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'])) {
            $userId = $_POST['userId'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, Rutas::$urls . Rutas::$consultarUsuario . $userId);
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


                    echo "<p class='mt-3' id='textUserId'>ID: " . htmlspecialchars($usuario['userId'] ?? '')  . "</p>";
                    echo "<p>Nombre: " . htmlspecialchars($usuario['name'] ?? '') . "</p>";
                    echo "<p>Apellido: " . htmlspecialchars($usuario['lastName'] ?? '') . "</p>";
                    echo "<p>Teléfono: " . htmlspecialchars($usuario['phone'] ?? '') . "</p>";
                    echo "<p>Fecha de Registro: " . htmlspecialchars($usuario['created'] ?? '') . "</p>";
                    echo "<p>Correo: " . htmlspecialchars($usuario['email'] ?? '') . "</p>";
                    // echo "<p>Imagen: " . htmlspecialchars($usuario['image'] ?? '') . "</p>";

                    if (!empty($usuario['image'])) {
                        echo "<p>Imagen de perfil:</p>";
                        echo "<img src='" . htmlspecialchars($usuario['image']) . "' alt='Imagen del usuario' style='max-width:200px; height:auto;'/>";
                    } else {
                        echo "<img src='assets\img\perfil.jpg' style='max-width:80px; height:auto;'/>";
                    }
                } else {
                    echo "<div class='alert alert-warning mt-3' role='alert'>Usuario no encontrado.</div>";
                }
            }



            curl_close($ch);
        }
        ?>

        <div class="container">
            <button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
            Eliminar Usuario</button>
        </div>

        <div class="modal" tabindex="-1" id="confirmDeleteModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div id="mensaje" class="container-sm mt-3"></div>
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Está seguro que deseas eliminar este usuario?</p>
                    </div>
                    <div class="modal-footer">
                        <form method="POST">
                            <input id=deleteId type="hidden" name="userId" value="<?php echo htmlspecialchars($usuario['userId']); ?>">
                            <button type="submit" class="btn btn-danger" id=btnDeleteUsr>Confirmar</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteModal = document.getElementById('confirmDeleteModal');
            const btnDeleteUsr = document.getElementById('btnDeleteUsr');


            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Button that triggered the modal
                const input = document.getElementById('deleteId'); // Input field in the modal
                const valueId = input.value; // Get the value of the input field

                // console.log(valueId);

                btnDeleteUsr.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation()

                    const dataUser = {
                        user: {
                            UserId: valueId
                        }
                    }
                    console.info(dataUser);
                    eliminarUsuario(dataUser);
                });
 
            });

        });
    </script>

    <script src="assets/js/del_user.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>

</html>