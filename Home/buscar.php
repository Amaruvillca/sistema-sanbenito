<?php
$titulo = "Buscar";
$nombrepagina = "Buscar"; 
require 'template/header.php';

use App\CirugiaProgramada;
use App\Ciruguas;
use App\Mascotas;
use App\Vacunas;
use App\Desparacitaciones;

$cirugias_programadas = CirugiaProgramada::all();
$vacunas = Vacunas::all();
$desparasitaciones= Desparacitaciones::all();
?>


<div class='dashboard-content'>
    
</div>

<?php
require 'template/footer.php';
?>
