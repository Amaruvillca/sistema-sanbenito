<?php
require '../../includes/app.php'; // Ruta correcta a tu archivo de conexión
use Dompdf\Dompdf;
use Dompdf\Options;

// Inicializar DOMPDF con opciones
$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isRemoteEnabled', true); // Habilitar recursos remotos como imágenes

$dompdf = new Dompdf($options);

// Verificar si se recibió el ID de la desparasitación
if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);
    parse_str($decrypted_data, $params);

    // Verificar si 'id_desparasitacion' está definido en $params
    if (isset($params['id_desparasitacion']) && $params['id_desparasitacion']) {
        $id_desparasitacion = $params['id_desparasitacion'];

        // Query para obtener los datos de la desparasitación, mascota, propietario y veterinario
        $query = "SELECT d.*, 
                         m.nombre AS nombre_mascota, 
                         m.imagen_mascota AS imagen_mascota, 
                         p.nombres AS nombre_propietario,
                         p.apellido_paterno AS apellidopa_propietario,
                         p.apellido_materno AS apellidoma_propietario,
                         vet.nombres AS nombre_veterinario,
                         vet.apellido_paterno AS apellidopa_veterinario,
                         vet.apellido_materno AS apellidoma_veterinario
                  FROM desparasitacion d
                  JOIN mascota m ON d.id_mascota = m.id_mascota
                  JOIN propietario p ON m.id_propietario = p.id_propietario
                  JOIN personal vet ON d.id_personal = vet.id_personal
                  WHERE d.id_desparasitacion = ?";

        // Preparar y ejecutar el query
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $id_desparasitacion);
        $stmt->execute();
        $result = $stmt->get_result();
        $datos_desparasitacion = $result->fetch_assoc();

        if ($datos_desparasitacion) {
            // Crear contenido HTML para el comprobante
            $html = "
            <html>
            <head>
                <title>Comprobante de Desparasitación</title>
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
                        max-width: 250px; /* Aumenta el tamaño del logo */
                        margin-bottom: 15px;
                    }
                    .title {
                        font-size: 24px;
                        font-weight: bold;
                        margin-bottom: 10px;
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
                        margin-bottom: 2px;
                    }
                    .info-section p {
                        margin: 2px 0;
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
                <center><div class='title'>Pago de Desparasitación</div></center>
                <div class='content'>
                    <p><strong>Nro. :</strong>  {$datos_desparasitacion['id_desparasitacion']}    <strong>Nombre de la Mascota:</strong> {$datos_desparasitacion['nombre_mascota']}</p>
                    <p><strong>Propietario:</strong> {$datos_desparasitacion['nombre_propietario']} {$datos_desparasitacion['apellidopa_propietario']} {$datos_desparasitacion['apellidoma_propietario']}</p>
                    <p><strong>Producto:</strong> {$datos_desparasitacion['producto']}   <strong>Principio Activo:</strong>  {$datos_desparasitacion['principio_activo']}</p>
                    <p><strong>Tipo:</strong> {$datos_desparasitacion['tipo_desparasitacion']} <strong>   Via:</strong> {$datos_desparasitacion['via']}  <strong>   Costo:</strong> {$datos_desparasitacion['costo']} Bs.</p>
                    
                    <p><strong>Fecha de Aplicación:</strong> {$datos_desparasitacion['fecha_aplicacion']}</p>
                    <p><strong>Próxima Dosis:</strong> {$datos_desparasitacion['proxima_desparasitacion']}</p>
                    
                    <p><strong>Veterinario:</strong> Dr. {$datos_desparasitacion['nombre_veterinario']} {$datos_desparasitacion['apellidopa_veterinario']} {$datos_desparasitacion['apellidoma_veterinario']}</p>
                </div>
                <div class='footer'>
                   
                    <p>Teléfono: +123 456 789</p>
                    <p>Email: info@sanbenito.com</p>
                </div>
            </body>
            </html>
            ";

            // Cargar el contenido HTML en DOMPDF
            $dompdf->loadHtml($html);
            // (Opcional) Configurar el tamaño y la orientación de la página
            $dompdf->setPaper('B6', 'portrait'); // Mantiene el ancho de B6 y alto dinámico
            // Renderizar el PDF
            $dompdf->render();
            // Mostrar el PDF en la ventana sin descargar
            $dompdf->stream("comprobante_desparasitacion.pdf", ["Attachment" => false]);
        } else {
            echo "No se encontraron datos de desparasitación.";
        }
    } else {
        // Si no hay 'id_desparasitacion', ir a la página anterior
        echo "<script>window.history.back();</script>";
        exit;
    }
} else {
    // Si no hay 'data' en el GET, ir a la página anterior
    echo "<script>window.history.back();</script>";
    exit;
}
