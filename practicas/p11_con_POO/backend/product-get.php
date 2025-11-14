<?php
require_once __DIR__ . '/myapi/Products.php';

header('Content-Type: application/json');

use TECWEB\MYAPI\Products;

if(isset($_GET['id'])) {
    try {
        $id = $_GET['id'];
        $products = new Products('marketzone', 'root', 'Rudytexcuc@no');
        $products->getById($id);
        echo $products->getData();
        
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'ID no proporcionado']);
}
?>