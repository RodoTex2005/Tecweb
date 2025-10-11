<?php
// update_producto.php - Versión mejorada para ejercicio 4
$link = new mysqli('localhost', 'root', 'Rudytexcuc@no', 'marketzone');

if ($link->connect_errno) {
    die('Falló la conexión: '.$link->connect_error);
}

// Obtener datos del formulario
$id = $link->real_escape_string($_POST['id']);
$nombre = $link->real_escape_string($_POST['nombre']);
$marca = $link->real_escape_string($_POST['marca']);
$modelo = $link->real_escape_string($_POST['modelo']);
$precio = $link->real_escape_string($_POST['precio']);
$detalles = $link->real_escape_string($_POST['detalles']);
$unidades = $link->real_escape_string($_POST['unidades']);
$imagen = $link->real_escape_string($_POST['imagen']);

// Si no se proporciona imagen, usar una por defecto
if(empty($imagen)) {
    $imagen = "imagen.png";
}

// CONSULTA UPDATE para modificar el producto
$sql = "UPDATE productos 
        SET nombre = '$nombre', 
            marca = '$marca', 
            modelo = '$modelo', 
            precio = $precio, 
            detalles = '$detalles', 
            unidades = $unidades, 
            imagen = '$imagen'
        WHERE id = $id";

if($link->query($sql)){
    echo "<div style='max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px;'>";
    echo "<h1 style='color: #28a745; text-align: center;'>✅ Producto Actualizado Correctamente</h1>";
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>Datos Actualizados del Producto (ID: $id):</h3>";
    echo "<p><strong>Nombre:</strong> " . htmlspecialchars($nombre) . "</p>";
    echo "<p><strong>Marca:</strong> " . htmlspecialchars($marca) . "</p>";
    echo "<p><strong>Modelo:</strong> " . htmlspecialchars($modelo) . "</p>";
    echo "<p><strong>Precio:</strong> $" . number_format($precio, 2) . "</p>";
    echo "<p><strong>Unidades:</strong> " . htmlspecialchars($unidades) . "</p>";
    echo "<p><strong>Imagen:</strong> " . htmlspecialchars($imagen) . "</p>";
    echo "</div>";
} else {
    echo "<div style='max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px;'>";
    echo "<h1 style='color: #dc3545; text-align: center;'>❌ ERROR: No se pudo actualizar el producto</h1>";
    echo "<p style='color: red;'>Error: " . $link->error . "</p>";
}

// Cierra la conexión
$link->close();
?>

<!-- HIPERVÍNCULOS REQUERIDOS PARA EL EJERCICIO 4 -->
<div style="text-align: center; margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 5px;">
    <h3>Opciones de Navegación:</h3>
    <a href="get_productos_xhtml_v2.php?tope=10" 
       style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 0 10px;">
       📋 Ver Productos XHTML
    </a>
    <a href="get_productos_vigentes_v2.php" 
       style="background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 0 10px;">
       🛍️ Ver Productos Vigentes
    </a>
    <br><br>
    <a href="formulario_productos_v3.php" 
       style="background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 0 10px;">
       📝 Editar Otro Producto
    </a>
</div>

</div>