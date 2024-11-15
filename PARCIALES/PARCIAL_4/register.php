<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'config.php';
include 'db_connection.php';

// Si el usuario ya está logueado, redirige a la página principal
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

// Maneja el registro de nuevos usuarios
if (isset($_POST['register'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); 

    if ($_POST['password'] != $_POST['password2']) {
        $_SESSION['error'] = 'Las contraseñas no coinciden';
        header('Location: login.php');
        exit();
    }

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = 'Este correo ya está registrado. Por favor, inicia sesión.';
    } else {
        // Inserta el nuevo usuario en la base de datos
        $stmt = $pdo->prepare('INSERT INTO usuarios (email, password, nombre) VALUES (?, ?, ?)');
        $stmt->execute([$email, $password, 'Usuario sin nombre']);
        $_SESSION['success'] = 'Registro exitoso. Por favor, inicia sesión.';
    }

    header('Location: login.php');
    exit();
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Registrarse</h2>

        <!-- Mostrar mensajes de error o éxito -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <!-- Formulario de Registro -->
        <h3>Registrarse</h3>
        <form method="POST" action="login.php">
            <label for="email">Correo:</label>
            <input type="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" required>

            <label for="password2">Verificar Contraseña:</label>
            <input type="password" name="password2" required>

            <button type="submit" name="register">Registrarse</button>
        </form>

        <!-- Botón para Ir a Iniciar Sesión -->
        <button onclick="window.location.href='login.php'">Ir a Iniciar Sesión</button>

        <!-- Botón de Inicio de Sesión con Google -->
        <p>O</p>
        <a href="<?php echo htmlspecialchars($googleLoginUrl); ?>" class="button">
            <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" viewBox="0 0 256 262">
                <!-- SVG del icono de Google aquí -->
            </svg>
            Continuar con Google
        </a>
    </div>
</body>
</html>
