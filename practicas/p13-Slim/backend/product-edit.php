<?php
    require_once __DIR__.'/vendor/autoload.php';
    
    use TECWEB\MYAPI\Update\ProductUpdater;
    
    // DEBUG: Ver qué datos están llegando
    error_log("=== DEBUG product-edit.php ===");
    error_log("Datos POST: " . print_r($_POST, true));
    
    // Verificar si los campos requeridos están presentes
    $required = ['id', 'nombre', 'precio', 'unidades', 'modelo', 'marca', 'descripcion'];
    foreach ($required as $field) {
        error_log("Campo $field: " . (isset($_POST[$field]) ? $_POST[$field] : "NO EXISTE"));
    }
    
    $productos = new ProductUpdater('marketzone');
    
    // Convertir $_POST a objeto
    $postData = new stdClass();
    foreach ($_POST as $key => $value) {
        $postData->$key = $value;
    }
    
    error_log("Datos enviados a ProductUpdater: " . print_r($postData, true));
    
    $result = $productos->edit($postData);
    error_log("Resultado: " . $result);
    
    echo $result;
?>