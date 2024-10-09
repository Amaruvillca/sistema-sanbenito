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
        'num_celular_secundario',
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
    public $num_celular_secundario;
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
        $this->num_celular_secundario= $args['num_celular_secundario'] ?? '';     
        $this->email= $args['email'] ?? '';
        $this->direccion= $args['direccion'] ?? '';
        $this->fecha_registro= $args['fecha_registro'] ?? date('Y-m-d');
        $this->id_personal= $args['id_personal'] ?? '';
    }
    public function validar()
    {
        // Si no existe `id_personal`, es un nuevo registro; si existe, es una edición
        if (!$this->id_propietario) {
            // Validar en caso de nuevo registro
            if ($this->verCarnet()) {
                self::$errores[] = 'El número de carnet ya está registrado.';
            }
            if ($this->email && $this->verEmail()) {
                self::$errores[] = 'El correo electrónico ya esta registrado.';
            }
            if ($this->verCelular()) {
                self::$errores[] = 'El número de celular ya está registrado.';
            }
        } else {
            // Validar en caso de edición, solo si se modifican los campos únicos

            // Verificar si el número de carnet ha cambiado
            if ($this->verCarnetEdicion()) {
                self::$errores[] = 'El número de carnet ya está registrado.';
            }
            // Verificar si la matrícula profesional ha cambiado
            if ($this->verEmailEdition()) {
                self::$errores[] = 'El correo electrónico ya está registrada.';
            }
            // Verificar si el número de celular ha cambiado
            if ($this->verCelularEdicion()) {
                self::$errores[] = 'El número de celular ya está registrado.';
            }
        }

        // Verificación de los campos obligatorios, aplicable tanto para crear como para editar
        if (!$this->nombres) {
            self::$errores[] = 'El nombre es obligatorio.';
        }
        if (!$this->apellido_paterno && !$this->apellido_materno) {
            self::$errores[] = 'Llene el apellido paterno o materno';
        }
        if (!$this->num_celular) {
            self::$errores[] = 'El número de celular es obligatorio.';
        }
        if (!$this->num_carnet) {
            self::$errores[] = 'El número de carnet es obligatorio.';
        }
        if ($this->email && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$errores[] = 'Si se proporciona, el correo electrónico debe ser válido.';
        }
        
        if (!$this->direccion) {
            self::$errores[] = 'La especialidad es obligatoria.';
        }
       

        return self::$errores;
    }
    public function verCarnet()
    {
        // Consulta para verificar si el número de carnet ya está registrado (nuevo registro)
        $query = "SELECT num_carnet FROM " . self::$tabla . " WHERE num_carnet = '" . self::$db->real_escape_string($this->num_carnet) . "'";
        $resultado = self::$db->query($query);
        return $resultado->num_rows > 0;
    }

    public function verCarnetEdicion()
    {
        // Verificar si el número de carnet ya está registrado por otro usuario en edición
        $query = "SELECT num_carnet FROM " . self::$tabla . " WHERE num_carnet = '" . self::$db->real_escape_string($this->num_carnet) . "' AND id_propietario != '" . self::$db->real_escape_string($this->id_propietario) . "' ";
        $resultado = self::$db->query($query);
        return $resultado->num_rows > 0;
    }

    public function verEmail()
    {
        // Consulta para nuevo registro
        $query = "SELECT email FROM " . self::$tabla . " WHERE email = '" . self::$db->real_escape_string($this->email) . "'";
        $resultado = self::$db->query($query);
        return $resultado->num_rows > 0;
    }

    public function verEmailEdition()
    {
        // Verificar en edición que la matrícula no esté registrada por otro usuario
        $query = "SELECT email FROM " . self::$tabla . " WHERE email = '" . self::$db->real_escape_string($this->email) . "' AND id_propietario != '" . self::$db->real_escape_string($this->id_propietario) . "' ";
        $resultado = self::$db->query($query);
        return $resultado->num_rows > 0;
    }

    public function verCelular()
    {
        // Consulta para nuevo registro
        $query = "SELECT num_celular FROM " . self::$tabla . " WHERE num_celular = '" . self::$db->real_escape_string($this->num_celular) . "'";
        $resultado = self::$db->query($query);
        return $resultado->num_rows > 0;
    }

    public function verCelularEdicion()
    {
        // Verificar en edición que el celular no esté registrado por otro usuario
        $query = "SELECT num_celular FROM " . self::$tabla . " WHERE num_celular = '" . self::$db->real_escape_string($this->num_celular) . "' AND id_propietario != '" . self::$db->real_escape_string($this->id_propietario) . "' ";
        $resultado = self::$db->query($query);
        return $resultado->num_rows > 0;
    }
}