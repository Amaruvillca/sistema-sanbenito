<?php
$titulo = "cirugias_hoy";
$nombrepagina = "Realizar cirugia";
require '../template/header.php';

use App\CirugiaRealizada;
use App\Mascotas;
use App\Cuenta;
use App\Ciruguas;
use App\CirugiaProgramada;

if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);
    parse_str($decrypted_data, $params);

    // Verifica si 'id_propietario' está definido en $params
    if (isset($params['id_cirugia_programada']) && $params['id_cirugia_programada'] && isset($params['id_mascota']) && $params['id_mascota']) {
        $id_cirugia_programada = $params['id_cirugia_programada'];
        $id_mascota = $params['id_mascota'];
        $id_cirugia = $params['id_cirugia'];
    } else {
        // Si no hay 'id_propietario', ir a la página anterior
        echo "<script>window.history.back();</script>";
        exit;
    }
} else {
    // Si no hay 'data' en el GET, ir a la página anterior
    echo "<script>window.history.back();</script>";
    exit;
}
$errores = CirugiaRealizada::getErrores();

$cirugia_realizada = new CirugiaRealizada();
$mascota = Mascotas::find($id_mascota);
$cirugiapro = CirugiaProgramada::find($id_cirugia_programada);
$tipocirugia = Ciruguas::find($cirugiapro->id_cirugia);

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

                const data = await response.json(); // Asegúrate de que el PHP devuelva un JSON
                console.log(data); // Manejar la respuesta aquí

                // Aquí puedes agregar más lógica para manejar la respuesta
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
    $cirugia_realizada = new CirugiaRealizada($_POST['cirealizada']);
    //debuguear($consulta);
    $errores = $cirugia_realizada->validar();
    if (empty($errores)) {
        $resultado = $cirugia_realizada->guardar();
        if ($resultado) {
            $cirugia_realizada->cambiarEstado($id_cirugia_programada);
            // Si el usuario se guarda correctamente, establecemos el mensaje
            $mensajeEstado = "success";
        }
    }
}

?>

<div class="dashboard-content">
    <center> <?php

                foreach ($errores as $error) :
                    messageError2($error);
                endforeach;

                ?></center>

    <form id="formConsulta" class="form-consulta " method="post">
        <center>
            <h2>Cirugia para <?php echo $mascota->nombre ?>-<?php echo $tipocirugia->nombre_cirugia?></h2>
        </center>
        <br>



        <fieldset>
            <legend>Constantes Fisiologicas</legend>
            <div class="mb-3">
                <label for="mucosa" class="form-label">Mucosa</label>
                <input type="text" name="cirealizada[mucosa]" class="form-control" id="mucosa" required value="<?php echo s($cirugia_realizada->mucosa); ?>">
            </div>
            <div class="mb-3">
                <label for="tiempoLlenadoCapilar" class="form-label">Tiempo de Llenado Capilar (segundos)</label>
                <input type="number" step="0.1" name="cirealizada[tiempo_de_llenado_capilar]" class="form-control" id="tiempoLlenadoCapilar" required value="<?php echo s($cirugia_realizada->tiempo_de_llenado_capilar); ?>">
            </div>
            <div class="mb-3">
                <label for="frecuenciaCardiaca" class="form-label">Frecuencia Cardiaca (latidos/minuto)</label>
                <input type="number" name="cirealizada[frecuencia_cardiaca]" class="form-control" id="frecuenciaCardiaca" required value="<?php echo s($cirugia_realizada->frecuencia_cardiaca); ?>">
            </div>
            <div class="mb-3">
                <label for="frecuenciaRespiratoria" class="form-label">Frecuencia Respiratoria (respiraciones/minuto)</label>
                <input type="number" class="form-control" name="cirealizada[frecuencia_respiratoria]" id="frecuenciaRespiratoria" required value="<?php echo s($cirugia_realizada->frecuencia_respiratoria); ?>">
            </div>
            <div class="mb-3">
                <label for="temperatura" class="form-label">Temperatura (°C)</label>
                <input type="number" step="0.01" name="cirealizada[temperatura]" class="form-control" id="temperatura" required value="<?php echo s($cirugia_realizada->temperatura); ?>">
            </div>
            <div class="mb-3">
                <label for="peso" class="form-label">Peso (kg)</label>
                <input type="number" step="0.01" name="cirealizada[peso]" class="form-control" id="peso" required value="<?php echo s($cirugia_realizada->peso); ?>">
            </div>
            <div class="mb-3">
                <label for="pulso" class="form-label">Pulso (latidos/minuto)</label>
                <input type="number" class="form-control" name="cirealizada[pulso]" id="pulso" required value="<?php echo s($cirugia_realizada->pulso); ?>">
            </div>

        </fieldset>


        <fieldset>
            <legend>Observaciones</legend>
            <div class="mb-3">
                <label for="diagnosticoPresuntivo" class="form-label"></label>
                <textarea class="form-control" name="cirealizada[observaciones]" id="diagnosticoPresuntivo" required><?php echo s($cirugia_realizada->observaciones); ?></textarea>
            </div>
        </fieldset>

        <fieldset>
            <legend>Costo</legend>
            <div class="mb-3">
                <label for="costo" class="form-label"></label>
                <input type="number" name="cirealizada[costo]" step="0.01" class="form-control" id="costo" required value="<?php echo s($cirugia_realizada->costo); ?>">
            </div>

        </fieldset>
        <input type="hidden" name="cirealizada[id_cirugia_programada]" value="<?php echo $id_cirugia_programada ?>">
        <input type="hidden" name="cirealizada[id_personal]" value="<?php echo $personal['id_personal'] ?>">
        <input type="hidden" name="cirealizada[id_cuenta]" value="<?php echo  $cuenta ?>">

        <button type="submit" class="btn btn-primary">terminar cirugia</button>
    </form>
    <div id="estadoProceso" style="display: none;"><?php echo $mensajeEstado; ?></div>

</div>

<script>
    // JavaScript para leer el estado del proceso y ejecutar la lógica correspondiente
    document.addEventListener("DOMContentLoaded", function() {
        var estadoProceso = document.getElementById("estadoProceso").textContent.trim();

        if (estadoProceso === "success") {
            Swal.fire({
                title: "¡Cirugia realizada con exito!",
                text: "Presiona el botón para regresar.",
                icon: "success",
                confirmButtonText: "De acuerdo",
            }).then(function() {
                window.location.href = '/sistema-sanbenito/home/cirugias_hoy.php';
            });
        }
    });
</script>
<?php
require '../template/footer.php';
?>