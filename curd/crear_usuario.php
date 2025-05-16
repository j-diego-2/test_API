<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <title>Agregar usuario</title>
</head>

<body>
    <h3 class="text-center mt-4">Agregar nuevo usuario</h3>

    <div id="mensaje" class="container-sm mt-3"></div>
    <!-- agregar la ruta en middleware/agregarUsuario.php en caso de que no funcione -->
    <form class="needs-validation" novalidate id="formUsuario" method="POST" enctype="multipart/form-data">
        <div class="container-sm mt-3">
            <div class="input-group mb-3">
                <span class="input-group-text" id="name">Nombre(s):</span>
                <input type="text" class="form-control" placeholder="--" name="name" required minlength="2" aria-label="name" aria-describedby="name">
                <div class="invalid-feedback">Por favor ingresa un nombre válido (mínimo 2 caracteres).</div>
            </div>

            <div class="input-group mb-3 ">
                <span class="input-group-text" id="lastName">Apellidos:</span>
                <input type="text" class="form-control" placeholder="--" name="lastName" required minlength="2" aria-label="lastName" aria-describedby="lastName">
                <div class="invalid-feedback">Por favor ingresa los apellidos.</div>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="phone">Telefono:</span>
                <input type="text" class="form-control" placeholder="--" name="phone" required pattern="\d{7,15}" aria-label="phone" aria-describedby="phone">
                <div class="invalid-feedback">Por favor ingresa un número válido (7 a 15 dígitos).</div>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="email">Correo:</span>
                <input type="text" class="form-control" placeholder="--" required minlength="5" maxlength="20" name="email" aria-label="email" aria-describedby="email">
                <div class="invalid-feedback">Por favor ingresa una direccion de correo valida.</div>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="password">Contraseña:</span>
                <input type="password" class="form-control" placeholder="--" name="password" required minlength="8" aria-label="password" aria-describedby="password">
                <div class="invalid-feedback">La contraseña debe tener al menos 8 caracteres.</div>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="password2">Repetir contraseña:</span>
                <input type="password" class="form-control" placeholder="--" name="password2" aria-label="password2" aria-describedby="password2">
                <div class="invalid-feedback">Las contraseñas deben ser iguales.</div>
            </div>
            <button type="submit" class="btn btn-primary">Agregar usuario</button>
            <a href="listar_usuarios.php" class="btn btn-primary" role="button">Regresar</a>
    </form>


    <script>
         document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formUsuario');
            const mensaje = document.getElementById('mensaje');
            

            form.addEventListener('submit', async function(e) {
                e.preventDefault(); // 🚫 Evita envío tradicional
                e.stopPropagation();

                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }

                const formData = new FormData(form);
                const password = formData.get('password');
                const password2 = formData.get('password2');

                if (password !== password2) {
                    mostrarMensaje('Las contraseñas no coinciden.', 'danger');
                    return;
                }

                const data = {
                    name: formData.get('name'),
                    lastName: formData.get('lastName'),
                    phone: formData.get('phone'),
                    email: formData.get('email'),
                    password: formData.get('password')
                };

                console.warn("Datos enviados: ", [data.name, data.lastName, data.phone, data.email, data.password]);

                try {
                    // Crear usuario
                    const userResponse = await fetch('middleware/agregarUsuario.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    
                    const userResult = await userResponse.json();
                    console.info("Respuesta de la API: ", userResult);

                    if(!userResult.success) {
                        mostrarMensaje(userResult.message, 'danger');
                        return;
                    }else {
                        mostrarMensaje('Usuario creado exitosamente.', 'success');
                    }
                    form.reset(); // Limpiar el formulario
                    form.classList.remove('was-validated'); // Limpiar validaciones

                } catch (error) {
                    console.error('Error:', error);
                    mostrarMensaje('Error al crear usuario: ' + error.message, 'danger');
                }
        });

        function mostrarMensaje(texto, tipo) {
        mensaje.innerHTML = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${texto}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        `;
    }
    });
    </script>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

</body>

</html>

