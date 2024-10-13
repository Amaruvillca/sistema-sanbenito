<?php
$titulo = "consultas_hoy";
$nombrepagina = "Consultas Programadas";
require 'template/header.php';

use App\Consulta;
use App\Mascotas;

// Obtener todas las consultas programadas
$consultasProgramadas = Consulta::all();
$consultasConcluidas = Consulta::all();

// Obtener la fecha de hoy en formato 'Y-m-d'
$fechaHoy = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $resultado = Consulta::borrar($id);
    if ($resultado) {
        $mensajeEstado = "success";
    } else {
        $mensajeEstado = "nosuccess";
    }
}
?>

<div class="dashboard-content">
    <div class="container">
        <div class="row">
            <h1><i class="bi bi-journal-text"></i> Consultas pendientes</h1>
            <hr>
            <center>
                <h3>Pendientes</h3>
            </center>
            <hr>
            <div class="row">
                <?php
                // Verificar si hay consultas programadas para hoy con estado "pendiente"
                $hayConsultasHoy = false;

                if (!empty($consultasProgramadas)) {
                    foreach ($consultasProgramadas as $consultaProgramada) {
                        // Comparar la fecha programada de la consulta con la fecha actual


                        // Mostrar solo si la fecha es hoy y el estado es "pendiente"
                        if ($consultaProgramada->estado == 'pendiente') {
                            $hayConsultasHoy = true; // Marca que hay consultas hoy
                ?>
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm h-100">
                                    <?php $mascota = Mascotas::find($consultaProgramada->id_mascota); ?>
                                    <div class="card-img-container" style="height: 150px; overflow: hidden;">
                                        <img src="/sistema-sanbenito/imagemascota/<?php echo $mascota->imagen_mascota; ?>" class="card-img-top img-fluid rounded-top" alt="Imagen de la mascota" style="object-fit: cover; width: 100%; height: 100%;">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-center">
                                            Motivo: <?php echo $consultaProgramada->motivo_consulta; ?>
                                        </h5>
                                        <p class="card-text text-center">
                                            <strong>Mascota:</strong> <?php echo $mascota->nombre; ?><br>

                                            <strong>Diagnostico Presuntivo:</strong> <?php echo $consultaProgramada->Diagnostico_presuntivo ?><br>
                                            <strong>Estado:</strong>

                                            <span class="badge bg-info">
                                                <?php echo $consultaProgramada->estado; ?>
                                            </span>
                                        </p>
                                    </div>

                                    <!-- Sección de botones -->
                                    <div class="card-footer bg-light text-center">
                                        <div class="d-flex justify-content-around">
                                            <!-- Botón de cancelar consulta -->


                                            <button type="button" class="btn btn-outline-info" onclick="verConsulta('<?php echo $mascota->nombre; ?>', '<?php echo $consultaProgramada->motivo_consulta; ?>', '<?php echo $consultaProgramada->Diagnostico_presuntivo; ?>', '<?php echo $consultaProgramada->estado; ?>')">
                                                <i class="bi bi-eye-fill"></i> Ver
                                            </button>



                                            <!-- Botón de realizar consulta -->
                                            <?php
                                            $id_consulta = $consultaProgramada->id_consulta;
                                            $id_mascotass = $consultaProgramada->id_mascota;
                                            $data = "id_consulta=$id_consulta&id_mascota=$id_mascotass";
                                            $encryptedData = encryptData($data);
                                            ?>
                                            <a href="/sistema-sanbenito/home/programado/tratamiento.php?data=<?php echo $encryptedData ?>" class="btn btn-outline-success">
                                                <i class="bi bi-check-circle-fill"></i> tratamiento
                                            </a>

                                        </div>
                                        <!-- Botón de completar consulta -->
                                        <form  method="POST">
                                            <input type="hidden" name="id" value="<?php echo $consultaProgramada->id_consulta; ?>">
                                            <button type="submit" class="btn btn-outline-primary completar" name="completarConsulta">
                                                <i class="bi bi-check2-circle"></i> Completar
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                <?php
                        } // Fin del if que verifica la fecha y el estado pendiente
                    } // Fin del foreach
                }

                // Si no hay consultas programadas para hoy
                if (!$hayConsultasHoy) {
                    echo "<p class='text-center'>No se encontraron consultas programadas para hoy.</p>";
                }
                ?>
            </div>
        </div>
    </div>


</div>

<div id="estadoProceso" style="display: none;"><?php echo $mensajeEstado; ?></div>

<script>
    function verConsulta(mascota, motivo, diagnostico, estado) {
        // Llenar los datos del modal
        document.getElementById('modalMascota').innerText = mascota;
        document.getElementById('modalMotivo').innerText = motivo;
        document.getElementById('modalDiagnostico').innerText = diagnostico;
        document.getElementById('modalEstado').innerText = estado;

        // Mostrar el modal
        var consultaModal = new bootstrap.Modal(document.getElementById('consultaModal'));
        consultaModal.show();
    }
</script>
<script>
    // Manejo de confirmación con SweetAlert2 al hacer clic en "Completar consulta"
    document.querySelectorAll('.completar').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Evita el envío inmediato del formulario

            const form = this.closest('form');

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success mx-2",
                    cancelButton: "btn btn-danger mx-2"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "¿Estás seguro de completar la consulta?",
                text: "¡No podrás revertir esta acción!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, completar",
                cancelButtonText: "No, cancelar",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Envía el formulario si se confirma la acción
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado",
                        text: "La consulta no fue completada",
                        icon: "error",
                        confirmButtonText: "De acuerdo",
                    });
                }
            });
        });
    });

    // Mostrar alertas de éxito o error al completar la consulta
    document.addEventListener("DOMContentLoaded", function() {
        var estadoProceso = document.getElementById("estadoProceso").innerHTML;

        if (estadoProceso === "success") {
            Swal.fire(
                "Consulta Completada",
                "La consulta ha sido completada correctamente.",
                "success"
            );
        } else if (estadoProceso === "nosuccess") {
            Swal.fire(
                "Error",
                "No se pudo completar la consulta.",
                "error"
            );
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
                <!-- Aquí se mostrarán los detalles de la consulta -->
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