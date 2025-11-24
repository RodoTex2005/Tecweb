<?php include("includes/db.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dueños</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Estilos propios -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include("includes/sidebar.php"); ?>

<div class="content">

<h1 class="mb-4">👤 Lista de Dueños</h1>

<a class="btn btn-primary" href="dueno_agregar.php">➕ Agregar Nuevo Dueño</a>

<br><br>

<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Teléfono</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
<?php
$sql = "SELECT OwnerID, Nombre, Apellido, Telefono
        FROM dueno
        WHERE Estado = 1
        ORDER BY Nombre";

$resultado = $conexion->query($sql);

while($fila = $resultado->fetch_assoc()) {
    echo "<tr>";
    echo "<td>".$fila['OwnerID']."</td>";
    echo "<td>".$fila['Nombre']."</td>";
    echo "<td>".$fila['Apellido']."</td>";
    echo "<td>".$fila['Telefono']."</td>";
    echo "<td><a class='btn btn-sm btn-outline-danger' href='dueno_eliminar.php?id=" . $fila['OwnerID'] . "'>🗑 Desactivar</a></td>";
    echo "</tr>";
}
?>
    </tbody>
</table>

</div> <!-- .content -->

</body>
</html>
