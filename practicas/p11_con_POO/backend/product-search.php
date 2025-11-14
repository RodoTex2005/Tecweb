<?php
require_once __DIR__ . '/myapi/Products.php';

use TECWEB\MYAPI\Products;

try {
    $products = new Products('marketzone', 'root', 'Rudytexcuc@no');
    
    if(isset($_GET['search']) && !empty($_GET['search'])) {
        $search = trim($_GET['search']);
        $exact = isset($_GET['exact']) ? $_GET['exact'] : false;
        
        if ($exact) {
            // Para validación de nombre exacto
            $products->singleByName($search);
        } else {
            // Búsqueda normal (incluye búsqueda por ID)
            $products->search($search);
        }
        
        $data = json_decode($products->getData(), true);
        
        // Si no hay resultados, devolver array vacío
        if (empty($data)) {
            echo '[]';
        } else {
            echo json_encode($data);
        }
    } else {
        echo '[]';
    }
    
} catch (Exception $e) {
    error_log("Error en product-search.php: " . $e->getMessage());
    echo json_encode(['error' => 'Error en la búsqueda: ' . $e->getMessage()]);
}
?>