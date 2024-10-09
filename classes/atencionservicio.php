<?php
namespace App;

class Atencionservicio extends ActiveRecord{
    protected static $tabla = "atiende_servicio";
    protected static $nombreId = 'id_atencion_servicio';

    protected static $columnas_db = [
        'id_atencion_servicio',
        'observaciones',
        'costo',
        'fecha_servicio',
        'id_mascota',
        'id_personal',
        'id_servicio',
        'id_cuenta'
    ];
    public $id_atencion_servicio;
    public $observaciones;
    public $costo;
    public $fecha_servicio;
    public $id_mascota;
    public $id_personal;
    public $id_servicio;
    public $id_cuenta;

    public function __construct($args = [])
    {
        $this->id_atencion_servicio = $args['id_atencion_servicio'] ?? '';
        $this->observaciones = $args['observaciones'] ?? '';
        $this->costo = $args['costo'] ?? '';
        $this->fecha_servicio = $args['fecha_servicio'] ?? date('Y-m-d');
        $this->id_mascota = $args['id_mascota'] ?? '';
        $this->id_personal = $args['id_personal'] ?? '';
        $this->id_servicio = $args['id_servicio'] ?? '';
        $this->id_cuenta = $args['id_cuenta'] ?? '';
    }

    // Getters
    
    public function validar()
    {
       

        // Validar id_mascota
        if (!$this->id_mascota) {
            self::$errores[] = 'Debes seleccionar la mascota.';
        }

        // Validar id_personal
        if (!$this->id_personal) {
            self::$errores[] = 'Debes seleccionar el personal que realizó el servicio.';
        }

        // Validar id_servicio
        if (!$this->id_servicio) {
            self::$errores[] = 'Debes seleccionar el tipo de servicio realizado.';
        }

        // Validar observaciones
        if (!$this->observaciones) {
            self::$errores[] = 'Debes añadir observaciones sobre el servicio.';
        }

        // Validar costo (que no esté vacío y que sea un número)
        if (!$this->costo) {
            self::$errores[] = 'Debes especificar el costo del servicio.';
        } elseif (!is_numeric($this->costo)) {
            self::$errores[] = 'El costo debe ser un número válido.';
        }
        if (!is_numeric($this->costo) || $this->costo < 0) {
            self::$errores[] = 'El costo de la vacuna debe ser un número positivo.';
        }
        return self::$errores;
    }
}
