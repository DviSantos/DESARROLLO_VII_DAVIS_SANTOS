<?php
// Verificar si el archivo existe
if (file_exists('datos_registrados.json')) {
    $datosRegistros = json_decode(file_get_contents('datos_registrados.json'), true);
} else {
    $datosRegistros = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Registros</title>
</head>

<body style="text-align: center;">
    <center><h2>Resumen de Registros</h2></center>
    <table border="1" style="border-color: blue;">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Edad</th>
                <th>Fecha de Nacimiento</th>
                <th>Sitio Web</th>
                <th>Género</th>
                <th>Intereses</th>
                <th>Comentarios</th>
                <th>Foto de Perfil</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($datosRegistros)): ?>
                <tr><td colspan="9">No hay registros disponibles.</td></tr>
            <?php else: ?>
                <?php foreach ($datosRegistros as $registro): ?>
                    <tr>
                        <td><?= isset($registro['nombre']) ? htmlspecialchars($registro['nombre']) : 'N/A' ?></td>
                        <td><?= isset($registro['email']) ? htmlspecialchars($registro['email']) : 'N/A' ?></td>
                        <td><?= isset($registro['edad']) ? htmlspecialchars($registro['edad']) : 'N/A' ?></td>
                        <td><?= isset($registro['nacimiento']) ? htmlspecialchars($registro['nacimiento']) : 'N/A' ?></td>
                        <td><?= isset($registro['sitio_web']) ? htmlspecialchars($registro['sitio_web']) : 'N/A' ?></td>
                        <td><?= isset($registro['genero']) ? htmlspecialchars($registro['genero']) : 'N/A' ?></td>
                        <td>
                            <?= isset($registro['intereses']) ? implode(', ', array_map('htmlspecialchars', $registro['intereses'])) : 'N/A' ?>
                        </td>
                        <td><?= isset($registro['comentarios']) ? htmlspecialchars($registro['comentarios']) : 'N/A' ?></td>
                        <td>
                            <?= isset($registro['foto_perfil']) ? "<img src='" . htmlspecialchars($registro['foto_perfil']) . "' width='100'>" : 'N/A' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
