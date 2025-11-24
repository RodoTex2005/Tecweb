<?php include("includes/db.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mascotas</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Estilos propios -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include("includes/sidebar.php"); ?>

<div class="content">

<h1 class="mb-4">🐶 Lista de Mascotas</h1>

<a class="btn btn-primary" href="mascota_agregar.php">➕ Agregar Mascota</a>
<a class="btn btn-secondary ms-2" href="cita_agregar.php">💉 Registrar cita médica</a>

<br><br>

<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Edad</th>
            <th>Peso</th>
            <th>Dueño</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
<?php
$sql = "SELECT m.PetID, m.Nombre AS mascota, m.Edad, m.Peso,
               d.Nombre AS dueno
        FROM mascota m
        JOIN dueno d ON m.OwnerID = d.OwnerID
        WHERE m.Estado = 1
        ORDER BY m.Nombre";

$resultado = $conexion->query($sql);

while($fila = $resultado->fetch_assoc()) {
    echo "<tr>";
    echo "<td>".$fila['PetID']."</td>";
    echo "<td>".$fila['mascota']."</td>";
    echo "<td>".$fila['Edad']."</td>";
    echo "<td>".$fila['Peso']."</td>";
    echo "<td>".$fila['dueno']."</td>";
    echo "<td><a class='btn btn-sm btn-outline-danger' href='mascota_eliminar.php?id=" . $fila['PetID'] . "'>🗑 Desactivar</a></td>";
    echo "</tr>";
}
?>
    </tbody>
</table>

</div> <!-- .content -->

</body>
</html>
