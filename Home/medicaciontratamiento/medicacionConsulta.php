<?php
$titulo = "consultas_hoy";
$nombrepagina = "medicacion";
require '../template/header.php';

use App\Tratamiento;
use App\Mascotas;

if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);
    parse_str($decrypted_data, $params);

    if (isset($params['id_programacion_tratamiento'], $params['id_mascota'], $params['id_cuenta']) && $params['id_programacion_tratamiento'] && $params['id_mascota'] && $params['id_cuenta']) {
        $id_programacion_tratamiento = $params['id_programacion_tratamiento'];
        $id_mascota = $params['id_mascota'];
        $id_cuenta = $params['id_cuenta'];
    } else {
        echo "<script>window.history.back();</script>";
        exit;
    }
} else {
    echo "<script>window.history.back();</script>";
    exit;
}

$tratamiento = Tratamiento::buscarTratamiento($id_programacion_tratamiento);
$mascotacon = Mascotas::find($id_mascota);
$id_tratamiento = $tratamiento[0]->id_tratamiento;
$mascota_nombre = $mascotacon->nombre ?? 'Mascota';
?>

<div class='dashboard-content'>
    <center><h1>Medicación para <?php echo $mascota_nombre; ?></h1></center>
    <div class="container">
        <div class="row">
            <!-- Formulario -->
            <div class="col-12 col-md-4">
                <div class="tarjeta-formulario">
                    <h2>Agregar Medicación</h2>
                    <form id="form-medicacion" class="formulario">
                        <div class="campo-formulario">
                            <label for="nombre_medicacion">Nombre del Medicamento</label>
                            <input type="text" name="nombre_medicacion" class="form-control" id="nombre_medicacion" required>
                        </div>
                        <div class="campo-formulario">
                            <label for="via">Vía de Administración</label>
                            <select class="form-control" name="via" id="via" required>
                                <option selected disabled value="">-- Seleccione --</option>
                                <option value="oral">Oral</option>
                                <option value="intravenosa">Intravenosa</option>
                                <option value="subcutánea">Subcutánea</option>
                                <option value="intramuscular">Intramuscular</option>
                                <option value="tópica">Tópica</option>
                                <option value="rectal">Rectal</option>
                            </select>
                        </div>
                        <div class="campo-formulario">
                            <label for="costo">Costo</label>
                            <input type="number" class="form-control" step="0.01" name="costo" id="costo" required>
                            <input type="hidden" name="id_tratamiento" id="id_tratamiento" value="<?php echo $id_tratamiento ?>">
                            <input type="hidden" name="id_cuenta" id="id_cuenta" value="<?php echo $id_cuenta ?>">
                        </div>
                        <div class="boton-formulario">
                            <button type="button" class="btn btn-primary" onclick="agregarMedicacion()">Agregar Medicación</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Tabla -->
            <div class="col-12 col-md-8">
                <div class="tarjeta-formulario medicamentos-registrados">
                    <h2>Medicamentos Suministrados</h2>
                    <table class="table table-striped" id="tabla-medicamentos">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Vía</th>
                                <th>Costo</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div class="boton-formulario">
                        <button type="button" class="btn btn-primary" onclick="guardarMedicacion()">Guardar Medicación</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Agregar medicación al localStorage y tabla
    function agregarMedicacion() {
        const nombreMedicacion = document.getElementById('nombre_medicacion').value.trim();
        const via = document.getElementById('via').value;
        const costo = parseFloat(document.getElementById('costo').value).toFixed(2);
        const fecha = new Date().toLocaleDateString('es-ES');
        const id_tratamiento = document.getElementById('id_tratamiento').value;
        const id_cuenta = document.getElementById('id_cuenta').value;

        if (!nombreMedicacion || !via || isNaN(costo)) {
            Swal.fire({
                icon: 'warning',
                title: 'Todos los campos son obligatorios',
            });
            return;
        }

        const medicamento = { nombreMedicacion, via, costo, fecha, id_tratamiento, id_cuenta };
        const medicamentos = JSON.parse(localStorage.getItem('medicamentos')) || [];
        medicamentos.push(medicamento);
        localStorage.setItem('medicamentos', JSON.stringify(medicamentos));
        agregarFilaTabla(medicamento);
        document.getElementById('form-medicacion').reset();
    }

    function agregarFilaTabla(medicamento) {
        const tabla = document.querySelector('#tabla-medicamentos tbody');
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${medicamento.nombreMedicacion}</td>
            <td>${medicamento.via}</td>
            <td>${medicamento.costo} Bs.</td>
            <td>${medicamento.fecha}</td>
            <td><button class="btn btn-danger btn-sm" onclick="eliminarFila(this, '${medicamento.nombreMedicacion}')">Eliminar</button></td>
        `;
        tabla.appendChild(fila);
    }

    function eliminarFila(btn, nombreMedicacion) {
        btn.closest('tr').remove();
        const medicamentos = JSON.parse(localStorage.getItem('medicamentos')) || [];
        const medicamentosActualizados = medicamentos.filter(med => med.nombreMedicacion !== nombreMedicacion);
        localStorage.setItem('medicamentos', JSON.stringify(medicamentosActualizados));
    }

    function guardarMedicacion() {
        const medicamentos = JSON.parse(localStorage.getItem('medicamentos')) || [];
        if (medicamentos.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No hay medicamentos para guardar',
            });
            return;
        }

        fetch('medicacion.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ medicamentos }),
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: "¡Medicación registrada con éxito!",
                    text: "Presiona el botón para regresar.",
                    icon: "success",
                }).then(() => {
                    localStorage.removeItem('medicamentos');
                    location.reload();
                    window.location.href = '/sistema-sanbenito/home/consultas_hoy.php';
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Error al guardar' });
            }
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Error de conexión' }));
    }

    document.addEventListener('DOMContentLoaded', () => {
        const medicamentos = JSON.parse(localStorage.getItem('medicamentos')) || [];
        medicamentos.forEach(agregarFilaTabla);
    });
</script>
Lorem ipsum dolor sit amet consectetur adipisicing elit. Officiis recusandae, deserunt aut doloribus nobis atque! Fugit architecto, mollitia rerum nulla aperiam, voluptatem quasi dolore, dolorum perferendis quisquam unde tenetur veritatis.
<?php require '../template/footer.php'; ?>
