<?php
namespace App;
class Mascotas extends ActiveRecord{

    protected static $tabla = "mascota";
    protected static $nombreId='id_mascota';
    protected static $columnas_db = [
        'id_mascota',
        'codigo_mascota',
        'imagen_mascota',
        'nombre',
        'especie',
        'sexo',
        'color',
        'raza',
        'fecha_nacimiento',
        'fecha_registro',
        'id_propietario'      
    ];
    
    public  $id_mascota;
    public  $codigo_mascota;
    public  $imagen_mascota;
    public  $nombre;
    public  $especie;
    public  $sexo;
    public  $color;
    public  $raza;
    public  $fecha_nacimiento;
    public  $fecha_registro;
    public $id_propietario;

    public function __construct($args = [])
    {
        $this->id_mascota= $args['id_mascota'] ?? '';
        $this->codigo_mascota= $args['codigo_mascota'] ?? '';
        $this->imagen_mascota= $args['imagen_mascota'] ?? 'mascota.png';
        $this->nombre= $args['nombre'] ?? '';
        $this->especie= $args['especie']?? '';
        $this->sexo= $args['sexo'] ?? '';     
        $this->color= $args['color'] ?? '';
        $this->raza= $args['raza'] ?? '';
        $this->fecha_nacimiento= $args['fecha_nacimiento'] ?? '';
        $this->fecha_registro= $args['fecha_registro'] ?? date('Y-m-d');
        $this->id_propietario= $args['id_propietario'] ?? '';
    }
    public function setCodigoMascota($codigo)
    {
        if ($codigo) {
            $this->codigo_mascota = $codigo;
        }
    }
    public function setImagen($imagen)
    {
        if ($imagen) {
            $this->imagen_mascota = $imagen;
        }
    }
    public function validar()
    {
      
        // Verificación de los campos obligatorios, aplicable tanto para crear como para editar
        if (!$this->nombre) {
            self::$errores[] = 'El nombre es obligatorio.';
        }
        if (!$this->especie ) {
            self::$errores[] = 'La especie es obligatorio';
        }
        if (!$this->sexo) {
            self::$errores[] = 'El sexo es obligatorio';
        }
        if (!$this->color) {
            self::$errores[] = 'El color es obligatorio';
        }
        if (!$this->raza) {
            self::$errores[] = 'La raza es obligatorio';
        }
        if (!$this->fecha_nacimiento) {
            self::$errores[] = 'La fecha de nacimiento es obligatorio';
        }
       

        return self::$errores;
    }
    public function generarCodigoMascota($numeroCarnet, $nombrePropietario, $numeroCelular, $idMascota) {
        // Obtener los primeros 3 dígitos del número de carnet
        $codigoCarnet = substr($numeroCarnet, 0, 3);
        
        // Obtener la primera letra del nombre del propietario
        $codigoPropietario = strtoupper(substr($nombrePropietario, 0, 1));
        
        // Obtener los primeros 3 dígitos del número de celular
        $codigoCelular = substr($numeroCelular, 0, 3);
        
        // Obtener la primera letra del nombre de la mascota
        $codigoMascota = strtoupper(substr($this->nombre, 0, 1));
        $ultimaLetra = strtoupper(substr($this->nombre, -1));
        
        // Concatenar para generar el código final de la mascota
        $codigoMascotaFinal = "SB" . $codigoCarnet . $codigoPropietario . $codigoCelular . $codigoMascota . $idMascota.$ultimaLetra;
        
        // Verificar si el código es único
        if(!$this->id_mascota){
        if ($this->verificarCodigoDuplicado($codigoMascotaFinal)) {
            // Si hay un duplicado, añadir un número aleatorio al final
            $codigoMascotaFinal .= rand(10, 99);
        }}else{
            if($this->verificarCodigoDuplicadoEditar($codigoMascotaFinal)){
                $codigoMascotaFinal .= rand(10, 99);
                
            }
        }
        
        return $codigoMascotaFinal;
    }
    
    // Función para verificar si el código ya existe en la base de datos
    private function verificarCodigoDuplicado($codigoMascotaFinal) {
        $query = "SELECT COUNT(*) as total FROM mascota WHERE codigo_mascota = '" . self::$db->real_escape_string($codigoMascotaFinal) . "'";
        $resultado = self::$db->query($query);
        $fila = $resultado->fetch_assoc();
    
        return $fila['total'] > 0;
    }
    // Función para verificar si el código ya existe en la base de datos, excluyendo la mascota actual
private function verificarCodigoDuplicadoEditar($codigoMascotaFinal) {
    // Escapar el código de la mascota y el ID para evitar inyecciones SQL
    $codigoMascotaEscapado = self::$db->real_escape_string($codigoMascotaFinal);
    $idMascotaEscapado = self::$db->real_escape_string($this->id_mascota);

    // Consulta SQL para verificar duplicados, excluyendo la mascota con el ID actual
    $query = "SELECT COUNT(*) as total FROM mascota WHERE codigo_mascota = '$codigoMascotaEscapado' AND id_mascota != '$idMascotaEscapado'";
    
    // Ejecutar la consulta
    $resultado = self::$db->query($query);
    $fila = $resultado->fetch_assoc();
    
    // Si el total es mayor a 0, significa que hay un código duplicado
    return $fila['total'] > 0;
}

}