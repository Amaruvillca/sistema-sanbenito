<?php
$titulo = "Propietarios";
$nombrepagina = "mas detalles";
require '../template/header.php';

//error_reporting(0);

use App\Desparacitaciones;
use App\Mascotas;
use App\Propietarios;
use App\Servicios;
use App\Vacunas;
use App\Atencionservicio;
use App\Consulta;
use App\CirugiaProgramada;
use App\CirugiaRealizada;
use App\Ciruguas;




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
$desparasitaciones = Desparacitaciones::all();
$atencion_servicios = Atencionservicio::all();
$atencionConsulta = Consulta::all();
$cirugiaprogramadas = CirugiaProgramada::all();


$atencionConsultaencontrados = false;
$atencion_servicio_encontradas= false;
$vacunas_encontradas = false;
$desparasityacion_encontradas = false;
$cirugia_encontradas = false;
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
                                    <a href="/sistema-sanbenito/home/desparasitaciones/crear.php?data=<?php echo $encryptedData ?>" class="btn btn-outline-warning px-4 py-2 shadow-sm" style="border-radius: 25px; transition: background-color 0.3s ease; background-color: #ffc107; border-color: #ffc107; color: white; font-weight: 600;">
                                        <i class="bi bi-bug"></i> Desparasitaciones
                                    </a>

                                    <!-- Botón de Cirugías -->
                                    <a href="/sistema-sanbenito/home/cirugia/programar.php?data=<?php echo $encryptedData ?>" class="btn btn-outline-danger px-4 py-2 shadow-sm" style="border-radius: 25px; transition: background-color 0.3s ease; background-color: #dc3545; border-color: #dc3545; color: white; font-weight: 600;">
                                        <i class="bi bi-heart-pulse"></i> Programar cirugia
                                    </a>

                                    <!-- Botón de Servicios -->
                                    <a href="/sistema-sanbenito/home/atencion_servicio/crear.php?data=<?php echo $encryptedData ?>" class="btn btn-outline-primary px-4 py-2 shadow-sm" style="border-radius: 25px; transition: background-color 0.3s ease; background-color: #005C43; border-color: #005C43; color: white; font-weight: 600;">
                                        <i class="bi bi-gear"></i> Otros Servicios
                                    </a>

                                    <!-- Botón de Consultas -->
                                    <a href="/sistema-sanbenito/home/consulta/crear.php?data=<?php echo $encryptedData ?>" class="btn btn-outline-info px-4 py-2 shadow-sm" style="border-radius: 25px; transition: background-color 0.3s ease; background-color: #17a2b8; border-color: #17a2b8; color: white; font-weight: 600;">
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
                                            <th>Diagnostico</th>
                                            <th>Costo</th>
                                            <th>Acciones</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $c = 1;
                                        $contador_registros = 0;  // Iniciar un contador para los registros
                                        $vacunas_encontradas = false;  // Bandera para verificar si hay registros

                                        foreach ($atencionConsulta as $key => $consulta) {
                                            if ($consulta->id_mascota == $id_mascota) {
                                                $vacunas_encontradas = true;

                                                if ($contador_registros < 30) {
                                        ?>
                                                    <tr>
                                                        <td><?php echo $c++ ?></td>
                                                        <td><?php echo $consulta->fecha_consulta ?></td>
                                                        <td><?php echo $consulta->motivo_consulta ?></td>
                                                        <td><?php echo $consulta->Diagnostico_presuntivo ?></td>
                                                        
                                                        <td><?php echo $consulta->costo . " Bs."; ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalVeterinario" onclick="mostrarDetallesVeterinario('<?php echo $consulta->id_personal; ?>')">
                                                            <i class="bi bi-eye-fill"></i> Ver
                                                            </button>
                                                            <?php
                                                            $id_consulta = $consulta->id_consulta;
                                                            $data = "id_consulta=$id_consulta";
                                                            $encryptedData = encryptData($data);
                                                            ?>
                                                            <a href="/sistema-sanbenito/home/report/consulta.php?data=<?php echo $encryptedData; ?>" class="btn btn-sm btn-danger" target="_blank">
                                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                        <?php
                                                    $contador_registros++;
                                                } else {
                                                    break;
                                                }
                                            }
                                        }

                                        if (!$vacunas_encontradas) {
                                            $mensaje = '<tr><th colspan="7"><center>No se encontraron consultas</center></th></tr>';
                                            echo $mensaje;
                                        }
                                        ?>
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
                                            <th>Cirugia</th>
                                            <th>Veteinario</th>

                                            
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $c = 1;
                                        $contador_registros = 0;  // Iniciar un contador para los registros
                                        $vacunas_encontradas = false;  // Bandera para verificar si hay registros

                                        foreach ($cirugiaprogramadas as $key => $cirugiapro) {
                                            if ($cirugiapro->id_mascota == $id_mascota && $cirugiapro->estado === "concluida") {
                                                $vacunas_encontradas = true;
$cirugia = Ciruguas::find($cirugiapro->id_cirugia);
                                                if ($contador_registros < 30) {
                                        ?>
                                                    <tr>
                                                        <td><?php echo $c++ ?></td>
                                                        <td><?php echo $cirugiapro->fecha_programada ?></td>
                                                        <td><?php echo $cirugia->nombre_cirugia ?></td>
                                                        
                                                        <td>
                                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalVeterinario" onclick="mostrarDetallesVeterinario('<?php echo $cirugiapro->id_personal; ?>')">
                                                            <i class="bi bi-eye-fill"></i> Ver
                                                            </button>
                                                            
                                                           
                                                        </td>
                                                    </tr>
                                        <?php
                                                    $contador_registros++;
                                                } else {
                                                    break;
                                                }
                                            }
                                        }

                                        if (!$vacunas_encontradas) {
                                            $mensaje = '<tr><th colspan="4"><center>No se encontraron cirugias</center></th></tr>';
                                            echo $mensaje;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Vacunas -->
                            <div class="tab-pane fade" id="vacunas" role="tabpanel" aria-labelledby="vacunas-tab">
                                <table class="table table-hover table-striped table-bordered" style="font-family: 'Poppins', sans-serif;">
                                    <thead class="table-dark" style="background-color: #005C43;">
                                        <tr>
                                            <th>#</th>
                                            <th>Contra</th>
                                            <th>Nombre vacuna</th>
                                            <th>Fecha</th>
                                            <th>Próxima dosis</th>
                                            <th>Costo</th>
                                            <th>Veterinario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $c = 1;
                                        $contador_registros = 0;  // Iniciar un contador para los registros
                                        $vacunas_encontradas = false;  // Bandera para verificar si hay registros

                                        foreach ($vacunas as $key => $vacuna) {
                                            if ($vacuna->id_mascota == $id_mascota) {
                                                $vacunas_encontradas = true;

                                                if ($contador_registros < 30) {
                                        ?>
                                                    <tr>
                                                        <td><?php echo $c++ ?></td>
                                                        <td><?php echo $vacuna->contra ?></td>
                                                        <td><?php echo $vacuna->nom_vac ?></td>
                                                        <td><?php echo $vacuna->fecha_vacuna ?></td>
                                                        <td><?php echo $vacuna->proxima_vacuna ?></td>
                                                        <td><?php echo $vacuna->costo . " Bs."; ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalVeterinario" onclick="mostrarDetallesVeterinario('<?php echo $vacuna->id_personal; ?>')">
                                                            <i class="bi bi-eye-fill"></i> Ver
                                                            </button>
                                                            <?php
                                                            $id_vacuna = $vacuna->id_vacuna;
                                                            $data = "id_vacuna=$id_vacuna";
                                                            $encryptedData = encryptData($data);
                                                            ?>
                                                            <a href="/sistema-sanbenito/home/report/vacuna.php?data=<?php echo $encryptedData; ?>" class="btn btn-sm btn-danger" target="_blank">
                                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                        <?php
                                                    $contador_registros++;
                                                } else {
                                                    break;
                                                }
                                            }
                                        }

                                        if (!$vacunas_encontradas) {
                                            $mensaje = '<tr><th colspan="7"><center>No se encontraron vacunas</center></th></tr>';
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
                                            <th>Producto</th>
                                            <th>Tipo</th>
                                            <th>Principio Activo</th>
                                            <th>Vía</th>
                                            <th>Fecha Aplicación</th>
                                            <th>Próxima Dosis</th>
                                            <th>Costo</th>
                                            <th>Veterinario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- 10 filas de ejemplo -->
                                        <?php
                                        $c = 1;
                                        $contador_registros = 0;  // Iniciar un contador para los registros
                                        

                                        foreach ($desparasitaciones as $key => $desparasitacion) {
                                            if ($desparasitacion->id_mascota == $id_mascota) {
                                                $desparasityacion_encontradas = true;

                                                // Solo mostrar los primeros 30 registros
                                                if ($contador_registros < 30) {
                                        ?>
                                                    <tr>
                                                        <td><?php echo $c++ ?></td>
                                                        <td><?php echo $desparasitacion->producto ?></td>
                                                        <td><?php echo $desparasitacion->tipo_desparasitacion ?></td>
                                                        <td><?php echo $desparasitacion->principio_activo ?></td>
                                                        <td><?php echo $desparasitacion->via ?></td>
                                                        <td><?php echo $desparasitacion->fecha_aplicacion ?></td>
                                                        <td><?php echo $desparasitacion->proxima_desparasitacion ?></td>
                                                        <td><?php echo $desparasitacion->costo . " Bs."; ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalVeterinario" onclick="mostrarDetallesVeterinario('<?php echo $desparasitacion->id_personal; ?>')">
                                                            <i class="bi bi-eye-fill"></i> Ver
                                                            </button>
                                                            <?php
                                                            $id_desparacitacion = $desparasitacion->id_desparasitacion;
                                                            $data = "id_desparasitacion=$id_desparacitacion";
                                                            $encryptedData = encryptData($data);
                                                            ?>
                                                          <a href="/sistema-sanbenito/home/report/desparasitacion.php?data=<?php echo $encryptedData; ?>" class="btn btn-sm btn-danger" target="_blank">
                                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                        <?php
                                                    $contador_registros++;  // Incrementar el contador después de mostrar un registro
                                                } else {
                                                    break;  // Detener el bucle cuando se han mostrado 30 registros
                                                }
                                            }
                                        }

                                        if (!$desparasityacion_encontradas) {
                                            $mensaje = '<tr><th colspan="9"><center>No se encontraron desparasitaciones</center></th></tr>';
                                            echo $mensaje;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


                            <!-- Servicios -->
                            <div class="tab-pane fade" id="servicios" role="tabpanel" aria-labelledby="servicios-tab">
                                <table class="table table-hover table-striped table-bordered" style="font-family: 'Poppins', sans-serif;">
                                    <thead class="table-dark" style="background-color: #005C43;">
                                    <tr>
                                            <th>#</th>
                                            <th>Servico</th>
                                            <th>Observaciones</th>
                                            <th>Fecha de servicio</th>
                                            <th>Costo</th>
                                            <th>Veterinario</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- 10 filas de ejemplo -->
                                        <?php
                                        $c = 1;
                                        $contador_registros = 0;  
                                        

                                        foreach ($atencion_servicios as $key => $atencion_servicio) {
                                            if ($atencion_servicio->id_mascota == $id_mascota) {
                                                $atencion_servicio_encontradas = true;

                                                // Solo mostrar los primeros 30 registros
                                                if ($contador_registros < 30) {
                                                    $servicioo= Servicios::find($atencion_servicio->id_servicio);
                                        ?>
                                                    <tr>
                                                        <td><?php echo $c++ ?></td>
                                                        <td><?php echo $servicioo->nombre_servicio ?></td>
                                                        <td><?php echo $atencion_servicio->observaciones ?></td>
                                                        <td><?php echo $atencion_servicio->fecha_servicio ?></td>
                                                        <td><?php echo $atencion_servicio->costo . " Bs."; ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalVeterinario" onclick="mostrarDetallesVeterinario('<?php echo $atencion_servicio->id_personal; ?>')">
                                                            <i class="bi bi-eye-fill"></i> Ver
                                                            </button>
                                                            <?php
                                                            $id_atencion_servicio = $atencion_servicio->id_atencion_servicio;
                                                            $data = "id_atencion_servicion=$id_atencion_servicio";
                                                            $encryptedData = encryptData($data);
                                                            ?>
                                                          <a href="/sistema-sanbenito/home/report/atencion_servicio.php?data=<?php echo $encryptedData; ?>" class="btn btn-sm btn-danger" target="_blank">
                                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                                            </a>
                                                        </td>
                                                       
                                                    </tr>
                                        <?php
                                                    $contador_registros++;  // Incrementar el contador después de mostrar un registro
                                                } else {
                                                    break;  // Detener el bucle cuando se han mostrado 30 registros
                                                }
                                            }
                                        }

                                        if (!$atencion_servicio_encontradas) {
                                            $mensaje = '<tr><th colspan="6"><center>No se encontraron servicios</center></th></tr>';
                                            echo $mensaje;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal para Ver Veterinario -->
            <div class="modal fade" id="modalVeterinario" tabindex="-1" aria-labelledby="modalVeterinarioLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="nombreVeterinario"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12 col-md-5 text-center">
                                    <img id="imagenVeterinario" src="/sistema-sanbenito/imagepersonal/veterinario.jpg" alt="Imagen del Veterinario" class="img-fluid rounded-circle">
                                </div>
                                <div class="col-12 col-md-7">

                                    <p><strong>Celular: </strong> <span id="celularVeterinario"></span></p>
                                    <p><strong>Carnet: </strong> <span id="carnetVeterinario"></span></p>
                                    <p><strong>Matrícula: </strong> <span id="matricula_profesional"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function mostrarDetallesVeterinario(idVeterinario) {
                    // Realiza una solicitud Fetch para obtener los detalles del veterinario
                    fetch(`/sistema-sanbenito/home/perfil/obtenerVeterinario.php?id=${idVeterinario}`)
                        .then(response => {
                            // Verifica si la respuesta es ok (status 200)
                            if (!response.ok) {
                                throw new Error('Error en la respuesta de la red');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data) {
                                // Corrige la asignación de la src de la imagen
                                document.getElementById("imagenVeterinario").src = data.imagen_personal ?
                                    `/sistema-sanbenito/imagepersonal/${data.imagen_personal}` :
                                    "/sistema-sanbenito/imagepersonal/veterinario.jpg";

                                document.getElementById("nombreVeterinario").innerText = `${data.nombres} ${data.apellido_paterno} ${data.apellido_materno}`;
                                document.getElementById("celularVeterinario").innerText = data.num_celular;
                                document.getElementById("carnetVeterinario").innerText = data.num_carnet;
                                document.getElementById("matricula_profesional").innerText = data.matricula_profesional;
                            } else {
                                console.error('No se encontraron detalles del veterinario');
                            }
                        })
                        .catch(error => console.error('Error al cargar los detalles del veterinario:', error));
                }
            </script>
        </div>

    </div>
</div>

<?php
require '../template/footer.php';
?>