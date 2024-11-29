<?php
namespace App;
class ActiveRecord
{
    protected static $db;
    protected static $nombreId = '';
    protected static $columnas_db = [];
    protected static $tabla = '';
    protected static $errores = [];
    public static function setDb($db)
    {
        self::$db = $db;
    }
    public static function all()
    {
        $query = 'SELECT * FROM ' . static::$tabla . ' ORDER BY ' . static::$nombreId . ' DESC';
        $resultado = self::consultarSql($query);
        //debuguear($resultado);
        return $resultado;
    }
    public static function consultarSql($query)
    {
        //condsultar bd
        $resultado = self::$db->query($query);
        //iterar
        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }
        //liberar la memoria
        $resultado->free();
        //retornar resultado
        return $array;
    }
    protected static function crearObjeto($registro)
    {
        $objeto = new static;
        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    public function guardar()
    {  //SANITISAR DATOS
        $atributos = $this->sanitizarAtributos();
        //insertar en la base de datos
        // insertar en la base de datos
        $query = "INSERT INTO " . static::$tabla . " (";
        $query .= join(', ', array_keys($atributos));
        $query .= ") VALUES ('";
        $query .= join("','", array_values($atributos));
        $query .= "')";
        $resultado = self::$db->query($query);
        return $resultado;
    }
    public  function actualizar($id)
    {
        $atributos = $this->sanitizarAtributos();
        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }
        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE " . static::$nombreId . " = '" . self::$db->escape_string($id) . "' ";
        $query .= " LIMIT 1 ";
        $resultado = self::$db->query($query);
        return $resultado;
    }
    //borrar datos
    public static function borrar($id)
    { //query de borrar datos por id
        $query = "DELETE FROM " . static::$tabla . " WHERE " . static::$nombreId . " = '" . $id . "' ";
        //ejecutar query
        $resultado = self::$db->query($query);
        return $resultado;
    }
    public function atributos(): array
    {
        $atributos = [];
        foreach (static::$columnas_db as $columnas) {
            if ($columnas == static::$nombreId) continue;
            $atributos[$columnas] = $this->$columnas;
        }
        return $atributos;
    }
    public function sanitizarAtributos(): array
    {
        $atributos = $this->atributos();
        $sanitisado = [];
        foreach ($atributos as $key => $value) {
            $sanitisado[$key] = self::$db->escape_string($value);
        }
        return $sanitisado;
    }
    public static function getErrores()
    {
        return static::$errores;
    }
    public function validar()
    {
        static::$errores = [];
        return static::$errores;
    }
    public static function buscarClaves($obtener, $tabla, $entidad, $parametro): String
    {
        // Preparar la consulta para evitar inyección SQL
        $query = "SELECT " . self::$db->real_escape_string($obtener) . " FROM " . self::$db->real_escape_string($tabla) . " WHERE " . self::$db->real_escape_string($entidad) . " = '" . self::$db->real_escape_string($parametro) . "'";

        // Ejecutar la consulta
        $resultado = self::$db->query($query);

        // Verificar si se obtuvo un resultado
        if ($resultado && $resultado->num_rows > 0) {
            // Obtener la primera fila del resultado
            $fila = $resultado->fetch_assoc();
            return $fila[$obtener]; // Retorna el valor de la columna solicitada
        } else {
            return ''; // Retorna una cadena vacía si no se encontró el registro
        }
    }
    public static function mostrarDatos($id)
    {
        // Preparar el query con placeholders para evitar SQL injection
        $query = "SELECT * FROM " . static::$tabla . " WHERE " . static::$nombreId . " = ?";
        // Preparar la declaración
        $stmt = self::$db->prepare($query);
        // Vincular el parámetro
        $stmt->bind_param('i', $id); // 'i' indica que es un entero
        // Ejecutar la consulta
        $stmt->execute();
        // Obtener el resultado
        $resultado = $stmt->get_result();

        // Verificar si se obtuvo algún registro
        if ($resultado->num_rows === 1) {
            // Retornar el registro como un array asociativo
            return $resultado->fetch_assoc();
        } else {
            // Si no hay resultados, retornar un array vacío o false
            return [];
        }
    }
    //buscar registro por el id
    public static function find($id)
    {
        //consulta query
        $query = "SELECT * FROM " . static::$tabla . " WHERE " . static::$nombreId . " = " . $id;
        // ejecutar
        $resultado = self::consultarSql($query);
        //retornar primer elemento
        return array_shift($resultado);
    }
    //sincronizar objeto en memoria
    public function sincronizar($arg = [])
    {
        foreach ($arg as $key => $value) {
            if (property_exists($this, $key) && !is_null(($value))) {
                $this->$key = $value;
            }
        }
    }
    public static function contarDatos()
    {
        // Prepara la consulta SQL
        $query = "SELECT COUNT(*) FROM " . static::$tabla;

        // Ejecuta la consulta
        $resultado = self::$db->query($query);

        // Verifica si la consulta fue exitosa
        if ($resultado) {
            // Obtiene el resultado
            $fila = $resultado->fetch_array(MYSQLI_NUM);
            $cantidad = $fila[0];

            // Libera el resultado
            $resultado->free();

            // Retorna el resultado
            return $cantidad;
        } else {
            // Maneja el error en caso de fallo en la consulta
            return false;
        }
    }
    public static function asociadosCuenta($id_cuenta) {
        $query = 'SELECT * FROM ' . static::$tabla . ' WHERE id_cuenta = ' . self::$db->escape_string($id_cuenta) . ' ORDER BY ' . static::$nombreId . ' DESC';
        $resultado = self::consultarSql($query);
        return $resultado;
    }
    
}
