<?php

namespace App;

require '../../includes/app.php';

use App\Cuenta;

// Verifica si se recibieron datos POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_propietario = $_POST['cuenta']?? null;

    if ($id_propietario) {
        $cuenta = new Cuenta($_POST['cuenta']);
        // Aquí puedes establecer otros atributos si es necesario
        // $cuenta->nombre_completo = ...;
        // Guardar la cuenta en la base de datos
        if ($cuenta->guardar()) {
            // Respuesta exitosa
            echo json_encode(['success' => true, 'message' => 'Cuenta creada exitosamente']);
        } else {
            // Manejar el error
            echo json_encode(['success' => false, 'error' => 'Error al guardar la cuenta']);
        }
    } else {
        // Manejar el caso donde no se proporciona id_propietario
        echo json_encode(['success' => false, 'error' => 'ID propietario no proporcionado']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método de solicitud no permitido']);
}

