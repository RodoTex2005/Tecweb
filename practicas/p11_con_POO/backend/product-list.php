<?php
require_once __DIR__ . '/myapi/Products.php';

header('Content-Type: application/json');

use TECWEB\MYAPI\Products;

try {
    // Probar con contraseña explícita
    $products = new Products('marketzone', 'root', 'Rudytexcuc@no');
    
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    if (!empty($search)) {
        $products->search($search);
    } else {
        $products->list();
    }
    
    echo $products->getData();
    
} catch (Exception $e) {
    error_log("Error en product-list.php: " . $e->getMessage());
    echo json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
}
?>