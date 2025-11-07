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
    $jsonData = json_decode($input);
    
    if($jsonData) {
        // VALIDACIÓN MEJORADA - OBTENER DATOS DEL JSON
        $nombre = isset($jsonData->nombre) ? mysqli_real_escape_string($conexion, $jsonData->nombre) : '';
        $marca = isset($jsonData->marca) ? mysqli_real_escape_string($conexion, $jsonData->marca) : '';
        $modelo = isset($jsonData->modelo) ? mysqli_real_escape_string($conexion, $jsonData->modelo) : '';
        $precio = isset($jsonData->precio) ? floatval($jsonData->precio) : 0;
        $unidades = isset($jsonData->unidades) ? intval($jsonData->unidades) : 0;
        $detalles = isset($jsonData->detalles) ? mysqli_real_escape_string($conexion, $jsonData->detalles) : '';
        $imagen = isset($jsonData->imagen) ? mysqli_real_escape_string($conexion, $jsonData->imagen) : 'img/default.png';
        $id = isset($jsonData->id) ? intval($jsonData->id) : 0;

    }
    
    // REALIZAR LA CONSULTA
    if($result = $conexion->query($sql)) {
        if($result->num_rows > 0) {
            $data['disponible'] = false;
        }
        $result->free();
    } else {
        $data['error'] = "Error en la consulta: " . mysqli_error($conexion);
    }
    
    $conexion->close();
} else {
    $data['error'] = "No se recibió el parámetro 'nombre'";
}

// SE HACE LA CONVERSIÓN DE ARRAY A JSON
echo json_encode($data, JSON_PRETTY_PRINT);
?>