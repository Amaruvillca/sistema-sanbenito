<?php
namespace App;
class Desparacitaciones extends ActiveRecord{
    
    protected static $tabla = "desparasitacion";
    protected static $nombreId = 'id_desparasitacion';


    protected static $columnas_db = [
        'id_desparasitacion',
        'producto',
        'tipo_desparasitacion',
        'principio_activo',
        'via',
        'costo',
        'fecha_aplicacion',
        'proxima_desparasitacion',
        'id_mascota',
        'id_personal'
    ];

    public $id_desparasitacion;
    public $producto;
    public $tipo_desparasitacion;
    public $principio_activo;
    public $via;
    public $costo;
    public $fecha_aplicacion;
    public $proxima_desparasitacion;
    public $id_mascota;
    public $id_personal;

    public function __construct($args = [])
    {
        $this->id_desparasitacion = $args['id_desparasitacion'] ?? '';
        $this->producto = $args['producto'] ?? '';
        $this->tipo_desparasitacion = $args['tipo_desparasitacion'] ?? '';
        $this->principio_activo = $args['principio_activo'] ?? '';
        $this->via = $args['via'] ?? '';
        $this->costo = $args['costo'] ?? '';
        $this->fecha_aplicacion = $args['fecha_aplicacion'] ?? date('Y-m-d');
        $this->proxima_desparasitacion = $args['proxima_desparasitacion'] ?? '';
        $this->id_mascota = $args['id_mascota'] ?? '';
        $this->id_personal = $args['id_personal'] ?? '';
    }
    public function validar()
    {
        
          // Validar producto
          if (!$this->producto) {
            self::$errores[] = 'Debes añadir el producto utilizado para la desparasitación.';
        }

        // Validar tipo de desparasitación
        if (!$this->tipo_desparasitacion) {
            self::$errores[] = 'Debes añadir el tipo de desparasitación.';
        }

        // Validar costo
        if (!$this->costo) {
            self::$errores[] = 'Debes añadir el costo de la desparasitación.';
        }

        // Validar vía de administración
        if (!$this->via) {
            self::$errores[] = 'Debes añadir la vía de administración.';
        }

        // Validar fecha de aplicación y próxima desparasitación
        if (!$this->fecha_aplicacion) {
            self::$errores[] = 'Debes añadir la fecha de aplicación.';
        }

        if (!$this->proxima_desparasitacion) {
            self::$errores[] = 'Debes añadir la fecha de la próxima desparasitación.';
        } else {
            // Validar que la próxima desparasitación no sea el mismo día ni un día anterior
            $fecha_aplicacion = strtotime($this->fecha_aplicacion);
            $proxima_desparasitacion = strtotime($this->proxima_desparasitacion);

            if ($proxima_desparasitacion <= $fecha_aplicacion) {
                self::$errores[] = 'La próxima desparasitación debe ser una fecha posterior a la fecha de aplicación.';
            }
        }

        // Validar que se haya añadido una mascota
        if (!$this->id_mascota) {
            self::$errores[] = 'Debes añadir a la mascota.';
        }

        // Validar que se haya añadido al personal
        if (!$this->id_personal) {
            self::$errores[] = 'Debes añadir al personal responsable.';
        }
    
        return self::$errores;
    }
}