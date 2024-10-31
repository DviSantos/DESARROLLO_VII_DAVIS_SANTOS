<?php
require_once "config.php";
require_once "libros.php";
require_once "usuarios.php";
require_once "prestamos.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Registrar un nuevo usuario
    if (isset($_POST['registrar_usuario'])) {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        registrarUsuario($nombre, $email, $password);
    }
    // Añadir un nuevo libro
    elseif (isset($_POST['agregar_libro'])) {
        $titulo = $_POST['titulo'];
        $autor = $_POST['autor'];
        $isbn = $_POST['isbn'];
        $year = $_POST['year'];
        $cantidad = $_POST['cantidad'];
        agregarLibro($titulo, $autor, $isbn, $year, $cantidad);
    }
    // Registrar un préstamo
    elseif (isset($_POST['registrar_prestamo'])) {
        $usuario_id = $_POST['usuario_id'];
        $libro_id = $_POST['libro_id'];
        registrarPrestamo($usuario_id, $libro_id);
    }
}

$usuarios = listarUsuarios();
$libros = listarLibros();
$prestamos = listPrestamosActivos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Gestión de Biblioteca (MySQLi)</title>
</head>
<body>
    <h1>Sistema de Gestión de Biblioteca</h1>

    <h2>Registrar Usuario</h2>
    <form method="post">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit" name="registrar_usuario">Registrar Usuario</button>
    </form>

    <h2>Añadir Libro</h2>
    <form method="post">
        <input type="text" name="titulo" placeholder="Título" required>
        <input type="text" name="autor" placeholder="Autor" required>
        <input type="text" name="isbn" placeholder="ISBN" required>
        <input type="number" name="year" placeholder="Año" required>
        <input type="number" name="cantidad" placeholder="Cantidad" required>
        <button type="submit" name="agregar_libro">Añadir Libro</button>
    </form>

    <h2>Registrar Préstamo</h2>
    <form method="post">
        <select name="usuario_id" required>
            <option value="">Seleccionar Usuario</option>
            <?php foreach ($usuarios as $usuario): ?>
                <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['nombre']; ?></option>
            <?php endforeach; ?>
        </select>
        <select name="libro_id" required>
            <option value="">Seleccionar Libro</option>
            <?php foreach ($libros as $libro): ?>
                <option value="<?php echo $libro['id']; ?>"><?php echo $libro['titulo']; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="registrar_prestamo">Registrar Préstamo</button>
    </form>

    <h2>Usuarios Registrados</h2>
    <ul>
        <?php foreach ($usuarios as $usuario): ?>
            <li><?php echo $usuario['nombre'] . ' - ' . $usuario['email']; ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Libros Disponibles</h2>
    <ul>
        <?php foreach ($libros as $libro): ?>
            <li><?php echo $libro['titulo'] . ' - ' . $libro['autor']; ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Préstamos Activos</h2>
    <ul>
        <?php foreach ($prestamos as $prestamo): ?>
            <li>
                Usuario ID: <?php echo $prestamo['nombre_usuario']; ?> - 
                Libro ID: <?php echo $prestamo['libro_titulo']; ?> - 
                Fecha Préstamo: <?php echo $prestamo['fecha_prestamo']; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
