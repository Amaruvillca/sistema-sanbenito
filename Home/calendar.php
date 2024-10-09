<?php
$titulo = "Calendario";
$nombrepagina = "calendario"; 
require 'template/header.php';

use App\CirugiaProgramada;
$cirugias_programadas = CirugiaProgramada::all();
use App\Ciruguas;
use App\Mascotas;
?>
<script src='/sistema-sanbenito/build/fullcalendar/dist/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es', 
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día'
            },
            selectable: true,  
            selectHelper: true, 
            events: <?php
                
                $eventos = [];

                // Recorre las cirugías programadas
                foreach ($cirugias_programadas as $cirugia) {
                    if ($cirugia->estado === 'pendiente') { // Verifica si el estado es "pendiente"
                      $mascota =  Mascotas::find($cirugia->id_mascota);
                      $cirugias =  Ciruguas::find($cirugia->id_cirugia);
                        $eventos[] = [
                            'title' => 'Cirugía Programada de: ' . $cirugias->nombre_cirugia . ' para ' . $mascota->nombre,
                            'start' => $cirugia->fecha_programada, // Asumiendo que fecha_programada es un DATETIME en formato ISO
                            'url' => 'cirugias_hoy.php' // Enlace a detalles de la cirugía
                        ];
                    }
                }

                // Usa json_encode() para generar el JSON correcto
                echo json_encode($eventos);
            ?>,

          

            // Evento cuando se hace clic en un evento existente
            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url; // Redirigir a la URL asociada al evento
                    info.jsEvent.preventDefault(); // Previene el comportamiento predeterminado de abrir el link en una nueva ventana
                }
            }
        });
        calendar.render();
    });
</script>

<style>
    #calendar {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .fc-toolbar {
        background-color: #f7f7f7;
        border-bottom: 1px solid #ddd;
        border-radius: 10px 10px 0 0;
    }

    .fc-button {
        background-color: #28a745; /* Color verde de San Benito */
        border: none;
        color: #fff;
        border-radius: 5px;
        padding: 5px 10px;
    }

    .fc-button:hover {
        background-color: #218838;
    }

    .fc-daygrid-day-number {
        color: #007bff;
        font-weight: bold;
    }

    .fc-day-today {
        background-color: rgba(40, 167, 69, 0.1);  /* Fondo para el día actual */
    }

    .fc-event {
        background-color: #28a745; /* Color verde de San Benito */
        border: none;
        border-radius: 5px;
        padding: 2px 5px;
        color: #ffffff; /* Color del texto blanco */
        transition: transform 0.2s;
    }

    .fc-event:hover {
        background-color: #218838;
        color: white;
        transform: scale(1.1);
    }
</style>

<div class='dashboard-content'>
    <div class='container'>
        <div class="row">
          <br>
            <div class="col-12">
                <div id='calendar'></div>
            </div>
            <div class="col-12">
                <div style="color: white;">
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi sed quos incidunt sequi quidem repellendus molestiae ratione veritatis quam, quasi qui placeat nisi corporis non recusandae! Dicta quo sint et.
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require 'template/footer.php';
?>
