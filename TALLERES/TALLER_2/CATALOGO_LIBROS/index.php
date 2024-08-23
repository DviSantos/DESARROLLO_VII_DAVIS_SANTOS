<?php
// Incluir archivos necesarios
include 'includes/header.php';
require 'includes/funciones.php';

// Obtener la lista de libros
$libros = obtenerLibros();
?>

<div class="catalogo">
    <?php
    //detalles de cada libro
    foreach ($libros as $libro) {
        echo mostrarDetallesLibro($libro);
    }
    ?>
</div>

<?php include 'includes/footer.php'; ?>