<?php
namespace TECWEB\MYAPI;

require_once __DIR__ . '/myapi/Products.php';

$prodObj = new Products('marketzone');

if (isset($_POST['id'])) {
    $prodObj->getById($_POST['id']);
    echo $prodObj->getData();
} else {
    echo json_encode(['success' => false, 'message' => 'ID no proporcionado'], JSON_PRETTY_PRINT);
}
?>