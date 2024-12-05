<?php
$titulo = "consultas_hoy";
$nombrepagina = "Registrar Tratamiento";
require '../template/header.php';

use App\Tratamiento;
use App\Mascotas;
use App\Cuenta;
use App\ProgramarTratamiento;

if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);
    parse_str($decrypted_data, $params);

    if (isset($params['id_programacion_tratamiento']) && $params['id_programacion_tratamiento'] && isset($params['id_mascota']) && $params['id_mascota']) {
        $id_programacion_tratamiento = $params['id_programacion_tratamiento'];
        $id_mascota = $params['id_mascota'];
    } else {
        echo "<script>window.history.back();</script>";
        exit;
    }
} else {
    echo "<script>window.history.back();</script>";
    exit;
}

$errores = Tratamiento::getErrores();
$tratamiento = new Tratamiento();
$mascota = Mascotas::find($id_mascota);
$programacion = ProgramarTratamiento::find($id_programacion_tratamiento);

$cuenta = Cuenta::buscarCuentaActiva($mascota->id_propietario);
if (empty($cuenta)) {
    messageError2("No se encontró una cuenta de pago, cree una.");
?>
    <center>
    <form id="crear-cuenta-form">
        <input type="hidden" name="cuenta[id_propietario]" value="<?php echo $mascota->id_propietario; ?>">
        <input type="hidden" name="cuenta[id_personal]" value="<?php echo $personal['id_personal']; ?>">
        <button type="button" class="btn btn-info" onclick="crearCuenta()">
            <i class="bi bi-file-text"></i> Iniciar cuenta
        </button>
    </form>
    </center>
    <script>
        async function crearCuenta() {
            const formData = new FormData(document.getElementById('crear-cuenta-form'));

            try {
                const response = await fetch('/sistema-sanbenito/home/cuenta/crear.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error al crear cuenta: ' + data.error);
                }

            } catch (error) {
                console.error('Error al enviar la solicitud:', error);
                alert('Error al crear cuenta. Intenta de nuevo.');
            }
        }
    </script>
<?php
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tratamiento = new Tratamiento($_POST['tratamiento']);
    $errores = $tratamiento->validar();
    if (empty($errores)) {
        $resultado = $tratamiento->guardar();
        if ($resultado) {
            $tratamiento->cambiarEstado($id_programacion_tratamiento);
            $mensajeEstado = "success";
        }
    }
}

?>

<div class="dashboard-content">
    <center>
        <?php foreach ($errores as $error) : ?>
            <?php messageError2($error); ?>
        <?php endforeach; ?>
    </center>

    <form id="formTratamiento" class="form-consulta" method="post">
        <center>
            <h2>Tratamiento para <?php echo $mascota->nombre; ?></h2>
        </center>
        <br>

        <fieldset>
            <legend>Detalles del Tratamiento</legend>
            <div class="mb-3">
                <label for="peso" class="form-label">Peso (kg)</label>
                <input type="number" step="0.01" name="tratamiento[peso]" class="form-control" id="peso" required value="<?php echo s($tratamiento->peso); ?>">
            </div>
            <div class="mb-3">
                <label for="temperatura" class="form-label">Temperatura (°C)</label>
                <input type="number" step="0.01" name="tratamiento[temperatura]" class="form-control" id="temperatura" required value="<?php echo s($tratamiento->temperatura); ?>">
            </div>
            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" name="tratamiento[observaciones]" id="observaciones" required><?php echo s($tratamiento->observaciones); ?></textarea>
            </div>
        </fieldset>

        <input type="hidden" name="tratamiento[id_programacion_tratamiento]" value="<?php echo $id_programacion_tratamiento; ?>">
        <input type="hidden" name="tratamiento[id_personal]" value="<?php echo $personal['id_personal']; ?>">

        <button type="submit" class="btn btn-primary">Registrar Tratamiento</button>
    </form>

    <div id="estadoProceso" style="display: none;"><?php echo $mensajeEstado ?? ''; ?></div>
</div>
<?php

$data = "id_programacion_tratamiento=$id_programacion_tratamiento&id_mascota=$id_mascota&id_cuenta=$cuenta";
$encryptedData = encryptData($data);
?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var estadoProceso = document.getElementById("estadoProceso").textContent.trim();

        if (estadoProceso === "success") {
            Swal.fire({
                title: "¡Tratamiento registrado con éxito!",
                text: "Presiona el botón para regresar.",
                icon: "success",
                confirmButtonText: "De acuerdo",
            }).then(function() {
                window.location.href = '/sistema-sanbenito/home/medicaciontratamiento/medicacionConsulta.php?data=<?php echo $encryptedData ?>';
            });
        }
    });
</script>

<?php
require '../template/footer.php';
?>
