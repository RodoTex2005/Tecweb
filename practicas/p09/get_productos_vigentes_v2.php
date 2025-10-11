<?php
@$link = new mysqli('localhost', 'root', 'Rudytexcuc@no', 'marketzone');	

if ($link->connect_errno) {
    die('Falló la conexión: '.$link->connect_error.'<br/>');
}

// Solo productos no eliminados (eliminado = 0)
$sql = "SELECT * FROM productos WHERE eliminado = 0";
$result = $link->query($sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos Vigentes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #28a745;
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #28a745;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .active {
            background-color: #d4edda;
            color: #155724;
        }
        .btn-editar {
            background-color: #007bff;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 12px;
        }
        .btn-editar:hover {
            background-color: #0056b3;
        }
        .summary {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        .no-products {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-size: 18px;
        }
        img {
            max-width: 80px;
            height: auto;
            border-radius: 4px;
        }
    </style>
    <script>
    function editarProducto(id) {
        var row = document.getElementById('row-' + id);
        var cells = row.querySelectorAll('.row-data');
        
        var nombre = cells[0].innerText;
        var marca = cells[1].innerText;
        var modelo = cells[2].innerText;
        var precio = cells[3].innerText.replace('$', '');
        var detalles = cells[4].innerText;
        var unidades = cells[5].innerText;
        var imagen = cells[6].querySelector('img') ? cells[6].querySelector('img').getAttribute('src') : '';
        
        // Quitar "img/" del path si existe
        if (imagen.includes('img/')) {
            imagen = imagen.replace('img/', '');
        }
        
        // Crear URL de forma segura
        var params = new URLSearchParams();
        params.append('id', id);
        params.append('nombre', nombre);
        params.append('marca', marca);
        params.append('modelo', modelo);
        params.append('precio', precio);
        params.append('detalles', detalles);
        params.append('unidades', unidades);
        params.append('imagen', imagen);
        
        window.location.href = 'formulario_productos_v2.php?' + params.toString();
    }
    </script>
</head>
<body>
    <div class="container">
        <h1>🛍️ Productos Vigentes</h1>
        <p class="summary">
            <strong>Mostrando solo productos activos (no eliminados)</strong>
        </p>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Precio</th>
                        <th>Detalles</th>
                        <th>Unidades</th>
                        <th>Imagen</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr id="row-<?= $row['id'] ?>">
                        <td><?= $row['id'] ?></td>
                        <td class="row-data"><strong><?= htmlspecialchars($row['nombre']) ?></strong></td>
                        <td class="row-data"><?= htmlspecialchars($row['marca']) ?></td>
                        <td class="row-data"><?= htmlspecialchars($row['modelo']) ?></td>
                        <td class="row-data">$<?= number_format($row['precio'], 2) ?></td>
                        <td class="row-data"><?= htmlspecialchars($row['detalles']) ?></td>
                        <td class="row-data"><?= $row['unidades'] ?></td>
                        <td class="row-data">
                            <?php if (!empty($row['imagen']) && $row['imagen'] != 'img/imagen.png'): ?>
                                <img src="<?= $row['imagen'] ?>" alt="<?= $row['nombre'] ?>" 
                                     onerror="this.src='img/imagen.png'">
                            <?php else: ?>
                                <span class="text-muted">Sin imagen</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge active">VIGENTE</span>
                        </td>
                        <td>
                            <button class="btn-editar" onclick="editarProducto(<?= $row['id'] ?>)">
                                Editar
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <div class="summary">
                <strong>Total de productos vigentes: <?= $result->num_rows ?></strong>
            </div>
        <?php else: ?>
            <div class="no-products">
                <h3>📭 No hay productos vigentes</h3>
                <p>No se encontraron productos activos en la base de datos.</p>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 30px;">
            <a href="formulario_productos_v2.php" style="
                background-color: #007bff;
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                margin: 0 10px;
            ">➕ Registrar Nuevo Producto</a>
            
            <a href="get_productos_xhtml_v2.php?tope=10" style="
                background-color: #6c757d;
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                margin: 0 10px;
            ">📋 Ver Productos XHTML</a>
        </div>
    </div>

    <?php
    if ($result) {
        $result->free();
    }
    $link->close();
    ?>
</body>
</html>