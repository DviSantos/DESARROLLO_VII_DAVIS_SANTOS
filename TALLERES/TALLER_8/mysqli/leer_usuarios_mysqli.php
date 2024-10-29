<?php
require_once "config_mysqli.php";

$sql = "SELECT id, nombre, email, fecha_registro FROM usuarios";
$result = mysqli_query($conn, $sql);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Nombre</th>";
        echo "<th>Email</th>";
        echo "<th>Fecha de Registro</th>";
        echo "<th>Acciones</th>";
        echo "</tr>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['nombre'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['fecha_registro'] . "</td>";
            echo "<td>";
            echo "<button><a href='actualizar_mysqli.php?id=" . $row['id'] . "'>Actualizar</a> </button>";
            echo "<button><a href='eliminar_mysqli.php?id=" . $row['id'] . "' onclick='return confirm(\"¿Estás seguro de eliminar este usuario?\");'>Eliminar</a></button>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<br>";
        echo "<button><a href='crear_usuario_mysqli.php'>Crear nuevo Usuario</a></button>";
        mysqli_free_result($result);
    } else {
        echo "No se encontraron registros.";
    }
} else {
    echo "ERROR: No se pudo ejecutar $sql. " . mysqli_error($conn);
}

mysqli_close($conn);
?>
