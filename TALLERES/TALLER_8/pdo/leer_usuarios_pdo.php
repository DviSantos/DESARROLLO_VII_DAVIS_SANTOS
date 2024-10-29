<?php
require_once "config_pdo.php";

$sql = "SELECT id, nombre, email, fecha_registro FROM usuarios";

if($stmt = $pdo->prepare($sql)){
    if($stmt->execute()){
        if($stmt->rowCount() > 0){
            echo "<table>";
                echo "<tr>";
                    echo "<th>ID</th>";
                    echo "<th>Nombre</th>";
                    echo "<th>Email</th>";
                    echo "<th>Fecha de Registro</th>";
                    echo "<th>Acciones</th>";
                echo "</tr>";
            while($row = $stmt->fetch()){
                echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nombre'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['fecha_registro'] . "</td>";
                    echo "<td>";
                    echo "<button><a href='actualizar_pdo.php?id=" . $row['id'] . "'>Actualizar</a> </button>";
                    echo "<button><a href='eliminar_pdo.php?id=" . $row['id'] . "' onclick='return confirm(\"¿Estás seguro de eliminar este usuario?\");'>Eliminar</a></button>";
                    echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<button><a href = 'crear_usuario_pdo.php'>Crear usuario</a></button>";
        } else{
            echo "No se encontraron registros.";
            echo "<button><a href = 'crear_usuario_pdo.php'>Crear usuario</a></button>";
        }
    } else{
        echo "ERROR: No se pudo ejecutar $sql. " . $stmt->errorInfo()[2];
    }
}

unset($stmt);
unset($pdo);
?>