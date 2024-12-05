<?php
require '../../includes/app.php';

use App\Perfil;
use App\Propietarios;
use App\Mascotas;
use App\Consulta;
use App\Tratamiento;
use App\ProgramarTratamiento;

if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);

    parse_str($decrypted_data, $params);

    // Ahora tienes acceso a los parámetros
    $id_consulta = $params['id_consulta'];
    if (!$id_consulta) {
        echo "<script>window.history.back();</script>";
        exit;
    }
} else {
    echo "<script>window.history.back();</script>";
    exit;
}

use Dompdf\Dompdf;


ob_start(); // Inicia el buffer para capturar la salida HTML.
$consulta = Consulta::find($id_consulta);
$mascota = Mascotas::find($consulta->id_mascota);
$propietario = Propietarios::find($mascota->id_propietario);
$perfil = Perfil::find($consulta->id_personal);
//debuguear($perfil);
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
                <td><strong>Fecha: <?php echo $consulta->fecha_consulta ?></strong></td>


            </tr>
            <tr>
                <td><strong>Médico Veterinario a Cargo:</strong> <?php
                                                                    echo htmlspecialchars($perfil->nombres) . ' ' .
                                                                        htmlspecialchars($perfil->apellido_paterno) . ' ' .
                                                                        htmlspecialchars($perfil->apellido_materno);
                                                                    ?></td>

            </tr>
        </table>

        <div class="section-title">Datos del Paciente</div>
        <table>
            <tr>
                <td><strong>Especie:</strong> <?php echo $mascota->especie ?></td>

                <td><strong>Raza:</strong><?php echo $mascota->raza ?></td>

            </tr>
            <tr>
                <td><strong>Fecha de nacimiento:</strong> <?php echo $mascota->fecha_nacimiento ?></td>

                <td><strong>Sexo:</strong> <?php echo $mascota->sexo ?></td>

            </tr>
            <tr>

                <td><strong>Color:</strong> <?php echo $mascota->color ?></td>

                <td><strong>Nombre:</strong> <?php echo $mascota->nombre ?></td>

            </tr>
        </table>

        <div class="section-title">Datos del Propietario</div>
        <table>
            <tr>
                <td colspan="2"><strong>Nombre:</strong> <?php
                                                            echo htmlspecialchars($propietario->nombres) . ' ' .
                                                                htmlspecialchars($propietario->apellido_paterno) . ' ' .
                                                                htmlspecialchars($propietario->apellido_materno);
                                                            ?></td>

            </tr>
            <tr>
                <td><strong>Cel.:</strong> <?php echo $propietario->num_celular ?></td>

                <td><strong>Dirección:</strong> <?php echo $propietario->direccion ?></td>

            </tr>
        </table>

        <div class="section-title">Motivo de la Consulta</div>
        <table>
            <tr>
                <td style="height: 40px;" ><?php echo $consulta->motivo_consulta ?></td>
            </tr>
        </table>

        <div class="section-title">Información de Anamnesis</div>
        <table>
            <tr>
                <td><strong>Vacunas Polivalentes:</strong><?php echo $consulta->vac_polivalentes ?></td>

                <td><strong>Vacuna Rabia:</strong><?php echo $consulta->vac_rabia ?></td>

                <td><strong>Desparasitación:</strong><?php echo $consulta->desparasitacion ?></td>

                <td><strong>Esterilizado:</strong><?php echo $consulta->esterelizado ?></td>

            </tr>
        </table>
        <table>
            <tr>
                <td colspan="2"><strong>Información:</strong> <?php echo $consulta->informacion ?></td>
                
            </tr>
        </table>

        <div class="section-title">Constantes Fisiológicas</div>
        <table>
            <tr>
                <td><strong>Mucosa:</strong> <?php echo $consulta->mucosa ?></td>

                <td><strong>TLLC:</strong> <?php echo $consulta->tiempo_de_llenado_capilar ?></td>

                <td><strong>F.Car.:</strong><?php echo $consulta->frecuencia_cardiaca?></td>

                <td><strong>F.Res.:</strong><?php echo $consulta->frecuencia_respiratoria ?></td>

                <td><strong>Peso:</strong><?php echo $consulta->peso ?></td>

            </tr>
            <tr>
                <td><strong>Pulso:</strong> <?php echo $consulta->pulso ?></td>

                <td colspan="7"><strong>Turgencia de Piel:</strong><?php echo $consulta->turgencia_de_piel ?></td>
                
            </tr>
        </table>

        <div class="section-title">Examen Clínico</div>
        <table>
            <tr>
                <td><strong>Actitud:</strong><?php echo $consulta->actitud ?></td>

                <td><strong>Ganglios Linfáticos:</strong><?php echo $consulta->ganglios_linfaticos ?></td>

                <td><strong>Hidratación:</strong><?php echo $consulta->hidratacion ?></td>

            </tr>
        </table>

        <div class="section-title">Diagnóstico Presuntivo</div>
        <table>
            <tr>
                <td style="height: 40px;" ><?php echo $consulta->Diagnostico_presuntivo ?></td>
            </tr>
        </table>

        <!-- <div class="section-title">Tratamiento</div>
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
    </div> -->
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