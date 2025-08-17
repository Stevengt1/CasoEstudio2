<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: ./index.php");
    exit();
}
require_once 'include/conexion.php';

// Cargar préstamos activos
$prestamos_data = [];
$resultado = $mysqli->query("SELECT id_prestamo, monto_total FROM prestamos WHERE estado = 'Activo'");
while ($row = $resultado->fetch_assoc()) {
    $prestamos_data[] = $row;
}
$resultado->close();

// Cargar pagos registrados
$pagos_data = [];
$resultado = $mysqli->query("
    SELECT p.id_pago, p.id_prestamo, p.monto_pagado, p.fecha_pago, p.numero_deposito,
           pr.monto_total
    FROM pagos p
    JOIN prestamos pr ON p.id_prestamo = pr.id_prestamo
    ORDER BY p.fecha_pago DESC
");
while ($row = $resultado->fetch_assoc()) {
    $pagos_data[] = $row;
}
$resultado->close();

// Si viene un id_pago por GET, cargar datos para edición
$pago_edit = null;
if (isset($_GET['id_pago'])) {
    $stmt = $mysqli->prepare("SELECT * FROM pagos WHERE id_pago = ?");
    $stmt->bind_param("i", $_GET['id_pago']);
    $stmt->execute();
    $res = $stmt->get_result();
    $pago_edit = $res->fetch_assoc();
    $stmt->close();
}

// Procesar envío del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pago         = $_POST['id_pago'] ?? null;
    $id_prestamo     = $_POST['id_prestamo'] ?? '';
    $monto_pagado    = $_POST['monto_pagado'] ?? '';
    $fecha_pago      = $_POST['fecha_pago'] ?? '';
    $numero_deposito = htmlspecialchars(trim($_POST['numero_deposito'] ?? ''), ENT_QUOTES, 'UTF-8');

    if (!is_numeric($monto_pagado) || $monto_pagado <= 0 || strlen($numero_deposito) < 4) {
        $_SESSION['mensaje'] = "Datos inválidos. Verifica el monto y número de depósito.";
        header("Location:" . $_SERVER['PHP_SELF']);
        exit();
    }

    if (!empty($id_pago)) {
        $stmt = $mysqli->prepare("UPDATE pagos SET id_prestamo=?, monto_pagado=?, fecha_pago=?, numero_deposito=? WHERE id_pago=?");
        $stmt->bind_param("idssi", $id_prestamo, $monto_pagado, $fecha_pago, $numero_deposito, $id_pago);
        $stmt->execute();
        $_SESSION['mensaje'] = ($stmt->affected_rows > 0) ? "Pago actualizado correctamente." : "No se realizaron cambios.";
        $stmt->close();
    } else {
        $stmt = $mysqli->prepare("INSERT INTO pagos (id_prestamo, monto_pagado, fecha_pago, numero_deposito) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idss", $id_prestamo, $monto_pagado, $fecha_pago, $numero_deposito);
        $stmt->execute();
        $_SESSION['mensaje'] = ($stmt->affected_rows > 0) ? "Pago registrado correctamente." : "No se registró el pago.";
        $stmt->close();
    }

    $mysqli->close();
    header("Location:" . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="javascript/pagos.js"></script>
</head>

<body>
    <div class="site">
        <?php include 'include/navBar.php'; ?>
        <main>
            <div class="container mt-4">
                <h2><?= $pago_edit ? "Editar Pago" : "Registrar Pago" ?></h2>

                <?php if (isset($_SESSION['mensaje'])): ?>
                    <script>
                        Swal.fire("Aviso", "<?= $_SESSION['mensaje'] ?>", "info");
                    </script>
                    <?php unset($_SESSION['mensaje']); ?>
                <?php endif; ?>

                <form method="POST" id="formPago">
                    <input type="hidden" name="id_pago" value="<?= $pago_edit['id_pago'] ?? '' ?>">

                    <div class="mb-3">
                        <label for="id_prestamo" class="form-label">Préstamo</label>
                        <select name="id_prestamo" id="id_prestamo" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($prestamos_data as $p): ?>
                                <option value="<?= $p['id_prestamo'] ?>" <?= ($pago_edit && $pago_edit['id_prestamo'] == $p['id_prestamo']) ? 'selected' : '' ?>>
                                    <?= "Préstamo #{$p['id_prestamo']} - ₡" . number_format($p['monto_total'], 2) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="monto_pagado" class="form-label">Monto Pagado</label>
                        <input type="number" step="0.01" class="form-control" name="monto_pagado" id="monto_pagado"
                            value="<?= $pago_edit['monto_pagado'] ?? '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_pago" class="form-label">Fecha del Pago</label>
                        <input type="date" class="form-control" name="fecha_pago" id="fecha_pago"
                            value="<?= $pago_edit['fecha_pago'] ?? '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="numero_deposito" class="form-label">Número de Depósito</label>
                        <input type="text" class="form-control" name="numero_deposito" id="numero_deposito"
                            value="<?= $pago_edit['numero_deposito'] ?? '' ?>" required>
                    </div>

                    <button type="submit" class="btn btn-success"><?= $pago_edit ? "Actualizar" : "Guardar" ?></button>
                </form>

                <hr class="my-5">

                <h3>Pagos Registrados</h3>
                <?php if (!empty($pagos_data)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Pago</th>
                                    <th>ID Préstamo</th>
                                    <th>Monto Préstamo</th>
                                    <th>Monto Pagado</th>
                                    <th>Fecha de Pago</th>
                                    <th>Número de Depósito</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pagos_data as $pago): ?>
                                    <tr>
                                        <td><?= $pago['id_pago'] ?></td>
                                        <td><?= $pago['id_prestamo'] ?></td>
                                        <td>₡<?= number_format($pago['monto_total'], 2) ?></td>
                                        <td>₡<?= number_format($pago['monto_pagado'], 2) ?></td>
                                        <td><?= date("d/m/Y", strtotime($pago['fecha_pago'])) ?></td>
                                        <td><?= htmlspecialchars($pago['numero_deposito']) ?></td>
                                        <td>
                                            <a href="?id_pago=<?= $pago['id_pago'] ?>" class="btn btn-sm btn-primary">Editar</a>
                                            <button class="btn btn-sm btn-danger" onclick="confirmarEliminacion(<?= $pago['id_pago'] ?>)">Eliminar</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No hay pagos registrados aún.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>