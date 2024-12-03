<?php
$titulo = "usuarios";
$nombrepagina = "mas detalles";
require '../template/header.php';

error_reporting(0);

use App\Perfil;
use App\User;
use App\Vacunas;
use App\Desparacitaciones;

$id_usuario = '';
$id_personal = '';
if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);

    parse_str($decrypted_data, $params);

    // Ahora tienes acceso a los parámetros

    if (isset($params['id_personal']) && $params['id_personal'] && isset($params['id_usuario']) && $params['id_usuario']) {
        $id_usuario = $params['id_usuario'];
        $id_personal = $params['id_personal'];
    } else {
        // Si no hay 'id_propietario', ir a la página anterior
        echo "<script>window.history.back();</script>";
        exit;
    }

    // Ahora puedes usar los parámetros para lo que necesites

    $datosUsuario = User::find($id_usuario);

    //debuguear($datosUsuario);
    $datosPerfil = Perfil::find($id_personal);
    // debuguear($datosPerfil);
} else {
    echo "<script>window.history.back();</script>";
    exit;
}
$vacunas = Vacunas::all();
$desparasitaciones = Desparacitaciones::all();
$vacunas_encontradas = false;
$desparasityacion_encontradas = false;
?>

<div class="dashboard-content">
    <div class='container'>
        <div class="row">
            <div class="col-12">
                <div class='card shadow-lg p-4' style="border-radius: 10px; background-color: #ffffff;">
                    <div class='card-body'>
                        <!-- Distribuir el contenido en tres columnas centradas vertical y horizontalmente -->
                        <div class="row text-center justify-content-center align-items-center">
                            <!-- Columna de la imagen -->
                            <div class="col-md-4 col-12 mb-4">
                                <img src="/sistema-sanbenito/imagepersonal/<?php echo $datosPerfil->imagen_personal ?>" alt="Imagen de perfil" class="img-fluid rounded-circle shadow-sm" style="width: 180px; height: 180px; object-fit: cover; transition: transform 0.3s ease; border: 4px solid #005C43;">
                                <h5 class="mt-3" style="color: #005C43; font-weight: bold;"><?php echo $datosPerfil->nombres . ' ' . $datosPerfil->apellido_paterno . ' ' . $datosPerfil->apellido_materno ?></h5>
                                <h6 class="text-muted"><?php echo $datosPerfil->matricula_profesional ?></h6>
                            </div>

                            <!-- Columna de datos del usuario -->
                            <div class="col-md-4 col-12 mb-4 text-md-start">
                                <h5 class="mb-3" style="color: #005C43; font-weight: bold; letter-spacing: 1px;">Datos del Usuario</h5>
                                <p class="mb-2 text-secondary"><strong>Correo:</strong> <?php echo $datosUsuario->email ?></p>
                                <p class="mb-2 text-secondary"><strong>Cargo:</strong> <?php echo $datosUsuario->rol ?></p>
                                <p class="mb-2 text-secondary"><strong>Estado:</strong>
                                    <span class="<?php echo $datosUsuario->estado == '1' ? 'text-success' : 'text-danger'; ?>">
                                        <?php echo $datosUsuario->estado == "1" ? "Activo" : "Inactivo"; ?>
                                    </span>
                                </p>
                                <?php
                                $id_usuario = $datosUsuario->id_usuario;
                                $data2 = "id_usuario=$id_usuario";
                                // Encripta los parámetros
                                $encryptedData2 = encryptData($data2); ?>
                                <a href="/sistema-sanbenito/home/user/editar.php?data=<?php echo $encryptedData2; ?>" class="btn btn-outline-primary mt-3 px-4 py-2 shadow-sm" style="border-radius: 25px; transition: background-color 0.3s ease; background-color: #005C43; border-color: #005C43; color: white; font-weight: 600;">
                                    <i class="bi bi-pencil-square"></i> Editar Usuario
                                </a>
                            </div>

                            <!-- Columna de datos del personal -->
                            <div class="col-md-4 col-12 mb-4 text-md-start">
                                <h5 class="mb-3" style="color: #005C43; font-weight: bold; letter-spacing: 1px;">Datos del Personal</h5>
                                <p class="mb-2 text-secondary"><strong>Celular:</strong> <?php echo $datosPerfil->num_celular ?></p>
                                <p class="mb-2 text-secondary"><strong>Carnet:</strong> <?php echo $datosPerfil->num_carnet ?></p>
                                <p class="mb-2 text-secondary"><strong>Dirección:</strong> <?php echo $datosPerfil->direccion ?></p>
                                <?php
                                $id_personal = $datosPerfil->id_personal;
                                $data1 = "id_personal=$id_personal";
                                // Encripta los parámetros
                                $encryptedData1 = encryptData($data1); ?>
                                <a href="/sistema-sanbenito/home/perfil/editar.php?data=<?php echo $encryptedData1; ?>" class="btn btn-outline-primary mt-3 px-4 py-2 shadow-sm" style="border-radius: 25px; transition: background-color 0.3s ease; background-color: #005C43; border-color: #005C43; color: white; font-weight: 600;">
                                    <i class="bi bi-pencil-square"></i> Editar Perfil
                                </a>
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
                                            <th>Contra</th>
                                            <th>Nombre vacuna</th>
                                            <th>Fecha</th>
                                            <th>Próxima dosis</th>
                                            <th>Costo</th>
                                            <th>Mascota</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $c = 1;
                                        $contador_registros = 0;  // Iniciar un contador para los registros
                                        $vacunas_encontradas = false;  // Bandera para verificar si hay registros
                                        
                                            foreach ($vacunas as $key => $vacuna) {
                                                if ($vacuna->id_personal == $id_personal) {
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
                                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalVeterinario" onclick="mostrarDetallesMascota('<?php echo $vacuna->id_mascota; ?>')">
                                                                    <i class="bi bi-eye-fill"></i></i> Ver
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
                                            <th>Mascota</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- 10 filas de ejemplo -->
                                        <?php
                                        $c = 1;
                                        $contador_registros = 0;  // Iniciar un contador para los registros
                                        $desparasityacion_encontradas = false;  // Bandera para verificar si hay registros
                                        
                                            foreach ($desparasitaciones as $key => $desparasitacion) {
                                                if ($desparasitacion->id_personal == $id_personal) {
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
                                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalVeterinario" onclick="mostrarDetallesMascota('<?php echo $desparasitacion->id_mascota; ?>')">
                                                                    <i class="bi bi-eye-fill"></i></i> Ver
                                                                </button>
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



            <!-- Modal para Ver Veterinario -->
            <div class="modal fade" id="modalVeterinario" tabindex="-1" aria-labelledby="modalVeterinarioLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="nombreMascota"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12 col-md-5 text-center">
                                    <img id="imagenVeterinario" src="/sistema-sanbenito/imagemascota/mascota.png" alt="Imagen del Veterinario" class="img-fluid rounded-circle">
                                </div>
                                <div class="col-12 col-md-7">
                                    <h5 id="nombreVeterinario"></h5>
                                    <p><strong>Especie: </strong> <span id="especie"></span></p>
                                    <p><strong>Sexo: </strong> <span id="sexo"></span></p>
                                    <p><strong>Color: </strong> <span id="color"></span></p>
                                    <p><strong>Raza: </strong> <span id="raza"></span></p>
                                    <p><strong>Código: </strong> <span id="codigo"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function mostrarDetallesMascota(idmascota) {

                    fetch(`/sistema-sanbenito/home/mascotas/obtenerMascota.php?id=${idmascota}`)
                        .then(response => {

                            if (!response.ok) {
                                throw new Error('Error en la respuesta de la red');
                            }
                            return response.json();
                        })
                        .then(data => {

                            if (data) {

                                document.getElementById("imagenVeterinario").src = data.imagen_mascota ?
                                    `/sistema-sanbenito/imagemascota/${data.imagen_mascota}` :
                                    "/sistema-sanbenito/imagemascota/mascota.png";

                                document.getElementById("nombreMascota").textContent = data.nombre || "Nombre no disponible";
                                document.getElementById("especie").textContent = data.especie || "Mascota no encontrada";
                                document.getElementById("sexo").textContent = data.sexo || "Mascota no encontrada";
                                document.getElementById("color").textContent = data.color || "Mascota no encontrada";
                                document.getElementById("raza").textContent = data.raza || "Mascota no encontrada";
                                document.getElementById("codigo").textContent = data.codigo_mascota || "Mascota no encontrada";
                            } else {

                                document.getElementById("nombreMascota").textContent = "Mascota no encontrada";
                                document.getElementById("especie").textContent = "Mascota no encontrada";
                                document.getElementById("sexo").textContent = "Mascota no encontrada";
                                document.getElementById("color").textContent = "Mascota no encontrada";
                                document.getElementById("raza").textContent = "Mascota no encontrada";
                                document.getElementById("codigo").textContent = "Mascota no encontrada";
                            }
                        })
                        .catch(error => {
                            console.error('Error al cargar los detalles del veterinario:', error);

                            document.getElementById("nombreMascota").textContent = "Error al cargar datos";
                            document.getElementById("especie").textContent = "Error al cargar datos";
                            document.getElementById("sexo").textContent = "Error al cargar datos";
                            document.getElementById("color").textContent = "Error al cargar datos";
                            document.getElementById("raza").textContent = "Error al cargar datos";
                            document.getElementById("codigo").textContent = "Error al cargar datos";
                        });
                }
            </script>

        </div>

    </div>
</div>

<?php
require '../template/footer.php';
?>