<?php
namespace App;
class Vacunas extends ActiveRecord{
    protected static $tabla = "vacuna";
    protected static $nombreId = 'id_vacuna';


    protected static $columnas_db = [
        'id_vacuna',
        'contra',
        'nom_vac',
        'costo',
        'fecha_vacuna',
        'proxima_vacuna',
        'id_mascota',
        'id_personal'
    ];

    public $id_vacuna;
    public $contra;
    public $nom_vac;
    public $costo;
    public $fecha_vacuna;
    public $proxima_vacuna;
    public $id_mascota;
    public $id_personal;

    public function __construct($args = [])
    {
        $this->id_vacuna = $args['id_vacuna'] ?? '';
        $this->contra = $args['contra'] ?? '';
        $this->nom_vac = $args['nom_vac'] ?? '';
        $this->costo = $args['costo'] ?? '';
        $this->fecha_vacuna = $args['fecha_vacuna'] ?? date('Y-m-d');
        $this->proxima_vacuna = $args['proxima_vacuna'] ?? '';
        $this->id_mascota = $args['id_mascota'] ?? '';
        $this->id_personal = $args['id_personal'] ?? '';
    }
    public function validar()
    {
        
         // Verificar si se ha proporcionado la enfermedad contra la que es la vacuna
         if (!$this->contra) {
            self::$errores[] = 'Debes añadir la enfermedad contra la que es la vacuna.';
        }

        // Verificar si se ha proporcionado el nombre de la vacuna
        if (!$this->nom_vac) {
            self::$errores[] = 'Debes añadir el nombre de la vacuna.';
        }

        // Verificar si se ha proporcionado el costo de la vacuna
        if (!$this->costo) {
            self::$errores[] = 'Debes añadir el costo de la vacuna.';
        }

        // Verificar si se ha proporcionado la fecha de la próxima vacuna
        if (!$this->proxima_vacuna) {
            self::$errores[] = 'Debes añadir la fecha de la próxima vacunación.';
        }
        if (!$this->id_mascota) {
            self::$errores[] = 'Debes añadir ha la mascota';
        }
        if (!$this->id_personal) {
            self::$errores[] = 'Debes añadir al personal';
        }
    
        return self::$errores;
    }
}