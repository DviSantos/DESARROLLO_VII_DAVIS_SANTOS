<?php
require_once "config.php";

// Registrar un nuevo usuario
function registrarUsuario($nombre, $email, $contraseña) {
    global $pdo;
    $hashed_password = password_hash($contraseña, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios_p (nombre, email, password) VALUES (:nombre, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nombre' => $nombre, ':email' => $email, ':password' => $hashed_password]);
}

// Listar todos los usuarios
function listarUsuarios($offset = 0, $limit = 10) {
    global $pdo;
    $sql = "SELECT * FROM usuarios_p LIMIT :offset, :limit";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Buscar usuarios por nombre o email
function buscarUsuario($keyword) {
    global $pdo;
    $sql = "SELECT * FROM usuarios_p WHERE nombre LIKE :keyword OR email LIKE :keyword";
    $stmt = $pdo->prepare($sql);
    $keyword = "%$keyword%";
    $stmt->bindValue(':keyword', $keyword, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Actualizar información de un usuario
function actualizarUsuario($id, $nombre, $email, $contraseña) {
    global $pdo;
    $sql = "UPDATE usuarios_p SET nombre = :nombre, email = :email, password = :password WHERE id = :id";
    $hashed_password = password_hash($contraseña, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':email' => $email,
        ':password' => $hashed_password,
        ':id' => $id
    ]);
}

// Eliminar un usuario
function eliminarUsuario($id) {
    global $pdo;
    $sql = "DELETE FROM usuarios_p WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
}
?>