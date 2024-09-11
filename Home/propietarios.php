<?php
$titulo = "Propietarios";
require 'template/header.php';


use App\User;
?>

<div class='dashboard-content'>
    <div class="container">
        <div class="row">
            <h1> <i class="bi bi-person-fill"></i>  Propietarios</h1>
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
                            <th>Apellidos y Nombres</th>
                            <th>Correo Electrónico</th>
                            <th>Celular</th>
                            <th>C.I Identidad</th>
                            <th>Rol</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas de datos -->
                        <?php
                        // Aquí puedes agregar PHP para generar filas dinámicamente desde la base de datos
                        ?>
                        <tr>
                            <td>h</td>
                            <td>h</td>
                            <td>h</td>
                            <td>h</td>
                            <td>h</td>
                            <td>h</td>
                            <td>h</td>
                        </tr>
                        <tr>
                            <td>h</td>
                            <td>h</td>
                            <td>i</td>
                            <td>h</td>
                            <td>h</td>
                            <td>h</td>
                            <td>h</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
            "pageLength": 100, // Cambiado a 10 para evitar mostrar demasiados registros en una sola página
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
</script>

<?php
require 'template/footer.php';
?>