<?php
namespace App;

class Tratamiento extends ActiveRecord {
    protected static $tabla = "tratamiento";
    protected static $nombreId = 'id_tratamiento';

    protected static $columnas_db = [
        'id_tratamiento',
        'fecha_tratamiento',
        'peso',
        'temperatura',
        'observaciones',
        'id_programacion_tratamiento',
        'id_personal'
    ];

    public $id_tratamiento;
    public $fecha_tratamiento;
    public $peso;
    public $temperatura;
    public $observaciones;
    public $id_programacion_tratamiento;
    public $id_personal;

    public function __construct($args = [])
    {
        $this->id_tratamiento = $args['id_tratamiento'] ?? null;
        $this->fecha_tratamiento = $args['fecha_tratamiento'] ?? date('Y-m-d');
        $this->peso = $args['peso'] ?? 0.0;
        $this->temperatura = $args['temperatura'] ?? 0.0;
        $this->observaciones = $args['observaciones'] ?? '';
        $this->id_programacion_tratamiento = $args['id_programacion_tratamiento'] ?? null;
        $this->id_personal = $args['id_personal'] ?? null;
    }

    public function validar()
    {
        self::$errores = [];

        if (!$this->fecha_tratamiento) {
            self::$errores[] = 'La fecha del tratamiento es obligatoria.';
        }
        if ($this->peso <= 0) {
            self::$errores[] = 'El peso debe ser mayor a 0.';
        }
        if ($this->temperatura <= 0) {
            self::$errores[] = 'La temperatura debe ser mayor a 0.';
        }
        if (!$this->observaciones) {
            self::$errores[] = 'Debes añadir observaciones.';
        }

        return self::$errores;
    }
    public function cambiarEstado($id_cirugia_programada, $nuevoEstado='concluida') {
        // Escapar los valores para evitar inyecciones SQL
        $id_cirugia_programada = self::$db->real_escape_string($id_cirugia_programada);
        $nuevoEstado = self::$db->real_escape_string($nuevoEstado);
    
        // Consulta SQL para actualizar el estado
        $query = "UPDATE programar_tratamiento 
                  SET estado = '$nuevoEstado' 
                  WHERE id_programacion_tratamiento = $id_cirugia_programada";
    
        // Ejecutar la consulta
        $resultado = self::$db->query($query);
    
        // Comprobar si la consulta fue exitosa
        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }
    public static function buscarTratamiento($id_cuenta)
    {
        $query = "SELECT * 
FROM tratamiento 
WHERE id_programacion_tratamiento = $id_cuenta 
";

//debuguear($query);
        $resultado = self::consultarSql($query);
        
        
        //debuguear($resultado);
        return $resultado;
    }
}
