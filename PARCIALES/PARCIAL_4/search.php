<?php
session_start();
include 'config.php';
include 'db_connection.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['query'])) {
    $query = urlencode($_GET['query']);
    $url = "https://www.googleapis.com/books/v1/volumes?q={$query}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $books = json_decode($response, true);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Libros</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="content-container">
        <h2>Buscar Libros</h2>
        <form method="GET" action="search.php">
            <label for="query">Buscar:</label>
            <input type="text" name="query" required>
            <button type="submit">Buscar</button>
        </form>

        <?php if (isset($books['items'])): ?>
            <div class="books">
                <?php foreach ($books['items'] as $book): ?>
                    <div class="book">
                        <img src="<?php echo $book['volumeInfo']['imageLinks']['thumbnail'] ?? ''; ?>" alt="Portada">
                        <div class="book-info">
                            <h3><?php echo htmlspecialchars($book['volumeInfo']['title']); ?></h3>
                            <p>Autor: <?php echo htmlspecialchars($book['volumeInfo']['authors'][0] ?? 'Desconocido'); ?></p>
                            <form method="POST" action="save_book.php">
                                <input type="hidden" name="google_books_id" value="<?php echo $book['id']; ?>">
                                <input type="hidden" name="title" value="<?php echo htmlspecialchars($book['volumeInfo']['title']); ?>">
                                <input type="hidden" name="author" value="<?php echo htmlspecialchars($book['volumeInfo']['authors'][0] ?? 'Desconocido'); ?>">
                                <input type="hidden" name="thumbnail" value="<?php echo htmlspecialchars($book['volumeInfo']['imageLinks']['thumbnail'] ?? ''); ?>">
                                
                                <label for="reseña">Escribe una reseña:</label>
                                <textarea name="reseña" required></textarea>
                                
                                <button type="submit">Guardar en mi biblioteca</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No se encontraron resultados.</p>
        <?php endif; ?>
    </div>
</body>
</html>
