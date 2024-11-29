<?php
$titulo = "Ingresos";
$nombrepagina = "Ingresos"; 
require 'template/header.php';

use App\CirugiaProgramada;
use App\Vacunas;
use App\Desparacitaciones;

$cirugias_programadas = CirugiaProgramada::all();
$vacunas = Vacunas::all();
$desparasitaciones= Desparacitaciones::all();
?>

<div class="dashboard-content container py-4">
    <h2 class="text-center mb-4"><?php echo $nombrepagina; ?></h2>
    
    <div class="row">
        <!-- Cirugías Programadas -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Cirugías Programadas</h5>
                </div>
                <div class="card-body">
                    <?php if (count($cirugias_programadas) > 0): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($cirugias_programadas as $cirugia): ?>
                                <li class="list-group-item">
                                    <strong><?php echo $cirugia->nombre_mascota; ?></strong> - <?php echo $cirugia->fecha_programada; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No hay cirugías programadas.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Vacunas -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Vacunas</h5>
                </div>
                <div class="card-body">
                    <?php if (count($vacunas) > 0): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($vacunas as $vacuna): ?>
                                <li class="list-group-item">
                                    <strong><?php echo $vacuna->nombre_mascota; ?></strong> - <?php echo $vacuna->fecha_aplicacion; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No hay vacunas registradas.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Desparasitaciones -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">Desparasitaciones</h5>
                </div>
                <div class="card-body">
                    <?php if (count($desparasitaciones) > 0): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($desparasitaciones as $desparasitacion): ?>
                                <li class="list-group-item">
                                    <strong><?php echo $desparasitacion->nombre_mascota; ?></strong> - <?php echo $desparasitacion->fecha_aplicacion; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No hay desparasitaciones registradas.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require 'template/footer.php';
?>
