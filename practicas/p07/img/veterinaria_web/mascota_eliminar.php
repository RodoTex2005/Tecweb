<?php include("includes/db.php"); ?>

<?php
$id = $_GET['id'];

$conexion->query("UPDATE mascota SET Estado = 0 WHERE PetID = $id");

header("Location: mascotas.php");
exit;
