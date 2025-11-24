<?php
    require_once __DIR__.'/vendor/autoload.php';
    
    use TECWEB\MYAPI\Read\ProductReader;
    
    $productos = new ProductReader('marketzone');
    echo $productos->search( $_GET['search'] );
?>