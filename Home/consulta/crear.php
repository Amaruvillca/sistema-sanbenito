<?php

$titulo = "Propietarios";
$nombrepagina = "consulta";
require '../template/header.php';

use App\Mascotas;
use App\Cuenta;
use App\Consulta;

$errores = Consulta::getErrores();
if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);
    parse_str($decrypted_data, $params);

    // Verifica si 'id_propietario' está definido en $params
    if (isset($params['id_propietario']) && $params['id_propietario'] && isset($params['id_mascota']) && $params['id_mascota']) {
        $id_propietario = $params['id_propietario'];
        $id_mascota = $params['id_mascota'];
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
$cuenta = Cuenta::buscarCuentaActiva($id_propietario);
if (empty($cuenta)) {
    messageError2("No se encontró una cuenta de pago, cree una.");
    echo '<div style="text-align: center; margin-top: 20px;">
            <a href="javascript:history.back()" style="text-decoration: none; background-color: #007bff; color: white; padding: 10px 20px; border-radius: 5px; display: inline-block;">
                Volver
            </a>
          </div>';
    exit;
}
$mascota = Mascotas::find($id_mascota);

$consulta = new Consulta();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consulta = new Consulta($_POST['consulta']);
    //debuguear($consulta);
    $errores = $consulta->validar();
    if (empty($errores)) {
        $resultado = $consulta->guardar();
        if ($resultado) {
            // Si el usuario se guarda correctamente, establecemos el mensaje
            $mensajeEstado = "success";
        }
    }
}
?>
<div class='dashboard-content'>
    <center> <?php

                foreach ($errores as $error) :
                    messageError2($error);
                endforeach;

                ?></center>

    <form id="formConsulta" class="form-consulta " method="post">
        <center>
            <h2>Registrar Consulta para <?php echo $mascota->nombre ?></h2>
        </center>
        <br>
        <fieldset>
            <legend>Motivo de la Consulta </legend>
            <div class="mb-3">
                <label for="motivoConsulta" class="form-label"></label>
                <textarea class="form-control" name="consulta[motivo_consulta]" id="motivoConsulta" required><?php echo s($consulta->motivo_consulta); ?></textarea>
            </div>
        </fieldset>
        <fieldset>
            <legend>Informacion de Anamnesis</legend>
            <div class="mb-3 form-check">
                <input type="checkbox" name="consulta[vac_polivalentes]" value="<?php echo "1"; ?>" class="form-check-input" id="vacPolivalentes" <?php if ($consulta->vac_polivalentes == "1") echo "checked" ?>>
                <label class="form-check-label" for="vacPolivalentes">Vacunas Polivalentes</label>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="consulta[vac_rabia]" value="<?php echo "1"; ?>" class="form-check-input" id="vacRabia" <?php if ($consulta->vac_rabia == "1") echo "checked" ?>>
                <label class="form-check-label" for="vacRabia">Vacuna contra la Rabia</label>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="consulta[desparasitacion]" value="<?php echo "1"; ?>" class="form-check-input" id="desparasitacion" <?php if ($consulta->desparasitacion == "1") echo "checked" ?>>
                <label class="form-check-label" for="desparasitacion">Desparasitación</label>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="consulta[esterelizado]" value="<?php echo "1"; ?>" class="form-check-input" id="esterelizado" <?php if ($consulta->esterelizado == "1") echo "checked" ?>>
                <label class="form-check-label" for="esterelizado">Esterilización</label>
            </div>
            <div class="mb-3">
                <label for="informacion" class="form-label">Información Adicional</label>
                <textarea class="form-control" name="consulta[informacion]" id="informacion"><?php echo s($consulta->informacion); ?></textarea>
            </div>
        </fieldset>

        <fieldset>
            <legend>Constantes Fisiologicas</legend>
            <div class="mb-3">
                <label for="mucosa" class="form-label">Mucosa</label>
                <input type="text" name="consulta[mucosa]" class="form-control" id="mucosa" required value="<?php echo s($consulta->mucosa); ?>">
            </div>
            <div class="mb-3">
                <label for="tiempoLlenadoCapilar" class="form-label">Tiempo de Llenado Capilar (segundos)</label>
                <input type="number" step="0.1" name="consulta[tiempo_de_llenado_capilar]" class="form-control" id="tiempoLlenadoCapilar" required value="<?php echo s($consulta->tiempo_de_llenado_capilar); ?>">
            </div>
            <div class="mb-3">
                <label for="frecuenciaCardiaca" class="form-label">Frecuencia Cardiaca (latidos/minuto)</label>
                <input type="number" name="consulta[frecuencia_cardiaca]" class="form-control" id="frecuenciaCardiaca" required value="<?php echo s($consulta->frecuencia_cardiaca); ?>">
            </div>
            <div class="mb-3">
                <label for="frecuenciaRespiratoria" class="form-label">Frecuencia Respiratoria (respiraciones/minuto)</label>
                <input type="number" class="form-control" name="consulta[frecuencia_respiratoria]" id="frecuenciaRespiratoria" required value="<?php echo s($consulta->frecuencia_respiratoria); ?>">
            </div>
            <div class="mb-3">
                <label for="temperatura" class="form-label">Temperatura (°C)</label>
                <input type="number" step="0.01" name="consulta[temperatura]" class="form-control" id="temperatura" required value="<?php echo s($consulta->temperatura); ?>">
            </div>
            <div class="mb-3">
                <label for="peso" class="form-label">Peso (kg)</label>
                <input type="number" step="0.01" name="consulta[peso]" class="form-control" id="peso" required value="<?php echo s($consulta->peso); ?>">
            </div>
            <div class="mb-3">
                <label for="pulso" class="form-label">Pulso (latidos/minuto)</label>
                <input type="number" class="form-control" name="consulta[pulso]" id="pulso" required value="<?php echo s($consulta->pulso); ?>">
            </div>
            <div class="mb-3">
                <label for="turgenciaPiel" class="form-label">Turgencia de Piel</label>
                <input type="text" class="form-control" name="consulta[turgencia_de_piel]" id="turgenciaPiel" required value="<?php echo s($consulta->turgencia_de_piel); ?>">
            </div>
        </fieldset>

        <fieldset>
            <legend>Examen Clínico</legend>
            <div class="mb-3">
                <label for="actitud" class="form-label">Actitud</label>
                <select class="form-select" id="actitud" name="consulta[actitud]" required>
                <option  disabled value="" selected>-- Seleccione --</option>
                    <option <?php if ($consulta->actitud == "activo") echo "selected" ?> value="activo">Activo</option>
                    <option <?php if ($consulta->actitud == "letargico") echo "selected" ?> value="letargico">Letárgico</option>
                    <option <?php if ($consulta->actitud == "pasivo") echo "selected" ?> value="pasivo">Pasivo</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="gangliosLinfaticos" class="form-label">Ganglios Linfáticos</label>
                <input type="text" class="form-control" name="consulta[ganglios_linfaticos]" id="gangliosLinfaticos" required value="<?php echo s($consulta->ganglios_linfaticos); ?>">
            </div>
            <div class="mb-3">
                <label for="hidratacion" class="form-label">Hidratación</label>
                <input type="text" class="form-control" name="consulta[hidratacion]" id="hidratacion" required value="<?php echo s($consulta->hidratacion); ?>">
            </div>


        </fieldset>
        <fieldset>
            <legend>Diagnóstico Presuntivo</legend>
            <div class="mb-3">
                <label for="diagnosticoPresuntivo" class="form-label"></label>
                <textarea class="form-control" name="consulta[Diagnostico_presuntivo]" id="diagnosticoPresuntivo" required><?php echo s($consulta->Diagnostico_presuntivo); ?></textarea>
            </div>
        </fieldset>

        <fieldset>
            <legend>Costo y Estado de la Consulta</legend>
            <div class="mb-3">
                <label for="costo" class="form-label">Costo</label>
                <input type="number" name="consulta[costo]" step="0.01" class="form-control" id="costo" required value="<?php echo s($consulta->costo); ?>">
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" name="consulta[estado]" id="estado" required>
                    <option  disabled value="" selected>-- Seleccione --</option>
                    <option <?php if ($consulta->estado == "pendiente") echo "selected" ?> value="pendiente">Pendiente</option>
                    <option <?php if ($consulta->estado == "completada") echo "selected" ?> value="completada">Completada</option>
                </select>
            </div>
        </fieldset>
        <input type="hidden" name="consulta[id_mascota]" value="<?php echo $id_mascota ?>">
        <input type="hidden" name="consulta[id_personal]" value="<?php echo $personal['id_personal'] ?>">
        <input type="hidden" name="consulta[id_cuenta]" value="<?php echo  $cuenta ?>">

        <button type="submit" class="btn btn-primary">Registrar Consulta</button>
    </form>
    <div id="estadoProceso" style="display: none;"><?php echo $mensajeEstado; ?></div>
</div>
<?php

$data = "id_propietario=$id_propietario&id_mascota=$id_mascota&id_cuenta=$cuenta";
$encryptedData = encryptData($data);
?>
<script>
    // JavaScript para leer el estado del proceso y ejecutar la lógica correspondiente
    document.addEventListener("DOMContentLoaded", function() {
        var estadoProceso = document.getElementById("estadoProceso").textContent.trim();

        if (estadoProceso === "success") {
            Swal.fire({
                title: "¡Consulta resgistrada con exito!",
                text: "Presiona el botón para regresar.",
                icon: "success",
                confirmButtonText: "De acuerdo",
            }).then(function() {
                window.location.href = '/sistema-sanbenito/home/medicacion/medicacionConsulta.php?data=<?php echo $encryptedData ?>';
            });
        }
    });
</script>

<?php
require '../template/footer.php';
?>