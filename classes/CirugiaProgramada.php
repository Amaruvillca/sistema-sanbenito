<?php
namespace App;

class CirugiaProgramada extends ActiveRecord {

    protected static $tabla = "cirugia_programada";
    protected static $nombreId = 'id_cirugia_programada';

    protected static $columnas_db = [
        'id_cirugia_programada',
        'estado',
        'fecha_programada',
        'fecha_creacion',
        'id_cirugia',
        'id_personal',
        'id_mascota'
    ];

    public $id_cirugia_programada;
    public $estado;
    public $fecha_programada;
    public $fecha_creacion;
    public $id_cirugia;
    public $id_personal;
    public $id_mascota;

    public function __construct($args = []) {
        $this->id_cirugia_programada = $args['id_cirugia_programada'] ?? '';
        $this->estado = $args['estado'] ?? 'pendiente'; 
        $this->fecha_programada = $args['fecha_programada'] ?? '';
        $this->fecha_creacion = $args['fecha_creacion'] ?? date('Y-m-d');
        $this->id_cirugia = $args['id_cirugia'] ?? '';
        $this->id_personal = $args['id_personal'] ?? '';
        $this->id_mascota = $args['id_mascota'] ?? '';
    }

    // Función para buscar cirugías programadas por estado
    public static function buscarPorEstado($estado) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE estado = ?";
        if ($stmt = self::$db->prepare($query)) {
            $stmt->bind_param('s', $estado);
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

    // Función para actualizar el estado de la cirugía programada
    public static function actualizarEstado($id_cirugia_programada, $nuevoEstado) {
        $query = "UPDATE " . static::$tabla . " SET estado = ? WHERE id_cirugia_programada = ?";
        if ($stmt = self::$db->prepare($query)) {
            $stmt->bind_param('si', $nuevoEstado, $id_cirugia_programada);
            return $stmt->execute();
        } else {
            die('Error en la consulta SQL: ' . self::$db->error);
        }
    }

    // Validaciones
    public function validar() {
        self::$errores = [];

        if (!$this->fecha_programada) {
            self::$errores[] = 'La fecha programada es obligatoria.';
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
        if (!in_array($this->estado, ['pendiente', 'cancelada', 'concluida'])) {
            self::$errores[] = 'El estado debe ser pendiente, cancelada o concluida.';
        }

        return self::$errores;
    }
}
