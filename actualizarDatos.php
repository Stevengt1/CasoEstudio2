<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: ./index.php");
    exit();
} else {
    require_once 'include/conexion.php';

    $usuarios_data = [];
    $resultado = $mysqli->query("SELECT id_cliente, identificacion, apellidos, nombre, telefono_personal, direccion_personal, email, lugar_trabajo, direccion_trabajo, telefono_trabajo, usuario FROM clientes");
    if ($resultado && $resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $usuarios_data[] = $row;
        }
    }
    $resultado->close();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userId        = $_POST['userId'];
        $identificacion = trim($_POST['cedula']);
        $apellidos     = $_POST['apellidos'];
        $nombre        = $_POST['nombre'];
        $telefonop     = $_POST['telefonop'];
        $direccion     = $_POST['direccion'];
        $email         = $_POST['email'];
        $lugart        = $_POST['lugart'];
        $direcciont    = $_POST['direcciont'];
        $telefonot     = $_POST['telefonot'];
        $usuario       = strtolower(trim($_POST['usuario']));
        $password      = $_POST['password'];
        $mensaje       = "";

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensaje = "Email inválido";
            exit();
        }

        $passwordHash = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

        if (!empty($userId)) {
            if (!empty($password)) {
                $sql = "UPDATE clientes SET identificacion=?, apellidos=?, nombre=?, telefono_personal=?, direccion_personal=?, email=?, lugar_trabajo=?, direccion_trabajo=?, telefono_trabajo=?, usuario=?, contrasena=? WHERE id_cliente=?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssssssssssssi", $identificacion, $apellidos, $nombre, $telefonop, $direccion, $email, $lugart, $direcciont, $telefonot, $usuario, $passwordHash, $userId);
            } else {
                $sql = "UPDATE clientes SET identificacion=?, apellidos=?, nombre=?, telefono_personal=?, direccion_personal=?, email=?, lugar_trabajo=?, direccion_trabajo=?, telefono_trabajo=?, usuario=? WHERE id_cliente=?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssssssssssi", $identificacion, $apellidos, $nombre, $telefonop, $direccion, $email, $lugart, $direcciont, $telefonot, $usuario, $userId);
            }

            $stmt->execute();
            $mensaje = ($stmt->affected_rows > 0) ? "Datos actualizados correctamente" : "No se realizaron cambios";
            $stmt->close();
        } else {
            $sql = "INSERT INTO clientes (identificacion, apellidos, nombre, telefono_personal, direccion_personal, email, lugar_trabajo, direccion_trabajo, telefono_trabajo, usuario, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("sssssssssss", $identificacion, $apellidos, $nombre, $telefonop, $direccion, $email, $lugart, $direcciont, $telefonot, $usuario, $passwordHash);
            $stmt->execute();
            $mensaje = ($stmt->affected_rows > 0) ? "Datos creados correctamente" : "No creó ningún usuario";
            $stmt->close();
        }

        $mysqli->close();
        header("Location:" . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finanzas Seguras S.A</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/stglobal.css">
    <link rel="stylesheet" href="css/stfooter.css">
    <link rel="stylesheet" href="css/stheader.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="javascript/actDatos.js"></script>
</head>

<body>
    <div class="site">
        <header>
            <?php include 'include/navBar.php' ?>
        </header>
        <main>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="m-2">Actualización de datos</h2>
                <button class="btn btn-outline-success m-2" id="btnAgregar" data-bs-toggle="modal" data-bs-target="#userModal">Agregar Usuario</button>
            </div>
            <section class="container-fluid mt-4">
                <table class="table table-bordered table-striped" id="tablaUsuarios">
                    <thead class="table-dark">
                        <tr>
                            <th>Cédula</th>
                            <th>Apellidos</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Email</th>
                            <th>Empresa</th>
                            <th>Dirección Empresa</th>
                            <th>Teléfono Empresa</th>
                            <th>Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios_data as $usuario): ?>
                            <tr>
                                <td><?= htmlspecialchars($usuario['identificacion']); ?></td>
                                <td><?= htmlspecialchars($usuario['apellidos']); ?></td>
                                <td><?= htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?= htmlspecialchars($usuario['telefono_personal']); ?></td>
                                <td><?= htmlspecialchars($usuario['direccion_personal']); ?></td>
                                <td><?= htmlspecialchars($usuario['email']); ?></td>
                                <td><?= htmlspecialchars($usuario['lugar_trabajo']); ?></td>
                                <td><?= htmlspecialchars($usuario['direccion_trabajo']); ?></td>
                                <td><?= htmlspecialchars($usuario['telefono_trabajo']); ?></td>
                                <td><?= htmlspecialchars($usuario['usuario']); ?></td>
                                <td>
                                    <a href="#"
                                        data-id="<?= $usuario['id_cliente']; ?>"
                                        data-identificacion="<?= $usuario['identificacion']; ?>"
                                        data-apellidos="<?= $usuario['apellidos']; ?>"
                                        data-nombre="<?= $usuario['nombre']; ?>"
                                        data-telefonop="<?= $usuario['telefono_personal']; ?>"
                                        data-direccion="<?= $usuario['direccion_personal']; ?>"
                                        data-email="<?= $usuario['email']; ?>"
                                        data-lugart="<?= $usuario['lugar_trabajo']; ?>"
                                        data-direcciont="<?= $usuario['direccion_trabajo']; ?>"
                                        data-telefonot="<?= $usuario['telefono_trabajo']; ?>"
                                        data-usuario="<?= $usuario['usuario']; ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#userModal"
                                        class="btn btn-outline-warning btn-sm">Editar</a>
                                    <a href="#" class="btn btn-outline-danger btn-sm eliminar-btn" data-id="<?= $usuario['id_cliente']; ?>">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </main>

        <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form id="userForm" method="POST" action="">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="userModalTitle">Agregar Usuario</h4>
                        </div>
                        <div class="modal-body">
                            <input id="userId" type="hidden" name="userId">
                            <div class="mt-3">
                                <label for="cedula" class="form-label">Cédula</label>
                                <input type="text" class="form-control" id="cedula" name="cedula" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefonop" class="form-label">Teléfono Personal</label>
                                <input type="text" class="form-control" id="telefonop" name="telefonop" required>
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="lugart" class="form-label">Lugar de trabajo</label>
                                <input type="text" class="form-control" id="lugart" name="lugart" required>
                            </div>
                            <div class="mb-3">
                                <label for="direcciont" class="form-label">Dirección del trabajo</label>
                                <input type="text" class="form-control" id="direcciont" name="direcciont" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefonot" class="form-label">Teléfono de trabajo</label>
                                <input type="text" class="form-control" id="telefonot" name="telefonot" required>
                            </div>
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-primary">Guardar</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <footer class="footer"> Finanzas Seguras S.A derechos reservados ©2025</footer>
    </div>
</body>

</html>