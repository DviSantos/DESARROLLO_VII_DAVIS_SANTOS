<?php
require_once "config.php";

// Registrar un préstamo de libro a un usuario
function registrarPrestamo($usuario_id, $libro_id) {
    global $conn;
    mysqli_begin_transaction($conn);

    try {
        $sql = "UPDATE libros SET cantidad = cantidad - 1 WHERE id = ? AND cantidad > 0";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $libro_id);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) == 0) {
            throw new Exception("El libro no está disponible.");
        }
        $sql = "INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo) VALUES (?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $usuario_id, $libro_id);
        mysqli_stmt_execute($stmt);
        mysqli_commit($conn);
        echo "Préstamo registrado con éxito.";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error al registrar préstamo: " . $e->getMessage();
    }
}

// Listar todos los préstamos activos
function listPrestamosActivos() {
    global $conn;
    $sql = "SELECT prestamos.id, usuarios_p.nombre AS nombre_usuario, libros.titulo AS libro_titulo, prestamos.fecha_prestamo 
            FROM prestamos 
            INNER JOIN usuarios_p ON prestamos.usuario_id = usuarios_p.id 
            INNER JOIN libros ON prestamos.libro_id = libros.id
            WHERE prestamos.fecha_devolucion IS NULL"; 

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        throw new mysqli_sql_exception("Error en la consulta: " . mysqli_error($conn));
    }

    $prestamos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $prestamos[] = $row;
    }

    return $prestamos;
}

// Registrar la devolución de un libro
function registrarDevolucion($prestamo_id) {
    global $conn;
    mysqli_begin_transaction($conn);
    try {
        $sql = "SELECT libro_id FROM prestamos WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $prestamo_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            throw new Exception("Préstamo no encontrado.");
        }
        $libro_id = $row['libro_id'];
        
        $sql = "UPDATE libros SET cantidad = cantidad + 1 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $libro_id);
        mysqli_stmt_execute($stmt);
        
        $sql = "UPDATE prestamos SET fecha_devolucion = NOW() WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $prestamo_id);
        mysqli_stmt_execute($stmt);
        mysqli_commit($conn);
        echo "Devolución registrada con éxito.";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error al registrar devolución: " . $e->getMessage();
    }
}
// Listar historial de libros prestados por un usuario
function listHistorialPrestamos($id_usuario) {
    global $conn;
    $sql = "SELECT prestamos.*, libros.titulo 
            FROM prestamos 
            JOIN libros ON prestamos.id_libro = libros.id 
            JOIN usuarios_p ON prestamos.id_usuario = usuarios_p.id 
            WHERE prestamos.id_usuario = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>
