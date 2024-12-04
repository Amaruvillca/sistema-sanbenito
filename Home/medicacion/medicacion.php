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
        $stmt = $db->prepare("INSERT INTO medicacionconsulta (nombre_medicacion, via, costo, fecha_medicacion, id_consulta, id_cuenta) VALUES (?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([
            $medicamento['nombreMedicacion'],
            $medicamento['via'],
            $medicamento['costo'],
            date('Y-m-d'),
            $medicamento['id_consulta'],
            $medicamento['id_cuenta']
        ]);

        if (!$success) break;
    }

    echo json_encode(['success' => $success]);
}
