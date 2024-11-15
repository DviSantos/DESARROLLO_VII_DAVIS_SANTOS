<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user']['id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];

    // Elimina el libro de la tabla libros_guardados
    $stmt = $pdo->prepare('DELETE FROM libros_guardados WHERE id = ? AND user_id = ?');
    $stmt->execute([$book_id, $_SESSION['user']['id']]);

    // Redirige de vuelta a my_books.php
    header('Location: my_books.php');
    exit;
}
?>
