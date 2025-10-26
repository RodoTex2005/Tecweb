<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);
ob_start();

include_once __DIR__.'/database.php';

$producto = file_get_contents('php://input');
if (empty($producto)) {
    ob_clean();
    echo json_encode([
        "status" => "error",
        "message" => "No se recibió ningún dato"
    ]);
    exit;
}

$jsonOBJ = json_decode($producto);
if (json_last_error() !== JSON_ERROR_NONE) {
    ob_clean();
    echo json_encode([
        "status" => "error",
        "message" => "JSON inválido: " . json_last_error_msg()
    ]);
    exit;
}

$data = [
    'status'  => 'error',
    'message' => 'Ya existe un producto con ese nombre'
];

if (!isset($conexion)) {
    ob_clean();
    echo json_encode([
        "status" => "error",
        "message" => "Error de conexión a la base de datos"
    ]);
    exit;
}

$conexion->set_charset("utf8");
$sql = "SELECT * FROM productos WHERE nombre = '{$jsonOBJ->nombre}' AND eliminado = 0";
$result = $conexion->query($sql);

if ($result && $result->num_rows == 0) {
    $sql = "INSERT INTO productos VALUES (null, '{$jsonOBJ->nombre}', '{$jsonOBJ->marca}', '{$jsonOBJ->modelo}', {$jsonOBJ->precio}, '{$jsonOBJ->detalles}', {$jsonOBJ->unidades}, '{$jsonOBJ->imagen}', 0)";
    if ($conexion->query($sql)) {
        $data['status'] = "success";
        $data['message'] = "Producto agregado";
    } else {
        $data['message'] = "ERROR al insertar: " . $conexion->error;
    }
}

if ($result) $result->free();
$conexion->close();

ob_clean();
echo json_encode($data, JSON_PRETTY_PRINT);
?>
