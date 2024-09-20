<?php
// Inicia el búfer de salida
$titulo = "Propietarios";
$nombrepagina = "Vacuna";
require '../template/header.php';

use App\Desparacitaciones;
use App\Mascotas;

$errores = Desparacitaciones::getErrores();
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
$desparasitacion = new Desparacitaciones();
$mascota = Mascotas::find($id_mascota);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $desparasitacion = new Desparacitaciones($_POST['desparasitacion']);
    $errores = $desparasitacion->validar();
    //debuguear($desparasitacion);
    if (empty($errores)) {
        $resultado = $desparasitacion->guardar();
        if ($resultado) {
            // Si el usuario se guarda correctamente, establecemos el mensaje
            $mensajeEstado = "success";
        }
    }
}

?>

<div class='dashboard-content'>
    <br><br>
    <center> <?php

                foreach ($errores as $error) :
                    messageError2($error);
                endforeach;

                ?></center>
    <div class='container'>
        <div class="row">
            <div class="col-12">
                <div class="card card-registro-vacuna">
                    <div class="card-header">
                        <h4>Registro de desparasitaciones de <?php echo $mascota->nombre ?></h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contra" class="form-label">Producto:</label>
                                    <input type="text" class="form-control" id="contra" name="desparasitacion[producto]" placeholder="Producto" required value="<?php echo s($desparasitacion->producto) ?>">
                                    <div class="invalid-feedback">Por favor, ingrese el producto.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nom_vac" class="form-label">tipo desparasitacion:</label>
                                    <select name="desparasitacion[tipo_desparasitacion]" class="form-control"  class="form-control form-select" required>
                                        <option selected disabled value="">-- Seleccione --</option>
                                        <option <?php echo $desparasitacion->tipo_desparasitacion == 'Interna' ? 'selected' : '' ?> value="Interna">Interna</option>
                                        <option <?php echo $desparasitacion->tipo_desparasitacion == 'Externa' ? 'selected' : '' ?> value="Externa">Externa</option>
                                    </select>
                                    <div class="invalid-feedback">Por favor, selccione el tipo de desparasitación.</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="costo" class="form-label">Principio activo:</label>
                                    <input type="text" class="form-control" id="costo" name="desparasitacion[principio_activo]" placeholder="principio activo" required value="<?php echo s($desparasitacion->principio_activo) ?>">
                                    <div class="invalid-feedback">Por favor, ingrese principio activo.</div>
                                </div>
                                <div class="col-md-6 ">
                                    <label for="proxima_vacuna" class="form-label">Via:</label>
                                    
                                    <select name="desparasitacion[via]" class="form-control" id="nom_vac" class="form-control form-select" required>
                                        <option selected disabled value="">-- Seleccione --</option>
                                        <option <?php echo $desparasitacion->via == 'Oral' ? 'selected' : '' ?> value="Oral">Oral</option>
                                        <option <?php echo $desparasitacion->via == 'Inyectable' ? 'selected' : '' ?> value="Inyectable">Inyectable</option>
                                        <option <?php echo $desparasitacion->via == 'Producto medicado' ? 'selected' : '' ?> value="Producto medicado">Producto medicado</option>
                                    </select>
                                    <div class="invalid-feedback">Por favor, seleccione la via.</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="costo" class="form-label">Costo:</label>
                                    <input type="number" step="0.001" class="form-control" id="costo" name="desparasitacion[costo]" placeholder="0.000" required value="<?php echo s($desparasitacion->costo) ?>">
                                    <div class="invalid-feedback">Por favor, ingrese el costo de la vacuna.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="proxima_vacuna" class="form-label">Próxima Desparasitación:</label>
                                    <input type="date" class="form-control" id="proxima_vacuna" name="desparasitacion[proxima_desparasitacion]" required value="<?php echo s($desparasitacion->proxima_desparasitacion) ?>">
                                    <div class="invalid-feedback">Por favor, ingrese la próxima fecha de vacunación.</div>
                                </div>
                            </div>
                            <input type="hidden" name="desparasitacion[id_mascota]" value="<?php echo $id_mascota ?>">
                            <input type="hidden" name="desparasitacion[id_personal]" value="<?php echo $personal['id_personal'] ?>">


                            <button type="submit" class="btn btn-primary w-100">Registrar Desparasitacín</button>
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
                title: "¡Desparasitación resgistrada con exito!",
                text: "Presiona el botón para regresar.",
                icon: "success",
                confirmButtonText: "De acuerdo",
            }).then(function() {
                window.location.href = '/sistema-sanbenito/home/vermas/mascotas.php?data=<?php echo $encryptedData ?>';
            });
        }
    });
</script>

<?php
require '../template/footer.php';
?>