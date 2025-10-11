<?php
// Recibir datos por GET
$id = $_GET['id'] ?? '';
$nombre = $_GET['nombre'] ?? '';
$marca = $_GET['marca'] ?? '';
$modelo = $_GET['modelo'] ?? '';
$precio = $_GET['precio'] ?? '';
$detalles = $_GET['detalles'] ?? '';
$unidades = $_GET['unidades'] ?? '';
$imagen = $_GET['imagen'] ?? '';

// Si no hay ID, es un producto nuevo
$esNuevo = empty($id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $esNuevo ? 'Registro' : 'Edición' ?> de Productos</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f0f0f0; }
        .container { background: white; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto; }
        h1 { color: #333; text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input, textarea, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .submit-btn { 
            background: #28a745; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            margin-right: 10px;
        }
        .cancel-btn { 
            background: #6c757d; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            text-decoration: none;
            display: inline-block;
        }
        .required { color: red; }
        .info-box {
            background: #e7f3ff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= $esNuevo ? 'Registro de Nuevo Producto' : 'Editar Producto (ID: ' . $id . ')' ?></h1>
        
        <?php if (!$esNuevo): ?>
        <div class="info-box">
            <strong>📝 Modo Edición:</strong> Estás editando el producto "<?= htmlspecialchars($nombre) ?>"
        </div>
        <?php endif; ?>
        
        <form action="<?= $esNuevo ? 'set_productov2.php' : 'update_producto.php' ?>" method="post">
            <?php if (!$esNuevo): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="nombre">Nombre <span class="required">*</span></label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required>
            </div>

            <div class="form-group">
                <label for="marca">Marca <span class="required">*</span></label>
                <input type="text" id="marca" name="marca" value="<?= htmlspecialchars($marca) ?>" required>
            </div>

            <div class="form-group">
                <label for="modelo">Modelo <span class="required">*</span></label>
                <input type="text" id="modelo" name="modelo" value="<?= htmlspecialchars($modelo) ?>" required>
            </div>

            <div class="form-group">
                <label for="precio">Precio <span class="required">*</span></label>
                <input type="number" id="precio" name="precio" step="0.01" min="100" value="<?= htmlspecialchars($precio) ?>" required>
            </div>

            <div class="form-group">
                <label for="detalles">Detalles</label>
                <textarea id="detalles" name="detalles"><?= htmlspecialchars($detalles) ?></textarea>
            </div>

            <div class="form-group">
                <label for="unidades">Unidades <span class="required">*</span></label>
                <input type="number" id="unidades" name="unidades" min="0" value="<?= htmlspecialchars($unidades) ?>" required>
            </div>

            <div class="form-group">
                <label for="imagen">Imagen</label>
                <input type="text" id="imagen" name="imagen" value="<?= htmlspecialchars($imagen) ?>" placeholder="nombre_imagen.jpg">
                <small>Ejemplo: laptop.jpg (se buscará en la carpeta img/)</small>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" class="submit-btn">
                    <?= $esNuevo ? 'Registrar Producto' : 'Actualizar Producto' ?>
                </button>
                <a href="javascript:history.back()" class="cancel-btn">Cancelar</a>
            </div>
        </form>
        
        <div style="margin-top: 20px; text-align: center;">
            <a href="get_productos_vigentes_v2.php">Ver Productos Vigentes</a> | 
            <a href="get_productos_xhtml_v2.php?tope=10">Ver Productos XHTML</a>
        </div>
    </div>
</body>
</html>