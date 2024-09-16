<?php
$titulo = "usuarios";
$nombrepagina = "mas detalles";
require '../template/header.php';

error_reporting(0);

use App\Perfil;
use App\User;

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
                                            <th>Fecha</th>
                                            <th>Vacuna</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- 10 filas de ejemplo -->
                                        <tr>
                                            <td>7</td>
                                            <td>2024-03-25</td>
                                            <td>Parvovirus</td>
                                            <td>Sin complicaciones</td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>2024-02-28</td>
                                            <td>Distemper</td>
                                            <td>Todo en orden</td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>2024-01-15</td>
                                            <td>Hepatitis</td>
                                            <td>Sin problemas</td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>2023-12-10</td>
                                            <td>Leptospirosis</td>
                                            <td>Vacunación exitosa</td>
                                        </tr>
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