<?php
$conexion = new mysqli("localhost", "root", "", "veterinaria");

if ($conexion->connect_errno) {
    die("Error en la conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>
