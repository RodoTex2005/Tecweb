<?php
// formulario_productos_v3.php - Ejercicio 4
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
    <title><?= $esNuevo ? 'Registro' : 'Edición' ?> de Productos - v3</title>
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
        .error { color: red; font-size: 12px; margin-top: 5px; }
        .char-count { font-size: 12px; color: #666; text-align: right; }
        .info-box {
            background: #e7f3ff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        .success { color: green; font-weight: bold; }
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
        
        <form action="<?= $esNuevo ? 'set_productov3.php' : 'update_producto.php' ?>" method="post" onsubmit="return validarFormulario()">
            <?php if (!$esNuevo): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <?php endif; ?>
            
            <!-- Nombre -->
            <div class="form-group">
                <label for="nombre">Nombre <span class="required">*</span></label>
                <input type="text" id="nombre" name="nombre" maxlength="100" 
                       value="<?= htmlspecialchars($nombre) ?>"
                       oninput="actualizarContador('nombre', 'contadorNombre', 100)">
                <div class="char-count"><span id="contadorNombre"><?= strlen($nombre) ?></span>/100 caracteres</div>
                <div id="errorNombre" class="error"></div>
            </div>

            <!-- Marca -->
            <div class="form-group">
                <label for="marca">Marca <span class="required">*</span></label>
                <select id="marca" name="marca">
                    <option value="">Selecciona una marca</option>
                    <option value="Sony" <?= $marca == 'Sony' ? 'selected' : '' ?>>Sony</option>
                    <option value="Samsung" <?= $marca == 'Samsung' ? 'selected' : '' ?>>Samsung</option>
                    <option value="Apple" <?= $marca == 'Apple' ? 'selected' : '' ?>>Apple</option>
                    <option value="LG" <?= $marca == 'LG' ? 'selected' : '' ?>>LG</option>
                    <option value="Xiaomi" <?= $marca == 'Xiaomi' ? 'selected' : '' ?>>Xiaomi</option>
                    <option value="HP" <?= $marca == 'HP' ? 'selected' : '' ?>>HP</option>
                    <option value="Dell" <?= $marca == 'Dell' ? 'selected' : '' ?>>Dell</option>
                </select>
                <div id="errorMarca" class="error"></div>
            </div>

            <!-- Modelo -->
            <div class="form-group">
                <label for="modelo">Modelo <span class="required">*</span></label>
                <input type="text" id="modelo" name="modelo" maxlength="25" 
                       value="<?= htmlspecialchars($modelo) ?>"
                       oninput="actualizarContador('modelo', 'contadorModelo', 25)">
                <div class="char-count"><span id="contadorModelo"><?= strlen($modelo) ?></span>/25 caracteres</div>
                <div id="errorModelo" class="error"></div>
            </div>

            <!-- Precio -->
            <div class="form-group">
                <label for="precio">Precio <span class="required">*</span></label>
                <input type="number" id="precio" name="precio" step="0.01" min="0" 
                       value="<?= htmlspecialchars($precio) ?>">
                <div id="errorPrecio" class="error"></div>
            </div>

            <!-- Detalles -->
            <div class="form-group">
                <label for="detalles">Detalles</label>
                <textarea id="detalles" name="detalles" maxlength="250" 
                          oninput="actualizarContador('detalles', 'contadorDetalles', 250)"><?= htmlspecialchars($detalles) ?></textarea>
                <div class="char-count"><span id="contadorDetalles"><?= strlen($detalles) ?></span>/250 caracteres</div>
            </div>

            <!-- Unidades -->
            <div class="form-group">
                <label for="unidades">Unidades <span class="required">*</span></label>
                <input type="number" id="unidades" name="unidades" min="0" 
                       value="<?= htmlspecialchars($unidades) ?>">
                <div id="errorUnidades" class="error"></div>
            </div>

            <!-- Imagen -->
            <div class="form-group">
                <label for="imagen">Imagen</label>
                <input type="text" id="imagen" name="imagen" 
                       value="<?= htmlspecialchars($imagen) ?>" 
                       placeholder="nombre_imagen.jpg">
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

    <script>
        // Función principal de validación
        function validarFormulario() {
            let valido = true;
            
            // Limpiar errores anteriores
            document.querySelectorAll('.error').forEach(error => error.textContent = '');
            
            // a. Validar Nombre (requerido, max 100 caracteres)
            const nombre = document.getElementById('nombre').value.trim();
            if (!nombre) {
                document.getElementById('errorNombre').textContent = 'El nombre es requerido';
                valido = false;
            } else if (nombre.length > 100) {
                document.getElementById('errorNombre').textContent = 'Máximo 100 caracteres permitidos';
                valido = false;
            }
            
            // b. Validar Marca (requerida, selección de lista)
            const marca = document.getElementById('marca').value;
            if (!marca) {
                document.getElementById('errorMarca').textContent = 'La marca es requerida';
                valido = false;
            }
            
            // c. Validar Modelo (requerido, alfanumérico, max 25 caracteres)
            const modelo = document.getElementById('modelo').value.trim();
            if (!modelo) {
                document.getElementById('errorModelo').textContent = 'El modelo es requerido';
                valido = false;
            } else if (!/^[A-Za-z0-9\s]+$/.test(modelo)) {
                document.getElementById('errorModelo').textContent = 'Solo se permiten caracteres alfanuméricos';
                valido = false;
            } else if (modelo.length > 25) {
                document.getElementById('errorModelo').textContent = 'Máximo 25 caracteres permitidos';
                valido = false;
            }
            
            // d. Validar Precio (requerido, mayor a 99.99)
            const precio = parseFloat(document.getElementById('precio').value);
            if (!precio || isNaN(precio)) {
                document.getElementById('errorPrecio').textContent = 'El precio es requerido';
                valido = false;
            } else if (precio <= 99.99) {
                document.getElementById('errorPrecio').textContent = 'El precio debe ser mayor a 99.99';
                valido = false;
            }
            
            // f. Validar Unidades (requerido, mayor o igual a 0)
            const unidades = parseInt(document.getElementById('unidades').value);
            if (isNaN(unidades)) {
                document.getElementById('errorUnidades').textContent = 'Las unidades son requeridas';
                valido = false;
            } else if (unidades < 0) {
                document.getElementById('errorUnidades').textContent = 'Las unidades deben ser mayor o igual a 0';
                valido = false;
            }
            
            if (valido) {
                const accion = <?= $esNuevo ? "'registrar'" : "'actualizar'"; ?>;
                alert('✅ FORMULARIO VÁLIDO\n\nLos datos han pasado todas las validaciones.\nSe procederá a ' + accion + ' el producto en la base de datos.');
                return true;
            } else {
                alert('❌ ERRORES EN EL FORMULARIO\n\nPor favor corrige los campos marcados en rojo antes de enviar.');
                return false;
            }
        }
        
        // Función para actualizar contadores de caracteres
        function actualizarContador(campoId, contadorId, maximo) {
            const campo = document.getElementById(campoId);
            const contador = document.getElementById(contadorId);
            const longitud = campo.value.length;
            contador.textContent = longitud;
            
            if (longitud > maximo) {
                contador.style.color = 'red';
            } else if (longitud > maximo * 0.8) {
                contador.style.color = 'orange';
            } else {
                contador.style.color = '#666';
            }
        }
        
        // Inicializar contadores al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            actualizarContador('nombre', 'contadorNombre', 100);
            actualizarContador('modelo', 'contadorModelo', 25);
            actualizarContador('detalles', 'contadorDetalles', 250);
        });
    </script>
</body>
</html>