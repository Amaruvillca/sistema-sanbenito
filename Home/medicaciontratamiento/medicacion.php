<?php
require '../../includes/app.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $medicamentos = $data['medicamentos'] ?? [];

    if (empty($medicamentos)) {
        echo json_encode(['success' => false, 'message' => 'No hay medicamentos para guardar.']);
        exit;
    }

    $db = conectarDb();
    $success = true;

    foreach ($medicamentos as $medicamento) {
        $stmt = $db->prepare("INSERT INTO medicacion (nombre_medicacion, via, costo, fecha_medicacion, id_tratamiento, id_cuenta) VALUES (?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([
            $medicamento['nombreMedicacion'],
            $medicamento['via'],
            $medicamento['costo'],
            date('Y-m-d'), // Cambia según tu formato de fecha
            $medicamento['id_tratamiento'],
            $medicamento['id_cuenta']
        ]);

        if (!$success) {
            echo json_encode(['success' => false, 'message' => 'Error al guardar los medicamentos.']);
            exit;
        }
    }

    echo json_encode(['success' => $success]);
}
