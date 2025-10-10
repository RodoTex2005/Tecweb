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
        .deleted {
            background-color: #f8d7da;
            color: #721c24;
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
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><strong><?= htmlspecialchars($row['nombre']) ?></strong></td>
                        <td><?= htmlspecialchars($row['marca']) ?></td>
                        <td><?= htmlspecialchars($row['modelo']) ?></td>
                        <td>$<?= number_format($row['precio'], 2) ?></td>
                        <td><?= htmlspecialchars($row['detalles']) ?></td>
                        <td><?= $row['unidades'] ?></td>
                        <td>
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
            <a href="formulario_productos.html" style="
                background-color: #007bff;
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                margin: 0 10px;
            ">➕ Registrar Nuevo Producto</a>
            
            <a href="ver_todos_productos.php" style="
                background-color: #6c757d;
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                margin: 0 10px;
            ">📋 Ver Todos los Productos</a>
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