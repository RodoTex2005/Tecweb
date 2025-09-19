<?php
    function esMultiplo($num) {
    if ($num % 5 == 0 && $num % 7 == 0) {
        return "R= El número $num SÍ es múltiplo de 5 y 7.";
    } else {
        return "R= El número $num NO es múltiplo de 5 y 7.";
    }
}
?>