<?php
// Inicia el búfer de salida
$titulo = "Dashboard";
$nombrepagina = "Dashboart";
require '../template/header.php';

use App\User;
//$usuario = new User();
//$usuario->Mandar();
?>


<div class='dashboard-content'>
<div class="container mt-5">
    <!-- Datos del propietario -->
    <div class="card card-propietario">
        <div class="card-body">
            <h5 class="card-title">Datos del Propietario</h5>
            <p class="card-text"><strong>Nombre:</strong> Juan Pérez</p>
            <p class="card-text"><strong>Teléfono:</strong> +123 456 789</p>
            <p class="card-text"><strong>Email:</strong> juan.perez@example.com</p>
        </div>
    </div>

    <!-- Datos de las mascotas -->
    <h5 class="title mb-4">Mascotas Asociadas</h5>

    <div class="row">
        <!-- Mascota 1 -->
        <div class="col-md-4">
            <div class="card card-mascota">
                <img src="https://placekitten.com/400/300" class="mascota-image" alt="Mascota">
                <div class="card-body">
                    <h5 class="card-title">Firulais</h5>
                    <p class="card-text"><strong>Especie:</strong> Perro</p>
                    <p class="card-text"><strong>Edad:</strong> 3 años</p>
                </div>
            </div>
        </div>

        <!-- Mascota 2 -->
        <div class="col-md-4">
            <div class="card card-mascota">
                <img src="https://placekitten.com/400/300" class="mascota-image" alt="Mascota">
                <div class="card-body">
                    <h5 class="card-title">Michi</h5>
                    <p class="card-text"><strong>Especie:</strong> Gato</p>
                    <p class="card-text"><strong>Edad:</strong> 2 años</p>
                </div>
            </div>
        </div>

        <!-- Mascota 3 -->
        <div class="col-md-4">
            <div class="card card-mascota">
                <img src="https://placekitten.com/400/300" class="mascota-image" alt="Mascota">
                <div class="card-body">
                    <h5 class="card-title">Toby</h5>
                    <p class="card-text"><strong>Especie:</strong> Perro</p>
                    <p class="card-text"><strong>Edad:</strong> 5 años</p>
                </div>
            </div>
        </div>
    </div>
</div>

</div>


<?php
require '../template/footer.php';
?>