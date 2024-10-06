<?php
ob_start();
define('REQUIRE_URL', __DIR__ . '/../../includes');
// define('ERROR_URL', __DIR__ . '/../../error');

require REQUIRE_URL . "/app.php";


estadoAutenticado(conectarDb());
$nombretabla = "personal";
$personal = mostrarTabla(conectarDb(), $_SESSION['id_usuario'], $nombretabla);
if (empty($personal)) {
    header('Location:/sistema-sanbenito/error/403.php?mensaje=2');
    exit;
}
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?php echo $nombrepagina; ?></title>
    <link rel="icon" type="image/webp" href="/sistema-sanbenito/build/img/logoblanco.webp">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/sistema-sanbenito/build/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.css" />


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/sistema-sanbenito/build/css/app.css">

</head>

<body>
    <!-- partial:index.partial.html -->
    <div class='dashboard'>
        <div class="dashboard-nav">
            <header><a href="#!" class="menu-toggle"><i class="fas fa-bars"></i></a>
                <a href="#" class="brand-logo">
                    <img class="logss" src="/sistema-sanbenito/build/img/log.jpg" alt="" height="50">
                </a>
            </header>
            <div class="nav-item-divider"></div>
            <nav class="dashboard-nav-list">
                <a href="/sistema-sanbenito/Home/index.php" class="dashboard-nav-item <?php if ($titulo == 'Dashboard') echo 'active'; ?>  "><i class="fas fa-tachometer-alt"></i>
                    dashboard
                </a>

                <a href="/sistema-sanbenito/Home/usuarios.php" class="<?php noMostrar(); ?> dashboard-nav-item <?php if ($titulo == 'usuarios') echo 'active'; ?>"><i class="bi bi-person-vcard-fill"></i>
                    usuarios
                </a>

                <a href="/sistema-sanbenito/Home/propietarios.php" class="dashboard-nav-item <?php if ($titulo == 'Propietarios') echo 'active'; ?> "><i class="bi bi-person-fill"></i>
                    propietarios
                </a>

                <!-- <a href="/sistema-sanbenito/Home/mascotas.php" class="dashboard-nav-item <?php if ($titulo == 'Mascotas') echo 'active'; ?>"><i class="fas fa-paw"></i>
                    Mascotas
                </a> -->

                <a href="/sistema-sanbenito/Home/calendar.php" class="dashboard-nav-item <?php if ($titulo == 'Calendario') echo 'active'; ?>"><i class="bi bi-calendar"></i>
                    Calendario
                </a>

                <div class='dashboard-nav-dropdown <?php if ($titulo == 'cirugias_hoy' || $titulo == 'tratamientos_hoy') echo 'show'; ?>'>
                    <a href="#!" class="dashboard-nav-item dashboard-nav-dropdown-toggle">
                    <i class="bi bi-calendar-day"></i>
                    hoy</a>
                    <div class='dashboard-nav-dropdown-menu'>
                        <a href="/sistema-sanbenito/home/cirugias_hoy.php" class="dashboard-nav-dropdown-item <?php if ($titulo == 'cirugias_hoy') echo 'active'; ?>">cirugias</a>
                        <a href="/sistema-sanbenito/home/tratamientos_hoy.php" class="dashboard-nav-dropdown-item <?php if ($titulo == 'tratamientos_hoy') echo 'active'; ?>">tratamientos</a>
                    </div>
                </div>
                <!-- <div class='dashboard-nav-dropdown'><a href="#!"
                        class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                            class="bi bi-clipboard2-plus-fill"></i></i>
                        Servicios</a>
                    <div class='dashboard-nav-dropdown-menu'>

                        <a href="#" class="dashboard-nav-dropdown-item">limpiesa dental</a><a href="#"
                            class="dashboard-nav-dropdown-item">peluqueria</a><a href="#"
                            class="dashboard-nav-dropdown-item">añadir servicio</a>
                    </div>
                </div> -->
                <div class="<?php noMostrar(); ?> dashboard-nav-dropdown <?php if ($titulo == 'Servicios' || $titulo == 'Cirugias') echo 'show'; ?>"><a href="#!"
                        class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i class="bi bi-gear"></i>
                        Ajustes </a>
                    <div class='dashboard-nav-dropdown-menu'>
                        <a href="/sistema-sanbenito/home/setting/servicios.php" class="dashboard-nav-dropdown-item <?php if ($titulo == 'Servicios') echo 'active'; ?>">Servicios</a>
                        <a href="/sistema-sanbenito/home/setting/cirugias.php" class="dashboard-nav-dropdown-item <?php if ($titulo == 'Cirugias') echo 'active'; ?>"> Cirugias </a>
                    </div>
                </div>

                <div class="<?php noMostrar(); ?> dashboard-nav-dropdown"><a href="#!"
                        class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i class="fas fa-money-check-alt"></i>
                        Ingresos </a>
                    <div class='dashboard-nav-dropdown-menu'><a href="#" class="dashboard-nav-dropdown-item">All</a><a
                            href="#" class="dashboard-nav-dropdown-item">Recent</a><a href="#"
                            class="dashboard-nav-dropdown-item"> Projections</a>
                    </div>
                </div>





            </nav>
        </div>
        <div class='dashboard-app'>
            <header class='dashboard-toolbar'>
                <a href="#!" class="menu-toggle"><i class="fas fa-bars"></i></a>
                <div class="perfil d-flex align-items-center">


                    <button class="perfil-boton btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                        <img width="30" height="30" class="rounded-circle me-2"
                            src="/sistema-sanbenito/imagepersonal/<?php echo $personal['imagen_personal']; ?>"
                            alt="imagen perfil">

                        <?php echo $personal['nombres'] . ' ' . $personal['apellido_paterno'] . ' ' . $personal['apellido_materno']; ?>
                    </button>
                </div>
            </header>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasRightLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="offcanvas-body">
                        <div class="centar-perfil position-relative">
                            <img width="180" height="180" class="rounded-circle perfil-imagen"
                                src="/sistema-sanbenito/imagepersonal/<?php echo $personal['imagen_personal']; ?>"
                                alt="imagen perfil">

                        </div>

                        <div class="datos-usuario">
                            <h4 class="nombre-usuario"><?php echo $personal['nombres']; ?></h4>
                            <h4 class="nombre-usuario"><?php echo $personal['apellido_paterno'] . ' ' . $personal['apellido_materno']; ?></h4>
                            <p class="rol-usuario"><?php echo $_SESSION['rol']; ?></p>
                            <p class="email-usuario"><?php echo $_SESSION['email']; ?></p>
                            <p class="telefono-usuario">+591 <?php echo $personal['num_celular']; ?></p>
                            <p class="direccion-usuario"><?php echo $personal['direccion']; ?></p>
                        </div>
                        <hr>
                        <div class="botonesUsuario">
                            <?php
                            $id_usuario = $_SESSION['id_usuario'];
                            $id_personal = $personal['id_personal'];


                            $data1 = "id_usuario=$id_usuario&id_personal=$id_personal";
                            // Encripta los parámetros
                            $encryptedData1 = encryptData($data1);
                            ?>

                            <a href="/sistema-sanbenito/home/vermas/personal.php?data=<?php echo $encryptedData1; ?>" type="button" class="btn btn-info"><i class="bi bi-eye-fill"></i> Ver Perfil</a>

                            <form action="/sistema-sanbenito/includes/salir.php" method="post">

                                <button type="submit" class="btn btn-danger">Salir <i class="bi bi-box-arrow-right"></i></button>

                            </form>
                        </div>

                    </div>
                </div>
            </div>