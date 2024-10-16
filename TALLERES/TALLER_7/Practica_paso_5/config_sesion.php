<?php
// Configuración segura de las cookies de la sesión
$secure = true;
$httponly = true;
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $secure,
    'httponly' => $httponly,
    'samesite' => 'Strict'
]);
session_start();
?>