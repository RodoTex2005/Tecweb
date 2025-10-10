<?php
/** SE CREA EL OBJETO DE CONEXION */
@$link = new mysqli('localhost', 'root', 'Rudytexcuc@no', 'marketzone');	

/** comprobar la conexión */
if ($link->connect_errno) 
{    
    die('Falló la conexión: '.$link->connect_error.'<br/>');
}

// Recibir datos del formulario
$nombre = $link->real_escape_string($_POST['nombre'] ?? '');
$marca  = $link->real_escape_string($_POST['marca'] ?? '');
$modelo = $link->real_escape_string($_POST['modelo'] ?? '');
$precio = floatval($_POST['precio'] ?? 0);
$detalles = $link->real_escape_string($_POST['detalles'] ?? '');
$unidades = intval($_POST['unidades'] ?? 0);
$imagen   = $link->real_escape_string($_POST['imagen'] ?? 'img/imagen.png');

// ==================== VALIDACIONES ====================
$errores = [];

// Validar campos obligatorios
if (empty($nombre) || empty($marca) || empty($modelo)) {
    $errores[] = "Los campos nombre, marca y modelo son obligatorios.";
}

// Validar si el producto ya existe - USANDO LAS COLUMNAS QUE SÍ TIENES
$sql_check = "SELECT COUNT(*) as total FROM productos WHERE nombre = '$nombre'";
$result_check = $link->query($sql_check);

if ($result_check) {
    $row = $result_check->fetch_assoc();
    if ($row['total'] > 0) {
        $errores[] = "Ya existe un producto con el mismo nombre.";
    }
}

// Validar precio y unidades
if ($precio <= 0) {
    $errores[] = "El precio debe ser mayor a 0.";
}
if ($unidades < 0) {
    $errores[] = "Las unidades no pueden ser negativas.";
}

// Si hay errores, mostrarlos
if (!empty($errores)) {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Error</title>
        <style>body {font-family: Arial; margin: 20px; background: #f8d7da; color: #721c24;}</style>
    </head>
    <body>
        <h1>Error al registrar producto</h1>
        <ul>';
    foreach ($errores as $error) {
        echo "<li>$error</li>";
    }
    echo '</ul><a href="formulario_productos.html">Volver</a></body></html>';
    $link->close();
    exit;
}


$sql = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
        VALUES ('{$nombre}', '{$marca}', '{$modelo}', {$precio}, '{$detalles}', {$unidades}, '{$imagen}', 0)";

if ($link->query($sql)) 
{
    echo '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Éxito</title>
        <style>
            body {font-family: Arial; margin: 20px; background: #d4edda; color: #155724;}
            .resumen {background: white; padding: 15px; border-radius: 5px; margin: 15px 0;}
            .warning {background: #fff3cd; color: #856404; padding: 10px; border-radius: 4px; margin: 10px 0;}
        </style>
    </head>
    <body>
        <h1>✅ Producto insertado exitosamente</h1>
        <p><strong>ID:</strong> '.$link->insert_id.'</p>
        
        <div class="warning">
            <strong>Nota:</strong> Faltan columnas en la tabla. Ejecuta el SQL de modificación.
        </div>
        
        <div class="resumen">
            <h3>Resumen del producto:</h3>
            <p><strong>Nombre:</strong> '.$nombre.'</p>
            <p><strong>Marca:</strong> '.$marca.' <em>(no guardada - columna faltante)</em></p>
            <p><strong>Modelo:</strong> '.$modelo.' <em>(no guardada - columna faltante)</em></p>
            <p><strong>Precio:</strong> $'.number_format($precio,2).'</p>
            <p><strong>Detalles:</strong> '.$detalles.' <em>(no guardado - columna faltante)</em></p>
            <p><strong>Unidades:</strong> '.$unidades.'</p>
            <p><strong>Estado eliminado:</strong> 0 <em>(no guardado - columna faltante)</em></p>
        </div>
        <a href="formulario_productos.html">Registrar otro producto</a>
    </body>
    </html>';
}
else
{
    echo '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Error</title>
        <style>body {font-family: Arial; margin: 20px; background: #f8d7da; color: #721c24;}</style>
    </head>
    <body>
        <h1>Error: El producto no pudo ser insertado</h1>
        <p>Error: '.$link->error.'</p>
        <a href="formulario_productos.html">Volver</a>
    </body>
    </html>';
}

$link->close();
?>