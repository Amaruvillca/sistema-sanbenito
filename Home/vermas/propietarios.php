<?php

$titulo = "Propietarios";
$nombrepagina = "ver más";
require '../template/header.php';


use App\Propietarios;
use App\Perfil;
use App\Mascotas;

error_reporting(0);
if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);
    parse_str($decrypted_data, $params);

    // Verifica si 'id_propietario' está definido en $params
    if (isset($params['id_propietario']) && $params['id_propietario']) {
        $b = $params['id_propietario'];
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

$propietarios = Propietarios::find($b);
$perfil = Perfil::find($propietarios->id_personal);
$mascotas = Mascotas::all();
$mascotas_encontradas = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_mascota'];
    $mascota = Mascotas::find($id);
    $rutaImagen = '../../imagemascota/';
    $resultado = Mascotas::borrar($id);

    if ($resultado) {
        // Si el usuario se guarda correctamente, establecemos el mensaje

        if ($mascota->imagen_mascota != "mascota.png") {
            //borrar imagen
            unlink($rutaImagen . $mascota->imagen_mascota);
        }
        $mensajeEstado = "success";
    } else {
        $mensajeEstado = "nosuccess";
    }
}
?>

<div class='dashboard-content'>
    <div class='container'>
        <div class="row">
            <center>
                <h3 class="card-title">Datos del Propietario</h3>
            </center>
            <div class="col-12">
                <div class="card-propietarios card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4 col-ms-3 d-grid align-items-center justify-content-center mb-4">
                                <p class="card-text"><strong>Nombre(s): </strong> <?php echo $propietarios->nombres ?></p>
                                <p class="card-text"><strong>Apellidos: </strong> <?php echo $propietarios->apellido_paterno . " " . $propietarios->apellido_materno ?></p>
                                <p class="card-text"><strong>Correo: </strong> <?php echo $propietarios->email ?></p>

                            </div>
                            <div class="col-12 col-md-4 col-ms-3 d-grid align-items-center justify-content-center mb-4">
                                <p class="card-text"><strong>Celular: </strong> <?php echo $propietarios->num_celular ?></p>
                                <p class="card-text"><strong>Celular Sec. : </strong> <?php echo ($propietarios->num_celular_secundario == '') ? 'S/N' : $propietarios->num_celular_secundario; ?></p>
                                <p class="card-text"><strong>Número de carnet: </strong> <?php echo $propietarios->num_carnet ?></p>

                            </div>
                            <div class="col-12 col-md-4 col-ms-3 d-grid align-items-center justify-content-center mb-4">

                                <p class="card-text"><strong>Fecha de registro: </strong> <?php echo $propietarios->fecha_registro ?></p>
                                <p class="card-text"><strong>Registrado por: </strong> <?php echo $perfil->nombres . " " . $perfil->apellido_paterno . " " . $perfil->apellido_materno ?></p>

                            </div>
                            <div class="col-12 d-grid align-items-center justify-content-center ">
                                <p class="card-text"><strong>Dirección: </strong> <?php echo $propietarios->direccion ?></p>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <center>
                    <h3 class="card-title">Mascotas Asociadas</h3>
                </center>
            </div>

            <div class="col-12 d-flex justify-content-end mb-4">
                <div class="d-flex align-items-center">
                    <label for="busqueda-mascotas" class="mb-0 me-2">Buscar:</label>
                    <input type="search" id="busqueda-mascotas" class="form-control" placeholder="Nombre o código mascota">
                </div>
            </div>
            <?php
            foreach ($mascotas as $key => $mascota) {
                if ($mascota->id_propietario == $b) {
                    $mascotas_encontradas = true;
            ?>
                    <div class="col-12 col-md-4 col-ms-6 col-sm-6 mb-4 card-mascota">
                        <div class='card-mascota card'>
                            <div class='card-header'>
                                <h5><?php echo $mascota->nombre; ?></h5>
                            </div>
                            <div class='card-body'>
                                <div class="col-12 mb-1">
                                    <center>
                                        <img src="/sistema-sanbenito/imagemascota/<?php echo $mascota->imagen_mascota; ?>" alt="Imagen de mascota" class="img-fluid rounded-circle shadow-sm" style="width: 180px; height: 180px; object-fit: cover; transition: transform 0.3s ease; border: 4px solid #005C43;">
                                    </center>
                                </div>
                                <center>
                                    <p><?php echo $mascota->codigo_mascota; ?></p>
                                </center>
                                <center>
                                    <p><strong>Edad:</strong> <?php
                                                                $fecha_nacimiento = new DateTime($mascota->fecha_nacimiento);
                                                                $hoy = new DateTime();
                                                                $edad = $hoy->diff($fecha_nacimiento);
                                                                echo $edad->y . ' años y ';
                                                                echo $edad->m . ' meses ';
                                                                ?></p>
                                </center>
                                <div class="row">
                                    <?php
                                    $id_propietario = $propietarios->id_propietario;
                                    $id_mascota = $mascota->id_mascota;
                                    $data = "id_propietario=$id_propietario&id_mascota=$id_mascota";
                                    $encryptedData = encryptData($data);
                                    ?>
                                    <div class="col-6 mb-2">
                                        <a href="/sistema-sanbenito/home/mascotas/editar.php?data=<?php echo $encryptedData; ?>" class="btn btn-success btn-sm w-100"> <i class="bi bi-pencil-square"></i> Editar</a>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <form method="post">
                                            <input type="hidden" name="id_mascota" value="<?php echo $mascota->id_mascota; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm w-100 delete-btn"><i class="bi bi-trash-fill"></i> Eliminar</button>
                                        </form>

                                    </div>
                                    <div class="col-12 mb-2">
                                        <a href="/sistema-sanbenito/home/vermas/mascotas.php?data=<?php echo $encryptedData; ?>" class="btn btn-info btn-sm w-100"><i class="bi bi-eye-fill"></i> Ver más</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
            }
            if (!$mascotas_encontradas) {
                ?>
                <div class="col-12">
                    <hr>
                    <hr>
                    <center>
                        No se encontraron mascotas asociadas a este propietario.
                    </center>
                    <hr>
                    <hr>

                </div>
            <?php
            }
            ?>
        </div>
        <div id="estadoProceso" style="display: none;"><?php echo $mensajeEstado; ?></div>
        <script>
            document.getElementById('busqueda-mascotas').addEventListener('keyup', function() {
                //Obtener el valor de búsqueda
                let searchQuery = this.value.toLowerCase();
                // Obtener todas las tarjetas de mascotas
                let mascotas = document.querySelectorAll('.card-mascota');
                // Recorrer todas las tarjetas y ocultar las que no coincidan
                mascotas.forEach(function(mascota) {
                    // Obtener el nombre y el código de la mascota en minúsculas
                    let nombreMascota = mascota.querySelector('h5').textContent.toLowerCase();
                    let codigoMascota = mascota.querySelector('p').textContent.toLowerCase();

                    // Comprobar si el valor de búsqueda coincide con el nombre o código
                    if (nombreMascota.includes(searchQuery) || codigoMascota.includes(searchQuery)) {
                        // Mostrar la tarjeta si coincide
                        mascota.style.display = '';
                    } else {
                        // Ocultar la tarjeta si no coincide
                        mascota.style.display = 'none';
                    }
                });
            });
        </script>
        <script>
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevenir el envío inmediato del formulario

                    const form = this.closest('form'); // Encuentra el formulario más cercano

                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: "btn btn-success mx-2",
                            cancelButton: "btn btn-danger mx-2"
                        },
                        buttonsStyling: false
                    });

                    swalWithBootstrapButtons.fire({
                        title: "¿Estás seguro de eliminar esta mascota?",
                        text: "Esta acción no se puede deshacer.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "No, cancelar",
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Envía el formulario si el usuario confirma
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            swalWithBootstrapButtons.fire({
                                title: "Cancelado",
                                text: "La mascota no fue eliminada.",
                                icon: "error",
                                confirmButtonText: "De acuerdo",
                            });
                        }
                    });
                });
            });
        </script>
        <script>
            // JavaScript para leer el estado del proceso y ejecutar la lógica correspondiente
            document.addEventListener("DOMContentLoaded", function() {
                var estadoProceso = document.getElementById("estadoProceso").textContent.trim();

                if (estadoProceso === "success") {
                    Swal.fire({
                        title: "¡Eliminado!",
                        text: "La mascota ha sido eliminado exitosamente.",
                        icon: "success",
                        confirmButtonText: "De acuerdo",
                    }).then(function() {
                        window.location.href = '/sistema-sanbenito/home/vermas/propietarios.php?data=<?php echo $encryptedData; ?>';
                    });
                } else {
                    if (estadoProceso === "nosuccess") {
                        Swal.fire({
                            title: "¡Ups algo salio mal!",
                            text: "No se pudo eliminar a la mascota",
                            icon: "error",
                            confirmButtonText: "De acuerdo",
                        });
                    }
                }
            });
        </script>

    </div>
    <?php
    $id_propietario = $propietarios->id_propietario;
    $data = "id_propietario=$id_propietario";
    $encryptedData = encryptData($data);
    ?>

    <!-- Botón flotante -->
    <a href="/sistema-sanbenito/home/mascotas/crear.php?data=<?php echo $encryptedData; ?>" class="btn-flotante"><i class="bi bi-plus"></i></a>
</div>

<?php
require '../template/footer.php';
?>