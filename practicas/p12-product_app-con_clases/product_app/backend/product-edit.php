<?php
    require_once __DIR__.'/vendor/autoload.php';
    
    use TECWEB\MYAPI\Update\ProductUpdater;
    
    $productos = new ProductUpdater('marketzone');
    echo $productos->edit( json_decode( json_encode($_POST) ) );
?>