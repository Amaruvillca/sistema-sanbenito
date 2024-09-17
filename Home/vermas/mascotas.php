<?php
$titulo = "Propietarios";
$nombrepagina = "mas detalles";
require '../template/header.php';

error_reporting(0);

use App\Mascotas;
use App\Propietarios;
use App\Vacunas;


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
$mascota = Mascotas::find($id_mascota);
$propietario = Propietarios::find($mascota->id_propietario);
$vacunas = Vacunas::all();
$mascotas_encontradas = false;
?>

<div class="dashboard-content">
    <div class='container'>
        <div class="row">
            <div class="col-12">
                <div class='card shadow-lg p-4' style="border-radius: 10px; background-color: #ffffff;">
                    <div class='card-body'>
                        <?php

                        $data = "id_propietario=$id_propietario";
                        $encryptedData = encryptData($data);
                        ?>
                        <a href="/sistema-sanbenito/home/vermas/propietarios.php?data=<?php echo $encryptedData ?>" class="btn btn-secondary" style="border-radius: 25px;">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>


                        <!-- Distribuir el contenido en tres columnas centradas vertical y horizontalmente -->
                        <div class="row text-center justify-content-center align-items-center">
                            <!-- Columna de la imagen -->
                            <div class="col-md-4 col-12 mb-4">
                                <img src="/sistema-sanbenito/imagemascota/<?php echo $mascota->imagen_mascota ?>" alt="Imagen de perfil" class="img-fluid rounded-circle shadow-sm" style="width: 190px; height: 190px; object-fit: cover; transition: transform 0.3s ease; border: 4px solid #005C43;">
                                <h5 class="mt-3" style="color: #005C43; font-weight: bold;"><?php echo $mascota->nombre ?></h5>
                                <h6 class="text-muted"><?php echo $mascota->codigo_mascota ?></h6>
                            </div>

                            <!-- Columna de datos del usuario -->
                            <div class="col-md-4 col-12 mb-4 text-md-start">
                                <h5 class="mb-3" style="color: #005C43; font-weight: bold; letter-spacing: 1px;">Datos del Mascota</h5>
                                <p class="mb-2 text-secondary"><strong>Especie: </strong> <?php echo $mascota->especie ?></p>
                                <p class="mb-2 text-secondary"><strong>Sexo: </strong> <?php echo $mascota->sexo ?></p>
                                <p class="mb-2 text-secondary"><strong>Color: </strong><?php echo $mascota->color ?> </p>
                                <p class="mb-2 text-secondary"><strong>Raza: </strong><?php echo $mascota->raza ?> </p>
                                <p class="mb-2 text-secondary"><strong>Edad: </strong><?php
                                                                                        $fecha_nacimiento = new DateTime($mascota->fecha_nacimiento);
                                                                                        $hoy = new DateTime();
                                                                                        $edad = $hoy->diff($fecha_nacimiento);
                                                                                        echo $edad->y . ' años y ';
                                                                                        echo $edad->m . ' meses ';
                                                                                        ?> </p>
                                <p class="mb-2 text-secondary"><strong>Fecha registro: </strong><?php echo $mascota->fecha_registro ?> </p>
                                <p class="mb-2 text-secondary"><strong>Propietario: </strong><?php echo $propietario->nombres . " " . $propietario->apellido_paterno . " " . $propietario->apellido_materno ?> </p>
                                <p class="mb-2 text-secondary"><strong>Celular: </strong><?php echo $propietario->num_celular ?> </p>
                                <p class="mb-2 text-secondary"><strong>Celular Sec. : </strong><?php echo $propietario->num_celular_secundario ?> </p>

                            </div>

                            <div class="col-md-4 col-12 mb-4 text-md-start">
                                <h5 class="mb-3" style="color: #005C43; font-weight: bold; letter-spacing: 1px;">Servicios</h5>

                                <div class="d-flex flex-column gap-3">
                                    <?php
                                    $id_propietario = $propietario->id_propietario;
                                    $id_mascota = $mascota->id_mascota;
                                    $data = "id_propietario=$id_propietario&id_mascota=$id_mascota";
                                    $encryptedData = encryptData($data);
                                    ?>
                                    <!-- Botón de Vacunas -->
                                    <a href="/sistema-sanbenito/home/vacunas/crear.php?data=<?php echo $encryptedData ?>" class="btn btn-outline-success px-4 py-2 shadow-sm" style="border-radius: 25px; transition: background-color 0.3s ease; background-color: #28a745; border-color: #28a745; color: white; font-weight: 600;">
                                        <i class="bi bi-shield-check"></i> Vacunas
                                    </a>

                                    <!-- Botón de Desparasitaciones -->
                                    <a href="/sistema-sanbenito/home/servicios/desparasitaciones.php" class="btn btn-outline-warning px-4 py-2 shadow-sm" style="border-radius: 25px; transition: background-color 0.3s ease; background-color: #ffc107; border-color: #ffc107; color: white; font-weight: 600;">
                                        <i class="bi bi-bug"></i> Desparasitaciones
                                    </a>

                                    <!-- Botón de Cirugías -->
                                    <a href="/sistema-sanbenito/home/servicios/cirugias.php" class="btn btn-outline-danger px-4 py-2 shadow-sm" style="border-radius: 25px; transition: background-color 0.3s ease; background-color: #dc3545; border-color: #dc3545; color: white; font-weight: 600;">
                                        <i class="bi bi-heart-pulse"></i> Cirugías
                                    </a>

                                    <!-- Botón de Servicios -->
                                    <a href="/sistema-sanbenito/home/servicios/otros.php" class="btn btn-outline-primary px-4 py-2 shadow-sm" style="border-radius: 25px; transition: background-color 0.3s ease; background-color: #005C43; border-color: #005C43; color: white; font-weight: 600;">
                                        <i class="bi bi-gear"></i> Otros Servicios
                                    </a>

                                    <!-- Botón de Consultas -->
                                    <a href="/sistema-sanbenito/home/servicios/consultas.php" class="btn btn-outline-info px-4 py-2 shadow-sm" style="border-radius: 25px; transition: background-color 0.3s ease; background-color: #17a2b8; border-color: #17a2b8; color: white; font-weight: 600;">
                                        <i class="bi bi-search"></i> Consultas
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <hr>
            <hr>
            <div class="col-12">
                <div class="card shadow-lg" style="border-radius: 15px; background-color: #f8f9fa;">
                    <div class="card-header" style="border-radius: 15px 15px 0 0; background-color: #005C43; color: white;">
                        <h3 class="m-0" style="font-family: 'Poppins', sans-serif;">Historial de Atenciones</h3>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="historialTabs" role="tablist" style="font-family: 'Poppins', sans-serif;">
                            <li class="nav-item">
                                <a class="nav-link active" id="consultas-tab" data-bs-toggle="tab" href="#consultas" role="tab" aria-controls="consultas" aria-selected="true">Consultas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="cirugias-tab" data-bs-toggle="tab" href="#cirugias" role="tab" aria-controls="cirugias" aria-selected="false">Cirugías</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="vacunas-tab" data-bs-toggle="tab" href="#vacunas" role="tab" aria-controls="vacunas" aria-selected="false">Vacunas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="desparacitaciones-tab" data-bs-toggle="tab" href="#desparacitaciones" role="tab" aria-controls="desparacitaciones" aria-selected="false">Desparasitaciones</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="servicios-tab" data-bs-toggle="tab" href="#servicios" role="tab" aria-controls="servicios" aria-selected="false">Servicios</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="historialTabsContent">
                            <!-- Consultas -->
                            <div class="tab-pane fade show active" id="consultas" role="tabpanel" aria-labelledby="consultas-tab">
                                <table class="table table-hover table-striped table-bordered" style="font-family: 'Poppins', sans-serif;">
                                    <thead class="table-dark" style="background-color: #005C43;">
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha</th>
                                            <th>Motivo</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Añadimos 10 filas de ejemplo -->
                                        <tr>
                                            <td>1</td>
                                            <td>2024-08-01</td>
                                            <td>Consulta general</td>
                                            <td>Ninguna observación</td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>2024-09-03</td>
                                            <td>Examen físico</td>
                                            <td>Sin problemas</td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>2024-09-05</td>
                                            <td>Consulta dental</td>
                                            <td>Requiere limpieza dental</td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>2024-09-07</td>
                                            <td>Consulta general</td>
                                            <td>Ninguna observación</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Cirugías -->
                            <div class="tab-pane fade" id="cirugias" role="tabpanel" aria-labelledby="cirugias-tab">
                                <table class="table table-hover table-striped table-bordered" style="font-family: 'Poppins', sans-serif;">
                                    <thead class="table-dark" style="background-color: #005C43;">
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha</th>
                                            <th>Procedimiento</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- 10 filas de ejemplo -->
                                        <tr>
                                            <td>1</td>
                                            <td>2024-09-01</td>
                                            <td>Cirugía de rodilla</td>
                                            <td>Recuperación exitosa</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>2024-08-20</td>
                                            <td>Cirugía ocular</td>
                                            <td>Sin complicaciones</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>2024-08-05</td>
                                            <td>Cirugía dental</td>
                                            <td>Todo en orden</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>2024-07-25</td>
                                            <td>Cirugía de cadera</td>
                                            <td>Recuperación satisfactoria</td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>2024-07-15</td>
                                            <td>Cirugía de ligamentos</td>
                                            <td>Todo normal</td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>2024-06-30</td>
                                            <td>Cirugía de fractura</td>
                                            <td>Recomendaciones dadas</td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>2024-06-20</td>
                                            <td>Cirugía abdominal</td>
                                            <td>Sin complicaciones</td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>2024-05-25</td>
                                            <td>Cirugía de rodilla</td>
                                            <td>Recuperación normal</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Vacunas -->
                            <div class="tab-pane fade" id="vacunas" role="tabpanel" aria-labelledby="vacunas-tab">
                                <table class="table table-hover table-striped table-bordered" style="font-family: 'Poppins', sans-serif;">
                                    <thead class="table-dark" style="background-color: #005C43;">
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha</th>
                                            <th>Proxima vacuna</th>
                                            <th>Contra</th>
                                            <th>Nombre vacuna</th>
                                            <th>costo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- 10 filas de ejemplo -->
                                        <?php
                                        $c = 1;
                                        $contador_registros = 0;  // Iniciar un contador para los registros

                                        foreach ($vacunas as $key => $vacuna) {

                                            if ($vacuna->id_mascota == $id_mascota) {
                                                $mascotas_encontradas = true;

                                                // Solo mostrar los primeros 30 registros
                                                if ($contador_registros < 30) {
                                        ?>

                                                    <tr>
                                                        <td><?php echo $c++ ?></td>
                                                        <td><?php echo $vacuna->fecha_vacuna ?></td>
                                                        <td><?php echo $vacuna->proxima_vacuna ?></td>
                                                        <td><?php echo $vacuna->contra ?></td>
                                                        <td><?php echo $vacuna->nom_vac ?></td>
                                                        <td><?php echo $vacuna->costo. " Bs."; ?></td>
                                                    </tr>

                                        <?php
                                                    $contador_registros++;  // Incrementar el contador después de mostrar un registro
                                                } else {
                                                    break;  // Detener el bucle cuando se han mostrado 30 registros
                                                }
                                            }
                                        }
                                        ?>

                                        <?php
                                        if (!$mascotas_encontradas) {
                                            $mensaje = '<tr><th colspan="6"><center>No se encontraron vacunas </center></th></tr>';
                                            echo $mensaje;
                                        }
                                        ?>
                                    </tbody>

                                </table>
                            </div>

                            <!-- Desparasitaciones -->
                            <div class="tab-pane fade" id="desparacitaciones" role="tabpanel" aria-labelledby="desparacitaciones-tab">
                                <table class="table table-hover table-striped table-bordered" style="font-family: 'Poppins', sans-serif;">
                                    <thead class="table-dark" style="background-color: #005C43;">
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha</th>
                                            <th>Desparasitación</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- 10 filas de ejemplo -->
                                        <tr>
                                            <td>8</td>
                                            <td>2024-03-20</td>
                                            <td>Externa</td>
                                            <td>Recuperación completa</td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>2024-02-10</td>
                                            <td>Interna</td>
                                            <td>Todo normal</td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>2023-12-01</td>
                                            <td>Mixta</td>
                                            <td>Observación finalizada</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Servicios -->
                            <div class="tab-pane fade" id="servicios" role="tabpanel" aria-labelledby="servicios-tab">
                                <table class="table table-hover table-striped table-bordered" style="font-family: 'Poppins', sans-serif;">
                                    <thead class="table-dark" style="background-color: #005C43;">
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha</th>
                                            <th>Servicio</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- 10 filas de ejemplo -->
                                        <tr>
                                            <td>5</td>
                                            <td>2024-07-10</td>
                                            <td>Baño y peluquería</td>
                                            <td>Todo en orden</td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>2024-06-15</td>
                                            <td>Chequeo general</td>
                                            <td>Sin observaciones</td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>2024-05-20</td>
                                            <td>Consulta especial</td>
                                            <td>Recomendaciones dadas</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                /* Estilo para los tabs */
                .nav-link {
                    color: #005C43;
                    /* Color del texto de los tabs no seleccionados */
                }

                .nav-link.active {
                    color: white;
                    /* Color del texto del tab seleccionado */
                    background-color: #005C43;
                    /* Color de fondo del tab seleccionado */
                    border-radius: 15px 15px 0 0;
                    /* Borde redondeado en la parte superior */
                }

                .nav-link:hover {
                    color: #003c31;
                    /* Color del texto al pasar el mouse */
                    background-color: #e0f2f1;
                    /* Color de fondo al pasar el mouse */
                }
            </style>



        </div>

    </div>
</div>

<?php
require '../template/footer.php';
?>