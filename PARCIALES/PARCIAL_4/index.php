<?php
session_start();
include 'db_connection.php';

// Verificar si el usuario está autenticado
$isAuthenticated = isset($_SESSION['user']);
$userName = $isAuthenticated ? $_SESSION['user']['name'] : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a tu Biblioteca Personal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Botón de Iniciar Sesión en la esquina superior derecha -->
    <?php if (!$isAuthenticated): ?>
        <a href="login.php" class="login-button">Iniciar Sesión</a>
    <?php endif; ?>

    <!-- Contenedor centralizado -->
    <div class="central-container">
        <!-- Mensaje de Bienvenida Centrado -->
        <h2>Bienvenido<?php echo $isAuthenticated ? ", " . htmlspecialchars($userName) : ""; ?></h2>
        <h3>Tu Biblioteca Personal</h3>

        <!-- Botones Principales -->
        <?php if ($isAuthenticated): ?>
            <div class="main-buttons">
                <a href="search.php" class="button">Buscar Libros</a>
                <a href="my_books.php" class="button">Ver mi Biblioteca</a>
                <a href="logout.php" class="button logout-button">Cerrar Sesión</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
