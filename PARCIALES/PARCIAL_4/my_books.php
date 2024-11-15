<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user']['id'])) {
    header('Location: index.php');
    exit;
}

// Obtener todos los libros guardados por el usuario
$stmt = $pdo->prepare('SELECT * FROM libros_guardados WHERE user_id = ?');
$stmt->execute([$_SESSION['user']['id']]);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Biblioteca</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="content-container">
        <h2>Mi Biblioteca</h2>
        
        <!-- Contenedor de desplazamiento de libros -->
        <div class="books-container">
            <?php if (count($books) > 0): ?>
                <?php foreach ($books as $book): ?>
                    <div class="book">
                        <img src="<?php echo htmlspecialchars($book['imagen_portada']); ?>" alt="Portada">
                        <div class="book-info">
                            <h3><?php echo htmlspecialchars($book['titulo']); ?></h3>
                            <p>Autor: <?php echo htmlspecialchars($book['autor']); ?></p>
                            <p>Fecha guardado: <?php echo htmlspecialchars($book['fecha_guardado']); ?></p>

                            <!-- Botón de eliminación del libro -->
                            <form method="POST" action="delete_book.php" style="margin-top: 10px;">
                                <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                <button type="submit" class="delete-button">Eliminar libro</button>
                            </form>

                            <!-- Muestra las reseñas para este libro -->
                            <div class="reviews">
                                <h4>Reseñas de otros usuarios:</h4>
                                <?php
                                $stmt = $pdo->prepare('SELECT usuarios.nombre, reseñas.reseña, reseñas.fecha FROM reseñas JOIN usuarios ON reseñas.user_id = usuarios.id WHERE reseñas.google_books_id = ?');
                                $stmt->execute([$book['google_books_id']]);
                                $reseñas = $stmt->fetchAll();
                                ?>
                                <?php if ($reseñas): ?>
                                    <ul>
                                        <?php foreach ($reseñas as $reseña): ?>
                                            <li>
                                                <strong><?php echo htmlspecialchars($reseña['nombre']); ?>:</strong> 
                                                <?php echo htmlspecialchars($reseña['reseña']); ?>
                                                <em>(<?php echo htmlspecialchars($reseña['fecha']); ?>)</em>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p>No hay reseñas para este libro.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No tienes libros guardados en tu biblioteca.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
                