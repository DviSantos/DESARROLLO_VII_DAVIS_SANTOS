<?php
function obtenerLibros() {
    // Simular una base de datos de libros
    return [
        [
            'titulo' => 'El Quijote',
            'autor' => 'Miguel de Cervantes',
            'anio_publicacion' => 1605,
            'genero' => 'Novela',
            'descripcion' => 'La historia del ingenioso hidalgo Don Quijote de la Mancha.'
        ],
        [
            'titulo' => 'Cien Años de Soledad',
            'autor' => 'Gabriel García Márquez',
            'anio_publicacion' => 1967,
            'genero' => 'Realismo Mágico',
            'descripcion' => 'La obra maestra de García Márquez, que narra la historia de la familia Buendía.'
        ],
        [
            'titulo' => '1984',
            'autor' => 'George Orwell',
            'anio_publicacion' => 1949,
            'genero' => 'Distopía',
            'descripcion' => 'Una novela sobre un futuro totalitario y opresivo.'
        ],
        [
            'titulo' => 'Orgullo y Prejuicio',
            'autor' => 'Jane Austen',
            'anio_publicacion' => 1813,
            'genero' => 'Romance',
            'descripcion' => 'La historia de Elizabeth Bennet y Mr. Darcy en la sociedad inglesa.'
        ],
        [
            'titulo' => 'El Hobbit',
            'autor' => 'J.R.R. Tolkien',
            'anio_publicacion' => 1937,
            'genero' => 'Fantasía',
            'descripcion' => 'Una aventura épica en la Tierra Media protagonizada por Bilbo Bolsón.'
        ]
    ];
}

function mostrarDetallesLibro($libro) {
    // Crear una cadena HTML con los detalles del libro
    return "
    <div class='libro'>
        <h3>{$libro['titulo']}</h3>
        <p><strong>Autor:</strong> {$libro['autor']}</p>
        <p><strong>Año de Publicación:</strong> {$libro['anio_publicacion']}</p>
        <p><strong>Género:</strong> {$libro['genero']}</p>
        <p>{$libro['descripcion']}</p>
    </div>
    ";
}
?>