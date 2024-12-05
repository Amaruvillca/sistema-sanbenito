<?php
namespace App;

class ProgramarTratamiento extends ActiveRecord {
    protected static $tabla = "programar_tratamiento";
    protected static $nombreId = 'id_programacion_tratamiento';

    protected static $columnas_db = [
        'id_programacion_tratamiento',
        'dia_tratamiento',
        'fecha_programada',
        'id_consulta',
        'id_personal',
        'estado'
    ];

    public $id_programacion_tratamiento;
    public $dia_tratamiento;
    public $fecha_programada;
    public $id_consulta;
    public $id_personal;
    public $estado;

    public function __construct($args = [])
    {
        $this->id_programacion_tratamiento = $args['id_programacion_tratamiento'] ?? '';
        $this->dia_tratamiento = $args['dia_tratamiento'] ?? '';
        $this->fecha_programada = $args['fecha_programada'] ?? date('Y-m-d H:i:s');
        $this->id_consulta = $args['id_consulta'] ?? null;
        $this->id_personal = $args['id_personal'] ?? null;
        $this->estado = $args['estado'] ?? null;
    }

    public function validar()
    {
        self::$errores = [];
        
        // Validar día del tratamiento
        if (!$this->dia_tratamiento) {
            self::$errores[] = 'El día del tratamiento es obligatorio.';
        } elseif (!is_numeric($this->dia_tratamiento) || $this->dia_tratamiento < 1 || $this->dia_tratamiento > 31) {
            self::$errores[] = 'El día del tratamiento debe ser un número entre 1 y 31.';
        }
        
        // Validar fecha programada
        if (!$this->fecha_programada) {
            self::$errores[] = 'La fecha programada es obligatoria.';
        } elseif (!strtotime($this->fecha_programada)) {
            self::$errores[] = 'La fecha programada no tiene un formato válido.';
        }

        // Validar id_consulta
        if (!$this->id_consulta) {
            self::$errores[] = 'El ID de consulta es obligatorio.';
        }

        // Validar id_personal
        if (!$this->id_personal) {
            self::$errores[] = 'El ID del personal es obligatorio.';
        }

        return self::$errores;
    }
}
