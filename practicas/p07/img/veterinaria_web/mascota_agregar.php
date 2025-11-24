<?php include("includes/db.php"); ?>
<?php include("includes/sidebar.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Mascota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>

<div style="margin-left: 220px; padding: 20px;">

<h1>Agregar Nueva Mascota</h1>

<form method="POST" action="mascota_agregar.php">

    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Edad:</label><br>
    <input type="number" name="edad" required><br><br>

    <label>Peso (kg):</label><br>
    <input type="number" step="0.1" name="peso" required><br><br>

    <label>Dueño:</label><br>
    <select name="dueno" required>
        <option value="">Seleccionar...</option>
        <?php
        $duenos = $conexion->query("SELECT OwnerID, Nombre, Apellido FROM dueno ORDER BY Nombre");
        while ($d = $duenos->fetch_assoc()) {
            echo "<option value='{$d['OwnerID']}'>{$d['Nombre']} {$d['Apellido']}</option>";
        }
        ?>
    </select><br><br>

    <label>Especie:</label><br>
    <select id="especie" name="especie" required>
        <option value="">Seleccionar...</option>
        <?php
        $especies = $conexion->query("SELECT EspecieID, Nombre FROM especie ORDER BY Nombre");
        while ($e = $especies->fetch_assoc()) {
            echo "<option value='{$e['EspecieID']}'>{$e['Nombre']}</option>";
        }
        ?>
    </select><br><br>

    <label>Raza:</label><br>
    <select id="raza" name="raza" required>
        <option value="">Seleccione una especie primero</option>
    </select><br><br>

    <button type="submit">Guardar Mascota</button>

</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $peso = $_POST['peso'];
    $dueno = $_POST['dueno'];
    $especie = $_POST['especie'];
    $raza = $_POST['raza'];

    $sql = "INSERT INTO mascota (Nombre, Edad, Peso, OwnerID, EspecieID, RazaID)
            VALUES ('$nombre', '$edad', '$peso', '$dueno', '$especie', '$raza')";

    if ($conexion->query($sql)) {
        echo "<p>✅ Mascota agregada correctamente.</p>";
        echo '<a href="mascotas.php">Volver a la lista</a>';
    } else {
        echo "<p>❌ Error: " . $conexion->error . "</p>";
    }
}
?>

</div>

<!-- SCRIPT PARA CARGAR RAZAS DINÁMICO -->
<script>
document.getElementById("especie").addEventListener("change", function() {
    let especieID = this.value;

    let xhr = new XMLHttpRequest();
    xhr.open("GET", "razas_por_especie.php?especie=" + especieID, true);
    xhr.onload = function() {
        document.getElementById("raza").innerHTML = this.responseText;
    }
    xhr.send();
});
</script>

</body>
</html>
