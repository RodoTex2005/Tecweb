<?php include("includes/db.php"); ?>
<?php include("includes/sidebar.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado de la Consulta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
<div style="margin-left: 220px; padding: 20px;">
<h1>Resultado</h1>

<table class="table table-hover table-striped">


<?php

$q = $_GET['q'];

switch($q) {

    case 1:
        $sql = "SELECT Nombre, Edad, Peso FROM mascota ORDER BY Nombre ASC";
        break;

    case 2:
        $sql = "SELECT Nombre, Edad, Peso FROM mascota ORDER BY Peso ASC";
        break;

    case 3:
        $sql = "SELECT Nombre, Edad, Peso FROM mascota ORDER BY Peso DESC";
        break;

    case 4:
        $sql = "SELECT Nombre, Apellido, Telefono FROM dueno ORDER BY Apellido ASC";
        break;

    case 5:
        $sql = "SELECT Nombre, Edad FROM mascota WHERE Edad < 3";
        break;

    case 6:
        $sql = "SELECT m.Nombre, e.Nombre AS Especie
                FROM mascota m
                JOIN especie e ON m.EspecieID = e.EspecieID";
        break;

    case 7:
        $sql = "SELECT e.Nombre AS Especie, COUNT(*) AS Total
                FROM mascota m
                JOIN especie e ON m.EspecieID = e.EspecieID
                GROUP BY e.Nombre";
        break;

    case 8:
        $sql = "SELECT m.Nombre AS Mascota, d.Nombre AS Dueño, pe.Nombre AS Servicio, pe.Costo
                FROM citaservicioestetico c
                JOIN mascota m ON c.PetID = m.PetID
                JOIN dueno d ON m.OwnerID = d.OwnerID
                JOIN procedimientoestetico pe ON c.EsteticoID = pe.EsteticoID
                ORDER BY pe.Costo DESC";
        break;

    case 9:
        $sql = "SELECT m.Nombre AS Mascota, pm.Nombre AS Procedimiento, pm.Costo
                FROM citamedica c
                JOIN mascota m ON c.PetID = m.PetID
                JOIN procedimientomedico pm ON c.ProcedimientoID = pm.ProcedimientoID
                WHERE pm.Costo > 500";
        break;

    case 10:
        $sql = "SELECT d.Nombre, d.Apellido, COUNT(m.PetID) AS TotalMascotas
                FROM dueno d
                JOIN mascota m ON d.OwnerID = m.OwnerID
                GROUP BY d.OwnerID
                HAVING COUNT(m.PetID) > 1";
        break;
}

$resultado = $conexion->query($sql);

// Mostrar tabla automáticamente
if ($resultado && $resultado->num_rows > 0) {
    echo "<tr>";
    while ($col = $resultado->fetch_field()) {
        echo "<th>" . $col->name . "</th>";
    }
    echo "</tr>";

    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        foreach($fila as $valor) {
            echo "<td>" . $valor . "</td>";
        }
        echo "</tr>";
    }
}

?>

</table>

<br>
<a href="consultas.php">Regresar</a>
</div> 
</body>
</html>

