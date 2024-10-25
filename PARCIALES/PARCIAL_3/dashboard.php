<?php
session_start();
if(!isset($_SESSION['usuario'])) {
    header("Location: validar.php");
    exit;
}

if (!isset($_SESSION['tareas'])) {
    $_SESSION['tareas'] = [];
}

$error = "";
$vacio = "";

//procesar y validar formulario de tareas y mostrarlo en el html

if (!isset($_POST['tarea']) || !isset($_POST['fecha_limite'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tarea = htmlspecialchars($_POST['tarea']);
        $fecha_limite = $_POST['fecha_limite'];
        $tareas = $_SESSION['tareas'];
        $tareas[] = ['tarea' => $tarea, 'fecha_limite' => $fecha_limite];
        $_SESSION['tareas'] = $tareas;
    }else{
        $error = "Por favor, llene todos los campos";
    }
}else{
    $vacio = "No hay tareas agregadas";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h2><?php echo "Bienvenido ". $_SESSION['usuario']; ?></h2><br><br>
    <h2>TAREAS</h2><br>
    <!-- Mostrar las tareas en el dashboard -->
    <?php 
    if (!empty($_SESSION['tareas'])) {
        foreach ($_SESSION['tareas'] as $tarea) {
            echo "<p>$tarea[tarea] - $tarea[fecha_limite]</p>";
        }
    }else{
        echo "<p>$vacio</p>";
    }
    
    ?>
    <h2>Agregar Tareas</h2>
    <form action="dashboard.php" method="POST"></form>
    <label for="tarea">Tarea</label>
    <input type="text" name="tarea" required>
    <label for="fecha">Fecha limite</label>
    <input type="date" name="fecha_limite" required>
    <input type="submit" value="Guardar">
    </form>
    <?php 
    if (isset($error)) {
        echo "<p style='color: red;'>$error</p>";
    }
    ?>
    <a href="cerrar_sesion.php">Cerrar Sesion</a>    
</body>
</html>