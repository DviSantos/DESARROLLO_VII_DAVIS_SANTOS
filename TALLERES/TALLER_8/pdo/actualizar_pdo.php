<?php
require_once "config_pdo.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Obtener la información del usuario
    $sql = "SELECT nombre, email FROM usuarios WHERE id = :id";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();

        if ($row) {
            $nombre = $row["nombre"];
            $email = $row["email"];
        } else {
            echo "Usuario no encontrado.";
            exit();
        }
        unset($stmt);
    }
} else {
    echo "ID de usuario no válido.";
    exit();
}

// Actualizar el usuario al enviar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];

    $sql = "UPDATE usuarios SET nombre = :nombre, email = :email WHERE id = :id";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Usuario actualizado exitosamente.";
            echo "<button><a href='leer_usuarios_pdo.php'>Volver a la lista de usuarios</a></button>";
        } else {
            echo "ERROR: No se pudo actualizar el usuario. " . $stmt->errorInfo()[2];
        }

        unset($stmt);
    }

    unset($pdo);
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
    <form action="actualizar_pdo.php?id=<?php echo $id; ?>" method="post">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $nombre; ?>">
        <br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $email; ?>">
        <br>
        <input type="submit" value="Actualizar">
    </form>
    <button><a href="leer_usuarios_pdo.php">Volver a la lista de usuarios</a></button>
</body>
</html>
