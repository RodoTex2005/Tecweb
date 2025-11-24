<?php include("includes/db.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Cita Médica</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Estilos propios -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include("includes/sidebar.php"); ?>

<div class="content">

<h1 class="mb-4">🏥 Registrar Cita Médica</h1>

<form method="POST" class="card p-4 shadow-sm" action="cita_agregar.php">

    <div class="mb-3">
        <label class="form-label">Mascota:</label>
        <select name="mascota" class="form-select" required>
            <option value="">Seleccionar...</option>
            <?php
            $sql = "SELECT PetID, Nombre FROM mascota WHERE Estado = 1 ORDER BY Nombre";
            $mascotas = $conexion->query($sql);
            while ($m = $mascotas->fetch_assoc()) {
                echo "<option value='{$m['PetID']}'>{$m['Nombre']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Fecha:</label>
        <input type="date" name="fecha" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Procedimiento Médico:</label>
        <select name="procedimiento" class="form-select" required>
            <option value="">Seleccionar...</option>
            <?php
            $proc = $conexion->query("SELECT ProcedimientoID, Nombre, Costo FROM procedimientomedico ORDER BY Nombre");
            while ($p = $proc->fetch_assoc()) {
                echo "<option value='{$p['ProcedimientoID']}'>{$p['Nombre']} (Costo: {$p['Costo']})</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Medicamento (opcional):</label>
        <select name="medicamento" class="form-select">
            <option value="">Ninguno</option>
            <?php
            $med = $conexion->query("SELECT MedicamentoID, Nombre FROM medicamento ORDER BY Nombre");
            while ($m = $med->fetch_assoc()) {
                echo "<option value='{$m['MedicamentoID']}'>{$m['Nombre']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Motivo de consulta:</label>
        <input type="text" name="motivo" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Observaciones:</label>
        <textarea name="observaciones" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-success">Guardar Cita</button>
    <a href="mascotas.php" class="btn btn-secondary ms-2">Cancelar</a>

</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $mascota = $_POST['mascota'];
    $fecha = $_POST['fecha'];
    $procedimiento = $_POST['procedimiento'];
    $medicamento = $_POST['medicamento'];
    $motivo = $_POST['motivo'];
    $observaciones = $_POST['observaciones'];

    $conexion->query("INSERT INTO citamedica (PetID, ProcedimientoID, Fecha, Motivo, Observaciones)
                      VALUES ('$mascota', '$procedimiento', '$fecha', '$motivo', '$observaciones')");

    $citaID = $conexion->insert_id;

    if (!empty($medicamento)) {
        $conexion->query("INSERT INTO tratamiento (CitaID, MedicamentoID)
                          VALUES ('$citaID', '$medicamento')");
    }

    echo "<div class='alert alert-success mt-3'>✅ Cita registrada con éxito</div>";
    echo "<a class='btn btn-primary' href='mascota_detalle.php?id=$mascota'>Ver Historial</a>";
}
?>

</div>

</body>
</html>
