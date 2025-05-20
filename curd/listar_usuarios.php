<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <title>Usuarios</title>
</head>

<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Usuarios registrados</h1>

        <a href="index.html" class="btn btn-danger mb-3" role="button">Regresar</a>
        <a href="crear_usuario.php" class="btn btn-primary mb-3" role="button">Agregar nuevo usuario</a>
        <a href="buscar_usuario.php" class="btn btn-primary mb-3" role="button">Buscar usuario</a>

        <div class="container">
            <form id="formExcel" action="middleware/cargar_excel.php" method="POST" enctype="multipart/form-data">
                <label for="excelFile" class="form-label">Seleccionar archivo</label>
                <input class="form-control" type="file" name="excelFile" id="excelFile" required>
                <button type="submit" class="btn btn-secondary mt-1">Cargar excel</button>
            </form>
        </div>



        <?php
        include 'middleware/rutas.php';
        $url = Rutas::$urls . Rutas::$consultarUsuario;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


        $respuesta = curl_exec($ch);

        if (curl_errno($ch)) echo curl_error($ch);
        else $info = json_decode($respuesta, true);


        if (isset($info['data']) && is_array($info['data'])) {
            echo "<br>";
            echo
            "
                <table class='table table-striped-columns table-bordered'>
                <thead>
                <tr>
                    <th scope='col'>Id</th>
                    <th scope='col'>Imagen de perfil</th>
                    <th scope='col'>Nombre(s)</th>
                    <th scope='col'>Apellido</th>
                    <th scope='col'>Telefono</th>
                    <th scope='col'>Fecha de registro</th>
                    <th scope='col'>Correo</th>
                    <th scope='col'>Acciones</th>
                <tr>
                </thead>
            ";

            foreach ($info['data'] as $post) {
                $fechaOriginal = $post['created'];
                $fecha = new DateTime($fechaOriginal);

                $darFormato = new IntlDateFormatter(
                    'es_MX',
                    IntlDateFormatter::LONG,
                    IntlDateFormatter::SHORT,
                    'America/Mexico_City',
                    IntlDateFormatter::GREGORIAN,
                    'dd/MMMM/yyyy - HH:mm'
                );

                $fechaFormateada = $darFormato->format($fecha);


                echo "<tbody>";
                echo "<tr 
                        data-userid='" . htmlspecialchars($post['userId']) . "' 
                        data-name='" . htmlspecialchars($post['name']) . "' 
                        data-lastname='" . htmlspecialchars($post['lastName']) . "' 
                        data-phone='" . htmlspecialchars($post['phone']) . "' 
                        data-email='" . htmlspecialchars($post['email']) . "'>";

                echo "<td class='pe-4'>" . htmlspecialchars($post['userId']) . "</td>";

                if (!empty($post['image'])) {
                    echo "<td class='pe-4 text-center align-middle''>" . "<img style='width:75px; height:75px; object-fit:cover;' src='" . htmlspecialchars($post['image'] ?? '') . "'/>" . "</td>";
                } else {
                    echo "<td class='pe-4 text-center align-middle'>";
                    echo "<img src='assets\img\perfil.jpg' style='max-width:80px; height:auto;'/>";
                    echo "</td>";
                }
                echo "<td class='pe-4'>" . htmlspecialchars($post['name']) . "</td>";
                echo "<td class='pe-4'>" . htmlspecialchars($post['lastName']) . "</td>";
                echo "<td class='pe-4'>" . htmlspecialchars($post['phone']) . "</td>";
                echo "<td class='pe-4'>" . htmlspecialchars($fechaFormateada) . "</td>";
                echo "<td class='pe-4'>" . htmlspecialchars($post['email']) . "</td>";
                echo "<td >" . "<button
                        type='button'
                        data-bs-toggle='modal'
                        data-bs-target='#miModal'
                        class='btn btn-primary editarUsuario'
                        data-userid='" . htmlspecialchars($post['userId']) . "' 
                        data-name='" . htmlspecialchars($post['name']) . "'
                        data-lastname='" . htmlspecialchars($post['lastName']) . "'
                        data-phone='" . htmlspecialchars($post['phone']) . "'
                        data-email='" . htmlspecialchars($post['email']) . "'>
                        Editar
                    </button>" .
                    "<button class='btn btn-danger btn-quitar-usuario ms-2' data-userid='" . htmlspecialchars($post['userId']) . "'>Quitar</button>"
                    . "</td>";
                echo "</tr>";
                echo "</tbody>";
            }

            echo "</table>";
        }
        curl_close($ch); ?>

        <div class="modal fade" id="miModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalTitle">Modificar datos del usuario</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation"
                            novalidate id="formUsuario" method="POST">

                            <!-- <p>ID del usuario: <span id="modalUserId"></span></p> -->
                            <input type="hidden" id="inputUserId" name="userId" value="">
                            <div id="mensaje" class="container-sm mt-3"></div>
                            <div class="container-sm mt-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Nombre(s):</span>
                                    <input type="text" class="form-control" placeholder="--"
                                        name="name" required minlength="2" id="inputName"
                                        aria-label="name" aria-describedby="name">

                                    <div class="invalid-feedback">Por favor ingresa un nombre válido (mínimo 2 caracteres).</div>
                                </div>

                                <div class="input-group mb-3 ">

                                    <span class="input-group-text">Apellidos:</span>
                                    <input type="text" class="form-control" placeholder="--"
                                        name="lastName" required minlength="2" id="inputLastName"
                                        aria-label="lastName" aria-describedby="lastName">

                                    <div class="invalid-feedback">Por favor ingresa los apellidos.</div>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Telefono:</span>
                                    <input type="text" class="form-control" placeholder="--"
                                        name="phone" required pattern="\d{7,15}" id="inputPhone"
                                        aria-label="phone" aria-describedby="phone">

                                    <div class="invalid-feedback">Por favor ingresa un número válido (7 a 15 dígitos).</div>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Correo:</span>
                                    <input type="text" class="form-control" placeholder="--"
                                        required minlength="5" maxlength="20" name="email" id="inputEmail"
                                        aria-label="email" aria-describedby="email">
                                    <div class="invalid-feedback">Por favor ingresa una direccion de correo valida.</div>
                                </div>
                                <button type="submit" class="btn btn-primary mb-2" value="usuario">Modificar</button>

                                <h1 class="modal-title fs-5">Cambiar contraseña</h1>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Contraseña:</span>
                                    <input type="password" class="form-control" placeholder="--"
                                        name="password" required minlength="8" id="inputPassword"
                                        aria-label="password" aria-describedby="password">

                                    <div class="invalid-feedback">La contraseña debe tener al menos 8 caracteres.</div>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Repetir contraseña:</span>
                                    <input type="password" class="form-control" placeholder="--"
                                        name="password2" aria-label="password2" id="inputPassword2"
                                        aria-describedby="password2">

                                    <div class="invalid-feedback">Las contraseñas deben ser iguales.</div>
                                </div>

                                <button id="btnSubmitPassword" type="submit" class="btn btn-primary mb-2" value="password">Guardar</button>

                                <div class=" mb-3">
                                    <label for="inputFile" class="form-label">Agregar archivo de imagen</label>
                                    <input class="form-control" type="file" id="inputFile" name="profileImage" accept="image/*">
                                    <button id="btnSubmitImage" type="submit" class="btn btn-primary mb-2" value="imagen">Guardar</button>


                        </form>
                    </div>

                </div>
                <p id="status"></p>




                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('miModal');
            const form = document.getElementById('formUsuario');
            let subirDatos = null
            let userId = null

            // Abre el modal y llena campos
            modal.addEventListener('show.bs.modal', function(event) {
                console.log('✅ Modal abierto');
                const button = event.relatedTarget;

                // Llenar campos desde atributos del botón
                userId = form.querySelector('#inputUserId').value = button.getAttribute('data-userid');
                form.querySelector('#inputName').value = button.getAttribute('data-name');
                form.querySelector('#inputLastName').value = button.getAttribute('data-lastname');
                form.querySelector('#inputPhone').value = button.getAttribute('data-phone');
                form.querySelector('#inputEmail').value = button.getAttribute('data-email');

            });

            form.querySelectorAll('button[type="submit"]').forEach(button => {
                button.addEventListener('click', function() {
                    subirDatos = this.value;
                });
            });

            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Evitar el envío del formulario por defecto
                event.stopPropagation(); // Detener la propagación del evento


                const formData = new FormData(form);
                const fileInput = form.querySelector('#inputFile')
                const file = fileInput.files[0];

                if (!subirDatos) {
                    alert('No se ha seleccionado una acción');
                    return;
                }

                switch (subirDatos) {
                    case 'usuario':

                        const dataUsuario = {
                            UserId: userId,
                            Name: formData.get('name'),
                            LastName: formData.get('lastName'),
                            Phone: formData.get('phone'),
                            Email: formData.get('email')
                        };
                        console.info('Datos del usuario:', dataUsuario);
                        actualizarUsuario(dataUsuario);
                        break;

                    case 'password':

                        const password1 = formData.get('password');
                        const password2 = formData.get('password2');
                        const dataPassword = {
                            UserId: userId,
                            Password: password1
                        };

                        if (password1 || password2) {
                            if (password1.length < 8) {
                                mostrarMensaje('La contraseña debe tener al menos 8 caracteres', 'danger');
                                return;
                            }

                            if (password1 !== password2) {
                                mostrarMensaje('Las contraseñas no coinciden', 'danger');
                                return;
                            }
                        }
                        actualizarContrasena(dataPassword);
                        break;



                    case 'imagen':
                        if (file) {
                            const dataImagen = {
                                UserId: userId,
                                profileImage: file
                            };
                            console.info('Datos de la imagen:', dataImagen);
                            cargarImagen(dataImagen);
                        } else {
                            mostrarMensaje('Por favor selecciona una imagen', 'danger');
                        }
                        break;
                    default:
                        alert('Acción no válida');
                        return;
                }

            });

            document.querySelectorAll('.btn-quitar-usuario').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-userid');
                    const fila = this.closest('tr');

                    if (fila) {
                        fila.hidden = true;
                    }
                });
            });

        })

       
    </script>


    <script src="assets/js/upd_user.js"></script>
    <script src="assets/js/upd_password.js"></script>
    <script src="assets/js/upd_image.js"></script>
    <script src="assets/js/rd_excel.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>


</body>

</html>