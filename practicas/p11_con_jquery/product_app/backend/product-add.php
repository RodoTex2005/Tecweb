<?php
include_once __DIR__.'/database.php';

// SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
$data = array(
    'status'  => 'error',
    'message' => 'Error desconocido'
);

// VERIFICAR SI SE RECIBIERON DATOS POR POST
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // VALIDACIÓN MEJORADA - OBTENER DATOS DEL FORMULARIO
    $nombre = isset($_POST['nombre']) ? mysqli_real_escape_string($conexion, $_POST['nombre']) : '';
    $marca = isset($_POST['marca']) ? mysqli_real_escape_string($conexion, $_POST['marca']) : '';
    $modelo = isset($_POST['modelo']) ? mysqli_real_escape_string($conexion, $_POST['modelo']) : '';
    $precio = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;
    $unidades = isset($_POST['unidades']) ? intval($_POST['unidades']) : 0;
    $detalles = isset($_POST['detalles']) ? mysqli_real_escape_string($conexion, $_POST['detalles']) : '';
    $imagen = isset($_POST['imagen']) ? mysqli_real_escape_string($conexion, $_POST['imagen']) : 'img/default.png';
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    // VALIDAR CAMPOS REQUERIDOS
    if(empty($nombre) || empty($marca) || empty($modelo) || $precio <= 0 || $unidades < 0) {
        $data['message'] = "Todos los campos requeridos deben ser válidos";
    } else {
        // VERIFICAR SI ES UNA ACTUALIZACIÓN O UNA INSERCIÓN
        if($id > 0) {
            // MODO ACTUALIZACIÓN
            $sqlCheck = "SELECT id FROM productos WHERE nombre = '{$nombre}' AND id != {$id} AND eliminado = 0";
            $resultCheck = $conexion->query($sqlCheck);
            
            if($resultCheck->num_rows == 0) {
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
    }

    $conexion->close();
} else {
    $data['message'] = "Método no permitido. Use POST.";
}

// SE HACE LA CONVERSIÓN DE ARRAY A JSON
echo json_encode($data, JSON_PRETTY_PRINT);
?>