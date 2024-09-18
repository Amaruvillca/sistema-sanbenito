<?php
// Inicia el búfer de salida
$titulo = "Propietarios";
$nombrepagina = "Vacuna";
require '../template/header.php';

use App\Vacunas;
use App\Mascotas;

$errores = Vacunas::getErrores();
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
$vacuna = new Vacunas();
$mascota = Mascotas::find($id_mascota);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vacuna = new Vacunas($_POST['vacuna']);
    $errores = $vacuna->validar();
    if (empty($errores)) {
        $resultado = $vacuna->guardar();
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
                                    <label for="contra" class="form-label">Contra:</label>
                                    <input type="text" class="form-control" id="contra" name="vacuna[contra]" placeholder="Enfermedad" required value="<?php echo s($vacuna->contra) ?>">
                                    <div class="invalid-feedback">Por favor, ingrese la enfermedad contra la que es la vacuna.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nom_vac" class="form-label">Nombre de la Vacuna:</label>
                                    <input type="text" class="form-control" id="nom_vac" name="vacuna[nom_vac]" placeholder="Nombre" required value="<?php echo s($vacuna->nom_vac) ?>">
                                    <div class="invalid-feedback">Por favor, ingrese el nombre de la vacuna.</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="costo" class="form-label">Costo:</label>
                                    <input type="number" step="0.001" class="form-control" id="costo" name="vacuna[costo]" placeholder="0.000" required value="<?php echo s($vacuna->costo) ?>">
                                    <div class="invalid-feedback">Por favor, ingrese el costo de la vacuna.</div>
                                </div>
                                <div class="col-md-6 ">
                                    <label for="proxima_vacuna" class="form-label">Próxima Vacunación:</label>
                                    <input type="date" class="form-control" id="proxima_vacuna" name="vacuna[proxima_vacuna]" required value="<?php echo s($vacuna->proxima_vacuna) ?>">
                                    <div class="invalid-feedback">Por favor, ingrese la próxima fecha de vacunación.</div>
                                </div>
                            </div>
                            <input type="hidden" name="vacuna[id_mascota]" value="<?php echo $id_mascota ?>">
                            <input type="hidden" name="vacuna[id_personal]" value="<?php echo $personal['id_personal']; ?>">


                            <button type="submit" class="btn btn-primary w-100">Registrar Vacuna</button>
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
                title: "¡Vacuna resgistrada con exito!",
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