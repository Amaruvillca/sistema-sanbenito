<?php

require '../../includes/app.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $db = conectarDb();
    
    // Obtener los datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"), true);

    // Obtener el ID del usuario y el nuevo estado
    $id_usuario = $data['id_usuario'];
    $nuevo_estado = $data['estado'];

    // Preparar la consulta para actualizar el estado del usuario
    $stmt = $db->prepare("UPDATE usuario SET estado = ? WHERE id_usuario = ?");
    
    // Verificar si la preparación fue exitosa
    if ($stmt) {
        // Enlazar los parámetros (estado e id_usuario)
        $stmt->bind_param('ii', $nuevo_estado, $id_usuario);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Responder con éxito
            echo json_encode(['success' => true]);
        } else {
            // Si hay un error en la ejecución
            echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta']);
        }
        
        // Cerrar la consulta
        $stmt->close();
    } else {
        // Si hay un error en la preparación de la consulta
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta']);
    }

    // Cerrar la conexión
    $db->close();
}

