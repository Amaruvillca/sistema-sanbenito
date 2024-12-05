<?php

$titulo = "Propietarios";
$nombrepagina = "ver más";
require '../template/header.php';


use App\Propietarios;
use App\Perfil;
use App\Mascotas;
use App\Cuenta;

error_reporting(0);
if (isset($_GET['data'])) {
    $encrypted_data = $_GET['data'];
    $decrypted_data = decryptData($encrypted_data);
    parse_str($decrypted_data, $params);

    // Verifica si 'id_propietario' está definido en $params
    if (isset($params['id_propietario']) && $params['id_propietario']) {
        $b = $params['id_propietario'];
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

$propietarios = Propietarios::find($b);
$perfil = Perfil::find($propietarios->id_personal);
$mascotas = Mascotas::all();

$cuenta = Cuenta::buscarCuentaActiva($b);

//debuguear($cuenta);

$historial_cuenta = Cuenta::all();
//debuguear($$historial_cuenta);
$mascotas_encontradas = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_mascota'];
    $mascota = Mascotas::find($id);
    $rutaImagen = '../../imagemascota/';
    $resultado = Mascotas::borrar($id);
    if ($resultado) {
        // Si el usuario se guarda correctamente, establecemos el mensaje
        if ($mascota->imagen_mascota != "mascota.png") {
            //borrar imagen
            unlink($rutaImagen . $mascota->imagen_mascota);
        }
        $mensajeEstado = "success";
    } else {
        $mensajeEstado = "nosuccess";
    }
}
?>

<div class='dashboard-content'>
    <div class='container'>
        <div class="row">




            <div class="col-12">
                <a href="/sistema-sanbenito/home/propietarios.php" class="btn btn-secondary" style="border-radius: 25px;"> <i class="bi bi-arrow-left"></i> Volver</a>
                <center>
                    <h3 class="card-title">Datos del Propietario</h3>
                </center>
            </div>

            <div class="col-12">
                <div class="card-propietarios card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4 col-ms-3 d-grid align-items-center justify-content-center mb-4">
                                <p class="card-text"><strong>Nombre(s): </strong> <?php echo $propietarios->nombres ?></p>
                                <p class="card-text"><strong>Apellidos: </strong> <?php echo $propietarios->apellido_paterno . " " . $propietarios->apellido_materno ?></p>
                                <p class="card-text"><strong>Correo: </strong> <?php echo $propietarios->email ?></p>
                                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#historialPagosModal">
                                    <i class="bi bi-eye-fill"></i> Historial de pagos
                                </button>


                            </div>
                            <div class="col-12 col-md-4 col-ms-3 d-grid align-items-center justify-content-center mb-4">
                                <p class="card-text"><strong>Celular: </strong> <?php echo $propietarios->num_celular ?></p>
                                <p class="card-text"><strong>Celular Sec. : </strong> <?php echo ($propietarios->num_celular_secundario == '') ? 'S/N' : $propietarios->num_celular_secundario; ?></p>
                                <p class="card-text"><strong>Número de carnet: </strong> <?php echo $propietarios->num_carnet ?></p>
                                <p class="card-text"><strong>Dirección: </strong> <?php echo $propietarios->direccion ?></p>



                            </div>
                            <div class="col-12 col-md-4 col-ms-3 d-grid align-items-center justify-content-center mb-4">

                                <p class="card-text"><strong>Fecha de registro: </strong> <?php echo $propietarios->fecha_registro ?></p>
                                <p class="card-text"><strong>Registrado por: </strong> <?php echo $perfil->nombres . " " . $perfil->apellido_paterno . " " . $perfil->apellido_materno ?></p>
                                <?php
                                if (!empty($cuenta)) {
                                    $cuentas = Cuenta::find($cuenta) ?? ""; //aqui hay error

                                    $saldo = Cuenta::saldoTotal($cuenta) ?? 0;
                                ?>
                                    <center>
                                        <button type="button" class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#pagarCuentaModal">
                                            <i class="bi bi-file-text"></i>
                                            Pagar cuenta
                                        </button>
                                    </center>

                                    <center>
                                        <form id="eliminar-cuenta-form">
                                            <input type="hidden" name="cuenta[id_cuenta]" value="<?php echo htmlspecialchars($cuenta); ?>">
                                            <button type="button" class="btn btn-danger" onclick="eliminarCuenta()">
                                                <i class="bi bi-x-circle-fill"></i> Cancelar cuenta
                                            </button>
                                        </form>
                                    </center>


                                    <script>
                                        async function eliminarCuenta() {
                                            const formData = new FormData(document.getElementById('eliminar-cuenta-form'));

                                            // Mostrar la alerta de confirmación
                                            const {
                                                value: confirmar
                                            } = await Swal.fire({
                                                title: '¿Estás seguro?',
                                                text: "¡No podrás recuperar esta cuenta después de eliminarla!",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#d33',
                                                cancelButtonColor: '#3085d6',
                                                confirmButtonText: 'Sí, eliminar',
                                                cancelButtonText: 'Cancelar'
                                            });

                                            // Si el usuario confirma la eliminación
                                            if (confirmar) {
                                                try {
                                                    const response = await fetch('/sistema-sanbenito/home/cuenta/eliminar.php', {
                                                        method: 'POST',
                                                        body: formData
                                                    });

                                                    if (!response.ok) {
                                                        throw new Error('Error en la red');
                                                    }

                                                    const data = await response.json(); // Asegúrate de que el PHP devuelva un JSON

                                                    if (data.success) {
                                                        Swal.fire({
                                                            icon: 'success',
                                                            title: 'Eliminado!',
                                                            text: 'La cuenta ha sido eliminada con éxito.',
                                                        }).then(() => {
                                                            location.reload(); // Refrescar la página después de eliminar
                                                        });
                                                    } else {
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Error',
                                                            text: 'No se pudo eliminar la cuenta: ' + data.error,
                                                        });
                                                    }

                                                } catch (error) {
                                                    console.error('Error al enviar la solicitud:', error);
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Error',
                                                        text: 'Error al eliminar la cuenta. Intenta de nuevo.',
                                                    });
                                                }
                                            } else {
                                                // Mostrar alerta de cancelación
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Cancelado',
                                                    text: 'La eliminación de la cuenta ha sido cancelada.',
                                                });
                                            }
                                        }
                                    </script>



                                <?php
                                } else {
                                ?>
                                    <center>

                                        <form id="crear-cuenta-form">
                                            <input type="hidden" name="cuenta[id_propietario]" value="<?php echo $b; ?>">
                                            <input type="hidden" name="cuenta[id_personal]" value="<?php echo $personal['id_personal']; ?>">
                                            <button type="button" class="btn btn-info" onclick="crearCuenta()">
                                                <i class="bi bi-file-text"></i> Iniciar cuenta
                                            </button>
                                        </form>

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


                                    </center>
                                <?php
                                }
                                ?>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <center>
                    <h3 class="card-title">Mascotas Asociadas</h3>
                </center>
            </div>

            <div class="col-12 d-flex justify-content-end mb-4">
                <div class="d-flex align-items-center">
                    <label for="busqueda-mascotas" class="mb-0 me-2">Buscar:</label>
                    <input type="search" id="busqueda-mascotas" class="form-control" placeholder="Nombre o código mascota">
                </div>
            </div>
            <?php
            foreach ($mascotas as $key => $mascota) {
                if ($mascota->id_propietario == $b) {
                    $mascotas_encontradas = true;
            ?>
                    <div class="col-12 col-md-4 col-ms-6 col-sm-6 mb-4 card-mascota">
                        <div class='card-mascota card'>
                            <div class='card-header'>
                                <h5><?php echo $mascota->nombre; ?></h5>
                            </div>
                            <div class='card-body'>
                                <div class="col-12 mb-1">
                                    <center>
                                        <img src="/sistema-sanbenito/imagemascota/<?php echo $mascota->imagen_mascota; ?>" alt="Imagen de mascota" class="img-fluid rounded-circle shadow-sm" style="width: 180px; height: 180px; object-fit: cover; transition: transform 0.3s ease; border: 4px solid #005C43;">
                                    </center>
                                </div>
                                <center>
                                    <p><?php echo $mascota->codigo_mascota; ?></p>
                                </center>
                                <center>
                                    <p><strong>Edad:</strong> <?php
                                                                $fecha_nacimiento = new DateTime($mascota->fecha_nacimiento);
                                                                $hoy = new DateTime();
                                                                $edad = $hoy->diff($fecha_nacimiento);
                                                                echo $edad->y . ' años y ';
                                                                echo $edad->m . ' meses ';
                                                                ?></p>
                                </center>
                                <div class="row">
                                    <?php
                                    $id_propietario = $propietarios->id_propietario;
                                    $id_mascota = $mascota->id_mascota;
                                    $data = "id_propietario=$id_propietario&id_mascota=$id_mascota";
                                    $encryptedData = encryptData($data);
                                    ?>
                                    <div class="col-6 mb-2">
                                        <a href="/sistema-sanbenito/home/mascotas/editar.php?data=<?php echo $encryptedData; ?>" class="btn btn-success btn-sm w-100"> <i class="bi bi-pencil-square"></i> Editar</a>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <form method="post">
                                            <input type="hidden" name="id_mascota" value="<?php echo $mascota->id_mascota; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm w-100 delete-btn"><i class="bi bi-trash-fill"></i> Eliminar</button>
                                        </form>

                                    </div>
                                    <div class="col-12 mb-2">
                                        <a href="/sistema-sanbenito/home/vermas/mascotas.php?data=<?php echo $encryptedData; ?>" class="btn btn-info btn-sm w-100"><i class="bi bi-eye-fill"></i> Ver más</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
            }
            if (!$mascotas_encontradas) {
                ?>
                <div class="col-12">
                    <hr>
                    <hr>
                    <center>
                        No se encontraron mascotas asociadas a este propietario.
                    </center>
                    <hr>
                    <hr>

                </div>
            <?php
            }
            ?>
        </div>
        <div id="estadoProceso" style="display: none;"><?php echo $mensajeEstado; ?></div>
        <script>
            document.getElementById('busqueda-mascotas').addEventListener('keyup', function() {
                //Obtener el valor de búsqueda
                let searchQuery = this.value.toLowerCase();
                // Obtener todas las tarjetas de mascotas
                let mascotas = document.querySelectorAll('.card-mascota');
                // Recorrer todas las tarjetas y ocultar las que no coincidan
                mascotas.forEach(function(mascota) {
                    // Obtener el nombre y el código de la mascota en minúsculas
                    let nombreMascota = mascota.querySelector('h5').textContent.toLowerCase();
                    let codigoMascota = mascota.querySelector('p').textContent.toLowerCase();

                    // Comprobar si el valor de búsqueda coincide con el nombre o código
                    if (nombreMascota.includes(searchQuery) || codigoMascota.includes(searchQuery)) {
                        // Mostrar la tarjeta si coincide
                        mascota.style.display = '';
                    } else {
                        // Ocultar la tarjeta si no coincide
                        mascota.style.display = 'none';
                    }
                });
            });
        </script>
        <script>
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevenir el envío inmediato del formulario

                    const form = this.closest('form'); // Encuentra el formulario más cercano

                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: "btn btn-success mx-2",
                            cancelButton: "btn btn-danger mx-2"
                        },
                        buttonsStyling: false
                    });

                    swalWithBootstrapButtons.fire({
                        title: "¿Estás seguro de eliminar esta mascota?",
                        text: "Esta acción no se puede deshacer.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "No, cancelar",
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Envía el formulario si el usuario confirma
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            swalWithBootstrapButtons.fire({
                                title: "Cancelado",
                                text: "La mascota no fue eliminada.",
                                icon: "error",
                                confirmButtonText: "De acuerdo",
                            });
                        }
                    });
                });
            });
        </script>
        <script>
            // JavaScript para leer el estado del proceso y ejecutar la lógica correspondiente
            document.addEventListener("DOMContentLoaded", function() {
                var estadoProceso = document.getElementById("estadoProceso").textContent.trim();

                if (estadoProceso === "success") {
                    Swal.fire({
                        title: "¡Eliminado!",
                        text: "La mascota ha sido eliminado exitosamente.",
                        icon: "success",
                        confirmButtonText: "De acuerdo",
                    }).then(function() {
                        window.location.href = '/sistema-sanbenito/home/vermas/propietarios.php?data=<?php echo $encryptedData; ?>';
                    });
                } else {
                    if (estadoProceso === "nosuccess") {
                        Swal.fire({
                            title: "¡Ups algo salio mal!",
                            text: "No se pudo eliminar a la mascota",
                            icon: "error",
                            confirmButtonText: "De acuerdo",
                        });
                    }
                }
            });
        </script>

    </div>
    <?php
    $id_propietario = $propietarios->id_propietario;
    $data = "id_propietario=$id_propietario";
    $encryptedData = encryptData($data);
    ?>


    <!-- Botón flotante -->
    <a href="/sistema-sanbenito/home/mascotas/crear.php?data=<?php echo $encryptedData; ?>" class="btn-flotante"><i class="bi bi-plus"></i></a>
</div>
<?php if (!empty($cuenta)): ?>
    <div class="modal fade" id="pagarCuentaModal" tabindex="-1" aria-labelledby="pagarCuentaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pagarCuentaModalLabel">Detalles de la cuenta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="formCuenta" action="/sistema-sanbenito/home/cuenta/pagar.php" method="post">
                        <input type="hidden" name="cuenta[id_cuenta]" value="<?php echo $cuentas->id_cuenta ?>">
                        <div class="mb-3">
                            <label for="nombreCompleto" class="form-label">Nombre Completo</label>
                            <input type="text" name="cuenta[nombre_completo]" class="form-control" id="nombreCompleto" value="<?php echo $cuentas->nombre_completo ?>">
                        </div>
                        <div class="mb-3">
                            <label for="numCarnet" class="form-label">Número de Carnet</label>
                            <input type="text" name="cuenta[num_carnet]" class="form-control" value="<?php echo $cuentas->num_carnet ?>" id="numCarnet">
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select name="cuenta[estado]" class="form-control" id="nom_vac" class="form-control form-select" required>
                                <option <?php if ($cuentas->estado == "pagada") echo "selected" ?> value="pagada">Pagada</option>
                                <option <?php if ($cuentas->estado == "adelanto") echo "selected" ?> value="adelanto">Adelanto</option>
                                <option <?php if ($cuentas->estado == "nopagada") echo "selected" ?> value="nopagada">No Pagada</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="saldoTotal" class="form-label">Saldo Total</label>
                            <input type="number" name="cuenta[saldo_total]" class="form-control" value="<?php echo $saldo ?>" id="saldoTotal" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="montoPagado" class="form-label">Monto Pagado</label>
                            <input type="number" name="cuenta[monto_pagado]" class="form-control" value="<?php echo $cuentas->monto_pagado ?>" id="montoPagado">
                        </div>
                        <div class="mb-3">
                            <label for="cambio" class="form-label">Cambio: Bs.</label>
                            <input type="number" class="form-control" id="cambio" value="0" readonly>
                        </div>
                        <input type="hidden" name="cuenta[fecha_apertura]" value="<?php echo $cuentas->fecha_apertura ?>">
                        <input type="hidden" name="cuenta[id_personal]" value="<?php echo $cuentas->id_personal ?>">
                        <input type="hidden" name="cuenta[id_propietario]" value="<?php echo $cuentas->id_propietario ?>">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-info" id="confirmarPago">Confirmar Pago</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
<?php endif; ?>
<!-- Modal -->
<div class="modal fade" id="historialPagosModal" tabindex="-1" aria-labelledby="historialPagosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historialPagosModalLabel">Historial de Pagos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>C.I.</th>
                            <th>Fecha Apertura</th>
                            <th>Fecha Pagada</th>
                            <th>Saldo Total</th>
                            <th>Monto Pagado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $contador_registros = 0;
                        $atencion_servicio_encontradas = false;

                        foreach ($historial_cuenta as $historial_cuentas) {
                            // Verificar si el registro corresponde al propietario
                            if ($historial_cuentas->id_propietario == $b && $historial_cuentas->estado == "pagada") {
                                $atencion_servicio_encontradas = true;

                                // Mostrar solo los primeros 30 registros
                                if ($contador_registros < 30) {
                        ?>
                                    <tr>
                                        <td><?php echo $historial_cuentas->id_cuenta; ?></td>
                                        <td><?php echo $historial_cuentas->nombre_completo; ?></td>
                                        <td><?php echo $historial_cuentas->num_carnet; ?></td>
                                        <td><?php echo $historial_cuentas->fecha_apertura; ?></td>
                                        <td><?php echo $historial_cuentas->fecha_pago; ?></td>
                                        <td><?php echo $historial_cuentas->saldo_total . " Bs."; ?></td>
                                        <td><?php echo $historial_cuentas->monto_pagado . " Bs."; ?></td>
                                        <td>
                                        <?php
                                                            $id_cuentaaa = $historial_cuentas->id_cuenta;
                                                            $dataaa = "id_cuenta=$id_cuentaaa";
                                                            $encryptedDataaa = encryptData($dataaa);
                                                            ?>
                                            <a href="/sistema-sanbenito/home/report/comprobante.php?data=<?php echo $encryptedDataaa ?>" class="btn btn-sm btn-danger" target="_blank">
                                                <i class="bi bi-file-earmark-pdf-fill"></i> Comprobante
                                            </a>
                                            

                                        </td>
                                    </tr>
                        <?php
                                    $contador_registros++;  // Incrementar el contador
                                } else {
                                    break;  // Detener el bucle después de mostrar 30 registros
                                }
                            }
                        }

                        if (!$atencion_servicio_encontradas) {
                            echo '<tr><th colspan="8"><center>No se encontraron pagos</center></th></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const saldoTotalInput = document.getElementById('saldoTotal');
        const montoPagadoInput = document.getElementById('montoPagado');
        const cambioInput = document.getElementById('cambio');

        function calcularCambio() {
            const saldoTotal = parseFloat(saldoTotalInput.value) || 0; // Valor del saldo total
            const montoPagado = parseFloat(montoPagadoInput.value) || 0; // Valor del monto pagado
            const cambio = montoPagado - saldoTotal; // Cálculo del cambio

            // Mostrar el cambio, evitando valores negativos
            cambioInput.value = cambio >= 0 ? cambio.toFixed(2) : 0;
        }

        // Agregar eventos de entrada a los campos
        montoPagadoInput.addEventListener('input', calcularCambio);
    });
</script>



<?php
require '../template/footer.php';
?>