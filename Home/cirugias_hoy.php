<?php
$titulo = "cirugias_hoy";
$nombrepagina = "Cirugías Programadas";
require 'template/header.php';

use App\CirugiaProgramada;
use App\Ciruguas;
use App\Mascotas;

// Obtener todas las cirugías programadas
$cirugiasProgramadas = CirugiaProgramada::all();
//cirugias concluidas
$cirugiaconcluida = CirugiaProgramada::all();

// Obtener la fecha de hoy en formato 'Y-m-d'
$fechaHoy = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $resultado = CirugiaProgramada::borrar($id);
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
            <h1><i class="bi bi-calendar2-heart"></i> Cirugías Programadas hoy</h1>
            <hr>
            <center><h3>Pendientes</h3></center>
            <hr>
            <div class="row">
                <?php 
                // Verificar si hay cirugías programadas para hoy
                $hayCirugiasHoy = false;

                if (!empty($cirugiasProgramadas)) {
                    foreach ($cirugiasProgramadas as $cirugiaProgramada) {
                        // Comparar la fecha programada de la cirugía con la fecha actual
                        $fechaCirugia = date('Y-m-d', strtotime($cirugiaProgramada->fecha_programada));

                        if ($fechaCirugia === $fechaHoy && $cirugiaProgramada->estado == 'pendiente') {
                            $hayCirugiasHoy = true; // Marca que hay cirugías hoy
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <?php $mascota = Mascotas::find($cirugiaProgramada->id_mascota); ?>
                            <div class="card-img-container" style="height: 150px; overflow: hidden;">
                                <img src="/sistema-sanbenito/imagemascota/<?php echo $mascota->imagen_mascota; ?>" class="card-img-top img-fluid rounded-top" alt="Imagen de la mascota" style="object-fit: cover; width: 100%; height: 100%;">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center">
                                    <?php $cirugia = Ciruguas::find($cirugiaProgramada->id_cirugia); ?>
                                    Cirugía: <?php echo $cirugia->nombre_cirugia; ?>
                                </h5>
                                <p class="card-text text-center">
                                    <strong>Mascota:</strong> <?php echo $mascota->nombre; ?><br>
                                    <strong>Fecha Programada:</strong> <?php echo $cirugiaProgramada->fecha_programada; ?><br>
                                    <strong>Estado:</strong>
                                    <span class="badge bg-info">
                                        <?php echo $cirugiaProgramada->estado; ?>
                                    </span>
                                </p>
                            </div>

                            <!-- Sección de botones -->
                            <div class="card-footer bg-light text-center">
                                <div class="d-flex justify-content-around">
                                    <!-- Botón de cancelar cirugía -->
                                    <form method="post" class="delete-form">
                                        <input type="hidden" name="id" value="<?php echo $cirugiaProgramada->id_cirugia_programada; ?>">
                                        <button type="button" class="btn btn-outline-danger delete-btn">
                                            <i class="bi bi-x-circle-fill"></i> Cancelar
                                        </button>
                                    </form>

                                    <!-- Botón de realizar cirugía -->
                                   
                                    <?php
                                        $id_cirugia_programada = $cirugiaProgramada->id_cirugia_programada;
                                        $id_mascotass=$cirugiaProgramada->id_mascota;
                                        $id_cirugiaas=$cirugiaProgramada->id_cirugia;
                                        $data = "id_cirugia_programada=$id_cirugia_programada&id_mascota=$id_mascotass&id_cirugia=$id_cirugiaas";
                                        $encryptedData = encryptData($data);
                                        ?>
                                        <a href="/sistema-sanbenito/home/programado/cirugia.php?data=<?php echo $encryptedData ?>" class="btn btn-outline-success" > <i class="bi bi-check-circle-fill"></i> Realizar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                        } // Fin del if que verifica la fecha
                    } // Fin del foreach
                }

                // Si no hay cirugías programadas para hoy
                if (!$hayCirugiasHoy) {
                    echo "<p class='text-center'>No se encontraron cirugías programadas para hoy.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    <hr>
            <center><h3>Concluidos</h3></center>
            <hr>
            <div class="container">
        <div class="row">
        
            
            <div class="row">
                <?php 
                // Verificar si hay cirugías programadas para hoy
                $hayCirugiasHoy = false;

                if (!empty($cirugiaconcluida)) {
                    foreach ($cirugiaconcluida as $cirugiaconcluidas) {
                        // Comparar la fecha programada de la cirugía con la fecha actual
                        $fechaCirugia = date('Y-m-d', strtotime($cirugiaconcluidas->fecha_programada));

                        if ($fechaCirugia === $fechaHoy && $cirugiaconcluidas->estado == 'concluida') {
                            $hayCirugiasHoy = true; // Marca que hay cirugías hoy
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <?php $mascota = Mascotas::find($cirugiaconcluidas->id_mascota); ?>
                            <div class="card-img-container" style="height: 150px; overflow: hidden;">
                                <img src="/sistema-sanbenito/imagemascota/<?php echo $mascota->imagen_mascota; ?>" class="card-img-top img-fluid rounded-top" alt="Imagen de la mascota" style="object-fit: cover; width: 100%; height: 100%;">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center">
                                    <?php $cirugia = Ciruguas::find($cirugiaconcluidas->id_cirugia); ?>
                                    Cirugía: <?php echo $cirugia->nombre_cirugia; ?>
                                </h5>
                                <p class="card-text text-center">
                                    <strong>Mascota:</strong> <?php echo $mascota->nombre; ?><br>
                                    <strong>Fecha Programada:</strong> <?php echo $cirugiaconcluidas->fecha_programada; ?><br>
                                    <strong>Estado:</strong>
                                    <span class="badge bg-info">
                                        <?php echo $cirugiaconcluidas->estado; ?>
                                    </span>
                                </p>
                            </div>

                            <!-- Sección de botones -->
                            <div class="card-footer bg-light text-center">
                                <div class="d-flex justify-content-around">
                                    <!-- Botón de cancelar cirugía -->
                                    <form method="post" class="delete-form">
                                        <input type="hidden" name="id" value="<?php echo $cirugiaconcluidas->id_cirugia_programada; ?>">
                                        <button type="button" class="btn btn-outline-info delete-btn">
                                        <i class="bi bi-eye-fill"></i> Ver
                                        </button>
                                    </form>

                                    <!-- Botón de realizar cirugía -->
                                    
                                    <?php
                                        $id_propietario = $mascota->id_propietario;
                                        $data = "id_propietario=$id_propietario";
                                        $encryptedData = encryptData($data);
                                        ?>
                                    <a href="/sistema-sanbenito/home/vermas/propietarios.php?data=<?php echo $encryptedData ?>" class="btn btn-outline-success">
                                    <i class="bi bi-check-circle-fill"></i> Pagar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                        } // Fin del if que verifica la fecha
                    } // Fin del foreach
                }

                // Si no hay cirugías programadas para hoy
                if (!$hayCirugiasHoy) {
                    echo "<p class='text-center'>No se encontraron cirugías Concluidas.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div id="estadoProceso" style="display: none;"><?php echo $mensajeEstado; ?></div>



<script>
    // Manejo de eliminación con confirmación
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success mx-2",
                    cancelButton: "btn btn-danger mx-2"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "¿Estás seguro de cancelar la cirugía?",
                text: "¡No podrás revertir esto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, cancelar",
                cancelButtonText: "No, mantener",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado",
                        text: "La cirugía no fue cancelada",
                        icon: "error",
                        confirmButtonText: "De acuerdo",
                    });
                }
            });
        });
    });

    // JavaScript para leer el estado del proceso y mostrar mensajes
    document.addEventListener("DOMContentLoaded", function() {
        var estadoProceso = document.getElementById("estadoProceso").textContent.trim();

        if (estadoProceso === "success") {
            Swal.fire({
                title: "¡Cancelado!",
                text: "La cirugía ha sido cancelada exitosamente.",
                icon: "success",
                confirmButtonText: "De acuerdo",
            }).then(function() {
                window.location.href = '/sistema-sanbenito/home/cirugias_hoy.php';
            });
        } else if (estadoProceso === "nosuccess") {
            Swal.fire({
                title: "¡Ups, algo salió mal!",
                text: "No se pudo cancelar la cirugía.",
                icon: "error",
                confirmButtonText: "De acuerdo",
            });
        }
    });
</script>

<?php
require 'template/footer.php';
?>
