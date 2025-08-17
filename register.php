<?php
session_start();
require_once 'include/conexion.php';

$validation_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identificacion = trim($_POST['identificacion']);
    $apellidos = $_POST['apellidos'];
    $nombre = $_POST['nombre'];
    $telefonop = $_POST['telefonop'];
    $direccion = $_POST['direccion'];
    $email = $_POST['email'];
    $lugart = $_POST['lugart'];
    $direcciont = $_POST['direcciont'];
    $telefonot = $_POST['telefonot'];
    $usuario = strtolower(trim($_POST['usuario']));
    $password = $_POST['password'];

    // Validación del correo
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validation_message = '<div class="alert alert-danger">Correo electrónico no válido.</div>';
    } else {
        // Verificar si el usuario existe
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM clientes WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->bind_result($count_usuario);
        $stmt->fetch();
        $stmt->close();

        // Verificar si la cédula existe
    $stmt_cedula = $mysqli->prepare("SELECT COUNT(*) FROM clientes WHERE identificacion = ?");
    $stmt_cedula->bind_param("s", $identificacion);
        $stmt_cedula->execute();
        $stmt_cedula->bind_result($count_cedula);
        $stmt_cedula->fetch();
        $stmt_cedula->close();

        if ($count_usuario > 0) {
            $validation_message = '<div class="alert alert-warning">El nombre de usuario ya está registrado. Por favor elige otro.</div>';
        } elseif ($count_cedula > 0) {
            $validation_message = '<div class="alert alert-warning">La cédula ya está registrada. Verifica que no estés duplicando el registro.</div>';
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $mysqli->prepare("insert into clientes (
                identificacion, apellidos, nombre, telefono_personal, direccion_personal, email,
                lugar_trabajo, direccion_trabajo, telefono_trabajo, usuario, contrasena
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "sssssssssss",
                $identificacion,
                $apellidos,
                $nombre,
                $telefonop,
                $direccion,
                $email,
                $lugart,
                $direcciont,
                $telefonot,
                $usuario,
                $hash
            );
            if ($stmt->execute()) {
                $validation_message = '<div class="alert alert-success">Usuario registrado exitosamente.</div>';
                header("Location: index.php");
            } else {
                $validation_message = '<div class="alert alert-danger">Error al registrar el usuario. Intenta nuevamente.</div>';
            }
            $mysqli->close();
        }
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
</head>

<body>
    <?php if (!empty($validation_message)) {
        echo $validation_message;
    } ?>
    <div class="site">
        <?php include 'include/navBar.php' ?>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h2>Registrar usuario</h2>
                </div>
                <div class="card-body">
                    <form id="registroform" method="POST" action="">
                        <div class="mb-3">
                            <label for="identificacion" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="identificacion" name="identificacion" required>
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
                        <button type="submit" class="btn btn-primary">Registrarse</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>