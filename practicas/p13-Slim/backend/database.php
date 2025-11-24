<?php
$conexion = @mysqli_connect(
    'localhost',
    'root',
    'Rudytexcuc@no',
    'marketzone'
);

if(!$conexion) {
    die('¡Base de datos NO conectada!');
}
?>