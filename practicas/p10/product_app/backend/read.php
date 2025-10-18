<?php
include_once __DIR__.'/database.php';

$data = array();
// SE CAMBIA PARA ACEPTAR BÚSQUEDA POR TEXTO
if( isset($_POST['search']) ) {
    $search = $_POST['search'];
    
    // SE MODIFICA LA QUERY PARA BUSCAR EN MÚLTIPLES CAMPOS
    $sql = "SELECT * FROM productos WHERE 
            (nombre LIKE '%{$search}%' OR 
             marca LIKE '%{$search}%' OR 
             detalles LIKE '%{$search}%') AND
            eliminado = 0";
    
    if ( $result = $conexion->query($sql) ) {
        // SE CREA UN ARREGLO PARA MÚLTIPLES RESULTADOS
        $data = array();
        while( $row = $result->fetch_array(MYSQLI_ASSOC) ) {
            $data[] = $row;
        }
        $result->free();
    } else {
        die('Query Error: '.mysqli_error($conexion));
    }
    $conexion->close();
} 

echo json_encode($data, JSON_PRETTY_PRINT);
?>