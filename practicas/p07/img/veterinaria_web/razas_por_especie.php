<?php
include("includes/db.php");

$especie = $_GET['especie'];

$razas = $conexion->query("SELECT RazaID, Nombre FROM raza WHERE EspecieID = $especie");

echo "<option value=''>Seleccionar raza...</option>";
while ($r = $razas->fetch_assoc()) {
    echo "<option value='{$r['RazaID']}'>{$r['Nombre']}</option>";
}
