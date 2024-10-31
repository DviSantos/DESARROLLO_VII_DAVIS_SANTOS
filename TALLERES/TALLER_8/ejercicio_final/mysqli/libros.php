<?php
require_once "config.php";

// Añadir un nuevo libro
function agregarLibro($titulo, $autor, $isbn, $ano_publicacion, $cantidad) {
    global $conn;
    $sql = "INSERT INTO libros (titulo, autor, isbn, year, cantidad) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $titulo, $autor, $isbn, $ano_publicacion, $cantidad);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error al añadir libro: " . mysqli_stmt_error($stmt));
    }
}

// Listar todos los libros
function listarLibros($offset = 0, $limit = 10) {
    global $conn;
    $sql = "SELECT * FROM libros LIMIT ?, ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $offset, $limit);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

// Buscar libros
function buscarLibros($query) {
    global $conn;
    $sql = "SELECT * FROM libros WHERE titulo LIKE ? OR autor LIKE ? OR isbn LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    $searchTerm = "%$query%";
    mysqli_stmt_bind_param($stmt, "sss", $searchTerm, $searchTerm, $searchTerm);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

// Actualizar información de libros
function actualizarLibro($id, $titulo, $autor, $isbn, $ano_publicacion, $cantidad) {
    global $conn;
    $sql = "UPDATE libros SET titulo = ?, autor = ?, isbn = ?, year = ?, cantidad = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $titulo, $autor, $isbn, $ano_publicacion, $cantidad, $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error al actualizar libro: " . mysqli_stmt_error($stmt));
    }
}

// Eliminar libros del sistema
function eliminarLibro($id) {
    global $conn;
    $sql = "DELETE FROM libros WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error al eliminar libro: " . mysqli_stmt_error($stmt));
    }
}
?>
