<?php
include_once __DIR__.'/database.php';

// SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
$producto = file_get_contents('php://input');
if(!empty($producto)) {
    $jsonOBJ = json_decode($producto);
    
    // VALIDAR SI EL PRODUCTO YA EXISTE
    $nombre = $jsonOBJ->nombre;
    $marca = $jsonOBJ->marca;
    $modelo = $jsonOBJ->modelo;
    
    $consulta = "SELECT id FROM productos WHERE 
                ((nombre = '{$nombre}' AND marca = '{$marca}') OR 
                 (marca = '{$marca}' AND modelo = '{$modelo}')) 
                AND eliminado = 0";
    
    if($result = $conexion->query($consulta)) {
        if($result->num_rows > 0) {
            echo "ERROR: Ya existe un producto con ese nombre y marca, o marca y modelo";
            $conexion->close();
            exit;
        }
        $result->free();
    }
    
    // INSERCIÓN
    $precio = floatval($jsonOBJ->precio);
    $unidades = intval($jsonOBJ->unidades);
    $detalles = $jsonOBJ->detalles;
    $imagen = $jsonOBJ->imagen;
    
    $sql = "INSERT INTO productos (nombre, marca, modelo, precio, unidades, detalles, imagen, eliminado) 
            VALUES ('{$nombre}', '{$marca}', '{$modelo}', {$precio}, {$unidades}, '{$detalles}', '{$imagen}', 0)";
    
    if($conexion->query($sql)) {
        echo "ÉXITO: Producto insertado correctamente";
    } else {
        echo "ERROR: No se pudo insertar el producto - " . mysqli_error($conexion);
    }
    
    $conexion->close();
} else {
    echo "ERROR: No se recibieron datos del producto";
}
?>