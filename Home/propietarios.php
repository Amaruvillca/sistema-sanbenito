<?php
$titulo = "Propietarios";
$nombrepagina = "Propietarios";
require 'template/header.php';

use App\Propietarios;

$propietarios = Propietarios::all();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $resultado = Propietarios::borrar($id);
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
            <h1><i class="bi bi-person-fill"></i> Propietarios</h1>
            <div class="row mb-3">
                <div class="col-md-12 text-end">
                    <a href="/sistema-sanbenito/home/propietarios/crear.php" class="btn btn-primary">
                        <i class="bi bi-person-plus-fill"></i> Añadir nuevo Propietario
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered dt-head-center" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombres y Apellidos</th>
                            <th>Celular</th>
                            <th>Cel.Sec.</th>
                            <th>Carnet</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas de datos -->
                        <?php $c = 1;
                        foreach ($propietarios as $key => $propietario): ?>
                            <tr>
                                <td><?php echo $c++ ?></td>
                                <td><?php echo $propietario->nombres . " " . $propietario->apellido_paterno . " " . $propietario->apellido_materno ?></td>
                                <td><?php echo $propietario->num_celular ?></td>
                                <td><?php echo ($propietario->num_celular_secundario == '') ? 'S/N' : $propietario->num_celular_secundario; ?></td>
                                <td><?php echo $propietario->num_carnet ?></td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <form method="post" class="delete-form">
                                            <input type="hidden" name="id" value="<?php echo $propietario->id_propietario ?>">
                                            <button type="button" class="btn btn-danger delete-btn">
                                                <i class="bi bi-trash-fill"></i> Eliminar
                                            </button>
                                        </form>

                                        <?php
                                        $id_propietario = $propietario->id_propietario;
                                        $data = "id_propietario=$id_propietario";
                                        $encryptedData = encryptData($data);
                                        ?>

                                        <a href="/sistema-sanbenito/home/propietarios/editar.php?data=<?php echo $encryptedData; ?>" class="btn btn-primary">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                        
                                        <a href="/sistema-sanbenito/home/vermas/propietarios.php?data=<?php echo $encryptedData; ?>" class="btn btn-info">
                                            <i class="bi bi-eye-fill"></i> Mascotas
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Inicialización de DataTables -->
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "pageLength": 100,
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
                title: "¿Estás seguro de eliminar al propietario?",
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
                        text: "El propietario no fue eliminado",
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
                window.location.href = '/sistema-sanbenito/home/propietarios.php';
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
require 'template/footer.php';
?>