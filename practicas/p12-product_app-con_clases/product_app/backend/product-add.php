<?php
    require_once __DIR__.'/vendor/autoload.php';
    
    use TECWEB\MYAPI\Create\ProductCreator;
    
    $productos = new ProductCreator('marketzone');
    echo $productos->add( json_decode( json_encode($_POST) ) );
?>