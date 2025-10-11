<?php
header("Content-Type: application/xhtml+xml; charset=utf-8");

// Verificar si se recibió el parámetro 'tope'
if (isset($_GET['tope'])) {
    $tope = (int)$_GET['tope']; // Convertir a entero para seguridad
} else {
    die('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head><title>Error</title></head>
<body>
<p>Parámetro "tope" no detectado.</p>
</body></html>');
}

// Conexión a la base de datos
$link = new mysqli('localhost', 'root', 'Rudytexcuc@no', 'marketzone');
if ($link->connect_errno) {
    die('Falló la conexión: '.$link->connect_error);
}

// Preparar la consulta para evitar inyección SQL
$stmt = $link->prepare("SELECT * FROM productos WHERE unidades <= ?");
$stmt->bind_param("i", $tope);
$stmt->execute();
$result = $stmt->get_result();
$productos = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$link->close();

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <title>Productos con unidades ≤ <?= $tope ?></title>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
    <style type="text/css">
        table { border-collapse: collapse; width: 90%; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #555; color: #fff; }
        img { max-width: 100px; }
        .btn-editar { 
            background-color: #007bff; 
            color: white; 
            padding: 5px 10px; 
            text-decoration: none; 
            border-radius: 3px; 
            border: none;
            cursor: pointer;
        }
        .btn-editar:hover { background-color: #0056b3; }
    </style>
    <script>
    function editarProducto(id) {
        var row = document.getElementById('row-' + id);
        var cells = row.querySelectorAll('.row-data');
        
        var nombre = cells[0].innerText;
        var descripcion = cells[1].innerText;
        var precio = cells[2].innerText.replace('$', '');
        var unidades = cells[3].innerText;
        var imagen = cells[4].querySelector('img').getAttribute('src');
        
        // Quitar "img/" del path si existe
        if (imagen.includes('img/')) {
            imagen = imagen.replace('img/', '');
        }
        
        // Crear URL de forma segura
        var params = new URLSearchParams();
        params.append('id', id);
        params.append('nombre', nombre);
        params.append('descripcion', descripcion);
        params.append('precio', precio);
        params.append('unidades', unidades);
        params.append('imagen', imagen);
        
        window.location.href = 'formulario_productos_v2.php?' + params.toString();
    }
    </script>
</head>
<body>
<h1>Productos con unidades ≤ <?= $tope ?></h1>

<?php if (!empty($productos)) : ?>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Unidades</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($productos as $prod) : ?>
        <tr id="row-<?= $prod['id'] ?>">
            <td><?= htmlspecialchars($prod['id'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="row-data"><?= htmlspecialchars($prod['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="row-data"><?= nl2br(htmlspecialchars($prod['descripcion'], ENT_QUOTES, 'UTF-8')) ?></td>
            <td class="row-data">$<?= number_format($prod['precio'], 2) ?></td>
            <td class="row-data"><?= (int)$prod['unidades'] ?></td>
            <td class="row-data">
                <img src="img/<?= htmlspecialchars($prod['imagen'], ENT_QUOTES, 'UTF-8') ?>" 
                     alt="<?= htmlspecialchars($prod['nombre'], ENT_QUOTES, 'UTF-8') ?>" />
            </td>
            <td>
                <button class="btn-editar" onclick="editarProducto(<?= $prod['id'] ?>)">
                    Editar
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p>No hay productos con unidades ≤ <?= $tope ?></p>
<?php endif; ?>

</body>
</html>