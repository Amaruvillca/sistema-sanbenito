<?php
namespace App;
class Ciruguas extends ActiveRecord{
    protected static $tabla = "cirugias";
    protected static $nombreId = 'id_cirugia';


    protected static $columnas_db = [
        'id_cirugia',
        'nombre_cirugia',
        'descripcion',
        'estado',
        'fecha_registro',
        'frecuencia',
        'id_personal'
    ];

    public $id_cirugia;
    public $nombre_cirugia;
    public $descripcion;
    public $estado;
    public $fecha_registro;
    public $frecuencia;
    public $id_personal;
    public function __construct($args = [])
    {
        $this->id_cirugia = $args['id_cirugia'] ?? '';
        $this->nombre_cirugia = $args['nombre_cirugia'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->estado = $args['estado'] ?? '1';
        $this->fecha_registro = $args['fecha_registro'] ?? date('Y-m-d');
        $this->frecuencia = $args['frecuencia']?? '';
        $this->id_personal = $args['id_personal'] ?? '';
    }
    public function validar()
    {
        
    
        // Verificar si se ha proporcionado un email
        if (!$this->nombre_cirugia) {
            self::$errores[] = 'Debes añadir el nombre de la cirugia';
        }
    
        // Verificar si se ha proporcionado un rol
        if (!$this->descripcion) {
            self::$errores[] = 'Debes añadir un descripcion';
        }
        if (!$this->frecuencia) {
            self::$errores[] = 'Debes seleccionar la frecuencia';
        }

    
        return self::$errores;
    }
}