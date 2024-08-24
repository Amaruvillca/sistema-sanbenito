<?php
// Importar la conexión a la base de datos
require 'includes/config/db.php';
$db = conectarDb();


$imagen_personal = 'imagen.jpg'; 
$nombres = "camila carla";
$apellido_paterno = "villa";
$apellido_materno = "perez";
$num_celular = "1235145";
$direccion = "Calle Falsa 123";
$num_carnet = "9876324211";
$profesion = "Veterinario";
$especialidad = "general";
$matricula_profesional = "12345-LP";
$fecha_registro = date('Y-m-d'); // Fecha actual
$id_usuario = 2; 


$query = "INSERT INTO personal (imagen_personal, nombres, apellido_paterno, apellido_materno, num_celular, direccion, num_carnet, profesion, especialidad, matricula_profesional, fecha_registro, id_usuario) 
VALUES ('${imagen_personal}', '${nombres}', '${apellido_paterno}', '${apellido_materno}', '${num_celular}', '${direccion}', '${num_carnet}', '${profesion}', '${especialidad}', '${matricula_profesional}', '${fecha_registro}', ${id_usuario})";


$resultado = mysqli_query($db, $query);

// Verificar el resultado de la inserción
if($resultado) {
    echo "Registro insertado correctamente.";
} else {
    echo "Error al insertar el registro: " . mysqli_error($db);
}

