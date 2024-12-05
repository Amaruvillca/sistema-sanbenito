<?php

namespace App;

class Consulta extends ActiveRecord
{

    protected static $tabla = "consulta";
    protected static $nombreId = 'id_consulta';

    protected static $columnas_db = [
        'id_consulta',
        'motivo_consulta',
        'vac_polivalentes',
        'vac_rabia',
        'desparasitacion',
        'esterelizado',
        'informacion',
        'mucosa',
        'tiempo_de_llenado_capilar',
        'frecuencia_cardiaca',
        'frecuencia_respiratoria',
        'temperatura',
        'peso',
        'pulso',
        'turgencia_de_piel',
        'actitud',
        'ganglios_linfaticos',
        'hidratacion',
        'Diagnostico_presuntivo',
        'costo',
        'fecha_consulta',
        'id_mascota',
        'id_personal',
        'id_cuenta'
    ];

    public $id_consulta;
    public $motivo_consulta;
    public $vac_polivalentes;
    public $vac_rabia;
    public $desparasitacion;
    public $esterelizado;
    public $informacion;
    public $mucosa;
    public $tiempo_de_llenado_capilar;
    public $frecuencia_cardiaca;
    public $frecuencia_respiratoria;
    public $temperatura;
    public $peso;
    public $pulso;
    public $turgencia_de_piel;
    public $actitud;
    public $ganglios_linfaticos;
    public $hidratacion;
    public $Diagnostico_presuntivo;
    public $costo;
   
    public $fecha_consulta;
    public $id_mascota;
    public $id_personal;
    public $id_cuenta;

    public function __construct($args = [])
    {
        $this->id_consulta = $args['id_consulta'] ?? '';
        $this->motivo_consulta = $args['motivo_consulta'] ?? '';
        $this->vac_polivalentes = $args['vac_polivalentes'] ?? '0';
        $this->vac_rabia = $args['vac_rabia'] ?? '0';
        $this->desparasitacion = $args['desparasitacion'] ?? '0';
        $this->esterelizado = $args['esterelizado'] ?? '0';
        $this->informacion = $args['informacion'] ?? '';
        $this->mucosa = $args['mucosa'] ?? '';
        $this->tiempo_de_llenado_capilar = $args['tiempo_de_llenado_capilar'] ?? '';
        $this->frecuencia_cardiaca = $args['frecuencia_cardiaca'] ?? '';
        $this->frecuencia_respiratoria = $args['frecuencia_respiratoria'] ?? '';
        $this->temperatura = $args['temperatura'] ?? '';
        $this->peso = $args['peso'] ?? '';
        $this->pulso = $args['pulso'] ?? '';
        $this->turgencia_de_piel = $args['turgencia_de_piel'] ?? '';
        $this->actitud = $args['actitud'] ?? 'activo'; // Por defecto
        $this->ganglios_linfaticos = $args['ganglios_linfaticos'] ?? '';
        $this->hidratacion = $args['hidratacion'] ?? '';
        $this->Diagnostico_presuntivo = $args['Diagnostico_presuntivo'] ?? '';
        $this->costo = $args['costo'] ?? '';
         // Estado por defecto
        $this->fecha_consulta = $args['fecha_consulta'] ?? date('Y-m-d');
        $this->id_mascota = $args['id_mascota'] ?? '';
        $this->id_personal = $args['id_personal'] ?? '';
        $this->id_cuenta = $args['id_cuenta'] ?? '';
    }

    // Función para buscar consultas pendientes por id de mascota
    public static function buscarConsultasPendientes($id_mascota)
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id_mascota = ? AND estado = 'pendiente'";
        if ($stmt = self::$db->prepare($query)) {
            $stmt->bind_param('i', $id_mascota);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $consultas = [];
            while ($row = $resultado->fetch_assoc()) {
                $consultas[] = $row;
            }
            return $consultas;
        } else {
            die('Error en la consulta SQL: ' . self::$db->error);
        }
    }

    // Función para actualizar el estado de la consulta
    public static function completarConsulta($id_consulta)
    {
        $query = "UPDATE " . static::$tabla . " SET estado = 'completada' WHERE id_consulta = ?";
        if ($stmt = self::$db->prepare($query)) {
            $stmt->bind_param('i', $id_consulta);
            return $stmt->execute();
        } else {
            die('Error en la consulta SQL: ' . self::$db->error);
        }
    }
    public function validar()
    {



        // Validar campos obligatorios
        if (!$this->motivo_consulta) {
            self::$errores[] = 'El motivo de la consulta es obligatorio.';
        }
        if (!$this->Diagnostico_presuntivo) {
            self::$errores[] = 'El motivo de la consulta es obligatorio.';
        }
        if (!$this->frecuencia_cardiaca || !is_numeric($this->frecuencia_cardiaca)) {
            self::$errores[] = 'La frecuencia cardiaca es obligatoria y debe ser numérica.';
        }
        if (!$this->temperatura || !is_numeric($this->temperatura)) {
            self::$errores[] = 'La temperatura es obligatoria y debe ser un valor numérico.';
        }
        if (!$this->peso || !is_numeric($this->peso)) {
            self::$errores[] = 'El peso es obligatorio y debe ser numérico.';
        }
        if (!$this->pulso || !is_numeric($this->pulso)) {
            self::$errores[] = 'El pulso es obligatorio y debe ser numérico.';
        }
        if (!$this->costo || !is_numeric($this->costo)) {
            self::$errores[] = 'El costo es obligatorio y debe ser un valor numérico.';
        }
        if (!$this->id_mascota) {
            self::$errores[] = 'El ID de la mascota es obligatorio.';
        }
        if (!$this->id_personal) {
            self::$errores[] = 'El ID del personal es obligatorio.';
        }
        if (!$this->id_cuenta) {
            self::$errores[] = 'El ID de la cuenta es obligatorio.';
        }

        // Validaciones adicionales (ejemplo: validación de campos booleanos)
        if (!in_array($this->vac_polivalentes, ['0', '1'])) {
            self::$errores[] = 'El valor de Vacunas Polivalentes debe ser 0 o 1.';
        }
        if (!in_array($this->vac_rabia, ['0', '1'])) {
            self::$errores[] = 'El valor de Vacuna contra la Rabia debe ser 0 o 1.';
        }
        if (!in_array($this->desparasitacion, ['0', '1'])) {
            self::$errores[] = 'El valor de Desparasitación debe ser 0 o 1.';
        }
        if (!in_array($this->esterelizado, ['0', '1'])) {
            self::$errores[] = 'El valor de Esterilización debe ser 0 o 1.';
        }

        // Puedes agregar más validaciones según sea necesario
        if (!$this->actitud) {
            self::$errores[] = 'La actitud es obligatoria.';
        }

        return self::$errores;
    }
    public static function buscarConsulta($id_cuenta, $id_mascota)
    {
        $query = "SELECT * 
FROM consulta 
WHERE id_mascota = $id_mascota and id_cuenta= $id_cuenta
ORDER BY id_consulta DESC 
LIMIT 1";
//debuguear($query);
        $resultado = self::consultarSql($query);
        
        
        //debuguear($resultado);
        return $resultado;
    }
}
