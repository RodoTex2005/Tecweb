<?php
    require_once __DIR__.'/vendor/autoload.php';
    
    use TECWEB\MYAPI\Create\ProductCreator;
    
    $productos = new ProductCreator('marketzone');
    // Cambiar: usar $_POST directamente en lugar de JSON
    echo $productos->add( (object)$_POST );
?>