<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user']['id'])) {
    echo 'Error: usuario no autenticado.';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $google_books_id = $_POST['google_books_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $thumbnail = $_POST['thumbnail'];
    $reseña = $_POST['reseña'];

    // Guarda el libro en la biblioteca personal del usuario (si no está duplicado)
    $stmt = $pdo->prepare('INSERT IGNORE INTO libros_guardados (user_id, google_books_id, titulo, autor, imagen_portada, fecha_guardado) VALUES (?, ?, ?, ?, ?, NOW())');
    $stmt->execute([$_SESSION['user']['id'], $google_books_id, $title, $author, $thumbnail]);

    // Guarda la reseña en la tabla reseñas
    $stmt = $pdo->prepare('INSERT INTO reseñas (user_id, google_books_id, reseña) VALUES (?, ?, ?)');
    $stmt->execute([$_SESSION['user']['id'], $google_books_id, $reseña]);

    echo 'Libro guardado con reseña.';
    header('Location: my_books.php');
    exit;
}
?>
