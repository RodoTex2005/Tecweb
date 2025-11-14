<?php
require_once __DIR__ . '/myapi/Products.php';

header('Content-Type: application/json');

use TECWEB\MYAPI\Products;

if(isset($_GET['id'])) {
    try {
        $id = $_GET['id'];
        $products = new Products('marketzone', 'root', 'Rudytexcuc@no');
        $products->delete($id);
        
        $result = json_decode($products->getData(), true);
        
        if ($result['success']) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Producto eliminado correctamente'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No se pudo eliminar el producto'
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
        'message' => 'ID no proporcionado'
    ]);
}
?>