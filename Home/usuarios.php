<?php
$titulo = "usuarios";
$nombrepagina = "usuarios";
require 'template/header.php';
verificaAcceso();
use App\User;
$usuarios = User::mostrar();
?>

<div class='dashboard-content'>
    <div class="container">
        <div class="row">
            <h1> <i class="bi bi-person-vcard-fill"></i> Usuarios</h1>
            <div class="row mb-3">
                <div class="col-md-12 text-end">
                    <a href="/sistema-sanbenito/home/user/crear.php" class="btn btn-primary">
                        <i class="bi bi-person-plus-fill"></i> Añadir nuevo usuario
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered dt-head-center" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombres y Apellidos</th>
                            <th>Correo electrónico</th>
                            <th>rol</th>
                            <th>C.I</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas de datos -->
                        <?php
                        $contador = 1;
                        if (!empty($usuarios)) {
                            foreach ($usuarios as $usuario) {
                                if(!$usuario['nombres']== ""){
                        ?>
                                <tr>
                                    <td><?php echo $contador++;  ?></td>
                                    <td><?php echo $usuario['nombres'] . ' ' . $usuario['apellido_paterno'] . ' ' . $usuario['apellido_materno'] ?></td>
                                    <td><?php echo $usuario['email'] ?></td>
                                    <td><?php if($usuario['rol']=="Administrador") echo "Adm."; else echo "Vet." ; ?></td>
                                   
                                    <td><?php echo $usuario['num_carnet'] ?? 'no configurado' ?></td>

                                    <td class="text-center">
                                        <label class="switch">
                                            <input type="checkbox" id="estadoSwitch<?php echo $usuario['id_usuario']; ?>" <?php echo $usuario['estado'] ? 'checked' : ''; ?> onchange="cambiarEstado(<?php echo $usuario['id_usuario']; ?>, this.checked)">
                                            <span class="slider"></span>
                                        </label>
                                    </td>

                                    <td><?php if ($usuario['nombres'] == null) {
                                            $email = $usuario['email'];
                                            $data = "email=$email";
                                            $encryptedData = encryptData($data);
                                        ?>

                                            <a href="/sistema-sanbenito/home/perfil/crear.php?data=<?php echo $encryptedData; ?>" class="btn btn-danger"><i class="bi bi-file-earmark-person"></i> Crear Perfil</a>


                                        <?php } else {
                                            $id_usuario = $usuario['id_usuario'];
                                            $id_personal = $usuario['id_personal'];
                                            $data = "id_usuario=$id_usuario&id_personal=$id_personal";

                                            // Encripta los parámetros
                                            $encryptedData = encryptData($data);
                                        ?>
                                            <a href="/sistema-sanbenito/home/vermas/personal.php?data=<?php echo $encryptedData; ?>" class="btn btn-info">
                                                <i class="bi bi-eye-fill"></i> Detalles
                                            </a>


                                        <?php } ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        else{
                            $borrar=User::borrar($usuario['id_usuario']);
                        }
                        }
                        } else {
                            echo "No se encontraron usuarios.";
                        }
                        ?>

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
<script>
    function cambiarEstado(idUsuario, nuevoEstado) {
    const estado = nuevoEstado ? 1 : 0;

    // Enviar la solicitud fetch con el método POST
    fetch('/sistema-sanbenito/home/user/cambiarEstado.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id_usuario: idUsuario, estado: estado })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            
        } else {
            alert('Error al actualizar el estado del usuario: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

</script>

<?php
require 'template/footer.php';
?>