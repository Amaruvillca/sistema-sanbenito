<?php

$titulo = "Mascotas";
require '../template/header.php';
?>

<div class='dashboard-content'>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-success text-white d-flex align-items-center">
                <span class="me-2"><i class="fas fa-paw"></i></i></span>
                <h4 class="mb-0">Nueva mascota</h4>
            </div>
            <div class="card-body">
                <form id="mascotaForm" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center mb-3">
                                <img id="clienteFoto" src="/sistema-sanbenito/imagemascota/mascota.png" class="img-thumbnail mb-2" alt="Foto del mascota" width="200px" height="200px">
                                <input type="file" id="fotoInput" style="display:none;">
                                <button type="button" class="btn btn-success btn-sm w-100" id="btnSubirFoto">Añadir foto</button>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="nombre" class="form-label">Nombre:</label>
                                    <input type="text" class="form-control" id="nombre" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese el nombre.
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="apellidos" class="form-label">Especie:</label>
                                    <input type="text" class="form-control" id="apellidos" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese la especie.
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Sexo:</label>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="radio" name="sexo" id="macho" value="macho" required>
                                            <label class="form-check-label" for="macho">
                                                Macho
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sexo" id="hembra" value="hembra" required>
                                            <label class="form-check-label" for="hembra">
                                                Hembra
                                            </label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback mt-2">
                                        Por favor seleccione el sexo.
                                    </div>
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label for="color" class="form-label">Color:</label>
                                    <input type="text" class="form-control" id="color" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese el color.
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="raza" class="form-label">Raza:</label>
                                    <input type="text" class="form-control" id="raza" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese la raza.
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="fechaNacimiento" class="form-label">Fecha de nacimiento:</label>
                                    <input type="date" class="form-control" id="fechaNacimiento" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese la fecha de nacimiento.
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="Propietario" class="form-label">Propietario</label>
                                    <select class="form-select" id="Propietario" required>
                                        <option selected disabled value="">--Seleccione--</option>

                                        <option>Juan</option>
                                        <option>Juan</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        seleccione un Propietario
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back();">
                            <i class="bi bi-arrow-left"></i>
                            Volver
                        </button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Registrar Mascota </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<script>
    document.getElementById('btnSubirFoto').addEventListener('click', function() {
        document.getElementById('fotoInput').click();
    });

    document.getElementById('fotoInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('clienteFoto').src = e.target.result;
                
            }
            reader.readAsDataURL(file);
        }
    });

    // Bootstrap form validation
    (function() {
        'use strict'

        const forms = document.querySelectorAll('.needs-validation')

        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })();
</script>

<?php
require '../template/footer.php';
?>