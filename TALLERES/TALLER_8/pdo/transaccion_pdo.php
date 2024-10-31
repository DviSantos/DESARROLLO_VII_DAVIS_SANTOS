<?php
require_once "config_pdo.php";

// Función para registrar errores en un archivo de log
function logError($message) {
    $logFile = 'error_log.txt';
    $currentDate = date('Y-m-d H:i:s');
    $formattedMessage = "[$currentDate] $message" . PHP_EOL;
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

try {
    $pdo->beginTransaction();

    // Insertar un nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, email) VALUES (:nombre, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nombre' => 'Nuevo Usuario', ':email' => 'nuevo@example.com']);
    $usuario_id = $pdo->lastInsertId();
    //manejo de errores
    if (!$stmt->execute([':nombre' => 'Nuevo Usuario', ':email' => 'nuevo@example.com'])) {
        throw new Exception("Error al insertar el usuario: " . $stmt->errorInfo()[2]);
    }

    // Insertar una publicación para ese usuario
    $sql = "INSERT INTO publicaciones (usuario_id, titulo, contenido) VALUES (:usuario_id, :titulo, :contenido)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':titulo' => 'Nueva Publicación',
        ':contenido' => 'Contenido de la nueva publicación'
    ]);
    //manejo de errores
    if (!$stmt->execute([
        ':usuario_id' => $usuario_id,
        ':titulo' => 'Nueva Publicación',
        ':contenido' => 'Contenido de la nueva publicación'
    ])) {
        throw new Exception("Error al insertar la publicación: " . $stmt->errorInfo()[2]);
    }
    
    // Confirmar la transacción
    $pdo->commit();
    echo "Transacción completada con éxito.";
} catch (Exception $e) {
    $pdo->rollBack();
    logError($e->getMessage());
    echo "Error en la transacción: " . $e->getMessage();
}
?>
