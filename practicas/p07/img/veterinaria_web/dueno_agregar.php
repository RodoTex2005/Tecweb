<?php include("includes/db.php"); ?>
<?php include("includes/sidebar.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Dueño</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>

<div style="margin-left: 220px; padding: 20px;">

<h1>Agregar Nuevo Dueño</h1>

<form method="POST" action="dueno_agregar.php">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Apellido:</label><br>
    <input type="text" name="apellido" required><br><br>

    <label>Teléfono:</label><br>
    <input type="text" name="telefono" required><br><br>

    <button type="submit">Guardar</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];

    $sql = "INSERT INTO dueno (Nombre, Apellido, Telefono)
            VALUES ('$nombre', '$apellido', '$telefono')";

    if ($conexion->query($sql)) {
        echo "<p>✅ Dueño agregado correctamente.</p>";
        echo '<a href="duenos.php">Volver a la lista</a>';
    } else {
        echo "<p>❌ Error al agregar: " . $conexion->error . "</p>";
    }
}
?>

</div>
</body>
</html>
