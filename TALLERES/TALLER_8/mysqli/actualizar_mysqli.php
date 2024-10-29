<?php
require_once "config_mysqli.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $sql = "SELECT nombre, email FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $nombre = $row["nombre"];
        $email = $row["email"];
    } else {
        echo "Usuario no encontrado.";
        exit();
    }

    mysqli_stmt_close($stmt);
} else {
    echo "ID de usuario no válido.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];

    $sql = "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $nombre, $email, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "Usuario actualizado exitosamente.";
        echo "<button><a href='leer_usuarios_mysqli.php'>Volver a la lista de usuarios</a></button>";
    } else {
        echo "ERROR: No se pudo actualizar el usuario. " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Usuario</title>
</head>
<body>
    <h2>Actualizar Usuario</h2>
    <form action="actualizar_mysqli.php?id=<?php echo $id; ?>" method="post">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $nombre; ?>">
        <br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $email; ?>">
        <br>
        <input type="submit" value="Actualizar">
    </form>
</body>
</html>
