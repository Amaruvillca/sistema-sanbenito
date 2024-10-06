<?php
require_once '../../includes/app.php'; 
use App\Mascotas;


if (isset($_GET['id'])) {
    $idMascota = $_GET['id'];
    $mas = new Mascotas(); 
    $mascota = $mas->find($idMascota); 

    if ($mascota) {
        echo json_encode($mascota);
    } else {
        echo json_encode(null);
    }
} else {
    echo json_encode(null);
}
