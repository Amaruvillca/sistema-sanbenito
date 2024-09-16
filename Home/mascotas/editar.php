<?php
$titulo = "Propietarios";
$nombrepagina = "Registrar mascota";
require '../template/header.php';

use App\Mascotas;
use App\Propietarios;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

try {
    $manager = new ImageManager(new ImagickDriver());
} catch (Exception $e) {
    $manager = new ImageManager(new GdDriver());
}
$codigo = '';
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
$errores = Mascotas::getErrores();
$propietario = Propietarios::find($id_propietario);
$mascotas = Mascotas::find($id_mascota);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //crar objeto mascota
    $mascotas = new Mascotas($_POST['mascota']);
    $mascotas->sincronizar($_POST['mascota']);


    // validar errores
    $errores = $mascotas->validar();


    if (empty($errores)) {
        // Verificar si se ha subido un archivo de imagen
        if ($_FILES['imagen']['tmp_name']) {
            $nombreimagen = md5(uniqid(rand(), true)) . ".jpg";
            $rutaImagen = '../../imagemascota/';
            if ($mascotas->imagen_mascota != "mascota.png") {
                //borrar imagen
                unlink($rutaImagen . $mascotas->imagen_mascota);
            }
            $rutaImagen = '../../imagemascota/' . $nombreimagen;
            $imagen = $manager->read($_FILES['imagen']['tmp_name']);  // Correcto en la versión ^3.7
            // Redimensionar la imagen a 500x500 píxeles
            $imagen->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();  // Mantener la proporción
                $constraint->upsize();  // Evitar agrandar imágenes pequeñas
            });
            // Guardar la imagen redimensionada en el servidor
            $imagen->save($rutaImagen);
            // Asignar la imagen al perfil
            $mascotas->setImagen($nombreimagen);
        }
        $codigo = $mascotas->generarCodigoMascota($propietario->num_carnet, $propietario->nombres, $propietario->num_celular, $propietario->id_propietario);
        $mascotas->setCodigoMascota($codigo);

        $resultado = $mascotas->actualizar($id_mascota);
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
            <div class="btn-primary card-header  text-white d-flex align-items-center">
                <span class="me-2"><i class="fas fa-paw"></i></i></span>
                <h4 class="mb-0">Nueva mascota</h4>
            </div>
            <div class="card-body">
                <form id="mascotaForm" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
                    <input type="hidden" name="mascota[id_mascota]" value="<?php echo $mascotas->id_mascota ?>">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center mb-3">
                                <img id="clienteFoto" src="/sistema-sanbenito/imagemascota/<?php echo $mascotas->imagen_mascota ?>" class="img-thumbnail mb-2" alt="Foto del mascota" width="200px" height="200px">
                                <input type="file" id="fotoInput" name="imagen" style="display:none; " accept="image/jpeg, image/png">
                                <button type="button" class="btn btn-primary btn-sm w-100" id="btnSubirFoto">Añadir foto</button>
                            </div>
                        </div>
                        <input type="hidden" name="mascota[imagen_mascota]" value="<?php echo $mascotas->imagen_mascota ?>">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="nombre" class="form-label">Nombre:</label>
                                    <input type="text" class="form-control" name="mascota[nombre]" id="nombre" required value="<?php echo s($mascotas->nombre); ?>">
                                    <div class="invalid-feedback">
                                        Por favor ingrese el nombre.
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="Especie" class="form-label">Especie:</label>
                                    <input type="text" class="form-control" name="mascota[especie]" id="Especie" required value="<?php echo s($mascotas->especie); ?>">
                                    <div class="invalid-feedback">
                                        Por favor ingrese la especie.
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Sexo:</label>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="radio" name="mascota[sexo]" id="macho" value="macho"
                                                <?php echo ($mascotas->sexo == 'macho') ? 'checked' : ''; ?> required>
                                            <label class="form-check-label" for="macho">Macho</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="mascota[sexo]" id="hembra" value="hembra"
                                                <?php echo ($mascotas->sexo == 'hembra') ? 'checked' : ''; ?> required>
                                            <label class="form-check-label" for="hembra">Hembra</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback mt-2">
                                        Por favor seleccione el sexo.
                                    </div>
                                </div>



                                <div class="col-md-4 mb-3">
                                    <label for="color" class="form-label">Color:</label>
                                    <input type="text" class="form-control" name="mascota[color]" id="color" required value="<?php echo s($mascotas->color); ?>">
                                    <div class="invalid-feedback">
                                        Por favor ingrese el color.
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="raza" class="form-label">Raza:</label>
                                    <input type="text" class="form-control" name="mascota[raza]" id="raza" required value="<?php echo s($mascotas->raza); ?>">
                                    <div class="invalid-feedback">
                                        Por favor ingrese la raza.
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="fechaNacimiento" class="form-label">Fecha de nacimiento:</label>
                                    <input type="date" class="form-control" name="mascota[fecha_nacimiento]" id="fechaNacimiento" required value="<?php echo s($mascotas->fecha_nacimiento); ?>">
                                    <div class="invalid-feedback">
                                        Por favor ingrese la fecha de nacimiento.
                                    </div>
                                </div>
                                <input type="hidden" name="mascota[id_propietario]" value="<?php echo $mascotas->fecha_registro ?>">
                                <input type="hidden" name="mascota[id_propietario]" value="<?php echo $id_propietario ?>">


                            </div>
                        </div>
                    </div>
                    <?php


                    $data = "id_propietario=$id_propietario";
                    $encryptedData = encryptData($data);
                    ?>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="/sistema-sanbenito/home/vermas/propietarios.php?data=<?php echo $encryptedData ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i>
                            Volver
                        </a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Guardar cambios </button>
                    </div>
                </form>
                <div id="estadoProceso" style="display: none;"><?php echo $mensajeEstado; ?></div>
            </div>
        </div>
    </div>

</div>
<script>
    document.getElementById('btnSubirFoto').addEventListener('click', function() {
        document.getElementById('fotoInput').click();
    });

    document.getElementById('fotoInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('clienteFoto').src = e.target.result;

            }
            reader.readAsDataURL(file);
        }
    });

    // Bootstrap form validation
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
<script>
    // JavaScript para leer el estado del proceso y ejecutar la lógica correspondiente
    document.addEventListener("DOMContentLoaded", function() {
        var estadoProceso = document.getElementById("estadoProceso").textContent.trim();

        if (estadoProceso === "success") {
            Swal.fire({
                title: "¡Datos actualizados con exito!",
                text: "Presiona el botón para regresar.",
                icon: "success",
                confirmButtonText: "De acuerdo",
            }).then(function() {
                window.location.href = '/sistema-sanbenito/home/vermas/propietarios.php?data=<?php echo $encryptedData ?>';
            });
        }
    });
</script>

<?php
require '../template/footer.php';
?>