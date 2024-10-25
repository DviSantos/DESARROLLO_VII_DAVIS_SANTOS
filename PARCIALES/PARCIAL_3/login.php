<?php
session_start();
if(isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit;
}
include 'usuarios.php';
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    if (!isset($users[$usuario]) && $contrasena === $users[$usuario]) {
        $_SESSION['usuario'] = $usuario;
        setcookie("usuario", $usuario, time() + 3600, "/");
        header("Location: dashboard.php");
        exit;
    }else{
        $error = "Usuario o contraseña incorrectos";
    }
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Inicio de sesión</h2>
    <form action="login.php" method="post">
        <label for="">Usuario</label>
        <input type="text" name="usuario" id="usuario" required><br><br>
        <label for="">Password</label>
        <input type="password" name="password" id="password" required><br><br>
        <input type="submit" value="Iniciar Sesion">
    </form>

    <?php 
    if (isset($error)) {
        echo "<p style='color: red;'>$error</p>";
    }
    ?>
</body>
</html>