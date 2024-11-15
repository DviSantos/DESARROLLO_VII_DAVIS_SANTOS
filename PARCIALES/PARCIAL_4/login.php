<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'config.php';
include 'db_connection.php';

// Maneja el registro de nuevos usuarios
if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    // Verifica si el usuario ya existe
    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo 'Este correo ya está registrado. Por favor, inicia sesión.';
    } else {
        // Inserta el nuevo usuario en la base de datos
        $stmt = $pdo->prepare('INSERT INTO usuarios (email, password, nombre) VALUES (?, ?, ?)');
        $stmt->execute([$email, $password, 'Usuario sin nombre']);
        echo 'Registro exitoso. Por favor, inicia sesión.';
    }
}

// Maneja el inicio de sesión de usuarios
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verifica las credenciales
    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Inicia la sesión
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['nombre']
        ];
        header('Location: index.php');
        exit;
    } else {
        echo 'Correo o contraseña incorrectos.';
    }
}

// URL para inicio de sesión con Google
$googleLoginUrl = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query([
    'client_id' => CLIENT_ID,
    'redirect_uri' => REDIRECT_URI,
    'response_type' => 'code',
    'scope' => 'openid email profile'
]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión o Registrarse</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Contenedor principal de login -->
    <div class="login-container">
        <h2>Iniciar Sesión</h2>

        <!-- Formulario de Iniciar Sesión -->
        <form method="POST" action="login.php">
            <label for="email">Correo:</label>
            <input type="email" name="email" required>
            
            <label for="password">Contraseña:</label>
            <input type="password" name="password" required>
            
            <button type="submit" name="login">Iniciar Sesión</button>
        </form>

        <h3>Registrarse</h3>
        <!-- Formulario de Registro -->
        <form method="POST" action="login.php">
            <label for="email">Correo:</label>
            <input type="email" name="email" required>
            
            <label for="password">Contraseña:</label>
            <input type="password" name="password" required>
            
            <button type="submit" name="register">Registrarse</button>
        </form>

        <!-- Enlace para iniciar sesión con Google -->
        <a href="<?php echo htmlspecialchars($googleLoginUrl); ?>" class="google-login">Iniciar sesión con Google</a>
    </div>
</body>
</html>
