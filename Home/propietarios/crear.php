<?php
$titulo = "Propietarios";
require '../template/header.php';

?>


<div class='dashboard-content'>
 
<div class="container mt-4">
        <div class="card">
            <div class="card-header bg-success text-white d-flex align-items-center">
                <span class="me-2"><i class="bi bi-person-fill"></i></span>
                <h4 class="mb-0">Nuevo Propietario</h4>
            </div>
            <div class="card-body">
                <form>
                    <div class="row">
                        
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombres</label>
                                    <input type="text" class="form-control" id="nombre" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="apellidos" class="form-label">Apellido Paterno</label>
                                    <input type="text" class="form-control" id="apellidos" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="apellidos" class="form-label">Apellido Materno</label>
                                    <input type="text" class="form-control" id="apellidos" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fechaNacimiento" class="form-label">Correo electrónico</label>
                                    <input type="email" class="form-control" id="fechaNacimiento" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nif" class="form-label">Número de carnet</label>
                                    <input type="number" class="form-control" id="nif" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="correo" class="form-label">Correo electrónico</label>
                                    <input type="email" class="form-control" id="correo" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="telefonoCasa" class="form-label">Numero de celular</label>
                                    <input type="tel" class="form-control" id="telefonoCasa" required>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Propietario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php
require '../template/footer.php';
?>