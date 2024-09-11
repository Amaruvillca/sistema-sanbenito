<?php
$titulo = "usuarios";
require '../template/header.php';
verificaAcceso();

use App\Perfil;
use App\User;

$id_usuario = '';
$id_personal = '';
if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);

    parse_str($decrypted_data, $params);

    // Ahora tienes acceso a los parámetros
    $id_usuario = $params['id_usuario'];
    $id_personal = $params['id_personal'];
    if (!$id_personal || !$id_personal) {
        header('Location:/sistema-sanbenito/error/403.php?mensaje=3');
    }

    // Ahora puedes usar los parámetros para lo que necesites

    $datosUsuario = User::mostrarDatos($id_usuario);
    $datosPerfil = Perfil::mostrarDatos($id_personal);
}else{
    header('Location:/sistema-sanbenito/error/403.php?mensaje=3');
}
?>

<div class="dashboard-content">
    <div class="container-fluid">
        <div class="row">
            <!-- Sección de detalles del Usuario y Perfil -->
            <div class="col-lg-4 col-md-4" style="width: 100%;">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Detalles del Personal</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="/sistema-sanbenito/imagepersonal/<?php echo $datosPerfil['imagen_personal'] ?>" alt="imagen veterinario" class="rounded-circle" width="200px" height="200px">
                        </div>
                        <p><strong>Nombre: </strong><?php echo $datosPerfil['nombres']; ?></p>
                        <p><strong>Apellidos: </strong><?php echo $datosPerfil['apellido_paterno'] . " " . $datosPerfil['apellido_materno']; ?></p>
                        <p><strong>Rol: </strong><?php echo $datosUsuario['rol']; ?></p>
                        <p><strong>Correo: </strong><?php echo $datosUsuario['email']; ?></p>
                        <p><strong>Celular: </strong><?php echo $datosPerfil['num_celular']; ?></p>
                        <p><strong>Carnet: </strong><?php echo $datosPerfil['num_carnet']; ?></p>
                        <p><strong>Dirección: </strong><?php echo $datosPerfil['direccion']; ?></p>
                        <div class="d-flex justify-content-between">
                            <a href="/sistema-sanbenito/home/user/editar.php" class="btn btn-outline-success">Editar usuario</a>
                            <?php 
                        $id_personal = $datosPerfil['id_personal'];
                        $data1 = "id_personal=$id_personal";
                        // Encripta los parámetros
                        $encryptedData1 = encryptData($data1);
                        ?>
                            <a href="/sistema-sanbenito/home/perfil/editar.php?data=<?php echo $encryptedData1; ?>" class="btn btn-outline-success">Editar Perfil</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Historial de Atenciones -->
            <div class="col-lg-8 col-md-8">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Historial de Atenciones</h5>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="historialTabs" role="tablist">
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
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha</th>
                                            <th>Motivo</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>2024-08-01</td>
                                            <td>Consulta general</td>
                                            <td>Ninguna observación</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>2024-08-15</td>
                                            <td>Revisión de herida</td>
                                            <td>Herida en proceso de cicatrización</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Otros tabs como Cirugías, Vacunas, etc. manteniendo el mismo estilo -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require '../template/footer.php';
?>