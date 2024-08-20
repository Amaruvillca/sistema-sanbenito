<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>hola</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/sistema-sanbenito/build/css/app.css">  
    <link rel="stylesheet" href="/sistema-sanbenito/build/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    
</head>

<body>
    <!-- partial:index.partial.html -->
    <div class='dashboard'>
        <div class="dashboard-nav">
            <header><a href="#!" class="menu-toggle"><i class="fas fa-bars"></i></a>
                <a href="#" class="brand-logo">
                    <img class="logss" src="../build/img/log.jpg" alt="" height="50">
                </a>
            </header>
            <div class="nav-item-divider"></div>
            <nav class="dashboard-nav-list">
                <a href="#" class="dashboard-nav-item active"><i class="fas fa-tachometer-alt"></i>
                    dashboard
                </a>

                <a href="#" class="dashboard-nav-item "><i class="bi bi-person-vcard-fill"></i></i>
                    usuarios
                </a>

                <a href="#" class="dashboard-nav-item "><i class="bi bi-person-fill"></i>
                    propietarios
                </a>

                <a href="#" class="dashboard-nav-item"><i class="fas fa-paw"></i></i>
                    Mascotas </a>


                <div class='dashboard-nav-dropdown'>
                    <a href="#!" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                            class="bi bi-hospital-fill"></i>
                        Cirugias </a>
                    <div class='dashboard-nav-dropdown-menu'>

                        <a href="#" class="dashboard-nav-dropdown-item">Castraciones</a>
                        <a href="#" class="dashboard-nav-dropdown-item">Esterilisacion</a>
                        <a href="#" class="dashboard-nav-dropdown-item">Añadir nueva cirugia</a>
                    </div>
                </div>
                <div class='dashboard-nav-dropdown'><a href="#!"
                        class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                            class="bi bi-clipboard2-plus-fill"></i></i>
                        Servicios</a>
                    <div class='dashboard-nav-dropdown-menu'>

                        <a href="#" class="dashboard-nav-dropdown-item">limpiesa dental</a><a href="#"
                            class="dashboard-nav-dropdown-item">peluqueria</a><a href="#"
                            class="dashboard-nav-dropdown-item">añadir servicio</a>
                    </div>
                </div>

                <div class='dashboard-nav-dropdown'><a href="#!"
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
            <header class='dashboard-toolbar'><a href="#!" class="menu-toggle"><i class="fas fa-bars"></i></a>
                <div class="perfil">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img width="40" height="40" style="border-radius: 50%; left: 2rem;"
                                src="https://www.shutterstock.com/image-photo/portrait-smiling-young-caucasian-woman-600nw-1769848013.jpg"
                                alt=""> Tú Perfil
                        </a>
                        <ul class="dropdown-menu">

                            <li><a href="#" class=" tama dashboard-nav-item "><i class="fas fa-cogs"></i> Ajustes </a>
                            </li>

                            <li><a href="#" class="tama dashboard-nav-item"><i class="fas fa-sign-out-alt"></i>Salir</a>
                            </li>
                        </ul>
                    </li>
                </div>
            </header>