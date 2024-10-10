<?php
require '../../includes/app.php'; // Incluye tu archivo de configuración
use Dompdf\Dompdf;
use App\Vacunas;
use App\Desparacitaciones;
use App\Atencionservicio;
use App\Consulta;
use App\CirugiaRealizada;
use App\Medicacion;
use App\Cuenta;
use App\Mascotas;
use App\Propietarios;
use App\perfil;

// Inicializa DomPDF


// Verifica si hay un parámetro 'data' en el GET
if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);
    parse_str($decrypted_data, $params);

    // Verifica si 'id_cuenta' está definido
    if (isset($params['id_cuenta']) && $params['id_cuenta']) {
        $id_cuenta = $params['id_cuenta'];
    } else {
        echo "<script>window.history.back();</script>";
        exit;
    }
} else {
    echo "<script>window.history.back();</script>";
    exit;
}

// Obtiene los datos asociados a la cuenta
$vacunas = Vacunas::asociadosCuenta($id_cuenta);
$desparacitacion = Desparacitaciones::asociadosCuenta($id_cuenta);
$atencion_servicio = Atencionservicio::asociadosCuenta($id_cuenta);
$consulta = Consulta::asociadosCuenta($id_cuenta);
$cirugia_realizada = CirugiaRealizada::asociadosCuenta($id_cuenta);
$medicacion = Medicacion::asociadosCuenta($id_cuenta);
$cuenta = Cuenta::find($id_cuenta);
$personal = Perfil::find($cuenta->id_personal);
$propietario = Propietarios::find($cuenta->id_propietario);

// Inicializa la clase DomPDF
$dompdf = new Dompdf();

// Crea el HTML para el comprobante
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura Veterinaria San Benito</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; margin: 0; padding: 0; font-size: 12px; }
        .container { width: 100%; margin: 0 auto; padding: 10px; }
        .header { text-align: center; }
        .header h1 { margin: 0; font-size: 16px; color: #005C43; }
        
        .client-details { margin-bottom: 0px; }
        .client-details p, .company-details p { margin: 0; }
        .service { border-bottom: 1px dashed #ccc; padding: 3px 0; }
        .total { font-weight: bold; margin-top: 10px; }
        .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado de la empresa -->
        <div class="header">
            <h1 style="font-size: 23px;">Veterinaria San Benito</h1>
            <p>Comprobante de pago Nro.' . $id_cuenta . ' Fecha: ' . date('d/m/Y') . '</p>
        </div>

        

        <!-- Detalles del cliente -->
        <div class="client-details">
            <h3>Datos de pagó</h3>
            <p>Nombre: ' . htmlspecialchars($cuenta->nombre_completo) . ' Carnet: ' . htmlspecialchars($cuenta->num_carnet) . ' </p>
            <p>veterinario: ' . htmlspecialchars($personal->nombres) . ' ' . htmlspecialchars($personal->apellido_paterno) . ' ' . htmlspecialchars($personal->apellido_materno) . '</p>
            <p>Propietario: ' . htmlspecialchars($propietario->nombres) . ' ' . htmlspecialchars($propietario->apellido_paterno) . ' ' . htmlspecialchars($propietario->apellido_materno) . '</p>
        </div>
        <!-- Detalles de servicios -->
        <h3>Servicios:</h3>';


// Función auxiliar para agregar filas
function agregarFilas($servicios, $nombre_servicio, &$html)
{
    foreach ($servicios as $servicio) {
        $mascota = Mascotas::find($servicio->id_mascota);
        $html .= '<div class="service">
            <strong>' . $nombre_servicio . '</strong> - Mascota: ' . htmlspecialchars($mascota->nombre) . ' - Costo: ' . number_format($servicio->costo, 2) . ' Bs.
        </div>';
    }
}

// Agrega cada servicio al HTML
agregarFilas($vacunas, 'Vacuna', $html);
agregarFilas($desparacitacion, 'Desparacitacion', $html);
agregarFilas($atencion_servicio, 'Servicio', $html);
agregarFilas($consulta, 'Consulta', $html);
agregarFilas($cirugia_realizada, 'Cirugía', $html);
agregarFilas($medicacion, 'Medicaciones', $html);

// Calcula el total de los servicios
$total = 0;
foreach ([$vacunas, $desparacitacion, $atencion_servicio, $consulta, $cirugia_realizada, $medicacion] as $servicios) {
    foreach ($servicios as $servicio) {
        $total += $servicio->costo;
    }
}

// Agrega el total al HTML
$html .= '
        <div class="total">
            <strong>Total:</strong> ' . number_format($total, 2) . ' Bs.
        </div>
        <div class="total">
            <strong>Pagado:</strong> ' . number_format($cuenta->monto_pagado, 2) . ' Bs.
        </div>
        <div class="total">
            <strong>Cambio:</strong> ' . number_format($cuenta->monto_pagado - $total, 2) . ' Bs.
        </div>

    <!-- Pie de página -->
    <div class="footer">
        <p>Gracias por confiar en Veterinaria San Benito. ¡Esperamos su próxima visita!</p>
        
    </div>
</div>
</body>
</html>';

// Carga el contenido HTML en DomPDF
$dompdf->loadHtml($html);

// Configura el tamaño de la hoja (A6) y la orientación (vertical)
$dompdf->setPaper('A6', 'portrait');

// Renderiza el PDF
$dompdf->render();

// Configura los encabezados para mostrar el PDF en el navegador sin descargarlo
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="comprobante.pdf"');

// Muestra el PDF en el navegador
echo $dompdf->output();
