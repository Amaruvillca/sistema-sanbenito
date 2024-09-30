<?php
$titulo = "usuarios";
$nombrepagina = "editar perfil";
require '../template/header.php';
error_reporting(0);
use App\Perfil;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

$mensajeEstado = '';
$errores = Perfil::getErrores();


try {
    $manager = new ImageManager(new ImagickDriver());  // Intenta usar Imagick
} catch (Exception $e) {
    $manager = new ImageManager(new GdDriver());  // Fallback a GD si Imagick no está disponible
}

$b = '';
$perfil = null;

if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);
    parse_str($decrypted_data, $params);

    // Ahora tienes acceso a los parámetros
    $b = $params['id_personal'];
    if (!$b) {
        echo "<script>window.history.back();</script>";
    }
} else {
    echo "<script>window.history.back();</script>";
}
$perfil = Perfil::find($b);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //crear objeto
    $perfil = new Perfil($_POST['perfil']);
    $perfil->sincronizar($_POST['perfil']);
    //debuguear($perfil);
    //validar campos
    $errores = $perfil->validar();
    if (empty($errores)) {
        if ($_FILES['imagen']['tmp_name']) {
            $nombreimagen = md5(uniqid(rand(), true)) . ".jpg";
            $rutaImagen = '../../imagepersonal/';
            if ($perfil->imagen_personal != "veterinario.jpg") {
                //borrar imagen
                unlink($rutaImagen . $perfil->imagen_personal);
            }
            $rutaImagen = '../../imagepersonal/' . $nombreimagen;
            $imagen = $manager->read($_FILES['imagen']['tmp_name']);  // Correcto en la versión ^3.7
            // Redimensionar la imagen a 500x500 píxeles
            $imagen->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();  // Mantener la proporción
                $constraint->upsize();  // Evitar agrandar imágenes pequeñas
            });
            // Guardar la imagen redimensionada en el servidor
            $imagen->save($rutaImagen);
            // Asignar la imagen al perfil
            $perfil->setImagen($nombreimagen);
        }

        $resultado = $perfil->actualizar($perfil->id_personal);
        if ($resultado) {
            // Si el usuario se guarda correctamente, establecemos el mensaje
            $mensajeEstado = "success";
        }
    }
}

?>

<div class="dashboard-content">
    <center> <?php

                foreach ($errores as $error) :
                    messageError2($error);
                endforeach;

                ?></center>
    <div class="container mt-4">
        <div class=" formulario--perfil card">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <span class="me-2"><i class="bi bi-person-fill"></i></span>
                <h4 class="mb-0">Editar Perfil</h4>
            </div>
            <div class="card-body">

                <form id="personalForm" class="needs-validation" method="post" novalidate enctype="multipart/form-data">
                    <div class="row">
                        <input class="no-mostrar" type="text" name="perfil[id_personal]" id="perfil" value="<?php echo s($perfil->id_personal) ?>">
                        <input class="no-mostrar" type="text" name="perfil[imagen_personal]" id="perfil" value="<?php echo s($perfil->imagen_personal) ?>">

                        <!-- Imagen del personal -->
                        <div class="col-md-3">
                            <div class="text-center mb-3">
                                <img id="personalFoto" src="/sistema-sanbenito/imagepersonal/<?php echo $perfil->imagen_personal; ?>" class="img-thumbnail mb-2" alt="Foto del personal" width="200" height="200">
                                <input type="file" id="fotoInput" name="imagen" style="display:none;" accept="image/jpeg, image/png">
                                <button type="button" class="btn btn-primary btn-sm w-100" id="btnSubirFoto"><i class="bi bi-camera"></i> Subir foto</button>
                            </div>
                        </div>

                        <!-- Información personal -->
                        <div class="col-md-9">
                            <div class="row">
                                <!-- Nombres -->
                                <div class="col-md-6 mb-3">
                                    <label for="perfil[nombres]" class="form-label">Nombre(s):</label>
                                    <input type="text" class="form-control" id="nombre" name="perfil[nombres]" value="<?php echo s($perfil->nombres); ?>" required>
                                    <div class="invalid-feedback">Por favor, ingrese nombre(s).</div>
                                </div>

                                <!-- Apellido Paterno -->
                                <div class="col-md-6 mb-3">
                                    <label for="perfil[apellido_paterno]" class="form-label">Apellido Paterno:</label>
                                    <input type="text" class="form-control" id="apellido_paterno" name="perfil[apellido_paterno]" value="<?php echo s($perfil->apellido_paterno); ?>">
                                    <div class="invalid-feedback">Por favor, ingrese el apellido paterno.</div>
                                </div>

                                <!-- Apellido Materno -->
                                <div class="col-md-6 mb-3">
                                    <label for="perfil[apellido_materno]" class="form-label">Apellido Materno:</label>
                                    <input type="text" class="form-control" id="apellido_materno" name="perfil[apellido_materno]" value="<?php echo s($perfil->apellido_materno); ?>">
                                    <div class="invalid-feedback">Por favor, ingrese el apellido paterno.</div>
                                </div>

                                <!-- Número de Celular -->
                                <div class="col-md-6 mb-3">
                                    <label for="perfil[num_celular]" class="form-label">Número de Celular:</label>
                                    <input type="tel" class="form-control" id="num_celular" name="perfil[num_celular]" required value="<?php echo s($perfil->num_celular); ?>">
                                    <div class="invalid-feedback">Por favor, ingrese el número de celular.</div>
                                </div>

                                <!-- Dirección -->
                                <div class="col-md-12 mb-3">
                                    <label for="perfil[direccion]" class="form-label">Dirección:</label>
                                    <input type="text" class="form-control" id="direccion" name="perfil[direccion]" required value="<?php echo s($perfil->direccion); ?>">
                                    <div class="invalid-feedback">Por favor, ingrese la dirección.</div>
                                </div>

                                <!-- Número de Carnet -->
                                <div class="col-md-6 mb-3">
                                    <label for="perfil[num_carnet]" class="form-label">Número de Carnet:</label>
                                    <input type="text" class="form-control" id="num_carnet" name="perfil[num_carnet]" required value="<?php echo s($perfil->num_carnet); ?>">
                                    <div class="invalid-feedback">Por favor, ingrese el número de carnet.</div>
                                </div>

                                <!-- Profesión -->
                                <div class="col-md-6 mb-3">
                                    <label for="perfil[profesion]" class="form-label">Profesión:</label>
                                    <input type="text" class="form-control" id="profesion" name="perfil[profesion]" required value="<?php echo s($perfil->profesion); ?>">
                                    <div class="invalid-feedback">Por favor, ingrese la profesión.</div>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="perfil[especialidad]" class="form-label">Especialidad:</label>
                                    <input type="text" class="form-control" id="especialidad" name="perfil[especialidad]" required value="<?php echo s($perfil->especialidad); ?>">
                                    <div class="invalid-feedback">Por favor, ingrese la Especialidad.</div>
                                </div>

                                <!-- Matrícula Profesional -->
                                <div class="col-md-6 mb-3">
                                    <label for="perfil[matricula_profesional]" class="form-label">Matrícula Profesional:</label>
                                    <input type="text" class="form-control" id="matricula" name="perfil[matricula_profesional]" required value="<?php echo s($perfil->matricula_profesional); ?>">
                                    <div class="invalid-feedback">Por favor, ingrese la Matrícula profecional.</div>
                                </div>
                                <input type="date" class="no-mostrar" name="perfil[fecha_registro]" value="<?php echo s($perfil->fecha_registro); ?>">
                                <input type="hidden" class="no-mostrar" name="perfil[id_usuario]" value="<?php echo s($perfil->id_usuario); ?>">


                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="javascript:void(0);" class="btn btn-secondary" onclick="window.history.back();">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Guardar Cambios</button>
                    </div>
                </form>
                <div id="estadoProceso" style="display: none;"><?php echo $mensajeEstado; ?></div>

            </div>
        </div>
    </div>
</div>
<?php ?>

<script>
    document.getElementById('btnSubirFoto').addEventListener('click', function() {
        document.getElementById('fotoInput').click();
    });

    document.getElementById('fotoInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('personalFoto').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Validación del formulario con Bootstrap
    (function() {
        'use strict'

        const forms = document.querySelectorAll('.needs-validation');

        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                }, false);
            });
    })();
</script>
<script>
    // JavaScript para leer el estado del proceso y ejecutar la lógica correspondiente
    document.addEventListener("DOMContentLoaded", function() {
        var estadoProceso = document.getElementById("estadoProceso").textContent.trim();

        if (estadoProceso === "success") {
            Swal.fire({
                title: "¡Datos Actualizados con Éxito!",
                text: "Presiona el botón para ver cambios.",
                icon: "success",
                confirmButtonText: "De acuerdo",
            }).then(function() {
                window.history.back();
            });
        }
    });
</script>



<?php
require '../template/footer.php';
?>