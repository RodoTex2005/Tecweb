<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Práctica 3</title>
</head>
<body>
    <h2>Ejercicio 1</h2>
    <p>Determina cuál de las siguientes variables son válidas y explica por qué:</p>
    <p>$_myvar,  $_7var,  myvar,  $myvar,  $var7,  $_element1, $house*5</p>
    <?php
        //AQUI VA MI CÓDIGO PHP
        $_myvar;
        $_7var;
        //myvar;       // Inválida
        $myvar;
        $var7;
        $_element1;
        //$house*5;     // Invalida
        
        echo '<h4>Respuesta:</h4>';   
    
        echo '<ul>';
        echo '<li>$_myvar es válida porque inicia con guión bajo.</li>';
        echo '<li>$_7var es válida porque inicia con guión bajo.</li>';
        echo '<li>myvar es inválida porque no tiene el signo de dolar ($).</li>';
        echo '<li>$myvar es válida porque inicia con una letra.</li>';
        echo '<li>$var7 es válida porque inicia con una letra.</li>';
        echo '<li>$_element1 es válida porque inicia con guión bajo.</li>';
        echo '<li>$house*5 es inválida porque el símbolo * no está permitido.</li>';
        echo '</ul>';
    ?>
    <h2>Ejercicio 2</h2>
    <p>Proporciona los valores de $a, $b, $c como sigue:</p>

    <?php
    // Primer bloque de asignaciones
    $a = "ManejadorSQL";
    $b = 'MySQL';
    $c = &$a; // $c referencia a $a

    echo "<h4>Primer bloque:</h4>";
    echo "\$a = $a<br>";
    echo "\$b = $b<br>";
    echo "\$c = $c<br>";

    // Segundo bloque de asignaciones
    $a = "PHP server";
    $b = &$a; // $b referencia a $a también
    // $c sigue apuntando a la primera referencia a $a anterior
    echo "<h4>Segundo bloque:</h4>";
    echo "\$a = $a<br>";
    echo "\$b = $b<br>";
    echo "\$c = $c<br>";

    // Explicación de lo ocurrido
    echo "<p><strong>Explicación:</strong> En el segundo bloque, \$b se asigna como referencia a \$a, 
    lo que significa que ahora \$b y \$a apuntan al mismo contenido ('PHP server'). 
    La variable \$c sigue apuntando a la primera referencia de \$a, que también cambió al nuevo valor. 
    Por eso \$a, \$b y \$c muestran 'PHP server'.</p>";

    // Liberar variables
    unset($a, $b, $c);
    ?>

    <h2>Ejercicio 3</h2>
    <p>Muestra el contenido de cada variable inmediatamente después de cada asignación y verifica su evolución:</p>

    <?php
    // 1) Asignación inicial
    $a = "PHP5";
    echo "<h4>Después de \$a = 'PHP5';</h4>";
    var_dump($a);

    // 2) Referencia en el arreglo
    $z[] = &$a;
    echo "<h4>Después de \$z[] = &\$a;</h4>";
    var_dump($z);

    // 3) Asignación a $b
    $b = "5a version de PHP";
    echo "<h4>Después de \$b = '5a version de PHP';</h4>";
    var_dump($b);

    // 4) Operación con $b
    $c = $b * 10;  // Como $b es string, el resultado será numérico (int 50) porque PHP intenta convertir
    echo "<h4>Después de \$c = \$b * 10;</h4>";
    var_dump($c);

    // 5) Concatenación de $a y $b
    $a .= $b;
    echo "<h4>Después de \$a .= \$b;</h4>";
    var_dump($a);

    // 6) Operación con $b y $c
    $b *= $c;  // PHP intenta convertir $b a número (lo que pueda del string)
    echo "<h4>Después de \$b *= \$c;</h4>";
    var_dump($b);

    // 7) Cambio de valor dentro del arreglo $z
    $z[0] = "MySQL";
    echo "<h4>Después de \$z[0] = 'MySQL';</h4>";
    var_dump($z);
    ?>


</body>
</html>