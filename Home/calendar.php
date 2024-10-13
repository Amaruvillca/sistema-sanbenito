<?php
$titulo = "Calendario";
$nombrepagina = "calendario"; 
require 'template/header.php';

use App\CirugiaProgramada;
use App\Ciruguas;
use App\Mascotas;
use App\Vacunas;
use App\Desparacitaciones;

$cirugias_programadas = CirugiaProgramada::all();
$vacunas = Vacunas::all();
$desparasitaciones= Desparacitaciones::all();
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
                        $mascota = Mascotas::find($cirugia->id_mascota);
                        $cirugias = Ciruguas::find($cirugia->id_cirugia);
                        $eventos[] = [
                            'title' => 'Cirugía Programada de: ' . $cirugias->nombre_cirugia . ' para ' . $mascota->nombre,
                            'start' => $cirugia->fecha_programada, 
                            'url' => 'cirugias_hoy.php', 
                            'color' => '#007bff', // Color para las cirugías
                            'backgroundColor' => '#007bff', // Color verde para vacunas
                        
                        ];
                    }
                }

                // Recorre las vacunas
                foreach ($vacunas as $vacuna) {
                    $mascota = Mascotas::find($vacuna->id_mascota);
                    $eventos[] = [
                        'title' => 'Vacuna: ' . $vacuna->nom_vac . ' para ' . $mascota->nombre,
                        'start' => $vacuna->proxima_vacuna, // Fecha de próxima vacuna
                        'url' => 'propietarios.php',
                        'allDay' => true, // Se muestra como evento de todo el día
                        'color' => '#28a745' // Color verde para vacunas
                    ];
                }

                // Recorre las desparasitaciones
                foreach ($desparasitaciones as $desparasitacion) {
                    $mascota = Mascotas::find($desparasitacion->id_mascota);
                    $eventos[] = [
                        'title' => 'Desparasitacion: ' . $desparasitacion->producto . ' para ' . $mascota->nombre,
                        'start' => $desparasitacion->proxima_desparasitacion, // Fecha de próxima desparasitacion
                        'url' => 'propietarios.php',
                        'allDay' => true, // Se muestra como evento de todo el día
                        'color' => '#ffc107' // Color amarillo para desparasitaciones
                    ];
                }

                // Usa json_encode() para generar el JSON correcto
                echo json_encode($eventos);
            ?>,

            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url;
                    info.jsEvent.preventDefault(); 
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
        background-color: #28a745; 
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
        background-color: rgba(40, 167, 69, 0.1);  
    }

    .fc-event {
        border-radius: 5px;
        padding: 2px 5px;
        color: #ffffff;
        transition: transform 0.2s;
    }

    .fc-event:hover {
        transform: scale(1.1);
    }
    
    /* defecto */
    .fc-event {
    background-color: #007bff; /* Color verde por defecto */
    border: none;
    border-radius: 5px;
    padding: 2px 5px;
    color: #ffffff;
    transition: transform 0.2s;
}

.fc-event:hover {
    background-color: #007bff;
    color: white;
    transform: scale(1.1);
}

</style>

<div class='dashboard-content'>
    <div class='container'>
        <div class="row">
            <div class="col-12">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
</div>
Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sunt, esse excepturi deleniti itaque voluptas ipsam porro corrupti ad repudiandae repellat obcaecati praesentium. Dolor, laudantium distinctio repudiandae excepturi labore et ipsum!
<?php
require 'template/footer.php';
?>
