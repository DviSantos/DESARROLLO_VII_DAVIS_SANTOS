<?php
require_once "config_pdo.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "DELETE FROM usuarios WHERE id = :id";
    
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Usuario eliminado exitosamente.";
            echo "<button><a href='leer_usuarios_pdo.php'>Volver a la lista de usuarios</a></button>";
        } else {
            echo "ERROR: No se pudo eliminar el usuario. " . $stmt->errorInfo()[2];
        }
        
        unset($stmt);
    }
} else {
    echo "ID de usuario no válido.";
}

unset($pdo);
?>
