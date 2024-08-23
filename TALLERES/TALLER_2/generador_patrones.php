<?php

//patrón de triángulo rectángulo usando asteriscos (*) con un bucle for
echo "Triangulo rectagulo:<br>";
for ($i = 1; $i <= 5; $i++) {
    for ($y = 1; $y <= $i; $y++) {
    echo(" * ");
}

echo "<br>";
}
echo "<br>";

//secuencia de números del 1 al 20, pero solo muestra los números impares. Utilizando un bucle while
$contador = 0;
echo "Números impares entre 1 a 20:<br>";
while ($contador < 20) {
    $contador++;
    if ($contador % 2 == 0) {
        continue;
    }
    echo "$contador ";
}
echo "<br><br>";

//contador regresivo desde 10 hasta 1, pero salta el número 5 con un bucle do-while
$time = 1;

echo "Contando del 1 al 10 sin el 5 con do-while:<br>";
do {
    if ($time != 5) {
    echo "$time ";
    }
    $time++;
} while ($time <= 10);
echo "<br><br>";

?>