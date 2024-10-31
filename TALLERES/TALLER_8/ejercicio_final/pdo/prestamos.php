<?php
require_once "config.php";

// Registrar un préstamo de libro a un usuario
function registrarPrestamo($usuario_id, $libro_id) {
    global $pdo;
    $pdo->beginTransaction();

    try {
        // Actualizar cantidad disponible de libro
        $sql = "UPDATE libros SET cantidad = cantidad - 1 WHERE id = :libro_id AND cantidad > 0";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':libro_id' => $libro_id]);
        if ($stmt->rowCount() == 0) {
            throw new Exception("El libro no está disponible.");
        }

        // Registrar el préstamo
        $sql = "INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo) VALUES (:usuario_id, :libro_id, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id, ':libro_id' => $libro_id]);

        $pdo->commit();
        echo "Préstamo registrado con éxito.";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error al registrar préstamo: " . $e->getMessage();
    }
}

// Listar todos los préstamos activos
function listPrestamosActivos($offset = 0, $limit = 10) {
    global $pdo;
    $stmt = $pdo->prepare("
    SELECT p.id, p.usuario_id, p.libro_id, p.fecha_prestamo, u.nombre AS nombre_usuario, l.titulo AS libro_titulo
    FROM prestamos p
    JOIN usuarios_p u ON p.usuario_id = u.id
    JOIN libros l ON p.libro_id = l.id
    WHERE p.fecha_devolucion IS NULL
");
$stmt->execute();
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Registrar la devolución de un libro
function registrarDevolucion($prestamo_id) {
    global $pdo;
    $pdo->beginTransaction();

    try {
        // Obtener el libro asociado al préstamo
        $sql = "SELECT libro_id FROM prestamos WHERE id = :prestamo_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':prestamo_id' => $prestamo_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            throw new Exception("Préstamo no encontrado.");
        }
        $libro_id = $row['libro_id'];

        // Actualizar cantidad disponible del libro
        $sql = "UPDATE libros SET cantidad = cantidad + 1 WHERE id = :libro_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':libro_id' => $libro_id]);

        // Registrar la fecha de devolución en el préstamo
        $sql = "UPDATE prestamos SET fecha_devolucion = NOW() WHERE id = :prestamo_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':prestamo_id' => $prestamo_id]);

        $pdo->commit();
        echo "Devolución registrada con éxito.";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error al registrar devolución: " . $e->getMessage();
    }
}

// Mostrar historial de préstamos de un usuario
function listHistorialPrestamos($usuario_id, $offset = 0, $limit = 10) {
    global $pdo;
    $sql = "SELECT prestamos.*, libros.titulo AS libro_titulo 
            FROM prestamos 
            JOIN libros ON prestamos.libro_id = libros.id 
            WHERE prestamos.usuario_id = :usuario_id 
            ORDER BY fecha_prestamo DESC 
            LIMIT :offset, :limit";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
