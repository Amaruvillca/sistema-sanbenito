<?php

namespace App;

require '../../includes/app.php';
$conexion = conectarDb();

// Verifica si se recibieron datos POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cuenta = $_POST['cuenta']['id_cuenta'] ?? null;

    if ($id_cuenta) {
        // Aquí se asume que tienes una clase `Cuenta` con un método para eliminar
        $cuenta = new Cuenta($_POST['cuenta']);

        

        // Llama al método para eliminar la cuenta en la base de datos
        if ($cuenta->borrar($id_cuenta)) {
            // Respuesta exitosa
            echo json_encode(['success' => true, 'message' => 'Cuenta eliminada exitosamente']);
        } else {
            // Manejar el error
            echo json_encode(['success' => false, 'error' => 'Error al eliminar la cuenta']);
        }
    } else {
        // Manejar el caso donde no se proporciona id_cuenta
        echo json_encode(['success' => false, 'error' => 'ID de cuenta no proporcionado']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método de solicitud no permitido']);
}


