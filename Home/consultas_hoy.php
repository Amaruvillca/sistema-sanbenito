<?php
$titulo = "consultas_hoy";
$nombrepagina = "Consultas Programadas";
require 'template/header.php';

use App\ProgramarTratamiento;
use App\Consulta;
use App\Mascotas;

// Obtener todas las consultas programadas
$tratamientosProgramados = ProgramarTratamiento::all();
$tratamientosProgramados2 = ProgramarTratamiento::all();
// Obtener la fecha de hoy en formato 'Y-m-d'
$fechaHoy = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $resultado = ProgramarTratamiento::borrar($id);
    $mensajeEstado = $resultado ? "success" : "nosuccess";
}
?>

<div class="dashboard-content">
    <div class="container">
        <div class="row">
            <h1><i class="bi bi-journal-text"></i> tratamientod pendientes</h1>
            <hr>
            <center>
                <h3>Pendientes</h3>
            </center>
            <hr>
            <div class="row">
                <?php
                // Verificar si hay consultas programadas para hoy con estado "pendiente"
                $hayConsultasHoy = false;

                if (!empty($tratamientosProgramados)) {
                    foreach ($tratamientosProgramados as $tratamiento) {
                        $fechaTratamiento = date('Y-m-d', strtotime($tratamiento->fecha_programada));

                        // Mostrar solo si la fecha es hoy y el estado es "pendiente"
                        if ($tratamiento->estado === 'pendiente' && $fechaHoy === $fechaTratamiento) {
                            $hayConsultasHoy = true; // Marca que hay consultas hoy
                            $consulta = Consulta::find($tratamiento->id_consulta);
                            $mascota = Mascotas::find($consulta->id_mascota);
                ?>
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm h-100">
                                    <div class="card-img-container" style="height: 150px; overflow: hidden;">
                                        <img src="/sistema-sanbenito/imagemascota/<?php echo $mascota->imagen_mascota; ?>" class="card-img-top img-fluid rounded-top" alt="Imagen de la mascota" style="object-fit: cover; width: 100%; height: 100%;">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-center">
                                            Motivo: <?php echo htmlspecialchars($consulta->motivo_consulta); ?>
                                        </h5>
                                        <p class="card-text text-center">
                                            <strong>Mascota:</strong> <?php echo htmlspecialchars($mascota->nombre); ?><br>
                                            <strong>Diagnóstico Presuntivo:</strong> <?php echo htmlspecialchars($consulta->Diagnostico_presuntivo); ?><br>
                                            <strong>Estado:</strong>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($tratamiento->estado); ?></span>
                                        </p>
                                    </div>

                                    <div class="card-footer bg-light text-center">
                                        <div class="d-flex justify-content-around">
                                            <!-- Botón de Ver -->
                                            <button type="button" class="btn btn-outline-info" onclick="verConsulta('<?php echo htmlspecialchars($mascota->nombre); ?>', '<?php echo htmlspecialchars($consulta->motivo_consulta); ?>', '<?php echo htmlspecialchars($consulta->Diagnostico_presuntivo); ?>', '<?php echo htmlspecialchars($tratamiento->estado); ?>')">
                                                <i class="bi bi-eye-fill"></i> Ver
                                            </button>

                                            <!-- Botón de tratamiento -->
                                            <?php
                                            $data = "id_programacion_tratamiento={$tratamiento->id_programacion_tratamiento}&id_mascota={$consulta->id_mascota}";
                                            $encryptedData = encryptData($data);
                                            ?>
                                            <a href="/sistema-sanbenito/home/programado/tratamiento.php?data=<?php echo $encryptedData ?>" class="btn btn-outline-success">
                                                <i class="bi bi-check-circle-fill"></i> Tratamiento
                                            </a>
                                        </div>

                                        <!-- Botón de completar -->
                                        
                                    </div>
                                </div>
                            </div>
                <?php
                        }
                    }
                }

                if (!$hayConsultasHoy) {
                    echo "<p class='text-center'>No se encontraron tratamientos programadas para hoy.</p>";
                }
                ?>
            </div>
        </div>
        
        <!-- concluidas -->
        <div class="container">
        <div class="row">
            
            
            <center>
                <h3>Concluidos</h3>
            </center>
            <hr>
            <div class="row">
                <?php
                // Verificar si hay consultas programadas para hoy con estado "pendiente"
                $hayConsultasHoy = false;

                if (!empty($tratamientosProgramados2)) {
                    foreach ($tratamientosProgramados2 as $tratamiento) {
                        $fechaTratamiento = date('Y-m-d', strtotime($tratamiento->fecha_programada));

                        // Mostrar solo si la fecha es hoy y el estado es "pendiente"
                        if ($tratamiento->estado === 'concluida' && $fechaHoy === $fechaTratamiento) {
                            $hayConsultasHoy = true; // Marca que hay consultas hoy
                            $consulta = Consulta::find($tratamiento->id_consulta);
                            $mascota = Mascotas::find($consulta->id_mascota);
                ?>
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm h-100">
                                    <div class="card-img-container" style="height: 150px; overflow: hidden;">
                                        <img src="/sistema-sanbenito/imagemascota/<?php echo $mascota->imagen_mascota; ?>" class="card-img-top img-fluid rounded-top" alt="Imagen de la mascota" style="object-fit: cover; width: 100%; height: 100%;">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-center">
                                            Motivo: <?php echo htmlspecialchars($consulta->motivo_consulta); ?>
                                        </h5>
                                        <p class="card-text text-center">
                                            <strong>Mascota:</strong> <?php echo htmlspecialchars($mascota->nombre); ?><br>
                                            <strong>Diagnóstico Presuntivo:</strong> <?php echo htmlspecialchars($consulta->Diagnostico_presuntivo); ?><br>
                                            <strong>Estado:</strong>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($tratamiento->estado); ?></span>
                                        </p>
                                    </div>

                                    <div class="card-footer bg-light text-center">
                                        <div class="d-flex justify-content-around">
                                            <!-- Botón de Ver -->
                                            <button type="button" class="btn btn-outline-info" onclick="verConsulta('<?php echo htmlspecialchars($mascota->nombre); ?>', '<?php echo htmlspecialchars($consulta->motivo_consulta); ?>', '<?php echo htmlspecialchars($consulta->Diagnostico_presuntivo); ?>', '<?php echo htmlspecialchars($tratamiento->estado); ?>')">
                                                <i class="bi bi-eye-fill"></i> Ver
                                            </button>

                                            <!-- Botón de tratamiento -->
                                            <?php
                                        $id_propietario = $mascota->id_propietario;
                                        $data = "id_propietario=$id_propietario";
                                        $encryptedData = encryptData($data);
                                        ?>
                                    <a href="/sistema-sanbenito/home/vermas/propietarios.php?data=<?php echo $encryptedData ?>" class="btn btn-outline-success">
                                    <i class="bi bi-check-circle-fill"></i> Pagar
                                    </a>
                                        </div>

                                        <!-- Botón de completar -->
                                        
                                    </div>
                                </div>
                            </div>
                <?php
                        }
                    }
                }

                if (!$hayConsultasHoy) {
                    echo "<p class='text-center'>No se encontraron tratamientos concluidos.</p>";
                }
                ?>
            </div>
        </div>
</div>

<div id="estadoProceso" style="display: none;"><?php echo $mensajeEstado ?? ''; ?></div>

<script>
    function verConsulta(mascota, motivo, diagnostico, estado) {
        document.getElementById('modalMascota').innerText = mascota;
        document.getElementById('modalMotivo').innerText = motivo;
        document.getElementById('modalDiagnostico').innerText = diagnostico;
        document.getElementById('modalEstado').innerText = estado;

        var consultaModal = new bootstrap.Modal(document.getElementById('consultaModal'));
        consultaModal.show();
    }

    document.querySelectorAll('.completar').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const form = this.closest('form');

            Swal.fire({
                title: "¿Estás seguro de completar la consulta?",
                text: "¡No podrás revertir esta acción!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, completar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        var estadoProceso = document.getElementById("estadoProceso").innerText;

        if (estadoProceso === "success") {
            Swal.fire("Consulta Completada", "La consulta ha sido completada correctamente.", "success");
        } else if (estadoProceso === "nosuccess") {
            Swal.fire("Error", "No se pudo completar la consulta.", "error");
        }
    });
</script>

<!-- Modal -->
<div class="modal fade" id="consultaModal" tabindex="-1" aria-labelledby="consultaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="consultaModalLabel">Detalles de la Consulta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Mascota:</strong> <span id="modalMascota"></span></p>
                <p><strong>Motivo de la consulta:</strong> <span id="modalMotivo"></span></p>
                <p><strong>Diagnóstico Presuntivo:</strong> <span id="modalDiagnostico"></span></p>
                <p><strong>Estado:</strong> <span id="modalEstado"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php require 'template/footer.php'; ?>
