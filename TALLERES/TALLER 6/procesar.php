<?php
require_once 'validaciones.php';
require_once 'sanitizacion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errores = [];
    $datos = [];

    // Procesar y validar cada campo
    $campos = ['nombre', 'email', 'edad', 'sitioWeb', 'genero', 'intereses', 'comentarios'];
    foreach ($campos as $campo) {
        if (isset($_POST[$campo])) {
            $valor = $_POST[$campo];
            $valorSanitizado = call_user_func("sanitizar" . ucfirst($campo), $valor);
            $datos[$campo] = $valorSanitizado;

            if (!call_user_func("validar" . ucfirst($campo), $valorSanitizado)) {
                $errores[] = "El campo $campo no es válido.";
            }
        }
    }

    // Procesar la foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_NO_FILE) {
        if (!validarFotoPerfil($_FILES['foto_perfil'])) {
            $errores[] = "La foto de perfil no es válida.";
        } else {
            $rutaDestino = 'uploads/' . basename($_FILES['foto_perfil']['name']);
            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $rutaDestino)) {
                $datos['foto_perfil'] = $rutaDestino;
            } else {
                $errores[] = "Hubo un error al subir la foto de perfil.";
            }
        }
    }

    // funcion para calcular la edad
    function calcularEdad($fechaNacimiento) {
        $fechaNacimiento = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($fechaNacimiento)->y;
        return $edad;
    }
    

    // Mostrar resultados o errores
    if (empty($errores)) {
        echo "<h2>Datos Recibidos:</h2>";
        echo "<table border='1'>";
        foreach ($datos as $campo => $valor) {
            echo "<tr>";
            echo "<th>" . ucfirst($campo) . "</th>";
            if ($campo === 'intereses') {
                echo "<td>" . implode(", ", $valor) . "</td>";
            } elseif ($campo === 'foto_perfil') {
                echo "<td><img src='$valor' width='100'></td>";
            } else {
                echo "<td>$valor</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<h2>Errores:</h2>";
        echo "<ul>";
        foreach ($errores as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
} else {
    echo "Acceso no permitido.";
}

// Guardar datos en un archivo JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $datos = [
        'nombre' => $_POST['nombre'] ?? '',
        'email' => $_POST['email'] ?? '',
        'edad' => $_POST['edad'] ?? '',
        'nacimiento' => $_POST['nacimiento'] ?? '',
        'sitio_web' => $_POST['sitio_web'] ?? '',
        'genero' => $_POST['genero'] ?? '',
        'intereses' => $_POST['intereses'] ?? [],
        'comentarios' => $_POST['comentarios'] ?? '',
        'foto_perfil' => 'uploads/' . $_FILES['foto_perfil']['name'] // Asegúrate de mover la foto antes de guardar
    ];

    // Mover el archivo subido
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        move_uploaded_file($_FILES['foto_perfil']['tmp_name'], 'uploads/' . $_FILES['foto_perfil']['name']);
    }

    // Leer datos existentes
    $registros = [];
    if (file_exists('datos_registrados.json')) {
        $registros = json_decode(file_get_contents('datos_registrados.json'), true);
    }

    // Agregar nuevo registro
    $registros[] = $datos;

    // Guardar nuevamente en el archivo
    file_put_contents('datos_registrados.json', json_encode($registros, JSON_PRETTY_PRINT));
}

echo "<br><a href='formulario.html'>Volver al formulario</a>";
echo "<br><a href='resumen.php'>Ver Resumen de Registros</a>";
?>