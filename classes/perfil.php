<?php

namespace App;

class Perfil extends ActiveRecord
{
    protected static $tabla = "personal";
    protected static $nombreId = 'id_personal';


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
        $this->id_personal = $args['id_personal'] ?? '';
        $this->imagen_personal = $args['imagen_personal'] ?? 'veterinario.jpg';
        $this->nombres = $args['nombres'] ?? '';
        $this->apellido_paterno = $args['apellido_paterno'] ?? '';
        $this->apellido_materno = $args['apellido_materno'] ?? '';
        $this->num_celular = $args['num_celular'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
        $this->num_carnet = $args['num_carnet'] ?? '';
        $this->profesion = $args['profesion'] ?? '';
        $this->especialidad = $args['especialidad'] ?? '';
        $this->matricula_profesional = $args['matricula_profesional'] ?? '';
        $this->fecha_registro = $args['fecha_registro'] ?? date('Y-m-d');
        $this->id_usuario = $args['id_usuario'] ?? '';
    }
    public function setImagen($imagen)
    {
        if ($imagen) {
            $this->imagen_personal = $imagen;
        }
    }

    public function validar()
    {
        // Si no existe `id_personal`, es un nuevo registro; si existe, es una edición
        if (!$this->id_personal) {
            // Validar en caso de nuevo registro
            if ($this->verCarnet()) {
                self::$errores[] = 'El número de carnet ya está registrado.';
            }
            if ($this->verMatriculaProfesional()) {
                self::$errores[] = 'La matrícula profesional ya está registrada.';
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
            if ($this->verMatriculaProfesionalEdicion()) {
                self::$errores[] = 'La matrícula profesional ya está registrada.';
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
        if (!$this->direccion) {
            self::$errores[] = 'La dirección es obligatoria.';
        }
        if (!$this->num_carnet) {
            self::$errores[] = 'El número de carnet es obligatorio.';
        }
        if (!$this->profesion) {
            self::$errores[] = 'La profesión es obligatoria.';
        }
        if (!$this->especialidad) {
            self::$errores[] = 'La especialidad es obligatoria.';
        }
        if (!$this->matricula_profesional) {
            self::$errores[] = 'La matrícula profesional es obligatoria.';
        }
         if (!$this->matricula_profesional) {
            self::$errores[] = 'La matrícula profesional es obligatoria.';
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
        $query = "SELECT num_carnet FROM " . self::$tabla . " WHERE num_carnet = '" . self::$db->real_escape_string($this->num_carnet) . "' AND id_personal != '" . self::$db->real_escape_string($this->id_personal) . "' ";
        $resultado = self::$db->query($query);
        return $resultado->num_rows > 0;
    }

    public function verMatriculaProfesional()
    {
        // Consulta para nuevo registro
        $query = "SELECT matricula_profesional FROM " . self::$tabla . " WHERE matricula_profesional = '" . self::$db->real_escape_string($this->matricula_profesional) . "'";
        $resultado = self::$db->query($query);
        return $resultado->num_rows > 0;
    }

    public function verMatriculaProfesionalEdicion()
    {
        // Verificar en edición que la matrícula no esté registrada por otro usuario
        $query = "SELECT matricula_profesional FROM " . self::$tabla . " WHERE matricula_profesional = '" . self::$db->real_escape_string($this->matricula_profesional) . "' AND id_personal != '" . self::$db->real_escape_string($this->id_personal) . "' ";
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
        $query = "SELECT num_celular FROM " . self::$tabla . " WHERE num_celular = '" . self::$db->real_escape_string($this->num_celular) . "' AND id_personal != '" . self::$db->real_escape_string($this->id_personal) . "' ";
        $resultado = self::$db->query($query);
        return $resultado->num_rows > 0;
    }
}
