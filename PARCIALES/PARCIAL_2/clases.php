
<?php
interface  Detalle{
    public function obtenerDetallesEspecificos():string;
}

abstract class Entrada implements  Detalle {

    public $id;
    public $fecha_creacion;
    public $tipo;
    public $titulo;
    public $descripcion;

    public function __construct($datos = []) {
        foreach ($datos as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}

class EntradaUnaColumna extends Entrada {

    public function __construct($datos = []) {
        parent::__construct($datos);
        $this->titulo='titulo';
        $this->descripcion='descripcion';
    }

    public function obtenerDetallesEspecificos():string {
        return "Entrada de una columna: " . $this->titulo;
    }

}

class EntradaDosColumnas extends Entrada {
    public $titulo1;
    public $descripcion1;
    public $titulo2;
    public $descripcion2;
    public function __construct($datos = []) {
        $this->titulo1='titulo1';
        $this->descripcion1='descripcion1';
        $this->titulo2='titulo2';
        $this->descripcion2='descripcion2';
        parent::__construct($datos);
    }
    
    public function obtenerDetallesEspecificos():  string {
        return "Entrada de dos columnas: " . $this->titulo1 . " | " . $this->titulo2;
    }
}

class EntradaTresColumnas extends Entrada {
    public $titulo1;
    public $descripcion1;
    public $titulo2;
    public $descripcion2;
    public $titulo3;
    public $descripcion3;
    public function __construct($datos = []) {
        $this->titulo1='titulo1';
        $this->descripcion1='descripcion1';
        $this->titulo2='titulo2';
        $this->descripcion2='descripcion2';
        $this->titulo3='titulo3';
        $this->descripcion3='descripcion3';
        parent::__construct($datos);
    }

    public function obtenerDetallesEspecificos(): string {
        return "Entrada de tres columnas: " . $this->titulo1 . " | " . $this->titulo2 . " | " . $this->titulo3;
    }
}
/**En la clase GestorBlog, implementen los siguientes métodos:
1. agregarEntrada(Entrada $entrada): Agrega una nueva entrada.
2. editarEntrada(Entrada $entrada): Actualiza una entrada existente.
3. eliminarEntrada($id): Elimina una entrada por su ID.
4. obtenerEntrada($id): Obtiene una entrada específica por su ID.
5. moverEntrada($id, $direccion): Mueve una entrada hacia arriba o abajo
en la lista. */
class GestorBlog {
    private $entradas = [];

    public function cargarEntradas() {
        if (file_exists('blog.json')) {
            $json = file_get_contents('blog.json');
            $data = json_decode($json, true);
            foreach ($data as $entradaData) {
                $this->entradas[] = new EntradaDosColumnas($entradaData);
            }
        }
    }

    public function guardarEntradas() {
        $data = array_map(function($entrada) {
            return get_object_vars($entrada);
        }, $this->entradas);
        file_put_contents('blog.json', json_encode($data, JSON_PRETTY_PRINT));
    }

    public function obtenerEntradas() {
        return $this->entradas;
    }

    public function agregarEntrada(Entrada $entrada) {
        $this->entradas[] = $entrada;
        $this->guardarEntradas();
    }

    public function editarEntrada(Entrada $entrada) {
        $index = array_search($entrada, $this->entradas);
        if ($index !== false) {
            $this->entradas[$index] = $entrada;
            $this->guardarEntradas();
        }
    }

    public function eliminarEntrada($id) {  
        $index = array_search($id, array_column($this->entradas, 'id'));
        if ($index !== false) {
            unset($this->entradas[$index]);
            $this->guardarEntradas();
        }
    }

    public function moverEntrada($id, $direccion) {
        $index = array_search($id, array_column($this->entradas, 'id'));
        if ($index !== false) {
            if ($direccion === 'up') {
                if ($index > 0) {
                    $temp = $this->entradas[$index - 1];
                    $this->entradas[$index - 1] = $this->entradas[$index];
                    $this->entradas[$index] = $temp;
                }
            } elseif ($direccion === 'down') {
                if ($index < count($this->entradas) - 1) {
                    $temp = $this->entradas[$index + 1];
                    $this->entradas[$index + 1] = $this->entradas[$index];
                    $this->entradas[$index] = $temp;
                }
            }
            $this->guardarEntradas();
        }
    }
    
}   