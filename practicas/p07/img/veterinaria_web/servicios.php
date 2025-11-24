<?php include("includes/db.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Servicios Estéticos</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Tus estilos -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include("includes/sidebar.php"); ?>

<div class="content">

<h1 class="mb-4">💇‍♂️ Servicios Estéticos</h1>

<!-- Filtro por mascota -->
<form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
        <label class="col-form-label">Buscar por mascota:</label>
    </div>
    <div class="col-auto">
        <input type="text" name="nombre_mascota" class="form-control" placeholder="Ej. Rocky">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </div>
</form>

<!-- Ordenar -->
<form method="GET" class="row g-2 mb-4">
    <div class="col-auto">
        <label class="col-form-label">Ordenar por:</label>
    </div>
    <div class="col-auto">
        <select name="orden" class="form-select">
            <option value="">Seleccionar...</option>
            <option value="fecha_desc">Fecha (más reciente)</option>
            <option value="fecha_asc">Fecha (más antiguo)</option>
            <option value="costo_asc">Costo (menor a mayor)</option>
            <option value="costo_desc">Costo (mayor a menor)</option>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-secondary">Aplicar</button>
    </div>
</form>

<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>Mascota</th>
            <th>Dueño</th>
            <th>Servicio</th>
            <th>Fecha</th>
            <th>Costo</th>
            <th>Observaciones</th>
        </tr>
    </thead>
    <tbody>

<?php

$sql = "
    SELECT m.Nombre AS Mascota, d.Nombre AS Dueno, pe.Nombre AS Servicio,
           c.Fecha, pe.Costo, c.Observaciones
    FROM citaservicioestetico c
    JOIN mascota m ON c.PetID = m.PetID
    JOIN dueno d ON m.OwnerID = d.OwnerID
    JOIN procedimientoestetico pe ON c.EsteticoID = pe.EsteticoID
";

// Filtro por nombre de mascota
if (!empty($_GET['nombre_mascota'])) {
    $nombre = $conexion->real_escape_string($_GET['nombre_mascota']);
    $sql .= " WHERE m.Nombre LIKE '%$nombre%'";
}

// Ordenamientos
if (!empty($_GET['orden'])) {
    switch($_GET['orden']) {
        case 'fecha_desc': $sql .= " ORDER BY c.Fecha DESC"; break;
        case 'fecha_asc': $sql .= " ORDER BY c.Fecha ASC"; break;
        case 'costo_asc': $sql .= " ORDER BY pe.Costo ASC"; break;
        case 'costo_desc': $sql .= " ORDER BY pe.Costo DESC"; break;
    }
}

$resultado = $conexion->query($sql);

while($fila = $resultado->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$fila['Mascota']}</td>";
    echo "<td>{$fila['Dueno']}</td>";
    echo "<td>{$fila['Servicio']}</td>";
    echo "<td>{$fila['Fecha']}</td>";
    echo "<td>$ {$fila['Costo']}</td>";
    echo "<td>{$fila['Observaciones']}</td>";
    echo "</tr>";
}
?>
    </tbody>
</table>

<a class="btn btn-primary mt-3" href="index.php">⬅ Regresar</a>

</div> <!-- .content -->

</body>
</html>
