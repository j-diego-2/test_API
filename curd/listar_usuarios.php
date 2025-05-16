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

        <a href="index.html" class="btn btn-primary mb-3" role="button">Regresar</a>
        <a href="crear_usuario.php" class="btn btn-primary mb-3" role="button">Agregar nuevo usuario</a>
        <a href="buscar_usuario.php" class="btn btn-primary mb-3" role="button">Buscar usuario</a>

        <?php
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://localhost:7240/user/data");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


        $respuesta = curl_exec($ch);

        if (curl_errno($ch)) echo curl_error($ch);
        else $info = json_decode($respuesta, true);

        //var_dump($respuesta);

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
                echo "<tbody>";
                echo "<tr 
                        data-userid='" . htmlspecialchars($post['userId']) . "' 
                        data-name='" . htmlspecialchars($post['name']) . "' 
                        data-lastname='" . htmlspecialchars($post['lastName']) . "' 
                        data-phone='" . htmlspecialchars($post['phone']) . "' 
                        data-email='" . htmlspecialchars($post['email']) . "'>";

                echo "<td class='pe-4'>" . htmlspecialchars($post['userId']) . "</td>";
                // echo "<td class='pe-4 text-center align-middle''>" . "<img style='width:75px; height:75px; object-fit:cover;' src='" . htmlspecialchars($post['image'] ?? '') . "'/>" ."</td>";

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
                echo "<td class='pe-4'>" . htmlspecialchars($post['created']) . "</td>";
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
                            action="middleware/editar_usuario.php"
                            novalidate id="formUsuario" method="POST"
                            enctype="multipart/form-data">

                            <!-- <p>ID del usuario: <span id="modalUserId"></span></p> -->
                            <input type="hidden" id="inputUserId" name="userId" value="">
                            <div id="mensaje" class="container-sm mt-3"></div>
                            <div class="container-sm mt-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Nombre(s):</span>
                                    <input type="text" class="form-control" placeholder="--"
                                        name="name" required minlength="2" id="inputName"
                                        aria-label="name" aria-describedby="name" disabled>
                                    <button type="button" class="btn btn-primary toggle-single"
                                        data-target="name">✏️</button>
                                    <div class="invalid-feedback">Por favor ingresa un nombre válido (mínimo 2 caracteres).</div>
                                </div>

                                <div class="input-group mb-3 ">
                                    <span class="input-group-text">Apellidos:</span>
                                    <input type="text" class="form-control" placeholder="--"
                                        name="lastName" required minlength="2" id="inputLastName"
                                        aria-label="lastName" aria-describedby="lastName" disabled>
                                    <button type="button" class="btn btn-primary toggle-single"
                                        data-target="lastName">✏️</button>
                                    <div class="invalid-feedback">Por favor ingresa los apellidos.</div>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Telefono:</span>
                                    <input type="text" class="form-control" placeholder="--"
                                        name="phone" required pattern="\d{7,15}" id="inputPhone"
                                        aria-label="phone" aria-describedby="phone" disabled>
                                    <button type="button" class="btn btn-primary toggle-single"
                                        data-target="phone">✏️</button>
                                    <div class="invalid-feedback">Por favor ingresa un número válido (7 a 15 dígitos).</div>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Correo:</span>
                                    <input type="text" class="form-control" placeholder="--"
                                        required minlength="5" maxlength="20" name="email" id="inputEmail"
                                        aria-label="email" aria-describedby="email" disabled>
                                    <button type="button" class="btn btn-primary toggle-single"
                                        data-target="email">✏️</button>
                                    <div class="invalid-feedback">Por favor ingresa una direccion de correo valida.</div>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Contraseña:</span>
                                    <input type="password" class="form-control" placeholder="--"
                                        name="password" required minlength="8" id="inputPassword"
                                        aria-label="password" aria-describedby="password" disabled>
                                    <button type="button" class="btn btn-primary toggle-single"
                                        data-target="password">✏️</button>
                                    <div class="invalid-feedback">La contraseña debe tener al menos 8 caracteres.</div>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Repetir contraseña:</span>
                                    <input type="password" class="form-control" placeholder="--"
                                        name="password2" aria-label="password2" id="inputPassword2"
                                        aria-describedby="password2" disabled>
                                    <button type="button" class="btn btn-primary toggle-single"
                                        data-target="password2">✏️</button>
                                    <div class="invalid-feedback">Las contraseñas deben ser iguales.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="inputFile" class="form-label">Agregar archivo de imagen</label>
                                    <input class="form-control" type="file" id="inputFile" name="profileImage" accept="image/*">
                                    <button type="button" class="btn btn-primary toggle-single"
                                        data-target="file">✏️</button>
                                </div>
                                <p id="status"></p>
                                <button type="submit" class="btn btn-primary mb-2">Modificar</button>
                        </form>
                    </div>
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
            console.log('✅ DOM completamente cargado');

            // Abre el modal y llena campos
            modal.addEventListener('show.bs.modal', function(event) {
                console.log('✅ Modal abierto');
                const button = event.relatedTarget;

                // Llenar campos desde atributos del botón
                form.querySelector('#inputUserId').value = button.getAttribute('data-userid');
                form.querySelector('#inputName').value = button.getAttribute('data-name');
                form.querySelector('#inputLastName').value = button.getAttribute('data-lastname');
                form.querySelector('#inputPhone').value = button.getAttribute('data-phone');
                form.querySelector('#inputEmail').value = button.getAttribute('data-email');
                form.querySelector('#inputPassword').value = '';
                form.querySelector('#inputPassword2').value = '';
                form.querySelector('#inputFile').value = '';

                // Deshabilitar todos menos el userId/
                form.querySelectorAll('input:not([type="hidden"])').forEach(input => input.disabled = true);
                //form.querySelector('#inputFile').disabled = false;


            });

            // Botones ✏️ para habilitar un campo individual
            document.querySelectorAll('.toggle-single').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target');

                    // Deshabilitar todos excepto el seleccionado
                    form.querySelectorAll('input:not([type="hidden"])').forEach(input => input.disabled = true);

                    const targetInput = document.getElementById('input' + capitalizeFirstLetter(targetId));
                    if (targetInput) {
                        targetInput.disabled = false;
                        setTimeout(() => targetInput.focus(), 50); // Ayuda con conflictos de foco
                    }
                });
            });

            // Enviar datos
            form.addEventListener('submit', function(e) {
                e.preventDefault()
                e.stopPropagation()

                const disabledInputs = []
                form.querySelectorAll('input:not([type="hidden"])').forEach(input => {
                    if (input.disabled) {
                        disabledInputs.push(input)
                        input.disabled = false
                    }
                });

                const formData = new FormData(form);

                const data = {
                    userId: formData.get('userId'),
                    name: formData.get('name'),
                    lastName: formData.get('lastName'),
                    phone: formData.get('phone'),
                    email: formData.get('email'),
                    password: formData.get('password'),
                    profileImage: formData.get('profileImage')
                };

                const password = formData.get('password');
                const password2 = formData.get('password2');

                if (password || password2) {
                    if (password !== password2) {
                        mostrarMensaje('Las contraseñas no coinciden', 'danger');
                        disabledInputs.forEach(input => input.disabled = true);
                        return;
                    }

                    if (password.length < 8) {
                        mostrarMensaje('La contraseña debe tener al menos 8 caracteres', 'danger');
                        disabledInputs.forEach(input => input.disabled = true);
                        return;
                    }

                    data.password = password;
                }

                const fileInput = form.querySelector('#inputFile')
                const file = fileInput.files[0];

                if (fileInput && fileInput.size > 0) {
                    const formData = new FormData();
                    formData.append('profileImage', file);
                    cargarImagen(formDataImg, data)
                } else {
                    data.profileImage = null;
                }

                console.info('Datos a enviar:', data);
                return data
                //actualizarUsuario(data)
            })

            function cargarImagen(formData, data) {
                fetch('middleware/cargar_imagen.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === 'success') {
                            const ruta = result.filePath;
                            document.getElementById('status').textContent = result.message;

                            // Usar el path en tu app
                            console.log('Ruta de la imagen:', ruta);

                            // Guardar en tu estructura de datos
                            data.profileImage = ruta;
                        } else {
                            document.getElementById('status').textContent = result.message;
                        }

                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('status').textContent = 'Error al subir la imagen.';
                    });

            }


            async function actualizarUsuario(data) {
                const url = 'middleware/editar_usuario.php';
                const options = {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                };

                try {
                    const response = await fetch(url, options);
                    const result = await response.json();
                    console.log('Respuesta del servidor:', result);

                    if (result.status === 200) {
                        mostrarMensaje(result.message, 'success');
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        mostrarMensaje(result.message, 'danger');
                    }
                } catch (error) {
                    console.error('Error al enviar la solicitud:', error);
                }
            }



            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }

            function mostrarMensaje(texto, tipo) {
                mensaje.innerHTML = `
                <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                ${texto}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`
            }


        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>


</body>

</html>