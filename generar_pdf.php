<?php
require 'vendor/autoload.php';

require 'includes/app.php';
$db = conectarDb();
// obtener datos pro

$sql = "SELECT p.id_producto, p.nombre,p.precio_venta, p.precio_venta*0.16 as iva,
p.precio_venta*0.16+p.precio_venta as presio_con_iva, p.id_fabrica, f.nombre as
nombre_fabrica
FROM productos as p , fabrica as f";

$results = mysqli_query($db, $sql);





use Dompdf\Dompdf;
use Dompdf\Options;

// Configurar Dompdf con algunas opciones
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Obtener la fecha y hora actual
$fecha = date('d/m/Y');




// Crear el HTML para el reporte
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Clientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
        }
        .header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 150px;
        }
        .date-time {
            text-align: right;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            text-align: right;
            color: #555;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://w7.pngwing.com/pngs/531/692/png-transparent-logo-graphic-design-art-online-shop.png" alt="Logo Empresa" class="logo">
        <div class="date-time">
            Fecha: ' . $fecha . '<br>
           
        </div>
    </div>
    <h1>Reporte de Clientes</h1>
    <table>
        <thead>
            <tr>
                <th>ID producto</th>
                <th>Nombre</th>
                <th>precio venta</th>
                <th>iva</th>
                <th>precio con iva</th>
                 <th>id fabrica</th>
                  <th>nombre fabrica</th>
            </tr>
        </thead>
        <tbody>';

         while($propiedades = mysqli_fetch_assoc($results)) {
    $html .= '<tr>';
    $html .= '<td>' . $propiedades['id_producto'] . '</td>';
    $html .= '<td>' . $propiedades['nombre'] . '</td>';
    $html .= '<td>' . $propiedades['precio_venta'] . '</td>';
    $html .= '<td>' . $propiedades['iva'] . '</td>';
    $html .= '<td>' . $propiedades['presio_con_iva'] . '</td>';
    $html .= '<td>' . $propiedades['id_fabrica'] . '</td>';
    $html .= '<td>' . $propiedades['nombre_fabrica'] . '</td>';
    
    $html .= '</tr>';
}

$html .= '
        </tbody>
    </table>
    <div class="footer">
        
    </div>
</body>
</html>';

// Cargar el HTML en Dompdf
$dompdf->loadHtml($html);

// Configurar el tamaño de papel y orientación
$dompdf->setPaper('A4', 'portrait');

// Renderizar el PDF
$dompdf->render();

// Reemplazar los marcadores de posición para los números de página
$canvas = $dompdf->getCanvas();
$canvas->page_text(520, 820, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0,0,0));

// Salida del PDF generado al navegador
$dompdf->stream("reporte_clientes.pdf", array("Attachment" => false));

