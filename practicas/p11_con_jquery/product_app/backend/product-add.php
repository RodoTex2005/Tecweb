<?php
include_once __DIR__.'/database.php';

// SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
$producto = file_get_contents('php://input');
$data = array(
    'status'  => 'error',
    'message' => 'Error desconocido'
);

if(!empty($producto)) {
    // SE TRANSFORMA EL STRING DEL JSON A OBJETO
    $jsonOBJ = json_decode($producto);
    
    // VALIDACIÓN MEJORADA
    $nombre = mysqli_real_escape_string($conexion, $jsonOBJ->nombre);
    $marca = mysqli_real_escape_string($conexion, $jsonOBJ->marca);
    $modelo = mysqli_real_escape_string($conexion, $jsonOBJ->modelo);
    
    // VERIFICAR SI ES UNA ACTUALIZACIÓN O UNA INSERCIÓN
    if(isset($jsonOBJ->id) && !empty($jsonOBJ->id)) {
        // MODO ACTUALIZACIÓN
        $id = intval($jsonOBJ->id);
        
        // VERIFICAR SI EXISTE OTRO PRODUCTO CON EL MISMO NOMBRE (EXCLUYENDO EL ACTUAL)
        $sqlCheck = "SELECT id FROM productos WHERE nombre = '{$nombre}' AND id != {$id} AND eliminado = 0";
        $resultCheck = $conexion->query($sqlCheck);
        
        if($resultCheck->num_rows == 0) {
            // ACTUALIZAR PRODUCTO
            $precio = floatval($jsonOBJ->precio);
            $unidades = intval($jsonOBJ->unidades);
            $detalles = mysqli_real_escape_string($conexion, $jsonOBJ->detalles);
            $imagen = mysqli_real_escape_string($conexion, $jsonOBJ->imagen);
            
            $sql = "UPDATE productos SET 
                    nombre = '{$nombre}',
                    marca = '{$marca}',
                    modelo = '{$modelo}', 
                    precio = {$precio},
                    detalles = '{$detalles}',
                    unidades = {$unidades},
                    imagen = '{$imagen}'
                    WHERE id = {$id} AND eliminado = 0";
            
            if($conexion->query($sql)) {
                $data['status'] = "success";
                $data['message'] = "Producto actualizado correctamente";
            } else {
                $data['message'] = "ERROR: No se ejecutó la actualización. " . mysqli_error($conexion);
            }
        } else {
            $data['message'] = "Ya existe otro producto con ese nombre";
        }
        
        $resultCheck->free();
        
    } else {
        // MODO INSERCIÓN
        $sqlCheck = "SELECT id FROM productos WHERE nombre = '{$nombre}' AND eliminado = 0";
        $resultCheck = $conexion->query($sqlCheck);
        
        if($resultCheck->num_rows == 0) {
            $conexion->set_charset("utf8");
            
            // INSERCIÓN CON DATOS VALIDADOS
            $precio = floatval($jsonOBJ->precio);
            $unidades = intval($jsonOBJ->unidades);
            $detalles = mysqli_real_escape_string($conexion, $jsonOBJ->detalles);
            $imagen = mysqli_real_escape_string($conexion, $jsonOBJ->imagen);
            
            $sql = "INSERT INTO productos VALUES (null, '{$nombre}', '{$marca}', '{$modelo}', {$precio}, '{$detalles}', {$unidades}, '{$imagen}', 0)";
            
            if($conexion->query($sql)) {
                $data['status'] = "success";
                $data['message'] = "Producto agregado correctamente";
            } else {
                $data['message'] = "ERROR: No se ejecutó la inserción. " . mysqli_error($conexion);
            }
        } else {
            $data['message'] = "Ya existe un producto con ese nombre";
        }
        
        $resultCheck->free();
    }

    $conexion->close();
} else {
    $data['message'] = "No se recibieron datos del producto";
}

// SE HACE LA CONVERSIÓN DE ARRAY A JSON
echo json_encode($data, JSON_PRETTY_PRINT);
?>