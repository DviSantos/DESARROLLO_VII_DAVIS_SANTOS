<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
echo 'Bienvenido, ' . htmlspecialchars($_SESSION['user']['name']);
?>
