<?php 
// Control de conexion de base de datos
// Activar reporte de errores
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$host = "localhost";
$usuario = "root";
$contrasenia = 'root';
$base_datos = 'finanzas_seguras';

// Crear conexion
$mysqli = new mysqli($host,$usuario,$contrasenia,$base_datos);

$mysqli->set_charset('utf8mb4');
?>