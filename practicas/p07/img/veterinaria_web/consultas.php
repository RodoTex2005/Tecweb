<?php include("includes/db.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultas Avanzadas</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Estilos propios -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include("includes/sidebar.php"); ?>

<div class="content">

<h1 class="mb-4">🔍 Consultas Avanzadas</h1>

<a class="btn btn-primary mb-4" href="cita_agregar.php">➕ Registrar cita médica</a>

<form action="consulta_resultado.php" method="GET" class="card p-4 shadow-sm" style="max-width: 550px;">

    <label class="form-label">Selecciona una consulta:</label>

    <select name="q" class="form-select mb-3" required>
        <option value="">Seleccionar...</option>
        <option value="1">Mascotas ordenadas por nombre</option>
        <option value="2">Mascotas ordenadas por peso (menor a mayor)</option>
        <option value="3">Mascotas ordenadas por peso (mayor a menor)</option>
        <option value="4">Dueños ordenados por apellido</option>
        <option value="5">Mascotas menores de 3 años</option>
        <option value="6">Mascotas de una especie específica</option>
        <option value="7">Mascotas agrupadas por especie</option>
        <option value="8">Servicios estéticos más caros primero</option>
        <option value="9">Historial médico con costos mayores a 500</option>
        <option value="10">Dueños con más de 1 mascota</option>
        <!-- Después continuamos hasta llegar a 30 -->
    </select>

    <button type="submit" class="btn btn-success w-100">Ejecutar Consulta</button>

</form>

<a class="btn btn-secondary mt-4" href="index.php">⬅ Regresar</a>

</div>

</body>
</html>
