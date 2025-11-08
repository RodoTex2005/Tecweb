<?php
include_once __DIR__.'/database.php';

$data = array(
    'disponible' => true,
    'message' => 'Nombre disponible'
);

if(isset($_GET['nombre'])) {
    $nombre = mysqli_real_escape_string($conexion, trim($_GET['nombre']));
    
    if(empty($nombre)) {
        $data['disponible'] = false;
        $data['message'] = 'El nombre no puede estar vacío';
    } else {
        // CAMBIO: productos → libros
        $sql = "SELECT id FROM libros WHERE nombre = '$nombre' AND eliminado = 0";
        
        if(isset($_GET['excludeId']) && !empty($_GET['excludeId'])) {
            $excludeId = intval($_GET['excludeId']);
            $sql .= " AND id != $excludeId";
        }
        
        $result = $conexion->query($sql);
        
        if($result) {
            if($result->num_rows > 0) {
                $data['disponible'] = false;
                $data['message'] = 'Ya existe un libro con ese nombre';
            }
            $result->free();
        }
    }
} else {
    $data['disponible'] = false;
    $data['message'] = 'No se recibió el nombre para validar';
}

$conexion->close();

header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);
?>