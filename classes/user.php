<?php

namespace App;

class User extends ActiveRecord
{
    protected static $tabla = "usuario";
    protected static $nombreId='id_usuario';
    protected static $columnas_db = [
        'id_usuario',
        'email',
        'password',
        'estado',
        'rol'
    ];
    public $id_usuario;
    public $email;
    public $password;
    public $estado;
    public $rol;

    public function __construct($args = [])
    {
        $this->id_usuario = $args['id_usuario']?? '';
        $this->email = $args['email']?? '';
        $this->password = $args['password']?? password_hash('123456789', PASSWORD_BCRYPT);
        $this->estado = $args['estado'] ?? '1';
        $this->rol = $args['rol'] ?? '';
    }

    public static function mostrar()
    {
        // Consulta SQL
        $query = 'SELECT 
                    u.id_usuario,
                    u.rol,
                    u.email,
                    u.estado,
                    p.id_personal,
                    p.nombres,
                    p.apellido_paterno,
                    p.apellido_materno,
                    p.num_celular,
                    p.num_carnet
                  FROM 
                    usuario u
                  LEFT JOIN 
                    personal p 
                  ON 
                    u.id_usuario = p.id_usuario
                  ORDER BY 
                    p.fecha_registro DESC';

        // Ejecutamos la consulta
        $result = self::$db->query($query);

        // Verificamos si la consulta fue exitosa
        if ($result->num_rows > 0) {
            // Array para almacenar los resultados
            $data = [];

            // Iteramos sobre los resultados y los almacenamos en el array
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            // Retornamos el array con los resultados
            return $data;
        } else {
            // Si no hay resultados, devolvemos un array vacío
            return [];
        }
    }
    public function validar()
    {
        // Verificar si el correo ya existe en la base de datos
        if ($this->verCorreo()) {
            self::$errores[] = 'Correo electrónico ya está en uso';
        }
    
        // Verificar si se ha proporcionado un email
        if (!$this->email) {
            self::$errores[] = 'Debes añadir un correo electrónico';
        }
    
        // Verificar si se ha proporcionado un rol
        if (!$this->rol) {
            self::$errores[] = 'Debes seleccionar un rol';
        }
    
        return self::$errores;
    }
    
    public function verCorreo()
    {
        // Consulta para verificar si el correo ya está registrado
        $query = "SELECT email FROM " . self::$tabla . " WHERE email = '" . self::$db->real_escape_string($this->email) . "'";
        
        $resultado = self::$db->query($query);
    
        // Verificar si la consulta devuelve algún resultado
        if ($resultado->num_rows > 0) {
            return true; // Correo ya existe
        }
        return false; // Correo no existe
    }
    
}
