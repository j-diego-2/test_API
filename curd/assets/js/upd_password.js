addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById('miModal');
    const form = document.getElementById('formUsuario');

    modal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        userId = form.querySelector('#inputUserId').value = button.getAttribute('data-userid');
        return userId;
        // console.log('Valor del id', userId);   
    })

    const formPassword = document.getElementById('formPassword');

    const password = formPassword.querySelector('#inputPassword1');
    const password2 = formPassword.querySelector('#inputPassword2');
     
});