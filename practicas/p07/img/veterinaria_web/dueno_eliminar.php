<?php include("includes/db.php"); ?>

<?php
$id = $_GET['id']; // ID recibido desde el enlace

$conexion->query("UPDATE dueno SET Estado = 0 WHERE OwnerID = $id");

header("Location: duenos.php");
exit;
