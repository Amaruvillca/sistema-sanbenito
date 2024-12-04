<?php 
namespace App;

class Medicacion extends ActiveRecord {
    protected static $tabla = "medicacionconsulta";
    protected static $nombreId = 'id_mediacion';

    protected static $columnas_db = [
        'id_mediacion',
        'nombre_medicacion',
        'via',
        'costo',
        'fecha_medicacion',
        'id_tratamiento',
        'id_cuenta'
    ];

    public $id_mediacion;
    public $nombre_medicacion;
    public $via;
    public $costo;
    public $fecha_medicacion;

    public $id_tratamiento;
    public $id_cuenta;

    public function __construct($args = []) {
        $this->id_mediacion = $args['id_mediacion'] ?? '';
        $this->nombre_medicacion = $args['nombre_medicacion'] ?? '';
        $this->via = $args['via'] ?? '';
        $this->costo = $args['costo'] ?? '0.00';
        $this->fecha_medicacion = $args['fecha_medicacion'] ?? date('Y-m-d');
        
        $this->id_tratamiento = $args['id_tratamiento'] ?? null;
        $this->id_cuenta = $args['id_cuenta'] ?? null;
    }

    public function validar() {

        if (!$this->nombre_medicacion) {
            self::$errores[] = 'Debes añadir el nombre de la medicación';
        }

        if (!$this->via) {
            self::$errores[] = 'Debes añadir la vía de administración';
        }

        if (!$this->costo || !is_numeric($this->costo)) {
            self::$errores[] = 'Debes añadir el costo de la medicación y debe ser numérico';
        }

        if (!$this->fecha_medicacion) {
            self::$errores[] = 'Debes añadir la fecha de medicación';
        }

        return self::$errores;
    }
}
