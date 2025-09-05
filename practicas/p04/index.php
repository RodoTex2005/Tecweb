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
    $a = "ManejadorSQL";
    $b = 'MySQL';
    $c = &$a; 
    
    echo "<h4>Primer bloque:</h4>";
    echo "\$a = $a<br>";
    echo "\$b = $b<br>";
    echo "\$c = $c<br>";

    $a = "PHP server";
    $b = &$a; 
    echo "<h4>Segundo bloque:</h4>";
    echo "\$a = $a<br>";
    echo "\$b = $b<br>";
    echo "\$c = $c<br>";

    echo "<p><strong>Explicación:</strong> En el segundo bloque, \$b se asigna como referencia a \$a, 
    lo que significa que ahora \$b y \$a apuntan al mismo contenido ('PHP server'). 
    La variable \$c sigue apuntando a la primera referencia de \$a, que también cambió al nuevo valor. 
    Por eso \$a, \$b y \$c muestran 'PHP server'.</p>";

    unset($a, $b, $c);
    ?>

    <h2>Ejercicio 3</h2>
    <p>Muestra el contenido de cada variable inmediatamente después de cada asignación y verifica su evolución:</p>

    <?php
    $a = "PHP5";
    echo "<h4>Después de \$a = 'PHP5';</h4>";
    var_dump($a);

    $z[] = &$a;
    echo "<h4>Después de \$z[] = &\$a;</h4>";
    var_dump($z);

    $b = "5a version de PHP";
    echo "<h4>Después de \$b = '5a version de PHP';</h4>";
    var_dump($b);

    $c = (int)$b * 10; 
    echo "<h4>Después de \$c = (int)\$b * 10;</h4>";
    var_dump($c);

    $a .= $b;
    echo "<h4>Después de \$a .= \$b;</h4>";
    var_dump($a);

    $b = (int)$b * $c;  
    echo "<h4>Después de \$b = (int)\$b * \$c;</h4>";
    var_dump($b);

    $z[0] = "MySQL";
    echo "<h4>Después de \$z[0] = 'MySQL';</h4>";
    var_dump($z);
    ?>

    <h2>Ejercicio 4</h2>
    <p>Lee y muestra los valores de las variables del ejercicio anterior usando la matriz <code>$GLOBALS</code>:</p>

    <?php
    $a = "PHP5";
    $z[] = &$a;
    $b = "5a version de PHP";
    $c = (int)$b * 10;
    $a .= $b;
    $b = (int)$b * $c;
    $z[0] = "MySQL";

    echo "<h4>Valores accedidos con \$GLOBALS:</h4>";
    echo "\$GLOBALS['a'] = " . $GLOBALS['a'] . "<br>";
    echo "\$GLOBALS['b'] = " . $GLOBALS['b'] . "<br>";
    echo "\$GLOBALS['c'] = " . $GLOBALS['c'] . "<br>";

    echo "<pre>";
    print_r($GLOBALS['z']);  
    echo "</pre>";
    ?>

    <h2>Ejercicio 5</h2>
    <p>Dar el valor de las variables <code>$a</code>, <code>$b</code>, <code>$c</code> al final del siguiente script:</p>

    <?php
    $a = "7 personas";      
    $b = (integer) $a;      
    $a = "9E3";             
    $c = (double) $a;       

    echo "<h4>Valores finales:</h4>";
    echo "\$a = $a<br>";  
    echo "\$b = $b<br>";  
    echo "\$c = $c<br>";  
    ?>

    <h2>Ejercicio 6</h2>
    <p>Dar y comprobar el valor booleano de las variables $a, $b, $c, $d, $e y $f:</p>

    <?php
    $a = "0";
    $b = "TRUE";
    $c = FALSE;
    $d = ($a OR $b);
    $e = ($a AND $c);
    $f = ($a XOR $b);

    echo "<h4>Comprobación con var_dump:</h4>";
    echo "\$a = "; var_dump((bool)$a); echo "<br>";
    echo "\$b = "; var_dump((bool)$b); echo "<br>";
    echo "\$c = "; var_dump($c); echo "<br>";
    echo "\$d = "; var_dump($d); echo "<br>";
    echo "\$e = "; var_dump($e); echo "<br>";
    echo "\$f = "; var_dump($f); echo "<br>";

    echo "<h4>Transformación de valores booleanos:</h4>";
    echo "\$c = " . ($c ? "true" : "false") . "<br>";
    echo "\$e = " . ($e ? "true" : "false") . "<br>";
    ?>

    <h2>Ejercicio 7</h2>
    <p>Usando la variable predefinida <code>$_SERVER</code>:</p>

    <?php
    echo "<h4>a) Versión de Apache y PHP</h4>";
    echo "Versión de PHP: " . phpversion() . "<br>";
    echo "Software del servidor (Apache): " . $_SERVER['SERVER_SOFTWARE'] . "<br>";

    echo "<h4>b) Nombre del sistema operativo (servidor)</h4>";
    echo PHP_OS . "<br>";

    echo "<h4>c) Idioma del navegador (cliente)</h4>";
    echo $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "<br>";
    ?>

</body>
</html>