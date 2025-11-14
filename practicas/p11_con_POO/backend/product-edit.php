<?php
require_once __DIR__ . '/myapi/Products.php';

header('Content-Type: application/json');

use TECWEB\MYAPI\Products;

$producto = file_get_contents('php://input');

if(!empty($producto)) {
    try {
        $jsonOBJ = json_decode($producto);
        
        if (!$jsonOBJ) {
            throw new Exception('JSON inválido');
        }
        
        $products = new Products('marketzone', 'root', 'Rudytexcuc@no');
        
        // Actualizar producto
        $products->edit(
            $jsonOBJ->id,
            $jsonOBJ->nombre,
            $jsonOBJ->marca,
            $jsonOBJ->modelo,
            $jsonOBJ->precio,
            $jsonOBJ->descripcion,
            $jsonOBJ->unidades,
            $jsonOBJ->imagen
        );
        
        $result = json_decode($products->getData(), true);
        
        if ($result['success']) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Producto actualizado correctamente'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No se pudo actualizar el producto'
            ]);
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Datos vacíos'
    ]);
}
?>