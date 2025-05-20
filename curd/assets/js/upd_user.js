function actualizarUsuario(dataUsuario) {
  const url = "middleware/editar_usuario.php";

  const payload = {
    user: {
      UserId: dataUsuario.UserId,
      Name: dataUsuario.Name,
      LastName: dataUsuario.LastName,
      Phone: dataUsuario.Phone,
      Email: dataUsuario.Email,
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
        mostrarMensaje("Usuario actualizado correctamente", "success");
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

function mostrarMensaje(texto, tipo) {
  mensaje.innerHTML = `
                <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                ${texto}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
}
