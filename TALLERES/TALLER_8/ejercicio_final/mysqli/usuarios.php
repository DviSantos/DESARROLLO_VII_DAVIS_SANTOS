<?php
require_once "config.php";

// Registrar un nuevo usuario
function registrarUsuario($nombre, $email, $contraseña) {
    global $conn;
    $hashed_password = password_hash($contraseña, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios_p (nombre, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $hashed_password);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error al registrar usuario: " . mysqli_stmt_error($stmt));
    }
}

// Listar todos los usuarios
function listarUsuarios($offset = 0, $limit = 10) {
    global $conn;
    $sql = "SELECT * FROM usuarios_p LIMIT ?, ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $offset, $limit);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

// Buscar usuarios
function buscarUsuario($query) {
    global $conn;
    $sql = "SELECT * FROM usuarios_p WHERE nombre LIKE ? OR email LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    $searchTerm = "%$query%";
    mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

// Actualizar información de usuarios
function actualizarUsuario($id, $nombre, $email, $contraseña) {
    global $conn;
    $sql = "UPDATE usuarios_p SET nombre = ?, email = ?" . ($contraseña ? ", password = ? " : "") . " WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($contraseña) {
        $hashed_password = password_hash($contraseña, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "sssi", $nombre, $email, $hashed_password, $id);
    } else {
        mysqli_stmt_bind_param($stmt, "ssi", $nombre, $email, $id);
    }
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error al actualizar usuario: " . mysqli_stmt_error($stmt));
    }
}

// Eliminar usuarios del sistema
function eliminarUsuario($id) {
    global $conn;
    $sql = "DELETE FROM usuarios_p WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error al eliminar usuario: " . mysqli_stmt_error($stmt));
    }
}
?>
