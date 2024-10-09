<?php
$titulo = "Propietarios";
$nombrepagina = "Editar propietario";
require '../template/header.php';

use App\Propietarios;
$mensajeEstado = '';
$errores = Propietarios::getErrores();


if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);
    parse_str($decrypted_data, $params);

    // Ahora tienes acceso a los parámetros
    $b = $params['id_propietario'];
    if (!$b) {
        header('Location:/sistema-sanbenito/error/403.php?mensaje=3');
    }
} else {
    header('Location:/sistema-sanbenito/error/403.php?mensaje=3');
}
$propietarios=Propietarios::find($b);




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // crear objeto
    $propietarios = new Propietarios($_POST['propietario']);
// validar errores
    $errores = $propietarios->validar();
    //si no hay errores
    if (empty($errores)) {
        $resultado = $propietarios->actualizar($b);
        if ($resultado) {
            // Si el usuario se guarda correctamente, establecemos el mensaje
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
                <span class="me-2"><i class="bi bi-person-fill"></i></span>
                <h4 class="mb-0">Editar Propietario</h4>
            </div>
            <div class="card-body">
                <form method="post" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <input type="hidden" name="propietario[id_propietario]" value="<?php echo s($propietarios->id_propietario) ?>" >
                                <div class="col-md-4 mb-3">
                                    <label for="nombre" class="form-label">Nombre(s):</label>
                                    <input type="text" class="form-control" name="propietario[nombres]" id="nombre" value="<?php echo s($propietarios->nombres) ?>" required>
                                    <div class="invalid-feedback">
                                        Por favor, ingrese su nombre.
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="apellidosPaterno" class="form-label">Apellido Paterno:</label>
                                    <input type="text" class="form-control" name="propietario[apellido_paterno]" id="apellidosPaterno" value="<?php echo s($propietarios->apellido_paterno) ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="apellidosMaterno" class="form-label">Apellido Materno:</label>
                                    <input type="text" class="form-control" name="propietario[apellido_materno]" id="apellidosMaterno" value="<?php echo s($propietarios->apellido_materno) ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="nif" class="form-label">Número de carnet:</label>
                                    <input type="text" class="form-control" name="propietario[num_carnet]" id="nif" required value="<?php echo s($propietarios->num_carnet) ?>">
                                    <div class="invalid-feedback">
                                        Por favor, ingrese su número de carnet.
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="telefonoCasa" class="form-label">Número de celular:</label>
                                    <input type="text" class="form-control" name="propietario[num_celular]"id="telefonoCasa" required value="<?php echo s($propietarios->num_celular) ?>">
                                    <div class="invalid-feedback">
                                        Por favor, ingrese su número de celular.
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="telefonoSec" class="form-label">Número de Alternativo:</label>
                                    <input type="text" class="form-control" name="propietario[num_celular_secundario]" id="telefonoSec"  value="<?php echo s($propietarios->num_celular_secundario) ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Correo electrónico:</label>
                                    <input type="email" class="form-control" name="propietario[email]" id="email" value="<?php echo s($propietarios->email) ?>">
                                    <div class="invalid-feedback">
                                        Por favor, ingrese un correo electrónico válido.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="direccion" class="form-label">Dirección:</label>
                                    <input type="text" class="form-control" name="propietario[direccion]" id="direccion" required value="<?php echo s($propietarios->direccion) ?>">
                                    <div class="invalid-feedback">
                                        Por favor, ingrese su dirección.
                                    </div>
                                </div>
                                <input type="hidden" name="propietario[fecha_registro]" value="<?php echo $propietarios->fecha_registro ?>">
                                <input type="hidden" name="propietario[id_personal]" value="<?php echo $personal['id_personal']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="/sistema-sanbenito/home/propietarios.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
                <div id="estadoProceso" style="display: none;"><?php echo $mensajeEstado; ?></div>
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
                title: "¡Datos Actualizados con Éxito!",
                text: "Presiona el botón para ver volver",
                icon: "success",
                confirmButtonText: "De acuerdo",
            }).then(function() {
                window.location.href = '/sistema-sanbenito/home/propietarios.php';
            });
        }
    });
</script>
<?php
require '../template/footer.php';
?>