<?php
require '../../includes/app.php'; 
use Dompdf\Dompdf;
use Dompdf\Options;

// Inicializar DOMPDF con opciones
$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);


if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);
    parse_str($decrypted_data, $params);

    
    if (isset($params['id_vacuna']) && $params['id_vacuna']) {
        $id_vacuna = $params['id_vacuna'];

        // Query para obtener los datos de la vacuna, mascota, propietario y veterinario
        $query = "SELECT v.*, 
                         m.nombre AS nombre_mascota, 
                         m.imagen_mascota AS imagen_mascota, 
                         p.nombres AS nombre_propietario,
                         p.apellido_paterno AS apellidopa_propietario,
                         p.apellido_materno AS apellidoma_propietario,
                         vet.nombres AS nombre_veterinario,
                         vet.apellido_paterno AS apellidopa_veterinario,
                         vet.apellido_materno AS apellidoma_veterinario
                  FROM vacuna v
                  JOIN mascota m ON v.id_mascota = m.id_mascota
                  JOIN propietario p ON m.id_propietario = p.id_propietario
                  JOIN personal vet ON v.id_personal = vet.id_personal
                  WHERE v.id_vacuna = ?";

        // Preparar y ejecutar el query
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $id_vacuna);
        $stmt->execute();
        $result = $stmt->get_result();
        $datos_vacuna = $result->fetch_assoc();

        if ($datos_vacuna) {
        
            $html = "
            <html>
            <head>
                <title>Comprobante de Vacunación</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0px;
                        color: #333;
                    }
                    .header {
                        text-align: center;
                        border-bottom: 2px solid #ccc;
                        padding-bottom: 5px;
                        margin-bottom: 5px;
                    }
                    .header img {
                        max-width: 50px; /* Aumenta el tamaño del logo */
                        margin-bottom: 15px;
                    }
                    .title {
                        font-size: 24px;
                        font-weight: bold;
                        margin-bottom: 15px;
                    }
                    .content {
                        text-align: left;
                        margin-bottom: 10px;
                    }
                    .content img {
                        width: 150px;
                        height: 150px;
                        border-radius: 50%;
                        margin-bottom: 10px;
                        object-fit: cover;
                    }
                    .content p {
                        font-size: 14px;
                        line-height: 1.8;
                    }
                    .content strong {
                        font-size: 16px;
                        color: #444;
                    }
                    .info-section {
                        margin-bottom: 5px;
                    }
                    .info-section p {
                        margin: 4px 0;
                    }
                    .footer {
                        text-align: center;
                        border-top: 2px solid #ccc;
                        padding-top: 5px;
                        margin-top: 30px;
                        font-size: 12px;
                        color: #777;
                    }
                </style>
            </head>
            <body>
                <div class='header'>
                
               
                    <center><h1><span style='color:red'>San</span> <span style='color:black'>Benito</span> </h1></center>
                </div>
                <center><div class='title'>Pago de Vacuna</div></center>
                <div class='content'>
                    <div class='info-section'>
                    <p><strong>Nro. : </strong> {$datos_vacuna['id_vacuna']}</p>
                        <p><strong>Nombre de la Mascota: </strong> {$datos_vacuna['nombre_mascota']}</p>
                        <p><strong>Propietario: </strong> {$datos_vacuna['nombre_propietario']} {$datos_vacuna['apellidopa_propietario']} {$datos_vacuna['apellidoma_propietario']}</p>
                        <p><strong>Vacuna Administrada: </strong> {$datos_vacuna['nom_vac']}</p>
                        <p><strong>Contra: </strong> {$datos_vacuna['contra']}</p>
                        <p><strong>Fecha de Vacunación: </strong> {$datos_vacuna['fecha_vacuna']}</p>
                        <p><strong>Próxima Vacunación: </strong> {$datos_vacuna['proxima_vacuna']}</p>
                        <p><strong>Costo: </strong> {$datos_vacuna['costo']} Bs.</p>
                        <p><strong>Veterinario: </strong> Dr. {$datos_vacuna['nombre_veterinario']} {$datos_vacuna['apellidopa_veterinario']} {$datos_vacuna['apellidoma_veterinario']}</p>
                    </div>
                </div>
                <div class='footer'>
                    
                    <p>Teléfono: +591 73582839</p>
                    <p>Email: info@sanbenito.com</p>
                </div>
                
            </body>
            </html>
            ";

            // Cargar el contenido HTML en DOMPDF
            $dompdf->loadHtml($html);
            // Configurar el tamaño y la orientación de la página
            $dompdf->setPaper('B6', 'portrait');
            // Renderizar el PDF
            $dompdf->render();
            // Mostrar el PDF en la ventana sin descargar
            $dompdf->stream("comprobante_vacunacion.pdf", ["Attachment" => false]);
        } else {
            echo "No se encontraron datos de vacunación.";
        }
    } else {
        
        echo "<script>window.history.back();</script>";
        exit;
    }
} else {
    
    echo "<script>window.history.back();</script>";
    exit;
}
