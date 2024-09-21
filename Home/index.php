<?php

$titulo = "Dashboard";
$nombrepagina = "Dashboart";
require 'template/header.php';
use App\User;
use App\Mascotas;
use App\Perfil;
use App\Propietarios;
$usuario=User::contarDatos();
$perfil=Perfil::contarDatos();
$mascotas=Mascotas::contarDatos();
$propietario=Propietarios::contarDatos();
?>


<div class='dashboard-content'>
    <div class='container'>
        <div class="row">

            <!-- Tarjeta de Usuarios -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="unique-card unique-card-usuarios">
                    <div class="card-body">
                        <h5>Usuarios</h5>
                        <p><?php echo $usuario ?></p>
                        <a href="/sistema-sanbenito/home/usuarios.php">Ver más</a>
                        <!-- Icono de usuarios -->
                        <i class="bi bi-person-vcard-fill icono-fondo"></i>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Perfiles -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="unique-card unique-card-perfiles">
                    <div class="card-body">
                        <h5>Perfiles</h5>
                        <p><?php echo $perfil ?></p>
                        <a href="/sistema-sanbenito/home/perfiles.php">Ver más</a>
                        <!-- Icono de perfil de usuario -->
                        <i class="bi bi-person-circle icono-fondo"></i>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Propietarios -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="unique-card unique-card-propietarios">
                    <div class="card-body">
                        <h5>Propietarios</h5>
                        <p><?php echo $propietario ?></p>
                        <a href="/sistema-sanbenito/home/propietarios.php">Ver más</a>
                        <!-- Icono de casa -->
                        <i class="bi bi-person-fill icono-fondo"></i>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Servicios -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="unique-card unique-card-servicios">
                    <div class="card-body">
                        <h5>Mascotas</h5>
                        <p><?php echo $mascotas ?></p>
                        <a href="/sistema-sanbenito/home/servicios.php">Ver más</a>
                        <!-- Icono de herramientas -->
                        <i class="fas fa-paw icono-fondo"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur eligendi officia, totam provident obcaecati mollitia fuga voluptatibus? Fuga ab possimus sequi, magnam totam, tempore modi minus, labore nam quam rem.
            Totam nostrum rem tenetur voluptas est, enim commodi, molestiae laborum ipsam debitis incidunt dolores necessitatibus, deleniti sequi aperiam ad corporis ipsa pariatur nulla libero architecto expedita eveniet. Quod, enim commodi.
            Ullam delectus id excepturi eaque vero tempora eos maiores nesciunt quod fugit? Est officia quos totam cumque delectus laudantium soluta alias corrupti quam distinctio harum reiciendis dolor, iste unde labore.
        </div>


    </div>

</div>

</div>


<?php
require 'template/footer.php';
?>