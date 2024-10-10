<?php

namespace App;

class Cuenta extends ActiveRecord
{

    protected static $tabla = "cuenta";
    protected static $nombreId = 'id_cuenta';


    protected static $columnas_db = [
        'id_cuenta',
        'nombre_completo',
        'num_carnet',
        'estado',
        'saldo_total',
        'monto_pagado',
        'fecha_apertura',
        'fecha_pago',
        'id_personal',
        'id_propietario'
    ];
    public $id_cuenta;

    public $nombre_completo;
    public $num_carnet;
    public $estado;
    public $saldo_total;
    public $monto_pagado;
    public $fecha_apertura;
    public $fecha_pago;
    public $id_personal;
    public $id_propietario;

    public function __construct($args = [])
    {
        $this->id_cuenta = $args['id_cuenta'] ?? '';
        $this->nombre_completo = $args['nombre_completo'] ?? 's/n';
        $this->num_carnet = $args['num_carnet'] ?? 'S/N';
        $this->estado = $args['estado'] ?? 'nopagada';
        $this->saldo_total = $args['saldo_total'] ?? '0';
        $this->monto_pagado = $args['monto_pagado'] ?? '0';
        $this->fecha_apertura = $args['fecha_apertura'] ?? date('Y-m-d');
        $this->fecha_pago = $args['fecha_pago'] ?? date('Y-m-d');
        $this->id_personal = $args['id_personal'] ?? '';
        $this->id_propietario = $args['id_propietario'] ?? '';
    }
    public static function buscarCuentaActiva($id_propietario)
    {
        // Consulta para obtener solo el id_cuenta cuando hay una cuenta 'nopagada' o 'adelanto'
        $query = "SELECT id_cuenta FROM " . static::$tabla . " WHERE id_propietario = ? AND (estado = 'nopagada' OR estado = 'adelanto') LIMIT 1";

        // Preparar la consulta
        if ($stmt = self::$db->prepare($query)) {

            // Enlazar el parámetro id_propietario
            $stmt->bind_param('i', $id_propietario);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener el resultado
            $resultado = $stmt->get_result();

            // Verificar si se encontró alguna cuenta activa
            if ($row = $resultado->fetch_assoc()) {
                // Retornar solo el id_cuenta
                return $row['id_cuenta'];
            } else {
                return null;
            }
        } else {
            // Manejar el error en la preparación de la consulta
            die('Error en la consulta SQL: ' . self::$db->error);
        }
    }
   
    public static function saldoTotal($id_cuenta): int
{
    $saldo = 0;

    // Array de consultas
    $consultas = [
        "SELECT SUM(costo) AS total_costo FROM vacuna WHERE id_cuenta = ?",
        "SELECT SUM(costo) AS total_costo FROM desparasitacion WHERE id_cuenta = ?",
        "SELECT SUM(costo) AS total_costo FROM atiende_servicio WHERE id_cuenta = ?",
        "SELECT SUM(costo) AS total_costo FROM cirugia_realizada WHERE id_cuenta = ?",
        "SELECT SUM(costo) AS total_costo FROM consulta WHERE id_cuenta = ?",
        "SELECT SUM(costo) AS total_costo FROM medicacion WHERE id_cuenta = ?"
    ];

    foreach ($consultas as $query) {
        // Prepara la consulta
        if ($stmt = self::$db->prepare($query)) {
            // Vincula el parámetro
            $stmt->bind_param('i', $id_cuenta); // 'i' indica que el parámetro es un entero
            $stmt->execute();

            // Obtiene el resultado
            $result = $stmt->get_result();
            $fech = $result->fetch_assoc();

            // Sumar el costo al saldo
            $saldo += $fech['total_costo'] !== null ? (int)$fech['total_costo'] : 0; // Asegúrate de que sea un entero

            // Cierra la declaración
            $stmt->close();
        }
    }

    return $saldo;
}


    
}
