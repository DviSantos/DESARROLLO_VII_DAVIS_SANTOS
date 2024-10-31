<?php
require_once "config.php";

// Añadir un nuevo libro
function agregarLibro($titulo, $autor, $isbn, $ano_publicacion, $cantidad) {
    global $pdo;
    $sql = "INSERT INTO libros (titulo, autor, isbn, ano_publicacion, cantidad) VALUES (:titulo, :autor, :isbn, :ano_publicacion, :cantidad)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':titulo' => $titulo, ':autor' => $autor, ':isbn' => $isbn, ':ano_publicacion' => $ano_publicacion, ':cantidad' => $cantidad]);
}

// Listar todos los libros
function listarLibros($offset = 0, $limit = 10) {
    global $pdo;
    $sql = "SELECT * FROM libros LIMIT :offset, :limit";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Buscar libros
function buscarLibros($query) {
    global $pdo;
    $sql = "SELECT * FROM libros WHERE titulo LIKE :query OR autor LIKE :query OR isbn LIKE :query";
    $stmt = $pdo->prepare($sql);
    $searchTerm = "%$query%";
    $stmt->bindParam(':query', $searchTerm);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Actualizar información de libros
function actualizarLibro($id, $titulo, $autor, $isbn, $ano_publicacion, $cantidad) {
    global $pdo;
    $sql = "UPDATE libros SET titulo = :titulo, autor = :autor, isbn = :isbn, ano_publicacion = :ano_publicacion, cantidad = :cantidad WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':titulo' => $titulo, ':autor' => $autor, ':isbn' => $isbn, ':ano_publicacion' => $ano_publicacion, ':cantidad' => $cantidad, ':id' => $id]);
}

// Eliminar libros del sistema
function eliminarLibro($id) {
    global $pdo;
    $sql = "DELETE FROM libros WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
}
?>
