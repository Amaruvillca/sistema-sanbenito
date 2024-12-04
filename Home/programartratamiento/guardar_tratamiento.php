<?php
require '../../includes/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $tratamientos = $data['tratamientos'] ?? [];

        if (empty($tratamientos)) {
            echo json_encode(['success' => false, 'message' => 'No hay tratamientos para guardar.']);
            exit;
        }

        $db = conectarDb();
        $success = true;

        $stmt = $db->prepare("INSERT INTO programar_tratamiento (dia_tratamiento, fecha_programada, id_consulta, id_personal) VALUES (?, ?, ?, ?)");

        foreach ($tratamientos as $tratami) {
            $success = $stmt->execute([
                $tratami['diaTratamiento'],
                $tratami['fechaProgramada'],
                $tratami['id_consulta'],
                $tratami['id_personal']
            ]);

            if (!$success) {
                throw new Exception('Error al insertar los datos en la base de datos.');
            }
        }

        echo json_encode(['success' => $success]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
