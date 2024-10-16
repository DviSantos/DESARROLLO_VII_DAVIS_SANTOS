<?php
include('config_sesion.php');

// Obtener ID del producto
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

//productos
$productos = [
    1 => ['nombre' => 'MEMOERIA RAM', 'precio' => 70],
    2 => ['nombre' => 'PROCESADOR', 'precio' => 200],
    3 => ['nombre' => 'DISCO SSD', 'precio' => 90],
    4 => ['nombre' => 'SISTEMA OPERATIVO', 'precio' => 120],
    5 => ['nombre' => 'TECLADO', 'precio' => 30],
];

// Verificar si el producto existe
if (isset($productos[$id])) {
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Añadir o incrementar producto
    if (isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id]['cantidad']++;
    } else {
        $_SESSION['carrito'][$id] = [
            'nombre' => $productos[$id]['nombre'],
            'precio' => $productos[$id]['precio'],
            'cantidad' => 1
        ];
    }
}

// Redirigir al carrito
header("Location: ver_carrito.php");
exit;
?>
