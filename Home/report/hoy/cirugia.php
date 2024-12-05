<?php
require '../../../includes/app.php';

use App\CirugiaRealizada;
use App\CirugiaProgramada;
use App\Perfil;
use App\Ciruguas;
use App\Propietarios;
use App\Mascotas;
use Dompdf\Dompdf;
use Dompdf\Options;

// Inicializa Dompdf con opciones
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans'); // Fuente para soportar caracteres especiales
$dompdf = new Dompdf($options);

// Obtener las vacunas aplicadas hoy
$cirugiasHoy = CirugiaRealizada::all(); // Aquí debes filtrar las vacunas por la fecha de hoy
$cirugiasHoy = array_filter($cirugiasHoy, function ($cirugiasHoy) {
    return $cirugiasHoy->fecha_cirugia == date('Y-m-d');
});

// Generar contenido HTML para el PDF
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Cirugías - Día</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            text-align: center;
            padding: 10px 0;
            border-bottom: 2px solid #005C43;
        }
        h1 {
            color: #005C43;
            margin: 0;
            font-size:30px;
        }
        h2 {
            margin: 5px 0 20px 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size:12px
            
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total {
            font-weight: bold;
            color: #005C43;
            text-align: right;
        }
    </style>
</head>
<body>
    <header>
        <h1>Veterinaria San Benito</h1>
        <h2>Cirugías realizadas de ' . ucfirst(strftime('%A, %d de %B de %Y')) . '</h2>
    </header>
    <main>
        <table>
            <thead>
                <tr>
                    <th>Nro.</th>
                    <th>Mascota</th>
                    <th>Propietario</th>
                    <th>Observaciones</th>
                    <th>Cirugía</th>
                    <th>Costo</th>
                     
                    <th>Atendido por</th>
                </tr>
            </thead>
            <tbody>';

$contador = 1;
foreach ($cirugiasHoy as $cirugiasHoy) {
    $cirugiapro = CirugiaProgramada::find($cirugiasHoy->id_cirugia_programada);
    $tipocirugia = Ciruguas::find($cirugiapro->id_cirugia);
    $mascota = Mascotas::find($cirugiapro->id_mascota);
    $perfil = Perfil::find($cirugiasHoy->id_personal);

    $propietario = Propietarios::find($mascota->id_propietario);

    // Generar la fila de la tabla con los detalles de la desparasitacion$cirugiasHoy
    $html .= '<tr>
                <td>' . $contador++ . '</td>
                <td>' . $mascota->nombre . '</td>
                <td>' . $propietario->nombres . ' ' . $propietario->apellido_paterno . ' ' . $propietario->apellido_materno . '</td>
                <td>' . $cirugiasHoy->observaciones . '</td>
                <td>' . $tipocirugia->nombre_cirugia . '</td>
                <td>' . number_format($cirugiasHoy->costo, 2, '.', ',') . ' Bs.</td>   
                <td>' . $perfil->nombres . ' ' . $perfil->apellido_paterno . ' ' . $perfil->apellido_materno . '</td>
              </tr>';
}

$html .= '
            </tbody>
        </table>
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