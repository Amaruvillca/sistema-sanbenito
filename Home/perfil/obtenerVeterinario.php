<?php
require_once '../../includes/app.php'; // Asegúrate de que la ruta sea correcta
use App\Perfil;

if (isset($_GET['id'])) {
    $idVeterinario = $_GET['id'];
    $perfil = new Perfil(); // Instancia la clase Perfil
    $veterinario = $perfil->find($idVeterinario); // Supón que tienes un método `find` para obtener los datos

    if ($veterinario) {
        echo json_encode($veterinario);
    } else {
        echo json_encode(null);
    }
} else {
    echo json_encode(null);
}
