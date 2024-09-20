<?php

$titulo = "Cirugias";
$nombrepagina = "Nueva cirugia";
require '../../template/header.php';
verificaAcceso();

use App\Ciruguas;
$errores = Ciruguas::getErrores();
$cirugias = new Ciruguas();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cirugias = new Ciruguas($_POST['cirugias']);
    $errores = $cirugias->validar();
    if (empty($errores)) {
        $resultado = $cirugias->guardar();
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

    <div class="container mt-4">
        <div class="card">
            <div class="card-header btn-primary text-white d-flex align-items-center">
                <span class="me-2"><i class="bi bi-heart-pulse-fill"></i></span>
                <h4 class="mb-0">Nueva cirugia</h4>
            </div>
            <div class="card-body">
                <form method="post" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="nombre" class="form-label">Nombre cirugia:</label>
                                    <input type="text" class="form-control" name="cirugias[nombre_cirugia]" id="nombre" value="<?php echo s($cirugias->nombre_cirugia) ?>" required>
                                    <div class="invalid-feedback">
                                        Por favor, ingrese nombre de cirugia.
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="floatingTextarea2" name="cirugias[descripcion]" style="height: 150px" value="<?php echo s($cirugias->descripcion) ?>" required></textarea>
                                        <label for="floatingTextarea2">Descripción:</label>
                                        <div class="invalid-feedback">
                                            Por favor, ingrese descripción.
                                        </div>
                                    </div>
                                </div>






                                <input type="hidden" name="cirugias[id_personal]" value="<?php echo $personal['id_personal']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="/sistema-sanbenito/home/setting/cirugias.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">Guardar Cirugía</button>
                    </div>
                </form>
                <div id="estadoProceso" style="display: none;"><?php echo $mensajeEstado; ?></div>
            </div>
        </div>
    </div>
</div>

</div>
<script>
    // Bootstrap validation logic
    (function() {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
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
    })()
</script>
<script>
    // JavaScript para leer el estado del proceso y ejecutar la lógica correspondiente
    document.addEventListener("DOMContentLoaded", function() {
        var estadoProceso = document.getElementById("estadoProceso").textContent.trim();

        if (estadoProceso === "success") {
            Swal.fire({
                title: "¡Cirugia creada con éxito!",
                text: "Presiona el botón para registrar una mascota.",
                icon: "success",
                confirmButtonText: "De acuerdo",
            }).then(function() {
                window.location.href = '/sistema-sanbenito/home/setting/cirugias.php';
            });
        }
    });
</script>

<?php
require '../../template/footer.php';
?>