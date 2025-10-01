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
    </style>
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
        </tr>
    </thead>
    <tbody>
    <?php foreach ($productos as $prod) : ?>
        <tr>
            <td><?= htmlspecialchars($prod['id'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($prod['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= nl2br(htmlspecialchars($prod['descripcion'], ENT_QUOTES, 'UTF-8')) ?></td>
            <td><?= number_format($prod['precio'], 2) ?></td>
            <td><?= (int)$prod['unidades'] ?></td>
            <td>
                <img src="img/<?= htmlspecialchars($prod['imagen'], ENT_QUOTES, 'UTF-8') ?>" 
                     alt="<?= htmlspecialchars($prod['nombre'], ENT_QUOTES, 'UTF-8') ?>" />
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
