<?php
$titulo = "Cirugias";
$nombrepagina = "Cirugias";
require '../template/header.php';
verificaAcceso();

use App\Ciruguas;

$cirugias = Ciruguas::all();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cirugia=$_POST['id_cirugia'];
    $resultado = Ciruguas::borrar($id_cirugia);
    if ($resultado) {
        // Si el usuario se guarda correctamente, establecemos el mensaje
        $mensajeEstado = "success";
     } else{
         $mensajeEstado = "nosuccess";
     }
}

?>

<div class='dashboard-content'>
    <div class="container">
        <div class="row">
            <h1><i class="bi bi-heart-pulse-fill"></i> Cirugías</h1>
            <div class="row mb-3">
                <div class="col-md-12 text-end">
                    <a href="/sistema-sanbenito/home/setting/cirugias/crear.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle-fill"></i> Añadir nueva cirugía
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered dt-head-center" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre de la Cirugía</th>
                            <th>Descripción</th>
                            <th>Fecha de Registro</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas de datos -->
                        <?php
                        $contador = 1;
                        if (!empty($cirugias)) {
                            foreach ($cirugias as $cirugia) {
                        ?>
                                <tr>
                                    <td><?php echo $contador++; ?></td>
                                    <td><?php echo $cirugia->nombre_cirugia; ?></td>
                                    <td><?php echo $cirugia->descripcion; ?></td>
                                    <td><?php echo $cirugia->fecha_registro; ?></td>


                                    <td class="text-center">
                                        <label class="switch">
                                            <input type="checkbox" id="estadoSwitch<?php echo $cirugia->id_cirugia; ?>" <?php echo $cirugia->estado ? 'checked' : ''; ?>>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <?php
                                        $id_cirugia = $cirugia->id_cirugia;
                                        $data = "id_cirugia=$id_cirugia";
                                        $encryptedData = encryptData($data);
                                        ?>
                                       
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                        <a href="/sistema-sanbenito/home/setting/cirugias/editar.php?data=<?php echo $encryptedData; ?>" class="btn btn-info">
                                            <i class="bi bi-pencil-fill"></i> Editar
                                        </a>
                                            
                                            <form method="post" class="delete-form">
                                            <input type="hidden" name="id_cirugia" value="<?php echo $cirugia->id_cirugia ?>">
                                            <button type="button" class="btn btn-danger delete-btn">
                                                <i class="bi bi-trash-fill"></i> Eliminar
                                            </button>
                                        </form>
                                        </div>

                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='7'>No se encontraron cirugías.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="estadoProceso" style="display: none;"><?php echo $mensajeEstado; ?></div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<!-- Inicialización de DataTables -->
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "pageLength": 10,
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });
    });
    // Manejo de eliminación con confirmación
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form'); // Encuentra el formulario más cercano

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success mx-2",
                    cancelButton: "btn btn-danger mx-2"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "¿Estás seguro de eliminar al cirugia?",
                text: "¡No podrás revertir esto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminarlo",
                cancelButtonText: "No, cancelar",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Envía el formulario si el usuario confirma
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado",
                        text: "El cirugia no fue eliminado",
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
                text: "propietario ha sido eliminado exitosamente.",
                icon: "success",
                confirmButtonText: "De acuerdo",
            }).then(function() {
                window.location.href = '/sistema-sanbenito/home/setting/cirugias.php';
            });
        }else{
            if (estadoProceso === "nosuccess"){
                Swal.fire({
                title: "¡Ups algo salio mal!",
                text: "No se pudo eliminar al propietario",
                icon: "error",
                confirmButtonText: "De acuerdo",
            });
            }
        }
    });
</script>
<?php
require '../template/footer.php';
?>