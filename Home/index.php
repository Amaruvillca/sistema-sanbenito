<?php

$titulo = "Dashboard";
$nombrepagina = "Dashboart";
require 'template/header.php';

use App\User;
use App\Desparacitaciones;
use App\Vacunas;
use App\Mascotas;
use App\tratamiento;
use App\CirugiaRealizada;
use App\Consulta;
use App\Perfil;


use App\Propietarios;
use App\Servicios;
use App\Ciruguas;
use App\Atencionservicio;
use App\CirugiaProgramada;
use App\Cuenta;

$usuario = User::contarDatos();
$mascotas = Mascotas::contarDatos();
$propietario = Propietarios::contarDatos();
$servicios = Servicios::contarDatos();
$cirugias = Ciruguas::contarDatos();

// tosas las vacunas y desparacitaciones
$vacunahoy = Vacunas::all();
$desparasitacionhoy = Desparacitaciones::all();
$servicioshoy = Atencionservicio::all();
$cirugiashoy = CirugiaRealizada::all();
//$tratamientohoy = tratamiento::all();
$consultashoy = Consulta::all();
$cuenta = new Cuenta();

?>


<div class='dashboard-content'>
    <div class='container'>
        <div class="row">
            <?php if ($_SESSION['rol'] == "Administrador"): ?>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="unique-card unique-card-usuarios">
                        <div class="card-body">
                            <h5>Usuarios</h5>
                            <p><?php echo $usuario ?></p>
                            <a href="/sistema-sanbenito/home/usuarios.php">Ver más</a>

                            <i class="bi bi-person-vcard-fill icono-fondo"></i>
                        </div>
                    </div>
                </div>

            <?php endif; ?>


            <div class="col-md-4 col-sm-6 mb-4">
                <div class="unique-card unique-card-propietarios">
                    <div class="card-body">
                        <h5>Propietarios</h5>
                        <p><?php echo $propietario ?></p>
                        <a href="/sistema-sanbenito/home/propietarios.php">Ver más</a>

                        <i class="bi bi-person-fill icono-fondo"></i>
                    </div>
                </div>
            </div>


            <div class="col-md-4 col-sm-6 mb-4">
                <div class="unique-card unique-card-servicios">
                    <div class="card-body">
                        <h5>Mascotas</h5>
                        <p><?php echo $mascotas ?></p>
                        <a href="/sistema-sanbenito/home/servicios.php">Ver más</a>

                        <i class="fas fa-paw icono-fondo"></i>
                    </div>
                </div>
            </div>
            <?php if ($_SESSION['rol'] == "Veterinario"): ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="unique-card unique-card-servicios2">
                        <div class="card-body">
                            <h5>Atendidos</h5>
                            <p><?php echo $mascotas ?></p>
                            <a href="/sistema-sanbenito/home/servicios.php">Ver más</a>

                            <i class="fas fa-paw icono-fondo"></i>
                        </div>
                    </div>
                </div>
            <?php endif; ?>





            <?php if ($_SESSION['rol'] == "Administrador"): ?>
                <div class="col-md-6 col-sm-6 mb-4">
                    <div class="unique-card unique-card-servicios2">
                        <div class="card-body">
                            <h5> <?php

                                    $fecha_completa = strftime('%A, %d de %B de %Y');

                                    echo ucfirst($fecha_completa);
                                    ?>
                            </h5>
                            <p><?php echo $cuenta->ingresoDia() . " Bs."; ?></p>
                            <a href="/sistema-sanbenito/home/perfiles.php">Ver más</a>
                            <a href="/sistema-sanbenito/home/report/ingresoDia.php" target="_blank" style="background-color: red" type="button" class="btn btn-danger"> <i class="bi bi-file-pdf-fill"></i> Imprimir</a>

                            <i class="bi bi-cash-coin icono-fondo"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 mb-4">
                    <div class="unique-card unique-card-perfiles">
                        <div class="card-body">
                            <h5><?php
                                $mes_y_anio = strftime('%B de %Y');
                                echo ucfirst($mes_y_anio);
                                ?>
                            </h5>

                            <p><?php echo $cuenta->ingresoMes() . " Bs."; ?></p>

                            <a href="/sistema-sanbenito/home/perfiles.php">Ver más</a>
                            <a href="/sistema-sanbenito/home/report/ingresosMes.php" target="_blank" style="background-color: red" type="button" class="btn btn-danger"> <i class="bi bi-file-pdf-fill"></i> Imprimir</a>

                            <i class="bi bi-wallet-fill icono-fondo"></i>

                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>



    </div>
    <!-- hoy -->

    <div class="container mt-5">
        <div class="row">
            <?php if ($_SESSION['rol'] == "Administrador") { ?>

                <div class="col-md-6 mb-4">
                    <div class="card card-vacunas-hoy shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icono-container me-3">
                                <i class="fas fa-syringe fa-3x text-primary"></i>
                            </div>
                            <div>
                                <h5 class="card-title">Vacunas Hoy</h5>
                                


                                <button class="btn btn-info " id="toggleVacunas">
                                    <i class="fas fa-chevron-down"></i> Mostrar detalles
                                </button>
                                <a href="/sistema-sanbenito/home/report/hoy/vacunas.php" target="_blank" type="button" class="btn btn-danger"> <i class="bi bi-file-pdf-fill"></i> Imprimir</a>
                            </div>
                        </div>

                        <div id="detailsVacunas" style="display: none;">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Contra</th>
                                            <th>Mascota</th>
                                            <th>Veterinario</th>
                                            <th>Propietario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $vacunaencontrada = false;
                                        foreach ($vacunahoy as $vacuna):
                                            $mascota = Mascotas::find($vacuna->id_mascota);
                                            $perfil = Perfil::find($vacuna->id_personal);
                                            $propietarios = Propietarios::find($mascota->id_propietario);
                                            if ($vacuna->fecha_vacuna == date('Y-m-d')) {
                                                $vacunaencontrada = true;
                                        ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($vacuna->contra); ?></td>
                                                    <td><?php echo htmlspecialchars($mascota->nombre); ?></td>
                                                    <td><?php echo htmlspecialchars($perfil->nombres . ' ' . $perfil->apellido_paterno . ' ' . $perfil->apellido_materno); ?></td>
                                                    <td><?php echo htmlspecialchars($propietarios->nombres . ' ' . $propietarios->apellido_paterno . ' ' . $propietarios->apellido_materno); ?></td>
                                                </tr>
                                            <?php }
                                        endforeach;
                                        if (!$vacunaencontrada): ?>
                                            <tr>
                                                <td colspan="4" class="text-center">No se encontraron vacunas</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="col-md-6 mb-4">
                    <div class="card card-desparasitaciones-hoy shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icono-container me-3">
                                <i class="fas fa-tablets fa-3x text-success"></i>
                            </div>
                            <div>
                                <h5 class="card-title">Desparasitaciones Hoy</h5>
                                


                                <button class="btn btn-info " id="toggleDesparasitaciones">
                                    <i class="fas fa-chevron-down"></i> Mostrar detalles
                                </button>
                                <a href="/sistema-sanbenito/home/report/hoy/desparasitaciones.php" target="_blank" type="button" class="btn btn-danger"> <i class="bi bi-file-pdf-fill"></i> Imprimir</a>
                            </div>
                        </div>

                        <div id="detailsDesparasitaciones" style="display: none;">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Mascota</th>
                                            <th>Propietario</th>
                                            <th>Atendido por</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $desparasitacionsEncontradas = false;
                                        foreach ($desparasitacionhoy as $desparasitacion):
                                            $mascota = Mascotas::find($desparasitacion->id_mascota);
                                            $perfil = Perfil::find($desparasitacion->id_personal);
                                            $propietario = Propietarios::find($mascota->id_propietario);
                                            if ($desparasitacion->fecha_aplicacion == date('Y-m-d')) {

                                                $desparasitacionsEncontradas = true;
                                        ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($desparasitacion->producto); ?></td>
                                                    <td><?php echo htmlspecialchars($mascota->nombre); ?></td>
                                                    <td>
                                                        <?php
                                                        echo htmlspecialchars($propietario->nombres) . ' ' .
                                                            htmlspecialchars($propietario->apellido_paterno) . ' ' .
                                                            htmlspecialchars($propietario->apellido_materno);
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo htmlspecialchars($perfil->nombres) . ' ' .
                                                            htmlspecialchars($perfil->apellido_paterno) . ' ' .
                                                            htmlspecialchars($perfil->apellido_materno);
                                                        ?>
                                                    </td>

                                                </tr>
                                            <?php }
                                        endforeach;
                                        if (!$desparasitacionsEncontradas): ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No se encontraron desparasitaciones</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card card-servicios-hoy shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icono-container me-3">
                                <i class="bi bi-bookmark-fill fa-3x text-warning"></i>
                            </div>
                            <div>
                                <h5 class="card-title"> Servicios Hoy</h5>
                                



                                <button class="btn btn-info " id="toggleServicio">
                                    <i class="fas fa-chevron-down"></i> Mostrar detalles
                                </button>
                                <a href="/sistema-sanbenito/home/report/hoy/servicios.php" target="_blank" type="button" class="btn btn-danger"> <i class="bi bi-file-pdf-fill"></i> Imprimir</a>
                            </div>
                        </div>

                        <div id="detailsServicio" style="display: none;">
                           
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Servicio</th>
                                            <th>Mascota</th>
                                            <th>Propietario</th>
                                            <th>Atendido por</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $serviciosEncontrados = false;

                                        foreach ($servicioshoy as $servicio):
                                            $mascota = Mascotas::find($servicio->id_mascota);
                                            $tiposervicio = Servicios::find($servicio->id_servicio);
                                            $perfil = Perfil::find($servicio->id_personal);
                                            $propietario = Propietarios::find($mascota->id_propietario);
                                            if ($servicio->fecha_servicio == date('Y-m-d')) {

                                                $serviciosEncontrados = true;
                                        ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($tiposervicio->nombre_servicio); ?></td>
                                                    <td><?php echo htmlspecialchars($mascota->nombre); ?></td>
                                                    <td>
                                                        <?php
                                                        echo htmlspecialchars($propietario->nombres) . ' ' .
                                                            htmlspecialchars($propietario->apellido_paterno) . ' ' .
                                                            htmlspecialchars($propietario->apellido_materno);
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo htmlspecialchars($perfil->nombres) . ' ' .
                                                            htmlspecialchars($perfil->apellido_paterno) . ' ' .
                                                            htmlspecialchars($perfil->apellido_materno);
                                                        ?>
                                                    </td>

                                                </tr>
                                            <?php }
                                        endforeach;
                                        if (!$serviciosEncontrados): ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No se encontraron servicios</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card card-cirugias-hoy shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icono-container me-3">

                                <i class="bi bi-activity fa-3x text-danger"></i>
                            </div>
                            <div>
                                <h5 class="card-title"> Cirugias Hoy</h5>
                                



                                <button class="btn btn-info " id="toggleCirugias">
                                    <i class="fas fa-chevron-down"></i> Mostrar detalles
                                </button>
                                <a href="#" type="button" class="btn btn-danger"> <i class="bi bi-file-pdf-fill"></i> Imprimir</a>
                            </div>
                        </div>

                        <div id="detailsCirugias" style="display: none;">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Cirugia</th>
                                            <th>Mascota</th>
                                            <th>Propietario</th>
                                            <th>Atendido por</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cirugiasEncontradas = false;

                                        foreach ($cirugiashoy as $cirugia):

                                            if ($cirugia->fecha_cirugia == date('Y-m-d')) {
                                                $cirugiasEncontradas = true;
                                                $cirugiapro = CirugiaProgramada::find($cirugia->id_cirugia_programada);
                                                $tipocirugia = Ciruguas::find($cirugiapro->id_cirugia);
                                                $mascota = Mascotas::find($cirugiapro->id_mascota);
                                                $perfil = Perfil::find($cirugia->id_personal);

                                                $propietario = Propietarios::find($mascota->id_propietario);
                                        ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($tipocirugia->nombre_cirugia); ?></td>
                                                    <td><?php echo htmlspecialchars($mascota->nombre); ?></td>
                                                    <td>
                                                        <?php
                                                        echo htmlspecialchars($propietario->nombres) . ' ' .
                                                            htmlspecialchars($propietario->apellido_paterno) . ' ' .
                                                            htmlspecialchars($propietario->apellido_materno);
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo htmlspecialchars($perfil->nombres) . ' ' .
                                                            htmlspecialchars($perfil->apellido_paterno) . ' ' .
                                                            htmlspecialchars($perfil->apellido_materno);
                                                        ?>
                                                    </td>

                                                </tr>
                                            <?php }
                                        endforeach;

                                        if (!$cirugiasEncontradas): ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No se encontraron cirugías</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card card-consulta-hoy shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icono-container me-3">


                                <i class="bi bi-clipboard2-fill fa-3x text-dark"></i>
                            </div>
                            <div>
                                <h5 class="card-title"> Consultas Hoy</h5>
                                



                                <button class="btn btn-info " id="toggleConsultas">
                                    <i class="fas fa-chevron-down"></i> Mostrar detalles
                                </button>
                                <a href="#" type="button" class="btn btn-danger"> <i class="bi bi-file-pdf-fill"></i> Imprimir</a>
                            </div>
                        </div>

                        <div id="detailsConsultas" style="display: none;">
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Motivo</th>
                                            <th>Mascota</th>
                                            <th>Propietario</th>
                                            <th>Atendido por</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cirugiasEncontradas = false;

                                        foreach ($consultashoy as $consulta):

                                            if ($consulta->fecha_consulta == date('Y-m-d')) {
                                                $cirugiasEncontradas = true;
                                                $mascota = Mascotas::find($consulta->id_mascota);
                                                $perfil = Perfil::find($consulta->id_personal);
                                                $propietario = Propietarios::find($mascota->id_propietario);
                                        ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($consulta->motivo_consulta); ?></td>
                                                    <td><?php echo htmlspecialchars($mascota->nombre); ?></td>
                                                    <td>
                                                        <?php
                                                        echo htmlspecialchars($propietario->nombres) . ' ' .
                                                            htmlspecialchars($propietario->apellido_paterno) . ' ' .
                                                            htmlspecialchars($propietario->apellido_materno);
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo htmlspecialchars($perfil->nombres) . ' ' .
                                                            htmlspecialchars($perfil->apellido_paterno) . ' ' .
                                                            htmlspecialchars($perfil->apellido_materno);
                                                        ?>
                                                    </td>

                                                </tr>
                                            <?php }
                                        endforeach;

                                        if (!$cirugiasEncontradas): ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No se encontraron consultas</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card card-tratamiento-hoy shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icono-container me-3">

                                <i class="bi bi-clipboard2-pulse-fill fa-3x text-secondary"></i>
                            </div>
                            <div>
                                <h5 class="card-title"> tratamientos Hoy</h5>
                                



                                <button class="btn btn-info " id="toggleTratamientos">
                                    <i class="fas fa-chevron-down"></i> Mostrar detalles
                                </button>
                                <a href="#" type="button" class="btn btn-danger"> <i class="bi bi-file-pdf-fill"></i> Imprimir</a>
                            </div>
                        </div>

                        <div id="detailsTratamientos" style="display: none;">
                            <h6>Detalles de Servicios</h6>

                            <ul class="list-group">
                                <?php foreach ($desparasitacionhoy as $desparasitacion): ?>
                                    <li class="list-group-item"><?php echo $desparasitacion->producto; ?> - <?php echo $desparasitacion->fecha_aplicacion; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-md-6 d-flex flex-column align-items-end">

                    <div class="card card-vacunas-hoy " style="height: 100vh; overflow-y: auto;">
                        <div class="card-header bg-info text-white d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0">Pendientes para hoy</h5>
                            <a href="#" class="btn btn-danger btn-sm">
                                <i class="bi bi-file-pdf-fill"></i> Imprimir
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icono-container me-3">
                                    <i class="bi bi-clipboard2-pulse-fill fa-3x text-secondary"></i>
                                </div>
                                <div>
                                    <p class="card-text">
                                        Total de tratamientos hoy:
                                        <strong><?php echo count($desparasitacionhoy); ?></strong>
                                    </p>

                                </div>
                            </div>
                            <div id="detailsTratamientos" class="shadow-sm w-100">
                                <h6 class="mb-3">Detalles de Servicios</h6>
                                <ul class="list-group">
                                    <?php foreach ($desparasitacionhoy as $desparasitacion): ?>
                                        <li class="list-group-item">
                                            <strong><?php echo $desparasitacion->producto; ?></strong> -
                                            <?php echo $desparasitacion->fecha_aplicacion; ?>

                                        </li>

                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-md-6 d-flex flex-column align-items-end">

                    <div class="card card-vacunas-hoy " style="height: 100vh; overflow-y: auto;">
                        <div class="card-header bg-info text-white d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0">Atendidos hoy</h5>
                            <a href="#" class="btn btn-danger btn-sm">
                                <i class="bi bi-file-pdf-fill"></i> Imprimir
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icono-container me-3">
                                    <i class="bi bi-clipboard2-pulse-fill fa-3x text-secondary"></i>
                                </div>
                                <div>
                                    <p class="card-text">
                                        Total de tratamientos hoy:
                                        <strong><?php echo count($desparasitacionhoy); ?></strong>
                                    </p>

                                </div>
                            </div>
                            <div id="detailsTratamientos" class="shadow-sm w-100">
                                <h6 class="mb-3">Detalles de Servicios</h6>
                                <ul class="list-group">
                                    <?php foreach ($desparasitacionhoy as $desparasitacion): ?>
                                        <li class="list-group-item">
                                            <strong><?php echo $desparasitacion->producto; ?></strong> -
                                            <?php echo $desparasitacion->fecha_aplicacion; ?>

                                        </li>

                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>


    <script>
        document.getElementById('toggleVacunas').addEventListener('click', function() {
            const details = document.getElementById('detailsVacunas');
            const isVisible = details.style.display === 'block';


            details.style.display = isVisible ? 'none' : 'block';

            this.innerHTML = isVisible ? '<i class="fas fa-chevron-down"></i> Mostrar detalles' : '<i class="fas fa-chevron-up"></i> Ocultar detalles';
        });


        document.getElementById('toggleDesparasitaciones').addEventListener('click', function() {
            const details = document.getElementById('detailsDesparasitaciones');
            const isVisible = details.style.display === 'block';


            details.style.display = isVisible ? 'none' : 'block';

            this.innerHTML = isVisible ? '<i class="fas fa-chevron-down"></i> Mostrar detalles' : '<i class="fas fa-chevron-up"></i> Ocultar detalles';
        });

        document.getElementById('toggleServicio').addEventListener('click', function() {
            const details = document.getElementById('detailsServicio');
            const isVisible = details.style.display === 'block';


            details.style.display = isVisible ? 'none' : 'block';

            this.innerHTML = isVisible ? '<i class="fas fa-chevron-down"></i> Mostrar detalles' : '<i class="fas fa-chevron-up"></i> Ocultar detalles';
        });

        document.getElementById('toggleCirugias').addEventListener('click', function() {
            const details = document.getElementById('detailsCirugias');
            const isVisible = details.style.display === 'block';


            details.style.display = isVisible ? 'none' : 'block';

            this.innerHTML = isVisible ? '<i class="fas fa-chevron-down"></i> Mostrar detalles' : '<i class="fas fa-chevron-up"></i> Ocultar detalles';
        });

        document.getElementById('toggleConsultas').addEventListener('click', function() {
            const details = document.getElementById('detailsConsultas');
            const isVisible = details.style.display === 'block';


            details.style.display = isVisible ? 'none' : 'block';

            this.innerHTML = isVisible ? '<i class="fas fa-chevron-down"></i> Mostrar detalles' : '<i class="fas fa-chevron-up"></i> Ocultar detalles';
        });

        document.getElementById('toggleTratamientos').addEventListener('click', function() {
            const details = document.getElementById('detailsTratamientos');
            const isVisible = details.style.display === 'block';


            details.style.display = isVisible ? 'none' : 'block';

            this.innerHTML = isVisible ? '<i class="fas fa-chevron-down"></i> Mostrar detalles' : '<i class="fas fa-chevron-up"></i> Ocultar detalles';
        });
    </script>



</div>


</div>



<?php
require 'template/footer.php';
?>