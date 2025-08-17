document.addEventListener('DOMContentLoaded', function () {
    const btnEditar = document.querySelectorAll('.btn-outline-warning'); // Coincide con el HTML
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    const modalTitle = document.getElementById('userModalTitle');
    const form = document.getElementById('userForm');
    const passwordInput = document.getElementById('password');
    const cedulaInput = document.getElementById('cedula');
    const usuarioInput = document.getElementById('usuario');

    btnEditar.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const identificacion = this.getAttribute('data-identificacion');
            const apellidos = this.getAttribute('data-apellidos');
            const nombre = this.getAttribute('data-nombre');
            const telefonop = this.getAttribute('data-telefonop');
            const direccion = this.getAttribute('data-direccion');
            const email = this.getAttribute('data-email');
            const lugart = this.getAttribute('data-lugart');
            const direcciont = this.getAttribute('data-direcciont');
            const telefonot = this.getAttribute('data-telefonot');
            const usuario = this.getAttribute('data-usuario');

            modalTitle.textContent = 'Editar Usuario';

            document.getElementById('userId').value = id;
            document.getElementById('cedula').value = identificacion;
            document.getElementById('apellidos').value = apellidos;
            document.getElementById('nombre').value = nombre;
            document.getElementById('telefonop').value = telefonop;
            document.getElementById('direccion').value = direccion;
            document.getElementById('email').value = email;
            document.getElementById('lugart').value = lugart;
            document.getElementById('direcciont').value = direcciont;
            document.getElementById('telefonot').value = telefonot;
            document.getElementById('usuario').value = usuario;

            document.getElementById('password').value = '';

            passwordInput.removeAttribute('required');

            modal.show();
        });
    });

    document.getElementById('userModal').addEventListener('hidden.bs.modal', () => {
        form.reset();
        document.getElementById('userId').value = '';
        modalTitle.textContent = 'Agregar Usuario';
        passwordInput.setAttribute('required', true);
    });
});