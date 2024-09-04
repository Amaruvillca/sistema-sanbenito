<?php
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';

    switch ($mensaje) {
        case 1:
            $mensaje = "El usuario no está activo en el sistema. Por favor, comunícate con el administrador para obtener más ayuda.";
            break;
        case 2:
            $mensaje = "Este usuario no tiene un perfil. Por favor, contacta al administrador para crear uno";
            break;
        case 3:
            $mensaje = "Lo sentimos, pero no tienes los permisos necesarios para acceder a esta página. Por favor, contacta al administrador si crees que esto es un error.";
            break;
        default:
            $mensaje = "";
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 403 - Acceso no autorizado</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #002b26;
            /* Verde oscuro más suave */
            color: #ffffff;
            font-family: 'Arial', sans-serif;
            text-align: center;
            padding: 40px;
        }

        .error-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border-radius: 15px;
            background-color: #003833;
            /* Fondo del contenedor */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .error-code {
            font-size: 7rem;
            color: #ff5252;
            /* Rojo más suave */
            font-weight: bold;
            margin-bottom: 20px;
        }

        .error-message {
            font-size: 1.5rem;
            margin: 20px 0;
            color: #e0f7fa;
            /* Texto más claro para mayor legibilidad */
        }

        .dog-animation {
            width: 200px;
            margin: auto;
            position: relative;
        }

        .dog-animation img {
            width: 100%;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .question-mark {
            font-size: 2rem;
            color: #ffeb3b;
            /* Amarillo más brillante */
            position: absolute;
            top: -20px;
            right: -20px;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .back-home {
            margin-top: 30px;
            font-size: 1.2rem;
            color: white;
            background-color: #ff5252;
            /* Botón rojo más suave */
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            transition: background-color 0.3s ease-in-out;
        }

        .back-home:hover {
            background-color: #ff1744;
            /* Color de fondo del botón al hacer hover */
            text-decoration: none;
        }

        .error-mensaje-recivido {
            font-size: 1.5rem;
            margin: 20px 0;
            color: #e0f7fa;
            padding-bottom: 1.5rem;

        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-code">403</div>
        <h1>¡Acceso denegado!</h1>
        <p class="error-message"> <?php echo $mensaje; ?></p>

        <div class="dog-animation">
            <img src="../build/img/vete5.png" alt="Perrito">
            <div class="question-mark">?</div>
        </div>
        <center>
        <button type="button" class="btn btn-secondary" onclick="window.history.back();">
                            <i class="bi bi-arrow-left"></i>
                            Volver
                        </button>
        </center>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>