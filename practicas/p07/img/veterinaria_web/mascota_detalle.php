<?php
include("includes/db.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Datos de mascota + dueño + especie + raza
$consulta = $conexion->query("
    SELECT m.*, d.Nombre AS DuenoNombre, d.Apellido, d.Telefono,
           e.Nombre AS Especie, r.Nombre AS Raza
    FROM mascota m
    JOIN dueno d ON m.OwnerID = d.OwnerID
    JOIN especie e ON m.EspecieID = e.EspecieID
    JOIN raza r ON m.RazaID = r.RazaID
    WHERE m.PetID = $id
");
$mascota = $consulta->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de Mascota</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos propios -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include("includes/sidebar.php"); ?>

<div class="content">

<?php if (!$mascota): ?>
    <div class="alert alert-warning">Mascota no encontrada.</div>
    <a class="btn btn-secondary" href="mascotas.php">Volver</a>
</div>
</body>
</html>
<?php exit; ?>
<?php endif; ?>

<h1 class="mb-3">🐶 Detalles de Mascota</h1>

<div class="row mb-4">
    <div class="col-md-6">
        <p><strong>Nombre:</strong> <?= htmlspecialchars($mascota['Nombre']) ?></p>
        <p><strong>Edad:</strong> <?= htmlspecialchars($mascota['Edad']) ?> años</p>
        <p><strong>Peso:</strong> <?= htmlspecialchars($mascota['Peso']) ?> kg</p>
    </div>
    <div class="col-md-6">
        <p><strong>Especie:</strong> <?= htmlspecialchars($mascota['Especie']) ?></p>
        <p><strong>Raza:</strong> <?= htmlspecialchars($mascota['Raza']) ?></p>
    </div>
</div>

<hr>

<h3>👤 Dueño</h3>
<p><strong>Nombre:</strong> <?= htmlspecialchars($mascota['DuenoNombre'] . " " . $mascota['Apellido']) ?></p>
<p><strong>Teléfono:</strong> <?= htmlspecialchars($mascota['Telefono']) ?></p>

<hr>

<h3 class="mt-4">🏥 Historial Médico</h3>

<?php
$historial = $conexion->query("
    SELECT c.Fecha, c.Motivo, pm.Nombre AS Procedimiento, pm.Costo
    FROM citamedica c
    JOIN procedimientomedico pm ON c.ProcedimientoID = pm.ProcedimientoID
    WHERE c.PetID = $id
    ORDER BY c.Fecha DESC
");
?>

<?php if ($historial && $historial->num_rows > 0): ?>

<table class="table table-hover table-striped mt-3">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Motivo</th>
            <th>Procedimiento</th>
            <th>Costo</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($fila = $historial->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($fila['Fecha']) ?></td>
            <td><?= htmlspecialchars($fila['Motivo']) ?></td>
            <td><?= htmlspecialchars($fila['Procedimiento']) ?></td>
            <td>$<?= htmlspecialchars($fila['Costo']) ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php else: ?>
    <div class="alert alert-info mt-3">Esta mascota no tiene historial médico.</div>
<?php endif; ?>

<a class="btn btn-primary mt-4" href="mascotas.php">⬅ Regresar</a>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
