<?php
require '../../includes/app.php';

use App\Cuenta;
use App\Perfil;
use Dompdf\Dompdf;
use Dompdf\Options;

// Inicializa Dompdf con opciones
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans'); // Fuente para soportar caracteres especiales
$dompdf = new Dompdf($options);

// Instanciar la clase Cuenta y obtener los datos
$cuenta = new Cuenta();
$datosPagos = $cuenta->ingresoDiaImprimir();

// Calcular el total de saldos
$totalSaldo = array_sum(array_column($datosPagos, 'saldo_total'));

$fecha_completa = strftime('%A, %d de %B de %Y');



// Generar contenido HTML para el PDF
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Pagos - Día</title>
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
        <h2>Reporte de Pagos de ' . ucfirst($fecha_completa) . '</h2>
    </header>
    <main>
        <table>
            <thead>
                <tr>
                    <th>Nro.</th>
                    
                    <th>Cliente</th>
                    <th>Carnet</th>
                    <th>Total</th>
                    <th>Pagado</th>
                    <th>Atendido por</th>
                </tr>
            </thead>
            <tbody>';
$contador = 1;
foreach ($datosPagos as $pago) {
    $perfil = Perfil::find($pago->id_personal);

    $html .= '<tr>
                
                <td>' . s($pago->id_cuenta) . '</td>
                <td>' . s($pago->nombre_completo) . '</td>
                <td>' . s($pago->num_carnet) . '</td>
                <td>' . number_format(s($pago->saldo_total), 2, '.', ',') . ' Bs.</td>
                <td>' . number_format(s($pago->monto_pagado), 2, '.', ',') . ' Bs.</td>
                <td>' . $perfil->nombres . ' ' . $perfil->apellido_paterno . ' ' . $perfil->apellido_materno . '</td>
              </tr>';
}
$html .= '
            </tbody>
        </table>
        <p class="total">Total Ingresos: ' . number_format($totalSaldo, 2, '.', ',') . ' Bs.</p>
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
$datosVeterinaria = "Veterinaria San Benito | Celuar: 76595194 | Generado el: $fechaHora ";
$canvas->page_text(40, 770, $datosVeterinaria, $font, 10, [0, 0, 0]);


// Enviar el PDF al navegador para descargar o visualizar
$dompdf->stream('reporte-pagos-dia.pdf', ['Attachment' => false]); // Cambia a true para forzar la descarga
