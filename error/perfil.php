
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Inactiva</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f2f2;
            color: #333;
            font-family: 'Arial', sans-serif;
        }
        .error-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .error-box {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        }
        .error-box h1 {
            font-size: 3rem;
            color: #cc0000; /* Color rojo acorde al logo */
        }
        .error-box h2 {
            font-size: 2rem;
            color: #333333;
            margin-bottom: 20px;
        }
        .error-box p {
            font-size: 1.2rem;
            color: #666666;
            margin-bottom: 30px;
        }
        .error-box a {
            text-decoration: none;
            color: #ffffff;
            background-color: #007bff; /* Color azul acorde al logo */
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .error-box a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-box">
            <h1>no se encontro ningun perfil </h1>
            <h2>No tienes acceso a esta página</h2>
            <p>lo lamento no se encontro un perfil asociado a este usuario</p>
            <a href="mailto:admin@tudominio.com">Contactar al Administrador</a>
        </div>
    </div>
</body>
</html>
