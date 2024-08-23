<?php

//definicion de variables
$nombre_completo = "Davis Santos";
$edad = 23;
$correo = "davis.santos@utp.ac.pa";
$telefono = 64705309;

//definicion de constante
define("OCUPACION", "Estudiante");

//definicion de cadena concatenada con diferentes metodos
$parrafo1 = "Hola, mi nombre es " . $nombre_completo . " y tengo " . $edad . " años.". " Mi correo eletronico es $correo.  Mi numero de telefono es " . $telefono . " Mi ocupacion es " . OCUPACION . ".";

//impresion de texto
echo $parrafo1 . "<br>";
print($parrafo1 . "<br>");
printf("Hola, Mi nombre es %s y tengo %d años. Mi correo eletronico es %s. Mi numero de telefono es %d Mi ocupacion es %s.",$nombre_completo,$edad,$correo,$telefono, OCUPACION );

//impresion con var_dump para mostra tipo y valor de cada variable
echo "<br>";
var_dump($nombre_completo);
echo "<br>";
var_dump($edad);
echo "<br>";
var_dump($correo);
echo "<br>";
var_dump($telefono);
?>