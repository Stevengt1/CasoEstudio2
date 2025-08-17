<?php
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
    <div class="site">
        <header class="header">
            <nav class="navbar navbar-expand-lg bg-primary-set">
                <div class="container-fluid">
                    <a class="navbar-brand" href="home.php">Finanzas Seguras S.A.</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="home.php">Inicio</a></li>
                            <li class="nav-item"><a class="nav-link" href="actualizarDatos.php">Actualizar Datos</a></li>
                            <li class="nav-item"><a class="nav-link" href="reportarPagos.php">Reportar Pagos</a></li>
                        </ul>
                        <form action="include/logout.php" method="POST" class="d-flex ms-auto">
                            <button class="btn btn-outline-danger" type="submit">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            </nav>
        </header>
    </div>
</body>

</html>