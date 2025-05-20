function cargarImagen(dataImagen) {
    const formData = new FormData();
    formData.append('profileImage', dataImagen.profileImage); // El nombre 'profileImage' debe coincidir con el del input y el nombre esperado en PHP
    const userId = dataImagen.UserId; // Asegúrate de que estás pasando el userId correctamente
    formData.append('userId', userId); // Agrega el userId al FormData

    fetch('middleware/cargar_imagen.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
            const imagePath = result.filePath; // Asegúrate de que el servidor devuelve la ruta correcta
            const path = imagePath.replace('..', '')
            const imageUrl = "http://localhost/curd" + path; // Cambia esto según tu configuración
            mostrarMensaje('Imagen subida correctamente', 'success');
            setTimeout(() => {
              location.reload();
            }, 3000);

            // console.log('URL de la imagen:', imageUrl);
            // console.log('Path de la imagen:', path);
        } else {
            mostrarMensaje('Error al subir la imagen: ' + result.message, 'danger');
        }

    })
    .catch(error => {
        mostrarMensaje('Error al subir la imagen: ' + error.message, 'danger');
    });
}


