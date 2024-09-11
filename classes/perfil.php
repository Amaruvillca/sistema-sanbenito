<?php
namespace App;
class Perfil extends ActiveRecord{
    protected static $tabla = "personal";
    protected static $nombreId='id_personal';
    protected static $columnas_db = [
        'id_personal',
        'imagen_personal',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'num_celular',
        'direccion',
        'num_carnet',
        'profesion',
        'especialidad',
        'matricula_profesional',
        'fecha_registro',
        'id_usuario'
    ];
    public $id_personal;
    public $imagen_personal;
    public $nombres;
    public $apellido_paterno;
    public $apellido_materno;
    public $num_celular;
    public $direccion;
    public $num_carnet;
    public $profesion;
    public $especialidad;
    public $matricula_profesional;
    public $fecha_registro;
    public $id_usuario;
    public function __construct($args = [])
    {
        $this->id_personal= $args['id_personal'] ?? '';
        $this->imagen_personal=$args['imagen_personal']?? 'veterinario.jpg';
        $this->nombres= $args['nombres'] ?? '';
        $this->apellido_paterno= $args['apellido_paterno'] ?? '';
        $this->apellido_materno= $args['apellido_materno'] ?? '';
        $this->num_celular= $args['num_celular'] ?? '';
        $this->direccion= $args['direccion'] ?? '';
        $this->num_carnet= $args['num_carnet']?? '';
        $this->profesion= $args['profesion'] ?? '';
        $this->especialidad= $args['especialidad'] ?? '';
        $this->matricula_profesional= $args['matricula_profesional'] ?? '';
        $this->fecha_registro= date('Y-m-d');
        $this->id_usuario= $args['id_usuario'] ?? '';
    }
    public function setImagen($imagen)
    {
        if ($imagen) {
            $this->imagen_personal= $imagen;
        }
    }
    public function validar()
{
    // Verificar si el número de carnet ya está registrado en la base de datos
    if ($this->verCarnet()) {
        self::$errores[] = 'El número de carnet ya está registrado.';
    }
    // Verificar si la matrícula profesional ya está registrada en la base de datos
    if ($this->verMatriculaProfesional()) {
        self::$errores[] = 'La matrícula profesional ya está registrada.';
    }
    if ($this->verCelular()) {
        self::$errores[] = 'El número de celular ya está registrado';
    }
    
    // Verificar si se ha proporcionado el nombre
    if (!$this->nombres) {
        self::$errores[] = 'El nombre es obligatorio.';
    }
    if (!$this->apellido_paterno && !$this->apellido_materno) {
        self::$errores[] = 'Llene el apellido paterno o materno';
    }
    
    // Verificar si se ha proporcionado un número de celular
    if (!$this->num_celular) {
        self::$errores[] = 'El número de celular es obligatorio.';
    }
    
    // Verificar si se ha proporcionado la dirección
    if (!$this->direccion) {
        self::$errores[] = 'La dirección es obligatoria.';
    }
    
    // Verificar si se ha proporcionado el número de carnet
    if (!$this->num_carnet) {
        self::$errores[] = 'El número de carnet es obligatorio.';
    }

    // Verificar si se ha proporcionado la profesión
    if (!$this->profesion) {
        self::$errores[] = 'La profesión es obligatoria.';
    }

    // Verificar si se ha proporcionado la especialidad
    if (!$this->especialidad) {
        self::$errores[] = 'La especialidad es obligatoria.';
    }

    // Verificar si se ha proporcionado la matrícula profesional
    if (!$this->matricula_profesional) {
        self::$errores[] = 'La matrícula profesional es obligatoria.';
    }

    return self::$errores;
}

public function verCarnet()
{
    // Consulta para verificar si el número de carnet ya está registrado
    $query = "SELECT num_carnet FROM " . self::$tabla . " WHERE num_carnet = '" . self::$db->real_escape_string($this->num_carnet) . "'";
    
    $resultado = self::$db->query($query);

    // Verificar si la consulta devuelve algún resultado
    if ($resultado->num_rows > 0) {
        return true; // El número de carnet ya existe
    }
    return false; // El número de carnet no existe
}

public function verMatriculaProfesional()
{
    // Consulta para verificar si la matrícula profesional ya está registrada
    $query = "SELECT matricula_profesional FROM " . self::$tabla . " WHERE matricula_profesional = '" . self::$db->real_escape_string($this->matricula_profesional) . "'";
    
    $resultado = self::$db->query($query);

    // Verificar si la consulta devuelve algún resultado
    if ($resultado->num_rows > 0) {
        return true; 
    }
    return false;
}
public function verCelular()
{
    // Consulta para verificar si la matrícula profesional ya está registrada
    $query = "SELECT num_celular FROM " . self::$tabla . " WHERE num_celular = '" . self::$db->real_escape_string($this->num_celular) . "'";
    
    $resultado = self::$db->query($query);

    // Verificar si la consulta devuelve algún resultado
    if ($resultado->num_rows > 0) {
        return true; 
    }
    return false;
}

}