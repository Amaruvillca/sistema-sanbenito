<?php
namespace App;
class Mascotas extends ActiveRecord{

    protected static $tabla = "mascota";
    protected static $nombreId='id_mascota';
    protected static $columnas_db = [
        'id_mascota',
        'codigo_mascota',
        'imagen_mascota',
        'nombre',
        'especie',
        'sexo',
        'color',
        'raza',
        'fecha_nacimiento',
        'fecha_registro',
        'id_propietario'      
    ];
    
    public  $id_mascota;
    public  $codigo_mascota;
    public  $imagen_mascota;
    public  $nombre;
    public  $especie;
    public  $sexo;
    public  $color;
    public  $raza;
    public  $fecha_nacimiento;
    public  $fecha_registro;
    public $id_propietario;

    public function __construct($args = [])
    {
        $this->id_mascota= $args['id_mascota'] ?? '';
        $this->codigo_mascota= $args['codigo_mascota'] ?? '';
        $this->imagen_mascota= $args['imagen_mascota'] ?? '';
        $this->nombre= $args['nombre'] ?? '';
        $this->especie= $args['especie']?? '';
        $this->sexo= $args['sexo'] ?? '';     
        $this->color= $args['color'] ?? '';
        $this->raza= $args['raza'] ?? '';
        $this->fecha_nacimiento= $args['fecha_nacimiento'] ?? '';
        $this->fecha_registro= date('Y-m-d');
        $this->id_propietario= $args['id_propietario'] ?? '';
    }



}