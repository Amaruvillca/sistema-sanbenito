<?php
require '../../../includes/app.php';

use App\Consulta;
use App\Perfil;
use App\Propietarios;
use App\Mascotas;
use Dompdf\Dompdf;
use Dompdf\Options;

// Inicializa Dompdf con opciones
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans'); // Fuente para soportar caracteres especiales
$dompdf = new Dompdf($options);

// Obtener las vacunas aplicadas hoy
$consultasHoy = Consulta::all(); // Aquí debes filtrar las vacunas por la fecha de hoy
$consultasHoy = array_filter($consultasHoy, function ($consultasHoy) {
    return $consultasHoy->fecha_consulta == date('Y-m-d');
});

// Generar contenido HTML para el PDF
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Consultas del Día</title>
   <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        color: #333;
        line-height: 1.5;
    }
    header {
        text-align: center;
        padding: 20px 0;
        border-bottom: 3px solid #005C43;
        margin-bottom: 20px;
    }
        p{
        font-size: 13px;
        }
    h1 {
        color: #005C43;
        margin: 0;
        font-size: 26px;
    }
    h2 {
        margin: 5px 0 20px;
        color: #666;
    }
    .container {
        padding: 20px;
    }
    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 20px;
        padding: 15px 20px;
        background-color: #fdfdfd;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .card h3 {
        margin: 0 0 10px 0;
        color: #005C43;
        font-size: 18px;
    }
    .card .line {
    display: flex;
    justify-content: center; /* Centra horizontalmente */
    align-items: center; /* Centra verticalmente */
    flex-wrap: wrap;
    gap: 15px; /* Espaciado entre elementos */
    margin-bottom: 12px;
    text-align: center; /* Opcional, centra el texto dentro de cada elemento */
}

    .line .item {
        margin-right: 15px;
        font-size: 13px;
    }
    .highlight {
        
        color: #005C43;
        font-size: 13px;
    }
    hr {
        margin: 15px 0;
        border: 0;
        border-top: 1px solid #ddd;
        font-size: 13px;
    }
    .footer {
        text-align: center;
        margin-top: 30px;
        font-size: 12px;
        color: #777;
    }
</style>

</head>
<body>
    <header>
        <h1>Veterinaria San Benito</h1>
        <h2>Consultas del día ' . ucfirst(strftime('%A, %d de %B de %Y')) . '</h2>
    </header>
    <main class="container">';
  
$contador = 1;
foreach ($consultasHoy as $consulta) {
    $mascota = Mascotas::find($consulta->id_mascota);
    $perfil = Perfil::find($consulta->id_personal);
    $propietario = Propietarios::find($mascota->id_propietario);

    $html .= '<div class="card">
    
    <div class="line">
    
    <span class="item"><span class="highlight">' . $contador++ . '    </span>
        <span class="item"><span class="highlight">Mascota:</span> ' . $mascota->nombre . '</span>
        <span class="item"><span class="highlight">Propietario:</span> ' . $propietario->nombres . ' ' . $propietario->apellido_paterno . ' ' . $propietario->apellido_materno . '</span>
        <span class="item"><span class="highlight">Atendido por:</span> ' . $perfil->nombres . ' ' . $perfil->apellido_paterno . ' ' . $perfil->apellido_materno . '</span>
        
    </div>
    <hr>
    <p><span class="highlight">Motivo:</span> ' . $consulta->motivo_consulta . '</p>
    <p><span class="highlight">Diagnóstico:</span> ' . $consulta->Diagnostico_presuntivo . '</p>
    <span class="item"><span class="highlight">Costo:</span> ' . number_format($consulta->costo, 2, '.', ',') . ' Bs.</span>
</div>';

}

$html .= '
    </main>
    
</body>
</html>';


// Cargar el HTML en Dompdf
$dompdf->loadHtml($html);

// Configurar tamaño y orientación del papel
$dompdf->setPaper('letter');

// Renderizar el PDF
$dompdf->render();

// Agregar numeración y pie de página personalizado
$canvas = $dompdf->getCanvas();
$font = $dompdf->getFontMetrics()->get_font("DejaVu Sans", "normal");

// Agregar numeración de páginas
$canvas->page_text(520, 770, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 10, [0, 0, 0]);

// Agregar datos de la veterinaria y fecha/hora
$fechaHora = date('d/m/Y H:i:s');
$datosVeterinaria = "Veterinaria San Benito | Celular: 76595194 | Generado el: $fechaHora ";
$canvas->page_text(40, 770, $datosVeterinaria, $font, 10, [0, 0, 0]);

// Enviar el PDF al navegador para descargar o visualizar
$dompdf->stream('reporte-vacunas-dia.pdf', ['Attachment' => false]); // Cambia a true para forzar la descarga
