<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 6</title>
</head>
<body>
    <?php
    include("src/funciones.php");
    ?>

    <h2>Ejercicio 1</h2>
    <p>Escribir programa para comprobar si un número es un múltiplo de 5 y 7</p>
    <?php
    if (isset($_GET['num1'])) {
        echo esMultiplo($_GET['num1']);  // llamas la función
    } 
    ?>

    <h2>Ejercicio 2</h2>
    <?php
        echo generarSecuencia();
    ?>

    <h2>Ejercicio 3</h2>
    <p>Buscar múltiplo aleatorio de un número dado</p>
    <?php
        if (isset($_GET['num3'])) {
            $n = $_GET['num3'];
            echo buscarMultiploWhile($n) . "<br>";
            echo buscarMultiploDoWhile($n);
        } else {
            echo "<p>Pasa el número en la URL, ej: ?num3=7</p>";
        }
    ?>
    <h2>Ejercicio 4</h2>
    <?php
        echo generarArregloAscii();
    ?>

    <h2>Ejercicio 5</h2>
<p>Ingrese su edad y sexo:</p>

<form action="" method="post">
    Edad: <input type="number" name="edad" required><br><br>
    Sexo:
    <select name="sexo" required>
        <option value="">Seleccione</option>
        <option value="femenino">Femenino</option>
        <option value="masculino">Masculino</option>
    </select><br><br>
    <input type="submit" value="Enviar">
</form>

<?php
// Solo llamamos a la función si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edad']) && isset($_POST['sexo'])) {
    echo verificarEdadSexo($_POST['edad'], $_POST['sexo']);
}
?>

<h2>Consulta de Parque Vehicular</h2>

    <form action="" method="post">
        Matrícula a buscar: <input type="text" name="matricula">
        <input type="submit" name="buscar" value="Buscar">
        <input type="submit" name="ver_todos" value="Ver Todos">
    </form>

    <?php
    if (isset($_POST['buscar']) && !empty($_POST['matricula'])) {
        $matricula = strtoupper($_POST['matricula']); // Convertir a mayúsculas
        buscarPorMatricula($parqueVehicular, $matricula);
    }

    if (isset($_POST['ver_todos'])) {
        mostrarTodosAutos($parqueVehicular);
    }
    ?>
</body>
</html>