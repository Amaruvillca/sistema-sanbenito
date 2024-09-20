<?php
namespace App;
class Servicios extends ActiveRecord{
    protected static $tabla = "servicios";
    protected static $nombreId = 'id_servicio';


    protected static $columnas_db = [
        'id_servicio',
        'nombre_servicio',
        'descripcion',
        'estado',
        'fecha_registro',
        'id_personal'
    ];

    public $id_servicio;
    public $nombre_servicio;
    public $descripcion;
    public $estado;
    public $fecha_registro;
    public $id_personal;
    public function __construct($args = [])
    {
        $this->id_servicio = $args['id_servicio'] ?? '';
        $this->nombre_servicio = $args['nombre_servicio'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->estado = $args['estado'] ?? '1';
        $this->fecha_registro = $args['fecha_registro'] ?? date('Y-m-d');
        $this->id_personal = $args['id_personal'] ?? '';
    }
    public function validar()
    {
        
    
        // Verificar si se ha proporcionado un email
        if (!$this->nombre_servicio) {
            self::$errores[] = 'Debes añadir el nombre del servicio';
        }
    
        // Verificar si se ha proporcionado un rol
        if (!$this->descripcion) {
            self::$errores[] = 'Debes añadir un descripcion';
        }
    
        return self::$errores;
    }
}