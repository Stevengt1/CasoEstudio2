<?php
session_start();
require_once 'include/conexion.php';

$validation_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = strtolower(trim($_POST['usuario']));
    $password = $_POST['password'];

    // Consulta con nombre incluido
    $stmt = $mysqli->prepare("SELECT id_cliente, usuario, contrasena, nombre FROM clientes WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id_cliente, $usuario_db, $clave_hash, $nombre);
        $stmt->fetch();

        if (password_verify($password, $clave_hash)) {
            $_SESSION['usuario'] = $usuario_db;
            $_SESSION['id_cliente'] = $id_cliente;
            $_SESSION['nombre'] = $nombre;
            header("Location: home.php");
            exit();
        } else {
            $validation_message = '<div class="alert alert-danger">Contraseña incorrecta.</div>';
        }
    } else {
        $validation_message = '<div class="alert alert-warning">Usuario no registrado.</div>';
    }

    $stmt->close();
    $mysqli->close();
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
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h2>Iniciar Sesión</h2>
                </div>
                <div class="card-body">
                    <form id="loginForm" method="POST" action="">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-outline-primary">Iniciar Sesión</button>
                    </form>
                    <a href="register.php" class="btn btn-outline-secondary mt-2">Registrarse</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>