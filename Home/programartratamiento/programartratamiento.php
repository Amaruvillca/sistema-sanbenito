<?php
$titulo = "Programar Tratamiento";
$nombrepagina = "programartratamiento";
require '../template/header.php';

use App\Consulta;
use App\Mascotas;

if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);
    parse_str($decrypted_data, $params);

    if (isset($params['id_mascota'], $params['id_consulta'])) {
        $id_mascota = $params['id_mascota'];
        $id_consulta = $params['id_consulta'];
    } else {
        echo "<script>window.history.back();</script>";
        exit;
    }
} else {
    echo "<script>window.history.back();</script>";
    exit;
}

$mascotacon = Mascotas::find($id_mascota);
?>

<div class='dashboard-content'>
    <center><h1>Programar tratamiento para <?php echo $mascotacon->nombre ?></h1></center>
    <div class="container">
        <div class="row">
            <!-- Formulario -->
            <div class="col-12 col-md-4">
                <div class="tarjeta-formulario">
                    <h2>Programar tratamiento</h2>
                    <form id="form-medicacion" class="formulario">
                        <div class="campo-formulario">
                            <label for="fecha_programada">Fecha programada</label>
                            <input type="datetime-local" name="fecha_programada" class="form-control" id="fecha_programada" required>
                        </div>
                        <div class="campo-formulario">
                            <input type="hidden" name="id_consulta" id="id_consulta" value="<?php echo $id_consulta ?>">
                            <input type="hidden" name="id_personal" id="id_personal" value="<?php echo $personal['id_personal'] ?>">
                        </div>
                        <div class="boton-formulario">
                            <button type="button" class="btn btn-primary" onclick="agregarTratamiento()">Agregar día de tratamiento</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Tabla -->
            <div class="col-12 col-md-8">
                <div class="tarjeta-formulario medicamentos-registrados">
                    <h2>Días Programados</h2>
                    <table class="table table-striped" id="tabla-tratamientos">
                        <thead>
                            <tr>
                                <th>Día Tratamiento</th>
                                <th>Fecha Programada</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div class="boton-formulario">
                        <button type="button" class="btn btn-primary" onclick="guardarTratamientos()">Guardar tratamientos</button>
                        <a href="/sistema-sanbenito/home/propietarios.php" type="button" class="btn btn-danger" >Cancelar</a>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
    let diaTratamiento = 2; // Día inicial del tratamiento

    // Agregar tratamiento al localStorage y tabla
    function agregarTratamiento() {
    const fechaProgramada = document.getElementById('fecha_programada').value;
    const id_consulta = document.getElementById('id_consulta').value;
    const id_personal = document.getElementById('id_personal').value;

    // Validación de campos vacíos
    if (!fechaProgramada) {
        Swal.fire({
            icon: 'warning',
            title: 'Todos los campos son obligatorios',
        });
        return;
    }

    // Recuperar tratamientos existentes
    const tratamientos = JSON.parse(localStorage.getItem('tratamientos')) || [];

    // Verificar si la fecha ya existe
    const fechaRepetida = tratamientos.some(trat => trat.fechaProgramada === fechaProgramada);
    if (fechaRepetida) {
        Swal.fire({
            icon: 'error',
            title: 'Esta fecha ya está programada',
        });
        return;
    }

    // Verificar si es el mismo día (opcional: dependiendo de tu lógica de negocio)
    const fechaIngresada = new Date(fechaProgramada).toDateString();
    const mismoDia = tratamientos.some(trat => new Date(trat.fechaProgramada).toDateString() === fechaIngresada);
    if (mismoDia) {
        Swal.fire({
            icon: 'error',
            title: 'Ya existe un tratamiento programado para este día',
        });
        return;
    }

    // Crear objeto de tratamiento
    const tratamiento = {
        diaTratamiento,
        fechaProgramada,
        id_consulta,
        id_personal
    };

    // Guardar en localStorage
    tratamientos.push(tratamiento);
    localStorage.setItem('tratamientos', JSON.stringify(tratamientos));

    // Agregar fila a la tabla
    agregarFilaTabla(tratamiento);

    // Incrementar el día del tratamiento
    diaTratamiento++;

    // Limpiar formulario
    document.getElementById('form-medicacion').reset();
}


    // Mostrar fila en la tabla
    function agregarFilaTabla(tratamiento) {
        const tabla = document.querySelector('#tabla-tratamientos tbody');
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${tratamiento.diaTratamiento}</td>
            <td>${tratamiento.fechaProgramada}</td>
            <td><button class="btn btn-danger btn-sm" onclick="eliminarFila(this, '${tratamiento.fechaProgramada}')">Eliminar</button></td>
        `;
        tabla.appendChild(fila);
    }
    function eliminarFila(btn, fechaProgramada) {
        // Elimina la fila de la tabla
        btn.closest('tr').remove();

        // Actualiza el localStorage
        const tratamientos = JSON.parse(localStorage.getItem('tratamientos')) || [];
        const tratamientosActualizados = tratamientos.filter(trat => trat.fechaProgramada !== fechaProgramada);
        localStorage.setItem('tratamientos', JSON.stringify(tratamientosActualizados));
    }

    // Guardar tratamientos en el backend
    function guardarTratamientos() {
    const tratamientos = JSON.parse(localStorage.getItem('tratamientos')) || [];

    if (tratamientos.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No hay tratamientos para guardar',
        });
        return;
    }

    fetch('guardar_tratamiento.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ tratamientos })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: "¡Tratamientos registrados con éxito!",
                icon: "success",
            }).then(() => {
                localStorage.removeItem('tratamientos');
                location.reload();
                window.location.href = '/sistema-sanbenito/home/calendar.php';
            });
        } else {
            Swal.fire({ icon: 'error', title: data.message || 'Error al guardar' });
        }
    })
    .catch(() => Swal.fire({ icon: 'error', title: 'Error de conexión' }));
}


    // Cargar tratamientos del localStorage al cargar la página
    document.addEventListener('DOMContentLoaded', () => {
        const tratamientos = JSON.parse(localStorage.getItem('tratamientos')) || [];
        tratamientos.forEach(agregarFilaTabla);
    });
</script>
Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus exercitationem dolor velit quam nobis obcaecati ducimus dolore! Esse, et! Iusto numquam ab facere eos veniam deleniti blanditiis sapiente laborum molestiae!
<?php require '../template/footer.php'; ?>
