<?php
class RecursoBiblioteca {

    public $prestable;
    public $id;
    public $titulo;
    public $autor;
    public $anioPublicacion;
    public $estado;
    public $fechaAdquisicion;
    public $tipo;

 
    public function __construct($datos) {
      

        foreach ($datos as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        
    }

   
}



// Implementar las clases Libro, Revista y DVD aquí

class GestorBiblioteca {
    private $recursos = [];

    public function cargarRecursos() {
        $json = file_get_contents('biblioteca.json');
        $data = json_decode($json, true);
        
        foreach ($data as $recursoData) {
            $recurso = new RecursoBiblioteca($recursoData);
            $this->recursos[] = $recurso;
        }
        
        return $this->recursos;
    }

    public function obtenerDetallesPrestamo() {
        return "Detalles del recurso.";
    }

    /* Implementen los siguientes métodos en la clase GestorBiblioteca:
• agregarRecurso(RecursoBiblioteca $recurso)
• eliminarRecurso($id)
• actualizarRecurso(RecursoBiblioteca $recurso)
• actualizarEstadoRecurso($id, $nuevoEstado)
• buscarRecursosPorEstado($estado)
• listarRecursos($filtroEstado = '', $campoOrden = 'id', $direccionOrden = 
'ASC') */
public function agregarRecurso(RecursoBiblioteca $recurso) {
    $this->recursos[] = $recurso;

}
public function eliminarRecurso($id){
    unset($this->recurso[$id]);
}

public function actualizarRecurso(RecursoBiblioteca $recurso){
    
}

}

// Clase Libro que hereda de RecursoBiblioteca
class Libro extends RecursoBiblioteca {
    private $isbn;

    public function __construct($titulo, $autor, $isbn) {
        parent::__construct($titulo, $autor);
        $this->isbn = $isbn;
    }

    public function getIsbn() {
        return $this->isbn;
    }

    // Implementación del método obtenerDetallesPrestamo
    public function obtenerDetallesPrestamo() {
        return "Libro: " . $this->titulo . " | Autor: " . $this->autor . " | ISBN: " . $this->isbn;
    }
}

// Clase Revista que hereda de RecursoBiblioteca
class Revista extends RecursoBiblioteca {
    private $numeroEdicion;

    public function __construct($titulo, $autor, $numeroEdicion) {
        parent::__construct($titulo, $autor);
        $this->numeroEdicion = $numeroEdicion;
    }

    public function getNumeroEdicion() {
        return $this->numeroEdicion;
    }

    // Implementación del método obtenerDetallesPrestamo
    public function obtenerDetallesPrestamo() {
        return "Revista: " . $this->titulo . " | Autor: " . $this->autor . " | Número de Edición: " . $this->numeroEdicion;
    }
}

// Clase DVD que hereda de RecursoBiblioteca
class DVD extends RecursoBiblioteca {
    private $duracion; // duración en minutos

    public function __construct($titulo, $autor, $duracion) {
        parent::__construct($titulo, $autor);
        $this->duracion = $duracion;
    }

    public function getDuracion() {
        return $this->duracion;
    }

    // Implementación del método obtenerDetallesPrestamo
    public function obtenerDetallesPrestamo() {
        return "DVD: " . $this->titulo . " | Director: " . $this->autor . " | Duración: " . $this->duracion . " minutos";
    }

    // Implementar los demás métodos aquí
}