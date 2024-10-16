<?php
include('config_sesion.php');

// Verificar si el carrito está vacío
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$total = 0;
?>
<style>
    body{
        text-align: center;
    }

    a{
        text-decoration: none;
    }

    button{
        border-radius: 12px;
    }

    section{
        background-color: #f5f5f5;
        width: 400px;
        margin: 0 auto;
        padding: 20px;
    }
    button:hover {
    background-color: greenyellow;
    color: white;
    }

    table{
        border-collapse: collapse;
        text-align: center;
    } 
    
    th, td {
        border: 1px solid #ddd;
        border-color: blue;
        text-align: center;
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
<h2>Carrito de Compras</h2>
<section>
    
<?php if (empty($carrito)): ?>
    <p>El carrito está vacío.</p>
<?php else: ?>
    <!-- Mostrar el carrito de compras -->
    <table>
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Cantidad</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($carrito as $id => $producto): ?>
            <tr>
                <td><?= htmlspecialchars($producto['nombre']) ?></td>
                <td><?= htmlspecialchars($producto['precio']) ?></td>
                <td><?= htmlspecialchars($producto['cantidad']) ?></td>
                <td><button><a class="a" href="eliminar_del_carrito.php?id=<?= $id ?>">Eliminar</a></button></td>
            </tr>
            <?php $total += $producto['precio'] * $producto['cantidad']; ?>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p>Total: $<?= $total ?></p>
    <button><a href="checkout.php">Finalizar Compra</a></button>
<?php endif; ?>
<button><a href="productos.php">Volver a Productos</a></button>
</section>
</body>
</html>
