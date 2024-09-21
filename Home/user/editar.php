<?php
// Inicia el búfer de salida
$titulo = "usuarios";
$nombrepagina = "Etitar usuario";
require '../template/header.php';


use App\User;
$id_personal = '';
if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);

    parse_str($decrypted_data, $params);

    // Ahora tienes acceso a los parámetros
    $id_usuario = $params['id_usuario'];
    
    if (!$id_usuario) {
        header('Location:/sistema-sanbenito/error/403.php?mensaje=3');
    }

    // Ahora puedes usar los parámetros para lo que necesites

    $usuario = User::find($id_usuario);
    //debuguear($datosUsuario);
    
    // debuguear($datosPerfil);
} else {
    header('Location:/sistema-sanbenito/error/403.php?mensaje=3');
}
//$usuario = new User;
$errores = User::getErrores();
$mensajeEstado = "";
$estado = false;
//$usuario = new User();
//$usuario->Mandar();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = new User($_POST['usuario']);
    $errores = $usuario->validar();
    if (empty($errores)) {
        $resultado = $usuario->guardar();
        if ($resultado) {
            // Si el usuario se guarda correctamente, establecemos el mensaje
            $mensajeEstado = "success";
            
        }
    }
}
?>

<div class='dashboard-content'>
    <canvas id="canvasBackground"></canvas>

    <div class="login-container d-flex align-items-center justify-content-center">
        <div class="login-box">
            <h2 class="text-center mb-4">Editar Usuario</h2>
            <?php

            foreach ($errores as $error) :
                messageError2($error);
            endforeach;

            ?>



            <form id="loginForm" method="post">
                <div class=" <?php noMostrar(); ?> mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" name="usuario[email]" class="form-control" id="email" placeholder="ejemplo@correo.com" required value="<?php echo s($usuario->email) ?>">
                    <div class="invalid-feedback">Por favor, ingresa un correo válido.</div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="usuario[email]" class="form-control" id="email" placeholder="tu anterior contraseña" required >
                    <div class="invalid-feedback">Por favor, ingresa un correo válido.</div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Nueva contraseña</label>
                    <input type="password" name="usuario[email]" class="form-control" id="email" placeholder="Nueva contraseña" required >
                    <div class="invalid-feedback">Por favor, ingresa un correo válido.</div>
                </div>
                <div class=" <?php noMostrar(); ?> mb-3">
                    <label for="rol" class="form-label">Rol</label>

                    <select name="usuario[rol]" class="form-control form-select">
                        <option selected disabled value="">-- Seleccione --</option>
                        <option <?php echo $usuario->rol == 'Administrador' ? 'selected' : '' ?> value="Administrador">Administrador</option>
                        <option <?php echo $usuario->rol == 'Veterinario' ? 'selected' : '' ?> value="Veterinario">Veterinario</option>
                    </select>

                    <div class="invalid-feedback">seleccione una opcion</div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>


                </div>
            </form>
            <div id="estadoProceso" style="display: none;"><?php echo $mensajeEstado; ?></div>
        </div>
    </div>

</div>
<script>
    const canvas = document.getElementById('canvasBackground');
    const ctx = canvas.getContext('2d');

    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const numberOfPoints = 100;
    const points = [];

    const colors = ['#FF6F61', '#6B5B95', '#88B04B', '#F7CAC9', '#92A8D1'];

    function getRandomColor() {
        return colors[Math.floor(Math.random() * colors.length)];
    }

    function createPoint() {
        return {
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            size: Math.random() * 5 + 2,
            dx: (Math.random() - 0.5) * 2,
            dy: (Math.random() - 0.5) * 2,
            color: getRandomColor()
        };
    }

    // Inicializar puntos
    for (let i = 0; i < numberOfPoints; i++) {
        points.push(createPoint());
    }

    function drawPoints() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        points.forEach(point => {
            ctx.fillStyle = point.color;
            ctx.beginPath();
            ctx.arc(point.x, point.y, point.size, 0, Math.PI * 2);
            ctx.fill();

            // Actualizar la posición del punto
            point.x += point.dx;
            point.y += point.dy;

            // Rebote en los bordes
            if (point.x < 0 || point.x > canvas.width) point.dx *= -1;
            if (point.y < 0 || point.y > canvas.height) point.dy *= -1;

            // Cambiar color de vez en cuando
            if (Math.random() < 0.02) {
                point.color = getRandomColor();
            }
        });
    }

    function animate() {
        drawPoints();
        requestAnimationFrame(animate);
    }

    animate();
</script>
<script>
    // JavaScript para leer el estado del proceso y ejecutar la lógica correspondiente
    document.addEventListener("DOMContentLoaded", function() {
        var estadoProceso = document.getElementById("estadoProceso").textContent.trim();

        if (estadoProceso === "success") {
            Swal.fire({
                title: "¡Usuario creado con éxito!",
                text: "Presiona el botón para proceder a crear el perfil.",
                icon: "success",
                confirmButtonText: "De acuerdo",
            }).then(function() {
                window.location.href = '/sistema-sanbenito/home/perfil/crear.php?data=<?php echo $encryptedData; ?>';
            });
        }
    });
</script>

<?php
require '../template/footer.php';
?>