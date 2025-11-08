<?php
include_once __DIR__.'/database.php';

$data = array(
    'status'  => 'error',
    'message' => 'Error desconocido'
);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $input = file_get_contents('php://input');
    $jsonOBJ = json_decode($input);
    
    if($jsonOBJ) {
        $nombre = isset($jsonOBJ->nombre) ? mysqli_real_escape_string($conexion, trim($jsonOBJ->nombre)) : '';
        $marca = isset($jsonOBJ->marca) ? mysqli_real_escape_string($conexion, trim($jsonOBJ->marca)) : '';
        $modelo = isset($jsonOBJ->modelo) ? mysqli_real_escape_string($conexion, trim($jsonOBJ->modelo)) : '';
        $precio = isset($jsonOBJ->precio) ? floatval($jsonOBJ->precio) : 0;
        $unidades = isset($jsonOBJ->unidades) ? intval($jsonOBJ->unidades) : 0;
        $detalles = isset($jsonOBJ->detalles) ? mysqli_real_escape_string($conexion, trim($jsonOBJ->detalles)) : '';
        $imagen = isset($jsonOBJ->imagen) ? mysqli_real_escape_string($conexion, trim($jsonOBJ->imagen)) : 'img/default.png';
        $id = isset($jsonOBJ->id) ? intval($jsonOBJ->id) : 0;

        if(empty($nombre)) {
            $data['message'] = 'El nombre del libro es requerido';
        } elseif(empty($marca)) {
            $data['message'] = 'La editorial es requerida';
        } elseif(empty($modelo)) {
            $data['message'] = 'El ISBN es requerido';
        } elseif($precio <= 0) {
            $data['message'] = 'El precio debe ser mayor a 0';
        } elseif($unidades < 0) {
            $data['message'] = 'Las unidades deben ser un número no negativo';
        } else {
            // CAMBIO: productos → libros
            $sqlCheck = "SELECT id FROM libros WHERE nombre = '$nombre' AND eliminado = 0";
            if($id > 0) {
                $sqlCheck .= " AND id != $id";
            }
            
            $resultCheck = $conexion->query($sqlCheck);
            
            if($resultCheck && $resultCheck->num_rows == 0) {
                if($id > 0) {
                    // CAMBIO: productos → libros
                    $sql = "UPDATE libros SET 
                            nombre = '$nombre',
                            marca = '$marca',
                            modelo = '$modelo', 
                            precio = $precio,
                            detalles = '$detalles',
                            unidades = $unidades,
                            imagen = '$imagen'
                            WHERE id = $id AND eliminado = 0";
                } else {
                    $conexion->set_charset("utf8");
                    // CAMBIO: productos → libros
                    $sql = "INSERT INTO libros VALUES (null, '$nombre', '$marca', '$modelo', $precio, '$detalles', $unidades, '$imagen', 0)";
                }
                
                if($conexion->query($sql)) {
                    $data['status'] = "success";
                    $data['message'] = $id > 0 ? "Libro actualizado correctamente" : "Libro agregado correctamente";
                } else {
                    $data['message'] = "Error en la base de datos: " . mysqli_error($conexion);
                }
            } else {
                $data['message'] = "Ya existe un libro con ese nombre";
            }
            
            if($resultCheck) {
                $resultCheck->free();
            }
        }
    } else {
        $data['message'] = "Error: Datos JSON no válidos";
    }

    $conexion->close();
} else {
    $data['message'] = "Método no permitido. Use POST.";
}

header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);
?>