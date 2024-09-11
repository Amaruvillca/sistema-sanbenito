<?php
namespace App;
class Propietarios extends ActiveRecord{

    protected static $tabla = "propietario";
    protected static $nombreId='id_propietario';
    protected static $columnas_db = [
        'id_propietario',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'num_carnet',
        'num_celular',
        'email',
        'direccion',
        'fecha_registro',
        'id_personal'      
    ];
    
    public  $id_propietario;
    public  $nombres;
    public  $apellido_paterno;
    public  $apellido_materno;
    public  $num_carnet;
    public  $num_celular;
    public  $email;
    public  $direccion;
    public  $fecha_registro;
    public  $id_personal;

    public function __construct($args = [])
    {
        $this->id_propietario= $args['id_propietario'] ?? '';
        $this->nombres= $args['nombres'] ?? '';
        $this->apellido_paterno= $args['apellido_paterno'] ?? '';
        $this->apellido_materno= $args['apellido_materno'] ?? '';
        $this->num_carnet= $args['num_carnet']?? '';
        $this->num_celular= $args['num_celular'] ?? '';     
        $this->email= $args['email'] ?? '';
        $this->direccion= $args['direccion'] ?? '';
        $this->fecha_registro= date('Y-m-d');
        $this->id_personal= $args['id_personal'] ?? '';
    }
}