<?php

$titulo = "Propietarios";
$nombrepagina = "Programar cirugía";
require '../template/header.php';

use App\Mascotas;
use App\Cuenta;
use App\CirugiaProgramada;
use App\Ciruguas;
$errores = CirugiaProgramada::getErrores();
if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);
    parse_str($decrypted_data, $params);

    // Verifica si 'id_propietario' está definido en $params
    if (isset($params['id_propietario']) && $params['id_propietario'] && isset($params['id_mascota']) && $params['id_mascota']) {
        $id_propietario = $params['id_propietario'];
        $id_mascota = $params['id_mascota'];
    } else {
        // Si no hay 'id_propietario', ir a la página anterior
        echo "<script>window.history.back();</script>";
        exit;
    }
} else {
    // Si no hay 'data' en el GET, ir a la página anterior
    echo "<script>window.history.back();</script>";

    exit;
}

$cirugias = Ciruguas::all();
$cirugias_encontradas = false;
$pograma_cirugia = new CirugiaProgramada();
$mascota = Mascotas::find($id_mascota);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pograma_cirugia = new CirugiaProgramada($_POST['programacirugia']);
    //debuguear($pograma_cirugia);
    $errores = $pograma_cirugia->validar();
     if (empty($errores)) {
         $resultado = $pograma_cirugia->guardar();
        if ($resultado) {
            // Si el usuario se guarda correctamente, establecemos el mensaje
            $mensajeEstado = "success";
        }
    }
}
?>

<div class='dashboard-content'>
    <br><br><br>
    <center> <?php

                // foreach ($errores as $error) :
                //     messageError2($error);
                // endforeach;

                ?></center>
    <div class='container'>
        <div class="row">
            <div class="col-12">
                <div class="card card-registro-vacuna">
                    <div class="card-header">
                        <h4>Programar cirugia de <?php echo $mascota->nombre?></h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="row">
                                <div class=" col-md-12 mb-3">
                                    <label for="estado" class="form-label">Cirugía:</label>
                                    <select class="form-select form-control" name="programacirugia[id_cirugia]" id="estado" required>
                                        <option disabled value="" selected>-- Seleccione --</option>
                                        <?php if (!empty($cirugias)) {
                                            foreach ($cirugias as $key => $cirugia) {
                                                if ($cirugia->estado == "1") {
                                                    $cirugias_encontradas = true;
                                        ?>
                                                    <option <?php if ($pograma_cirugia->id_cirugia == $cirugia->id_cirugia) echo "selected" ?> value="<?php echo $cirugia->id_cirugia ?>"><?php echo $cirugia->nombre_cirugia ?></option>
                                                <?php }
                                            }
                                            if (!$cirugias_encontradas) {
                                                ?>
                                                <option disabled value="" selected>no hay cirugias activas</option>
                                            <?php
                                            }
                                        } else { ?>
                                            <option disabled value="" selected>no hay cirugias registradas</option>
                                        <?php }

                                        ?>
                                    </select>
                                    <div class="invalid-feedback">Por favor, seleccione la cirugia</div>
                                </div>
                                <div class="col-md-12  ">
                                    <label for="proxima_vacuna" class="form-label">Fecha Promagramada:</label>
                                    <input type="datetime-local" class="form-control" id="proxima_vacuna" name="programacirugia[fecha_programada]" required value="<?php echo s($pograma_cirugia->fecha_programada) ?>">
                                    <div class="invalid-feedback">Por favor, ingrese la la fecha de la cirugia.</div>
                                </div>
                                
                            </div>

                            
                            <input type="hidden" name="programacirugia[id_mascota]" value="<?php echo $id_mascota ?>">
                            <input type="hidden" name="programacirugia[id_personal]" value="<?php echo $personal['id_personal']; ?>">


                            <button type="submit" class=" btn btn-primary w-100" style="margin-top: 1rem;">Registrar Vacuna</button>
                        </form>
                        <div id="estadoProceso" style="display: none;"><?php echo $mensajeEstado; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Validación de Bootstrap 5
    (function() {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>
<script>
    // JavaScript para leer el estado del proceso y ejecutar la lógica correspondiente
    document.addEventListener("DOMContentLoaded", function() {
        var estadoProceso = document.getElementById("estadoProceso").textContent.trim();

        if (estadoProceso === "success") {
            Swal.fire({
                title: "¡Cirugía programada con exito!",
                text: "Presiona el botón para regresar.",
                icon: "success",
                confirmButtonText: "De acuerdo",
            }).then(function() {
                window.location.href = '/sistema-sanbenito/home/calendar.php';
            });
        }
    });
</script>
<?php
require '../template/footer.php';
?>