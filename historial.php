<?php
require 'includes/app.php';

use Dompdf\Dompdf;

ob_start(); // Inicia el buffer para capturar la salida HTML.
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia Clínica - Veterinaria San Benito</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            
        }

        .container {
            padding: 0px;
        }

        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            color: #006400;
            /* Verde oscuro */
        }

        .subtitle {
            text-align: center;
            font-size: 14px;
            color: #228B22;
            /* Verde intermedio */
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #f4f4f4;
            font-size: 12px;
        }

        .section-title {
            background-color: #006400;
            /* Verde oscuro */
            color: white;
            padding: 5px;
            font-size: 14px;
            margin-top: 10px;
            text-transform: uppercase;
        }

        .no-border {
            border: none !important;
        }

        .note {
            font-size: 10px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="title">Historia Clínica</div>
        <div class="subtitle">Veterinaria "San Benito"</div>

        <table>
            <tr>
                <td><strong>Fecha:</strong></td>


            </tr>
            <tr>
                <td><strong>Médico Veterinario a Cargo:</strong></td>

            </tr>
        </table>

        <div class="section-title">Datos del Paciente</div>
        <table>
            <tr>
                <td><strong>Especie:</strong></td>
                
                <td><strong>Raza:</strong></td>
                
            </tr>
            <tr>
                <td><strong>Edad:</strong></td>
                
                <td><strong>Sexo:</strong></td>
                
            </tr>
            <tr>

                <td><strong>Color:</strong></td>
                
                <td><strong>Nombre:</strong></td>
                
            </tr>
        </table>

        <div class="section-title">Datos del Propietario</div>
        <table>
            <tr>
                <td colspan="2"><strong>Nombre:</strong></td>
                
            </tr>
            <tr>
                <td><strong>Cel.:</strong></td>
                
                <td><strong>Dirección:</strong></td>
                
            </tr>
        </table>

        <div class="section-title">Motivo de la Consulta</div>
        <table>
            <tr>
                <td style="height: 40px;" class=""></td>
            </tr>
        </table>

        <div class="section-title">Información de Anamnesis</div>
        <table>
            <tr>
                <td><strong>Vacunas Polivalentes:</strong></td>
                
                <td><strong>Vacuna Rabia:</strong></td>
                
                <td><strong>Desparasitación:</strong></td>
                
                <td><strong>Esterilizado:</strong></td>
                
            </tr>
        </table>
        <table>
            <tr>
                <td><strong>Información:</strong></td>
                <td colspan="2" class=""></td>
            </tr>
        </table>

        <div class="section-title">Constantes Fisiológicas</div>
        <table>
            <tr>
                <td><strong>Mucosa:</strong></td>
                
                <td><strong>TLLC:</strong></td>
                
                <td><strong>F.Car.:</strong></td>
                
                <td><strong>F.Res.:</strong></td>
                
                <td><strong>Peso:</strong></td>
                
            </tr>
            <tr>
                <td><strong>Pulso:</strong></td>
                
                <td><strong>Turgencia de Piel:</strong></td>
                <td colspan="7"></td>
            </tr>
        </table>

        <div class="section-title">Examen Clínico</div>
        <table>
            <tr>
                <td><strong>Actitud:</strong></td>
                
                <td><strong>Ganglios Linfáticos:</strong></td>
                
                <td><strong>Hidratación:</strong></td>
                
            </tr>
        </table>

        <div class="section-title">Diagnóstico Presuntivo</div>
        <table>
            <tr>
                <td style="height: 40px;" class=""></td>
            </tr>
        </table>

        <div class="section-title">Tratamiento</div>
        <table>
            <tr>
                <th>Día Tratamiento</th>
                <th>fecha taratmiento</th>
                <th>Peso</th>
                <th>Temperatura</th>
                <th>Observaciones</th>
                
            </tr>
            <tr>
                <td style="height: 40px;" class=""></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                
            </tr>
        </table>
        <p class="note">Nota: Este documento es generado automáticamente por el sistema de la veterinaria San Benito.</p>
    </div>
</body>

</html>
<?php
$html = ob_get_clean(); // Captura el contenido del buffer.

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("historia_clinica.pdf", ["Attachment" => false]);
?>