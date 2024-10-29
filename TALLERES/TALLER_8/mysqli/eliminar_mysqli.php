<?php
require_once "config_mysqli.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "Usuario eliminado exitosamente.";
        echo "<button><a href='leer_usuarios_mysqli.php'>Volver a la lista de usuarios</a></button>";
    } else {
        echo "ERROR: No se pudo eliminar el usuario. " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "ID de usuario no válido.";
}

mysqli_close($conn);
?>
