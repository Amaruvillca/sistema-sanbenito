<?php 
namespace App;

class CirugiaRealizada extends ActiveRecord {

    protected static $tabla = "cirugia_realizada";
    protected static $nombreId = 'id_cirugia_realizada';

    protected static $columnas_db = [
        'id_cirugia_realizada',
        'estado',
        'fecha_programada',
        'fecha_creacion',
        'mucosa',
        'tiempo_de_llenado_capilar',
        'frecuencia_cardiaca',
        'frecuencia_respiratoria',
        'peso',
        'pulso',
        'fecha_cirugia',
        'costo',
        'id_cirugia',
        'id_personal',
        'id_mascota',
        'id_cuenta'
    ];

    public $id_cirugia_realizada;
    public $estado;
    public $fecha_programada;
    public $fecha_creacion;
    public $mucosa;
    public $tiempo_de_llenado_capilar;
    public $frecuencia_cardiaca;
    public $frecuencia_respiratoria;
    public $peso;
    public $pulso;
    public $fecha_cirugia;
    public $costo;
    public $id_cirugia;
    public $id_personal;
    public $id_mascota;
    public $id_cuenta;

    public function __construct($args = []) {
        $this->id_cirugia_realizada = $args['id_cirugia_realizada'] ?? null;
        $this->estado = $args['estado'] ?? 'pendiente';
        $this->fecha_programada = $args['fecha_programada'] ?? '';
        $this->fecha_creacion = $args['fecha_creacion'] ?? date('Y-m-d');
        $this->mucosa = $args['mucosa'] ?? '';
        $this->tiempo_de_llenado_capilar = $args['tiempo_de_llenado_capilar'] ?? '';
        $this->frecuencia_cardiaca = $args['frecuencia_cardiaca'] ?? '';
        $this->frecuencia_respiratoria = $args['frecuencia_respiratoria'] ?? '';
        $this->peso = $args['peso'] ?? '';
        $this->pulso = $args['pulso'] ?? '';
        $this->fecha_cirugia = $args['fecha_cirugia'] ?? '';
        $this->costo = $args['costo'] ?? '';
        $this->id_cirugia = $args['id_cirugia'] ?? null;
        $this->id_personal = $args['id_personal'] ?? null;
        $this->id_mascota = $args['id_mascota'] ?? null;
        $this->id_cuenta = $args['id_cuenta'] ?? null;
    }

    // Método de validación para los campos de la cirugía realizada
    public function validar() {
        self::$errores = [];

        if (!$this->fecha_programada) {
            self::$errores[] = 'La fecha programada es obligatoria.';
        }
        if (!$this->mucosa) {
            self::$errores[] = 'El campo Mucosa es obligatorio.';
        }
        if (!$this->tiempo_de_llenado_capilar || !is_numeric($this->tiempo_de_llenado_capilar)) {
            self::$errores[] = 'El tiempo de llenado capilar es obligatorio y debe ser numérico.';
        }
        if (!$this->frecuencia_cardiaca || !is_numeric($this->frecuencia_cardiaca)) {
            self::$errores[] = 'La frecuencia cardíaca es obligatoria y debe ser numérica.';
        }
        if (!$this->frecuencia_respiratoria || !is_numeric($this->frecuencia_respiratoria)) {
            self::$errores[] = 'La frecuencia respiratoria es obligatoria y debe ser numérica.';
        }
        if (!$this->peso || !is_numeric($this->peso)) {
            self::$errores[] = 'El peso es obligatorio y debe ser numérico.';
        }
        if (!$this->pulso || !is_numeric($this->pulso)) {
            self::$errores[] = 'El pulso es obligatorio y debe ser numérico.';
        }
        if (!$this->fecha_cirugia) {
            self::$errores[] = 'La fecha de cirugía es obligatoria.';
        }
        if (!$this->costo || !is_numeric($this->costo)) {
            self::$errores[] = 'El costo es obligatorio y debe ser numérico.';
        }
        if (!$this->id_cirugia) {
            self::$errores[] = 'El ID de la cirugía es obligatorio.';
        }
        if (!$this->id_personal) {
            self::$errores[] = 'El ID del personal es obligatorio.';
        }
        if (!$this->id_mascota) {
            self::$errores[] = 'El ID de la mascota es obligatorio.';
        }
        if (!$this->id_cuenta) {
            self::$errores[] = 'El ID de la cuenta es obligatorio.';
        }

        return self::$errores;
    }

    // Método para buscar cirugías pendientes por id de mascota
    public static function buscarCirugiasPendientes($id_mascota) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id_mascota = ? AND estado = 'pendiente'";
        if ($stmt = self::$db->prepare($query)) {
            $stmt->bind_param('i', $id_mascota);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $cirugias = [];
            while ($row = $resultado->fetch_assoc()) {
                $cirugias[] = $row;
            }
            return $cirugias;
        } else {
            die('Error en la consulta SQL: ' . self::$db->error);
        }
    }

    // Método para marcar una cirugía como realizada
    public static function marcarRealizada($id_cirugia_realizada) {
        $query = "UPDATE " . static::$tabla . " SET estado = 'realizada' WHERE id_cirugia_realizada = ?";
        if ($stmt = self::$db->prepare($query)) {
            $stmt->bind_param('i', $id_cirugia_realizada);
            return $stmt->execute();
        } else {
            die('Error en la consulta SQL: ' . self::$db->error);
        }
    }
}
