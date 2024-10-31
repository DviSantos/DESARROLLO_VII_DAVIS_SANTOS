<?php
require_once "config_mysqli.php";

// Función para registrar errores en un archivo de log
function logError($message) {
    $logFile = 'error_log.txt';
    $currentDate = date('Y-m-d H:i:s');
    $formattedMessage = "[$currentDate] $message" . PHP_EOL;
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

mysqli_begin_transaction($conn);

try {
    // Insertar un nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, email) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $nombre, $email);
    $nombre = "Nuevo Usuario";
    $email = "nuevo@example.com";

    // Manejo de errores
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error al insertar el usuario: " . mysqli_stmt_error($stmt));
    }

    $usuario_id = mysqli_insert_id($conn);

    // Insertar una publicación para ese usuario
    $sql = "INSERT INTO publicaciones (usuario_id, titulo, contenido) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $usuario_id, $titulo, $contenido);
    $titulo = "Nueva Publicación";
    $contenido = "Contenido de la nueva publicación";

    // Manejo de errores
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error al insertar la publicación: " . mysqli_stmt_error($stmt));
    }

    mysqli_commit($conn);
    echo "Transacción completada con éxito.";
} catch (Exception $e) {
    mysqli_rollback($conn);
    logError($e->getMessage());
    echo "Error en la transacción: " . $e->getMessage();
}

mysqli_close($conn);
?>
