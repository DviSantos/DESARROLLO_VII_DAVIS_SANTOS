<?php
include('config_sesion.php');

// Verificar si el carrito tiene productos
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$total = 0;

// Si el carrito no está vacío, calcular el total y vaciarlo
if (!empty($carrito)) {
    foreach ($carrito as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }

    unset($_SESSION['carrito']);

    setcookie('usuario', 'Cliente', time() + 86400, '/', '', true, true);
}
?>

<style>
    body{
        text-align: center;
    }
    
    a{
        text-decoration: none;
    }


    button{
        position: relative;
        border-radius: 12px;
    }
    button:hover {
    background-color: greenyellow;
    color: white;
    }

    table{
        border-collapse: collapse;
        width: 100%;
        text-align: center;
    }

    section{
        background-color: #f5f5f5;
        width: 400px;
        margin: 0 auto;
        padding: 20px;
    }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h2>Resumen de la Compra</h2>
    <section>
        <table>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
            </tr>
            <?php foreach ($carrito as $producto): ?>
                <tr>
                    <td><?= $producto['nombre'] ?></td>
                    <td>$<?= $producto['precio'] ?></td>
                    <td><?= $producto['cantidad'] ?></td>
                    <td>$<?= $producto['precio'] * $producto['cantidad'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
<?php if ($total > 0): ?>
    <p>Gracias por tu compra. El total es $<?= $total ?>.</p>
<?php else: ?>
    <p>No tienes productos en el carrito.</p>
<?php endif; ?>
</section>
<br>
<button><a href="productos.php">Volver a Productos</a></button>

</body>
</html>