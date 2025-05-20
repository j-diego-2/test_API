function actualizarContrasena(dataPassword) {
    const url = "middleware/cambiar_contrasena.php";

    const payload = {
        user: {
            UserId: dataPassword.UserId,
            Password: dataPassword.Password,
        },
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
        mostrarMensaje("Contraseña actualizada", "success");
        setTimeout(() => {
          location.reload();
        }, 3000);
      } else {
        mostrarMensaje(
          "Error al actualizar el usuario: " + result.message,
          "danger"
        );
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      mostrarMensaje("Error al actualizar el usuario", "danger");
    });
}