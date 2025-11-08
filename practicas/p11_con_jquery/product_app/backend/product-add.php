<?php
include_once __DIR__.'/database.php';

// SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
$data = array(
    'status'  => 'error',
    'message' => 'Error desconocido'
);

// VERIFICAR SI SE RECIBIERON DATOS POR POST
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // LEER EL JSON DEL BODY
    $input = file_get_contents('php://input');
    $jsonOBJ = json_decode($input);
    
    if($jsonOBJ) {
        // VALIDACIÓN MEJORADA - OBTENER DATOS DEL JSON
        $nombre = isset($jsonOBJ->nombre) ? mysqli_real_escape_string($conexion, trim($jsonOBJ->nombre)) : '';
        $marca = isset($jsonOBJ->marca) ? mysqli_real_escape_string($conexion, trim($jsonOBJ->marca)) : '';
        $modelo = isset($jsonOBJ->modelo) ? mysqli_real_escape_string($conexion, trim($jsonOBJ->modelo)) : '';
        $precio = isset($jsonOBJ->precio) ? floatval($jsonOBJ->precio) : 0;
        $unidades = isset($jsonOBJ->unidades) ? intval($jsonOBJ->unidades) : 0;
        $detalles = isset($jsonOBJ->detalles) ? mysqli_real_escape_string($conexion, trim($jsonOBJ->detalles)) : '';
        $imagen = isset($jsonOBJ->imagen) ? mysqli_real_escape_string($conexion, trim($jsonOBJ->imagen)) : 'img/default.png';
        $id = isset($jsonOBJ->id) ? intval($jsonOBJ->id) : 0;

        // VALIDAR CAMPOS REQUERIDOS
        if(empty($nombre)) {
            $data['message'] = 'El nombre del producto es requerido';
        } elseif(empty($marca)) {
            $data['message'] = 'La marca es requerida';
        } elseif(empty($modelo)) {
            $data['message'] = 'El modelo es requerido';
        } elseif($precio <= 0) {
            $data['message'] = 'El precio debe ser mayor a 0';
        } elseif($unidades < 0) {
            $data['message'] = 'Las unidades deben ser un número no negativo';
        } else {
            // VERIFICAR SI ES UNA ACTUALIZACIÓN O UNA INSERCIÓN
            if($id > 0) {
                // MODO ACTUALIZACIÓN
                $sqlCheck = "SELECT id FROM productos WHERE nombre = '$nombre' AND id != $id AND eliminado = 0";
                $resultCheck = $conexion->query($sqlCheck);
                
                if($resultCheck && $resultCheck->num_rows == 0) {
                    $sql = "UPDATE productos SET 
                            nombre = '$nombre',
                            marca = '$marca',
                            modelo = '$modelo', 
                            precio = $precio,
                            detalles = '$detalles',
                            unidades = $unidades,
                            imagen = '$imagen'
                            WHERE id = $id AND eliminado = 0";
                    
                    if($conexion->query($sql)) {
                        $data['status'] = "success";
                        $data['message'] = "Producto actualizado correctamente";
                    } else {
                        $data['message'] = "ERROR: No se ejecutó la actualización. " . mysqli_error($conexion);
                    }
                } else {
                    $data['message'] = "Ya existe otro producto con ese nombre";
                }
                
                if($resultCheck) {
                    $resultCheck->free();
                }
                
            } else {
                // MODO INSERCIÓN
                $sqlCheck = "SELECT id FROM productos WHERE nombre = '$nombre' AND eliminado = 0";
                $resultCheck = $conexion->query($sqlCheck);
                
                if($resultCheck && $resultCheck->num_rows == 0) {
                    $conexion->set_charset("utf8");
                    $sql = "INSERT INTO productos VALUES (null, '$nombre', '$marca', '$modelo', $precio, '$detalles', $unidades, '$imagen', 0)";
                    
                    if($conexion->query($sql)) {
                        $data['status'] = "success";
                        $data['message'] = "Producto agregado correctamente";
                    } else {
                        $data['message'] = "ERROR: No se ejecutó la inserción. " . mysqli_error($conexion);
                    }
                } else {
                    $data['message'] = "Ya existe un producto con ese nombre";
                }
                
                if($resultCheck) {
                    $resultCheck->free();
                }
            }
        }
    } else {
        $data['message'] = "Error: Datos JSON no válidos";
    }

    $conexion->close();
} else {
    $data['message'] = "Método no permitido. Use POST.";
}

// SE HACE LA CONVERSIÓN DE ARRAY A JSON
header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);
?>