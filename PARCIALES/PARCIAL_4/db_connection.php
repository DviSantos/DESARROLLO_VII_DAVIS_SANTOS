<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=parcial4_db', 'root', 'admin');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
