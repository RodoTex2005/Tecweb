<?php
    function esMultiplo($num1) {
    if ($num1%5 == 0 && $num1%7 == 0) {
        return "R= El número $num1 SÍ es múltiplo de 5 y 7.";
    } else {
        return "R= El número $num1 NO es múltiplo de 5 y 7.";
    }
}

function generarSecuencia() {
    $matriz = [];
    $iteraciones = 0;

    do {
        $iteraciones++;
        $a = rand(1, 999);
        $b = rand(1, 999);
        $c = rand(1, 999);
        $matriz[] = [$a, $b, $c];
    } while (!($a % 2 == 1 && $b % 2 == 0 && $c % 2 == 1));

    $totalNumeros = $iteraciones * 3;

    $resultado = "<h3>Secuencias generadas:</h3><ul>";
    foreach ($matriz as $fila) {
        $resultado .= "<li>" . implode(", ", $fila) . "</li>";
    }
    $resultado .= "</ul>";
    $resultado .= "<p>$totalNumeros números obtenidos en $iteraciones iteraciones</p>";

    return $resultado;
}

function buscarMultiploWhile($num) {
    $contador = 0;
    $encontrado = false;

    while (!$encontrado) {
        $aleatorio = rand(1, 1000);
        $contador++;
        if ($aleatorio % $num == 0) {
            $encontrado = true;
        }
    }
    return "Con while: Se encontró el número $aleatorio en $contador intentos.";
}

function buscarMultiploDoWhile($num) {
    $contador = 0;
    do {
        $aleatorio = rand(1, 1000);
        $contador++;
    } while ($aleatorio % $num != 0);

    return "Con do-while: Se encontró el número $aleatorio en $contador intentos.";
}

function generarArregloAscii() {
    $arreglo = [];
    for ($i = 97; $i <= 122; $i++) {
        $arreglo[$i] = chr($i);
    }

    $tabla = "<table border='1' cellpadding='5'><tr><th>Índice</th><th>Valor</th></tr>";
    foreach ($arreglo as $key => $value) {
        $tabla .= "<tr><td>$key</td><td>$value</td></tr>";
    }
    $tabla .= "</table>";

    return $tabla;
}

function verificarEdadSexo($edad, $sexo) {
    if ($sexo == "femenino" && $edad >= 18 && $edad <= 35) {
        return "<p>Bienvenida, usted está en el rango de edad permitido.</p>";
    } else {
        return "<p>Lo sentimos, no cumple con los requisitos de edad o sexo.</p>";
    }
}

$parqueVehicular = [
    "ABC1234" => [
        "Auto" => ["marca"=>"HONDA","modelo"=>2020,"tipo"=>"camioneta"],
        "Propietario" => ["nombre"=>"Alfonzo Esparza","ciudad"=>"Puebla, Pue.","direccion"=>"C.U., Jardines de San Manuel"]
    ],
    "DEF5678" => [
        "Auto" => ["marca"=>"MAZDA","modelo"=>2019,"tipo"=>"sedan"],
        "Propietario" => ["nombre"=>"Ma. del Consuelo Molina","ciudad"=>"Puebla, Pue.","direccion"=>"97 oriente"]
    ],
    "GHI9012" => [
        "Auto" => ["marca"=>"TOYOTA","modelo"=>2021,"tipo"=>"hachback"],
        "Propietario" => ["nombre"=>"Luis Ramirez","ciudad"=>"Ciudad de México","direccion"=>"Av. Reforma 100"]
    ],
    "JKL3456" => [
        "Auto" => ["marca"=>"FORD","modelo"=>2018,"tipo"=>"camioneta"],
        "Propietario" => ["nombre"=>"Ana Torres","ciudad"=>"Guadalajara","direccion"=>"Col. Americana 12"]
    ],
    "MNO7890" => [
        "Auto" => ["marca"=>"CHEVROLET","modelo"=>2022,"tipo"=>"sedan"],
        "Propietario" => ["nombre"=>"Carlos López","ciudad"=>"Monterrey","direccion"=>"Calle 5 #123"]
    ],
    "PQR2345" => [
        "Auto" => ["marca"=>"NISSAN","modelo"=>2017,"tipo"=>"hachback"],
        "Propietario" => ["nombre"=>"Sofia Martinez","ciudad"=>"Cancún","direccion"=>"Av. Kukulkan 45"]
    ],
    "STU6789" => [
        "Auto" => ["marca"=>"VOLKSWAGEN","modelo"=>2020,"tipo"=>"sedan"],
        "Propietario" => ["nombre"=>"Fernando Jiménez","ciudad"=>"Toluca","direccion"=>"Av. Lerdo 77"]
    ],
    "VWX0123" => [
        "Auto" => ["marca"=>"KIA","modelo"=>2019,"tipo"=>"camioneta"],
        "Propietario" => ["nombre"=>"Patricia Vega","ciudad"=>"Mérida","direccion"=>"Calle 60 200"]
    ],
    "YZA4567" => [
        "Auto" => ["marca"=>"HYUNDAI","modelo"=>2021,"tipo"=>"sedan"],
        "Propietario" => ["nombre"=>"Miguel Sánchez","ciudad"=>"Querétaro","direccion"=>"Jardines del Parque 5"]
    ],
    "BCD8901" => [
        "Auto" => ["marca"=>"BMW","modelo"=>2022,"tipo"=>"camioneta"],
        "Propietario" => ["nombre"=>"Laura Gómez","ciudad"=>"Puebla, Pue.","direccion"=>"Centro Histórico 10"]
    ],
    "EFG2345" => [
        "Auto" => ["marca"=>"MERCEDES","modelo"=>2020,"tipo"=>"sedan"],
        "Propietario" => ["nombre"=>"Jorge Rivera","ciudad"=>"Toluca","direccion"=>"Av. Juárez 50"]
    ],
    "HIJ6789" => [
        "Auto" => ["marca"=>"AUDI","modelo"=>2019,"tipo"=>"hachback"],
        "Propietario" => ["nombre"=>"Valeria Cruz","ciudad"=>"Guadalajara","direccion"=>"Col. Chapultepec 8"]
    ],
    "KLM0123" => [
        "Auto" => ["marca"=>"FORD","modelo"=>2021,"tipo"=>"sedan"],
        "Propietario" => ["nombre"=>"Ricardo Morales","ciudad"=>"Ciudad de México","direccion"=>"Calle 16 de Septiembre 12"]
    ],
    "NOP4567" => [
        "Auto" => ["marca"=>"TOYOTA","modelo"=>2018,"tipo"=>"camioneta"],
        "Propietario" => ["nombre"=>"Isabel Herrera","ciudad"=>"Monterrey","direccion"=>"Av. Constitución 45"]
    ],
    "QRS8901" => [
        "Auto" => ["marca"=>"CHEVROLET","modelo"=>2022,"tipo"=>"hachback"],
        "Propietario" => ["nombre"=>"Daniela Ortiz","ciudad"=>"Cancún","direccion"=>"Av. Bonampak 33"]
    ]
];

function mostrarTodosAutos($parque) {
    echo "<pre>";
    print_r($parque);
    echo "</pre>";
}

function buscarPorMatricula($parque, $matricula) {
    if (isset($parque[$matricula])) {
        echo "<pre>";
        print_r($parque[$matricula]);
        echo "</pre>";
    } else {
        echo "<p>Matrícula no encontrada.</p>";
    }
}
?>

