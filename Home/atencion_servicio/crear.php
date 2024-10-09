<?php

$titulo = "Propietarios";
$nombrepagina = "Propietarios";
require '../template/header.php';

use App\Atencionservicio;
use App\Mascotas;
use App\Servicios;
use App\Cuenta;


$errores = Atencionservicio::getErrores();
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
$cuenta = Cuenta::buscarCuentaActiva($id_propietario);
if (empty($cuenta)) {
    messageError2("No se encontró una cuenta de pago, cree una.");
    echo '<div style="text-align: center; margin-top: 20px;">
            <a href="javascript:history.back()" style="text-decoration: none; background-color: #007bff; color: white; padding: 10px 20px; border-radius: 5px; display: inline-block;">
                Volver
            </a>
          </div>';
    exit;
}
$mascota = Mascotas::find($id_mascota);
$atencion_servicio = new Atencionservicio();
$servicios = Servicios::all();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $atencion_servicio = new Atencionservicio($_POST['atencion_servicio']);
    $errores = $atencion_servicio->validar();
    if (empty($errores)) {
        $resultado = $atencion_servicio->guardar();
        if ($resultado) {
           
            $mensajeEstado = "success";
        }
    }

}

?>


<div class='dashboard-content'>
<center> <?php

foreach ($errores as $error) :
    messageError2($error);
endforeach;

?></center>
    <div class='container mt-3'>
        <div class="row">
            <div class="col-12">
                <div class="card card-registro-vacuna">
                    <div class="card-header">
                        <h4>Registro de Servicio de <?php echo $mascota->nombre ?></h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-12 mb-3 ">
                                    <label for="proxima_vacuna" class="form-label">Servicio:</label>

                                    <select name="atencion_servicio[id_servicio]" class="form-control" id="nom_vac" class="form-control form-select" required>
                                        <option selected disabled value="">-- Seleccione --</option>
                                        <?php $c = 1;
                                        if (!empty($servicios)) {
                                            foreach ($servicios as $key => $servicio):
                                                if ($servicio->estado == "1") {
                                        ?>
                                                    <option <?php echo $atencion_servicio->id_servicio == $servicio->id_servicio? 'selected' : '' ?> value="<?php echo $servicio->id_servicio ?>"> <?php echo $servicio->nombre_servicio ?> </option>
                                                <?php } else { ?>
                                                    <option value="">no se encontraron servicios activos</option>
                                            <?php
                                                }
                                            endforeach;
                                        } else { ?>
                                            <option value="">no se encontraron servicios</option>
                                        <?php } ?>


                                    </select>
                                    <div class="invalid-feedback">Por favor, seleccione el Servicio.</div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="floatingTextarea2" name="atencion_servicio[observaciones]" style="height: 150px"required><?php echo s($atencion_servicio->observaciones)?></textarea>
                                        <label for="floatingTextarea2">Observacion:</label>
                                        <div class="invalid-feedback">
                                            Por favor, ingrese Observaciones.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="costo" class="form-label">Costo:</label>
                                    <input type="number" step="0.001" class="form-control" id="costo" name="atencion_servicio[costo]" placeholder="0.000" required value="<?php  echo s($atencion_servicio->costo) ?>">
                                    <div class="invalid-feedback">Por favor, ingrese el costo.</div>
                                </div>
                            </div>


                            <input type="hidden" name="atencion_servicio[id_mascota]" value="<?php echo $id_mascota ?>">
                            <input type="hidden" name="atencion_servicio[id_personal]" value="<?php echo $personal['id_personal']; ?>">
                            <input type="hidden" name="atencion_servicio[id_cuenta]" value="<?php echo $cuenta ?>">



                            <button type="submit" class="btn btn-primary w-100">Registrar Vacuna</button>
                        </form>
                        <div id="estadoProceso" style="display: none;"><?php echo $mensajeEstado; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function() {
            'use strict'

            const forms = document.querySelectorAll('.needs-validation')

            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })();
    </script>
    <?php

$data = "id_propietario=$id_propietario&id_mascota=$id_mascota";
$encryptedData = encryptData($data);
?>
    <script>
    // JavaScript para leer el estado del proceso y ejecutar la lógica correspondiente
    document.addEventListener("DOMContentLoaded", function() {
        var estadoProceso = document.getElementById("estadoProceso").textContent.trim();

        if (estadoProceso === "success") {
            Swal.fire({
                title: "¡Servicio realizado con exito!",
                text: "Presiona el botón para regresar.",
                icon: "success",
                confirmButtonText: "De acuerdo",
            }).then(function() {
                window.location.href = '/sistema-sanbenito/home/vermas/mascotas.php?data=<?php echo $encryptedData ?>';
            });
        }
    });
</script>
</div>


<?php
require '../template/footer.php';
?>