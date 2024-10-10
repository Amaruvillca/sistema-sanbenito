<?php 
namespace App;

class CirugiaRealizada extends ActiveRecord {
    protected static $tabla = "cirugia_realizada";
    protected static $nombreId = 'id_cirugia_realizada';

    protected static $columnas_db = [
        'id_cirugia_realizada',
        'fecha_cirugia',
        'mucosa',
        'tiempo_de_llenado_capilar',
        'frecuencia_cardiaca',
        'frecuencia_respiratoria',
        'peso',
        'pulso',
        'observaciones',
        'costo',
        'id_cirugia_programada',
        'id_personal',
        'id_cuenta'
    ];

    public $id_cirugia_realizada;
    public $fecha_cirugia;
    public $mucosa;
    public $tiempo_de_llenado_capilar;
    public $frecuencia_cardiaca;
    public $frecuencia_respiratoria;
    public $peso;
    public $pulso;
    public $observaciones;
    public $costo;
    public $id_cirugia_programada;
    public $id_personal;
    public $id_cuenta;

    public function __construct($args = []) {
        $this->id_cirugia_realizada = $args['id_cirugia_realizada'] ?? '';
        $this->fecha_cirugia = $args['fecha_cirugia'] ?? date('Y-m-d');
        $this->mucosa = $args['mucosa'] ?? '';
        $this->tiempo_de_llenado_capilar = $args['tiempo_de_llenado_capilar'] ?? '';
        $this->frecuencia_cardiaca = $args['frecuencia_cardiaca'] ?? '';
        $this->frecuencia_respiratoria = $args['frecuencia_respiratoria'] ?? '';
        $this->peso = $args['peso'] ?? '';
        $this->pulso = $args['pulso'] ?? '';
        $this->observaciones = $args['observaciones'] ?? '';
        $this->costo = $args['costo'] ?? '0.00';
        $this->id_cirugia_programada = $args['id_cirugia_programada'] ?? null;
        $this->id_personal = $args['id_personal'] ?? null;
        $this->id_cuenta = $args['id_cuenta'] ?? null;
    }

    public function validar() {
        self::$errores = [];

        if (!$this->fecha_cirugia) {
            self::$errores[] = 'Debes añadir la fecha de la cirugía';
        }

        if (!$this->mucosa) {
            self::$errores[] = 'Debes añadir el estado de la mucosa';
        }

        if (!$this->tiempo_de_llenado_capilar || !is_numeric($this->tiempo_de_llenado_capilar)) {
            self::$errores[] = 'Debes añadir el tiempo de llenado capilar y debe ser numérico';
        }

        if (!$this->frecuencia_cardiaca || !is_numeric($this->frecuencia_cardiaca)) {
            self::$errores[] = 'Debes añadir la frecuencia cardíaca y debe ser numérica';
        }

        if (!$this->frecuencia_respiratoria || !is_numeric($this->frecuencia_respiratoria)) {
            self::$errores[] = 'Debes añadir la frecuencia respiratoria y debe ser numérica';
        }

        if (!$this->peso || !is_numeric($this->peso)) {
            self::$errores[] = 'Debes añadir el peso y debe ser numérico';
        }

        if (!$this->pulso || !is_numeric($this->pulso)) {
            self::$errores[] = 'Debes añadir el pulso y debe ser numérico';
        }

        if (!$this->observaciones) {
            self::$errores[] = 'Debes añadir las observaciones';
        }

        if (!$this->costo || !is_numeric($this->costo)) {
            self::$errores[] = 'Debes añadir el costo de la cirugía y debe ser numérico';
        }

        return self::$errores;
    }
}
