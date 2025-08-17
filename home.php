<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: ./index.php");
    exit();
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
    <div class="site">
        <?php include 'include/navBar.php' ?>
        <?php 
        echo "<h1 class='text-center mt-4'>Bienvenido, " . htmlspecialchars($_SESSION['nombre']) . "</h1>";
        ?>
    </div>
</body>

</html>