<?php
require 'includes/app.php';
$db = conectarDb();
$password = '';
$email = '';
$errores = [];
//incluir la autentificacion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)));
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (!$email) {
        $errores[] = 'EL email es obligatorio ';
    }

    if (!$password) {
        $errores[] = 'la contraseña debe ser llenada ';
    }
    if (empty($errores)) {
        // revisar si el usuario existe
        $query = " SELECT * FROM usuario WHERE email = '${email}' ";
        $result = mysqli_query($db, $query);
        if ($result->num_rows) {
            //pasword es correcto
            $usuario = mysqli_fetch_assoc($result);
            //verificar si el passwor es correcto
            $auto = password_verify($password, $usuario['password']);

            if (!$auto) {
                $errores[] = 'contaseña no valida';
            } else {
                // session_start();
                // $_SESSION['usuario'] = $usuario['email'];
                // $_SESSION['login'] = true;
                // header('Location:admin/index.php');
                echo 'inicio secion correcta';
            }
        } else {
            $errores[] = "el usuario no exixte";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" href="build/img/logoblanco.webp">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="build/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="build/css/app.css">

</head>


<body>

    <div class="container-fluid">
        <div class="row">
            <div class=" login--imagen col-md-6 col-12">

                <img id="logo-img" src="build/img/logocomp.webp" alt="Logo de San Benito" width="500" style="z-index:100">


                <!-- <p>Bienvenido a San Benito, donde el bienestar de tus mascotas es nuestra prioridad.
     Nuestro equipo está aquí para brindar el mejor cuidado y atención a esos seres
      que son parte de tu familia. ¡Gracias por confiar en nosotros para cuidar a
       quienes más amas!</p> -->

            </div>
            <div class="login--form col-md-6 col-12">
                <form id="loginForm" method="post" class="login--formulario">
                    <a class=" text-center logo-ingreso" href="index.html">


                        <img id="logo-img" src="build/img/logho.png" alt="Logo de San Benito" width="325">


                    </a>
                    <h1 class="text-center mb-4">Iniciar Sesión</h1>
                    <?php
                    $index = 0; // O usa un contador para generar un índice único

                    foreach ($errores as $error) :
                        mesajeError($error, $index++);
                    endforeach;


                    ?>


                    <div class="form-group mb-3">
                        <input id="inputEmail" name="email" type="email" placeholder="Correo electrónico" autofocus
                            class="form-control shadow-sm px-4" value="<?php echo $email ?>">
                            <div id="emailError" class="text-danger"></div>
                    </div>
                    <div class="form-group mb-3">
                        <input id="inputPassword" name="password" type="password" placeholder="Contraseña"
                            class="form-control shadow-sm px-4" value="<?php echo $password ?>">
                            <div id="passwordError" class="text-danger"></div>
                    </div>
                    <div class="custom-control custom-checkbox mb-3 text-center">
                        <input id="customCheck1" type="checkbox" class="custom-control-input">
                        <label for="customCheck1" class="custom-control-label">Mostrar Contraseña</label>
                    </div>
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary text-uppercase mb-2 shadow-sm">Ingresar</button>
                    </div>


                </form>
            </div>
        </div>
    </div>

    <script src="build/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="build/js/bundle.min.js"></script>
</body>

</html>