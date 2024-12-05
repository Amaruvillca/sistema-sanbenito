<?php
$titulo = "Buscar";
$nombrepagina = "Buscar";
require 'template/header.php';
verificaAcceso();

use App\Perfil;
$totalSaldo = 0;
$error = '';
$cuentas = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $fecha_inicio = $_GET['fecha_inicio'] ?? '';
    $fecha_fin = $_GET['fecha_fin'] ?? '';
    $estado = $_GET['estado'] ?? '';

    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        try {
            $conexion = conectarDb();
            $query = "SELECT * FROM cuenta WHERE fecha_pago BETWEEN ? AND ?";

            if (!empty($estado)) {
                $query .= " AND estado = ?";
            }

            $stmt = $conexion->prepare($query);

            if (!empty($estado)) {
                $stmt->bind_param("sss", $fecha_inicio, $fecha_fin, $estado);
            } else {
                $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $cuentas = $result->fetch_all(MYSQLI_ASSOC);

            $stmt->close();
            $conexion->close();
        } catch (Exception $e) {
            $error = "Error en la búsqueda: " . $e->getMessage();
        }
    } else {
        $error = "Por favor, ingresa ambas fechas para buscar.";
    }
}
?>

<div class="dashboard-content">
    <div class="container py-4">
        <h1 class="mb-4 text-center">Buscar Cuentas por Rango de Fechas</h1>
        <form method="GET" action="" class="row g-3">
            <div class="col-md-4">
                <label for="fecha_inicio" class="form-label">Fecha de inicio:</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
            </div>
            <div class="col-md-4">
                <label for="fecha_fin" class="form-label">Fecha de fin:</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>">
            </div>
            <div class="col-md-4">
                <label for="estado" class="form-label">Estado de factura:</label>
                <select id="estado" name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="nopagada" <?php echo $estado == 'nopagada' ? 'selected' : ''; ?>>No pagada</option>
                    <option value="pagada" <?php echo $estado == 'pagada' ? 'selected' : ''; ?>>Pagada</option>
                    <option value="adelanto" <?php echo $estado == 'adelanto' ? 'selected' : ''; ?>>Adelanto</option>
                </select>
            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary w-50">Filtrar</button>
            </div>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4 text-center">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($cuentas)): ?>
            <div class="table-responsive mt-4">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Saldo Total</th>
                            <th>Monto Pagado</th>
                            <th>Fecha Pago</th>
                            <th>Atendido por</th>
                            <th>Estado</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cuentas as $cuenta): 
                            $perfil = Perfil::find($cuenta['id_personal']);
                            $estadoClase = '';
                            if ($cuenta['estado'] == 'pagada') {
                                $estadoClase = 'table-success';
                            } elseif ($cuenta['estado'] == 'nopagada') {
                                $estadoClase = 'table-danger';
                            } elseif ($cuenta['estado'] == 'adelanto') {
                                $estadoClase = 'table-warning';
                            }

                            // Suma el saldo total al acumulador
                            $totalSaldo += $cuenta['saldo_total'];
                        ?>
                            <tr>
                                <td class="text-center"><?php echo htmlspecialchars($cuenta['id_cuenta']); ?></td>
                                <td><?php echo htmlspecialchars($cuenta['nombre_completo']); ?></td>
                                <td class="text-end"><?php echo number_format($cuenta['saldo_total'], 2); ?> Bs.</td>
                                <td class="text-end"><?php echo number_format($cuenta['monto_pagado'], 2); ?> Bs.</td>
                                <td class="text-center"><?php echo htmlspecialchars($cuenta['fecha_pago']); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($perfil->nombres) . ' ' . 
                                                            htmlspecialchars($perfil->apellido_paterno) . ' ' . 
                                                            htmlspecialchars($perfil->apellido_materno); ?></td>
                                <td class="text-center <?php echo $estadoClase; ?>">
                                    <?php echo ucfirst(htmlspecialchars($cuenta['estado'])); ?>
                                </td>
                               
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="2" class="text-end fw-bold">Total Saldo:</td>
                            <td class="text-end fw-bold"><?php echo number_format($totalSaldo, 2); ?> Bs.</td>
                            <td colspan="5"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="text-center mt-3">
                <button onclick="window.print()" class="btn btn-danger"><i class="bi bi-file-pdf-fill"></i> Imprimir</button>
                
            </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'GET'): ?>
            <div class="alert alert-warning mt-4 text-center">
                No se encontraron resultados para el rango de fechas proporcionado.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require 'template/footer.php';
?>
