<?php
    require_once __DIR__.'/vendor/autoload.php';
    
    use TECWEB\MYAPI\Delete\ProductDeleter;
    
    $productos = new ProductDeleter('marketzone');
    echo $productos->delete( $_POST['id'] );
?>