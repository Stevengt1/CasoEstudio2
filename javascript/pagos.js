document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formPago");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const monto = parseFloat(form.monto_pagado.value);
    const fecha = form.fecha_pago.value;
    const deposito = form.numero_deposito.value.trim();

    // Validaciones
    if (isNaN(monto) || monto <= 0) {
      Swal.fire("Error", "El monto debe ser mayor a cero.", "error");
      return;
    }

    if (deposito.length < 4) {
      Swal.fire("Error", "El número de depósito debe tener al menos 4 caracteres.", "error");
      return;
    }

    const hoy = new Date().toISOString().split("T")[0];
    if (!fecha || fecha > hoy) {
      Swal.fire("Error", "La fecha del pago no puede estar vacía ni ser futura.", "error");
      return;
    }

    // Confirmación
    Swal.fire({
      title: "¿Confirmar registro de pago?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, guardar",
      cancelButtonText: "Cancelar"
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });
});

function confirmarEliminacion(idPago) {
  Swal.fire({
    title: "¿Eliminar este pago?",
    text: "Esta acción no se puede deshacer.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      fetch("eliminarPago.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({ id_pago: idPago })
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            Swal.fire("Eliminado", data.message, "success");
            // Eliminar la fila de la tabla
            const fila = document.querySelector(`button[onclick='confirmarEliminacion(${idPago})']`).closest("tr");
            if (fila) fila.remove();
          } else {
            Swal.fire("Error", data.message, "error");
          }
        })
        .catch(() => {
          Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
        });
    }
  });
}