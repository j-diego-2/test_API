function eliminarUsuario(dataUser){
    const url  = "middleware/eliminar_usuario.php";
    const payload = {
        user: {
            UserId: dataUser.user.UserId
        }
    };
    
    const options = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
    };

    fetch(url, options)
        .then((response) => response.json())
        .then((result) => {
            if (result.success) {
                mostrarMensaje("Usuario eliminado correctamente", "success");
                setTimeout(() => {
                    location.reload();
                }, 2500);
            } else {
                mostrarMensaje(
                    "Error al eliminar el usuario: " + result.message,
                    "danger"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            mostrarMensaje("Error al eliminar el usuario", "danger");
        });

}

function mostrarMensaje(texto, tipo) {
    mensaje.innerHTML = `
                <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                ${texto}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
}