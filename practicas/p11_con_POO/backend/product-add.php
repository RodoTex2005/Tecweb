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
        
        // Verificar si ya existe un producto con ese nombre
        $products->singleByName($jsonOBJ->nombre);
        $existingProduct = json_decode($products->getData(), true);
        
        if (empty($existingProduct)) {
            // Agregar nuevo producto
            $products->add(
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
                    'message' => 'Producto agregado correctamente'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se pudo agregar el producto'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Ya existe un producto con ese nombre'
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