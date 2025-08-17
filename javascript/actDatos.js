document.addEventListener('DOMContentLoaded', function () {
    const btnEditar = document.querySelectorAll('.btn-outline-warning');
    const modalElement = document.getElementById('userModal');
    const modal = new bootstrap.Modal(modalElement);
    const modalTitle = document.getElementById('userModalTitle');
    const form = document.getElementById('userForm');
    const passwordInput = document.getElementById('password');

    btnEditar.forEach(btn => {
        btn.addEventListener('click', function () {
            modalTitle.textContent = 'Editar Usuario';

            document.getElementById('userId').value = this.dataset.id;
            document.getElementById('cedula').value = this.dataset.identificacion;
            document.getElementById('apellidos').value = this.dataset.apellidos;
            document.getElementById('nombre').value = this.dataset.nombre;
            document.getElementById('telefonop').value = this.dataset.telefonop;
            document.getElementById('direccion').value = this.dataset.direccion;
            document.getElementById('email').value = this.dataset.email;
            document.getElementById('lugart').value = this.dataset.lugart;
            document.getElementById('direcciont').value = this.dataset.direcciont;
            document.getElementById('telefonot').value = this.dataset.telefonot;
            document.getElementById('usuario').value = this.dataset.usuario;

            document.getElementById('password').value = '';
            passwordInput.removeAttribute('required');

            modal.show();
        });
    });

    modalElement.addEventListener('hidden.bs.modal', () => {
        form.reset();
        document.getElementById('userId').value = '';
        modalTitle.textContent = 'Editar Usuario';
        passwordInput.setAttribute('required', true);
    });

    // Activar botón de eliminar
    document.querySelectorAll('.eliminar-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const userId = this.dataset.id;

            Swal.fire({
                title: '¿Eliminar usuario?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) {
                    window.location.href = `actualizarDatos.php?delete=${userId}`;
                }
            });
        });
    });
});

