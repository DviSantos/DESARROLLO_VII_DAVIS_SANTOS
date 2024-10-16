<?php
include('config_sesion.php');
//listar los productos en una tabla hml
// Lista de productos (se puede reemplazar con una base de datos)
$productos = [
    1 => ['nombre' => 'MEMOERIA RAM', 'precio' => 70],
    2 => ['nombre' => 'PROCESADOR', 'precio' => 200],
    3 => ['nombre' => 'DISCO SSD', 'precio' => 90],
    4 => ['nombre' => 'SISTEMA OPERATIVO', 'precio' => 120],
    5 => ['nombre' => 'TECLADO', 'precio' => 30],
];
?>

<style>
    /*alinear la tabla en el centro usando el body en css*/
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
    <title>Productos</title>
</head>
<body>
<h2>Lista de Productos</h2>
<section>
<table>
    <thead>
        <tr>
        <th>Nombre</th>
        <th>Precio</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($productos as $id => $producto): ?>
        <tr>
            <td><?= htmlspecialchars($producto['nombre']) ?></td>
            <td><?= htmlspecialchars($producto['precio']) ?></td>
            <td><button><a href="agregar_al_carrito.php?id=<?= $id ?>">Añadir al carrito</a><a href="ver_carrito.php"></a></button></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</section>
</body>
</html>