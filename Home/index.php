<?php

$titulo = "Dashboard";
$nombrepagina = "Dashboart";
require 'template/header.php';

use App\User;
use App\Desparacitaciones;
use App\Vacunas;
use App\Mascotas;
use App\CirugiaRealizada;
use App\Consulta;


use App\Propietarios;
use App\Servicios;
use App\Ciruguas;
use App\Atencionservicio;

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
$consultashoy = Consulta:: all();

?>


<div class='dashboard-content'>
    <div class='container'>
        <div class="row">
<?php if($_SESSION['rol']=="Administrador"): ?>
           
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="unique-card unique-card-usuarios">
                    <div class="card-body">
                        <h5>Usuarios</h5>
                        <p><?php echo $usuario ?></p>
                        <a href="/sistema-sanbenito/home/usuarios.php">Ver más</a>
                        
                        <i class="bi bi-person-vcard-fill icono-fondo"></i>
                    </div>
                </div>
            </div>

<?php endif;?>

          
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="unique-card unique-card-propietarios">
                    <div class="card-body">
                        <h5>Propietarios</h5>
                        <p><?php echo $propietario ?></p>
                        <a href="/sistema-sanbenito/home/propietarios.php">Ver más</a>
                       
                        <i class="bi bi-person-fill icono-fondo"></i>
                    </div>
                </div>
            </div>

            
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="unique-card unique-card-servicios">
                    <div class="card-body">
                        <h5>Mascotas</h5>
                        <p><?php echo $mascotas ?></p>
                        <a href="/sistema-sanbenito/home/servicios.php">Ver más</a>
                        
                        <i class="fas fa-paw icono-fondo"></i>
                    </div>
                </div>
            </div>

        
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="unique-card unique-card-servicios2">
                    <div class="card-body">
                        <h5>Servicios</h5>
                        <p><?php echo $servicios ?></p>
                        <a href="/sistema-sanbenito/home/servicios.php">Ver más</a>
                        

                        <i class="bi bi-bookmark-fill icono-fondo"></i>
                    </div>
                </div>
            </div>
           

            
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="unique-card unique-card-perfiles">
                    <div class="card-body">
                        <h5>Cirugias</h5>
                        <p><?php echo $cirugias ?></p>
                        <a href="/sistema-sanbenito/home/perfiles.php">Ver más</a>
                        
                        <i class="bi bi-bookmark-heart icono-fondo"></i>
                    </div>
                </div>
            </div>

        </div>



    </div>
    <!-- hoy -->
    <div class="container mt-5">
        <div class="row">
            
            <div class="col-md-6 mb-4">
                <div class="card card-vacunas-hoy shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="icono-container me-3">
                            <i class="fas fa-syringe fa-3x text-primary"></i>
                        </div>
                        <div>
                            <h5 class="card-title">Vacunas Hoy</h5>
                            <p class="card-text">Total de vacunas aplicadas hoy: <?php echo count($vacunahoy); ?></p>

                           
                            <button class="btn btn-info " id="toggleVacunas">
                                <i class="fas fa-chevron-down"></i> Mostrar detalles
                            </button>
                            <a href="#" type="button" class="btn btn-danger"> <i class="bi bi-file-pdf-fill"></i> Imprimir</a>
                        </div>
                    </div>
                    
                    <div id="detailsVacunas" style="display: none;">
                        <h6>Detalles de Vacunas</h6>
                        <ul class="list-group mb-3">
                            <?php $vacunaencontrada = false;
                            foreach ($vacunahoy as $vacuna):
                                if ($vacuna->fecha_vacuna == date('Y-m-d')) {
                                    $vacunaencontrada = true;
                            ?>
                                    <li class="list-group-item"><?php echo $vacuna->contra; ?> - <?php echo $vacuna->fecha_vacuna; ?></li>
                                <?php }
                            endforeach;
                            if (!$vacunaencontrada) {
                                ?>
                                <li class="list-group-item">no se encontraron vacunas</li>
                            <?php } ?>
                        </ul>
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
                            <p class="card-text">Total de desparasitaciones hoy: <?php echo count($desparasitacionhoy); ?></p>

                            
                            <button class="btn btn-info " id="toggleDesparasitaciones">
                                <i class="fas fa-chevron-down"></i> Mostrar detalles
                            </button>
                            <a href="#" type="button" class="btn btn-danger"> <i class="bi bi-file-pdf-fill"></i> Imprimir</a>
                        </div>
                    </div>
                    
                    <div id="detailsDesparasitaciones" style="display: none;">
                        <h6>Detalles de Desparasitaciones</h6>
                        <ul class="list-group">
                            <?php 
                            $desparasitacions= false;
                            foreach ($desparasitacionhoy as $desparasitacion): 
                                $desparacitacion= true;
                            ?>
                                <li class="list-group-item"><?php echo $desparasitacion->producto; ?> - <?php echo $desparasitacion->fecha_aplicacion; ?></li>
                            <?php endforeach; 
                            if (!$desparasitacions) {
                            ?>
                            <li class="list-group-item">no se encontraron desparasitaciones</li>
                            <?php } ?>
                        </ul>
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
                            <p class="card-text">Total de Servicios hoy: <?php echo count($desparasitacionhoy); ?></p>


                           
                            <button class="btn btn-info " id="toggleServicio">
                                <i class="fas fa-chevron-down"></i> Mostrar detalles
                            </button>
                            <a href="#" type="button" class="btn btn-danger"> <i class="bi bi-file-pdf-fill"></i> Imprimir</a>
                        </div>
                    </div>
                    
                    <div id="detailsServicio" style="display: none;">
                        <h6>Detalles de Servicios</h6>

                        <ul class="list-group">
                            <?php $servicioss=false; foreach ($servicioshoy as $servicios):
                                $servicioss=true;
                                ?>
                                <li class="list-group-item"><?php echo $servicios->observaciones; ?> - <?php echo $servicios->id_servicio; ?></li>
                            <?php endforeach; 
                            if (!$servicioss) {
                            ?>
                            <li class="list-group-item">no se encontraron servicios</li>
                            <?php } ?>
                        </ul>
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
                            <p class="card-text">Total de cirugias hoy: <?php echo count($desparasitacionhoy); ?></p>


                            
                            <button class="btn btn-info " id="toggleCirugias">
                                <i class="fas fa-chevron-down"></i> Mostrar detalles
                            </button>
                            <a href="#" type="button" class="btn btn-danger"> <i class="bi bi-file-pdf-fill"></i> Imprimir</a>
                        </div>
                    </div>
                   
                    <div id="detailsCirugias" style="display: none;">
                        <h6>Detalles</h6>

                        <ul class="list-group">
                            <?php foreach ($cirugiashoy as $cirugias): ?>
                                <li class="list-group-item"><?php echo $cirugias->producto; ?> - <?php echo $cirugias->fecha_aplicacion; ?></li>
                            <?php endforeach; ?>
                        </ul>
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
                            <p class="card-text">Total de Consultas hoy: <?php echo count($desparasitacionhoy); ?></p>


                            
                            <button class="btn btn-info " id="toggleConsultas">
                                <i class="fas fa-chevron-down"></i> Mostrar detalles
                            </button>
                            <a href="#" type="button" class="btn btn-danger"> <i class="bi bi-file-pdf-fill"></i> Imprimir</a>
                        </div>
                    </div>
                    
                    <div id="detailsConsultas" style="display: none;">
                        <h6>Detalles de Servicios</h6>

                        <ul class="list-group">
                            <?php foreach ($desparasitacionhoy as $desparasitacion): ?>
                                <li class="list-group-item"><?php echo $desparasitacion->producto; ?> - <?php echo $desparasitacion->fecha_aplicacion; ?></li>
                            <?php endforeach; ?>
                        </ul>
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
                            <p class="card-text">Total de tratamientos hoy: <?php echo count($desparasitacionhoy); ?></p>


                            
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
<div class="container">
    <div class="row">
        <div class="col-12">
        <canvas id="myChart" width="400" height="200"></canvas>
        </div>
        <div class="col-12">
            
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul','Ago','Sep','Oct','Nov'],
        datasets: [{
            label: 'Servicios Mensuales',
            data: [12, 19, 3, 5, 2, 3, 7],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<?php
require 'template/footer.php';
?>