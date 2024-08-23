<?php
//definicion de variables
$calificacion = rand(0,100);
$letra;

//estructura if-elseif-else para determinar la letra correspondiente a la calificación
if ($calificacion >= 90) {
$letra = "A";
} elseif ($calificacion >= 80) {
$letra = "B";
} elseif ($calificacion >= 70) {
$letra = "C";
} elseif ($calificacion >= 60) {
$letra = "D";
} else {
$letra = "F";
}

//impresion
echo $calificacion . "<br>";
echo "Tu calificacion es $letra <br>";

//añadido de operador ternario
$resultadoTernario = ($letra == "F") ? "Reprobado" : "Aprobado";
echo "$resultadoTernario<br><br>";

//switch para imprimir un mensaje adicional basado en la letra de la calificación
switch ($letra) {
case "A":
    echo "Excelente trabajo.<br>";
    break;
case "B":
    echo "Buen trabajo.<br>";
    break;
case "C":
    echo "Trabajo aceptable.<br>";
    break;
case "D":
    echo "Necesitas mejorar.<br>";
    break;
default:
    echo "Debes esforzarte más.<br>";
}
echo "<br>";
?>