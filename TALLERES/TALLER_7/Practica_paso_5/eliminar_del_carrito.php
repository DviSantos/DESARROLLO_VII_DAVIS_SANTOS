<?php
include('config_sesion.php');

// Obtener el ID del producto a eliminar
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Verificar si el producto está en el carrito
if (isset($_SESSION['carrito'][$id])) {
    unset($_SESSION['carrito'][$id]);
}

// Redirigir al carrito
header("Location: ver_carrito.php");
exit;
?>
