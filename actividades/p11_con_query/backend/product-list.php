<?php
include_once __DIR__.'/database.php';

$data = array();

// CAMBIO: productos → libros
if ( $result = $conexion->query("SELECT * FROM libros WHERE eliminado = 0") ) {
    $rows = $result->fetch_all(MYSQLI_ASSOC);

    if(!is_null($rows)) {
        foreach($rows as $num => $row) {
            foreach($row as $key => $value) {
                $data[$num][$key] = utf8_encode($value);
            }
        }
    }
    $result->free();
} else {
    die('Query Error: '.mysqli_error($conexion));
}
$conexion->close();

echo json_encode($data, JSON_PRETTY_PRINT);
?>