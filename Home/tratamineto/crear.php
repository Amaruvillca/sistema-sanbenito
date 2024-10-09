<?php
$titulo = "Tratamientos y Medicaciones";
$nombrepagina = "tratamiento_medicacion";
require '../template/header.php';

use App\Tratamiento;
use App\Medicación;

// Simulamos que los medicamentos registrados están vacíos al principio




// Verificar si el tratamiento fue registrado

?>

<div class='dashboard-content'>
    <div class="contenedor-tratamiento-medicacion">
        <!-- Formulario para registrar un tratamiento -->
        <div class="tarjeta-formulario">
            <h2>Registrar Tratamiento</h2>
            <form action="insertar.php" method="POST" class="formulario">
                <!-- Campos del formulario de tratamiento -->
                <div class="campo-formulario">
                    <label for="dia_tratamiento">Día del Tratamiento</label>
                    <input type="number" class="form-control" name="dia_tratamiento" id="dia_tratamiento" min="1" max="7" required>
                </div>
                
                <div class="campo-formulario">
                    <label for="peso">Peso</label>
                    <input type="number" class="form-control" step="0.01" name="peso" id="peso" required>
                </div>
                <div class="campo-formulario">
                    <label for="temperatura">Temperatura</label>
                    <input type="number" class="form-control" step="0.01" name="temperatura" id="temperatura" required>
                </div>
                <div class="campo-formulario">
                    <label for="observaciones">Observaciones</label>
                    <textarea name="observaciones" class="form-control" id="observaciones" required></textarea>
                </div>
                
                <div class="boton-formulario">
                    <input type="submit" name="registrar_tratamiento" value="Registrar Tratamiento">
                </div>
               
                <div style="color: white;">
                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Itaque est commodi sed ullam non eius, 
                </div>
            </form>
        </div>

        <!-- Formulario de medicación y tabla de medicamentos (solo visible si el tratamiento ha sido registrado) -->
        <div class="tarjeta-formulario">
            <h2>Agregar Medicación</h2>
            
            <form action="procesar_medicacion.php" method="POST" class="formulario">
                <!-- Campos del formulario de medicación -->
                <div class="campo-formulario">
                    <label for="nombre_medicacion">Nombre de la Medicación</label>
                    <input type="text" name="nombre_medicacion" class="form-control" id="nombre_medicacion" required>
                </div>
                <div class="campo-formulario">
                    <label for="via">Vía de Administración</label>
                    <input type="text" class="form-control" name="via" id="via" required>
                </div>
                <div class="campo-formulario">
                    <label for="costo">Costo</label>
                    <input type="number" class="form-control" step="0.01" name="costo" id="costo" required>
                </div>

                <div class="boton-formulario">
                    <input type="submit" value="Agregar Medicación">
                </div>
            </form>
            
        </div>

        <!-- Tabla de medicamentos registrados -->
        
        <div class="tarjeta-formulario medicamentos-registrados">
            <h2>Medicamentos Suministrados</h2>
            <table class="tabla-medicamentos">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Vía</th>
                        <th>Costo</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($medicamentos)) : ?>
                        <tr>
                            <td colspan="4">No se ha suministrado ningún medicamento.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($medicamentos as $medicamento) : ?>
                            <tr>
                                <td><?= $medicamento['nombre_medicacion'] ?></td>
                                <td><?= $medicamento['via'] ?></td>
                                <td><?= $medicamento['costo'] ?></td>
                                <td><?= $medicamento['fecha_medicacion'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <br>
            <div class="boton-formulario guardar-medicacion">
                <input type="submit" value="Guardar Medicación">
            </div>
        </div>
       
       
    </div>
</div>

<?php
require '../template/footer.php';
?>  
