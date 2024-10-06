<?php
$titulo = "Calendario";
$nombrepagina = "calendario"; 
require 'template/header.php';

?>
<script src='/sistema-sanbenito/build/fullcalendar/dist/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          locale: 'es',  // Para mostrar el calendario en español
          headerToolbar: {  // Personalización del header
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
          },
          buttonText: {  // Personaliza los textos de los botones
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
          },
          selectable: true,  // Habilitar la selección de fechas
          selectHelper: true, // Mostrar ayuda visual al seleccionar
          events: [
            {
              title: 'Evento de prueba',
              start: '2024-10-15',
              end: '2024-10-15',
              url: 'https://tupagina.com/detalle-evento',  // URL del evento
            }
          ],  // Agrega eventos aquí si tienes

          // Función para agregar un evento al seleccionar una fecha
          select: function(info) {
            var eventTitle = prompt('Ingrese el título del evento:');
            if (eventTitle) {
              calendar.addEvent({
                title: eventTitle,
                start: info.startStr,
                end: info.endStr,
                allDay: info.allDay
              });
              alert('Evento agregado: ' + eventTitle);
            }
            calendar.unselect(); // Deseleccionar después de agregar el evento
          },

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
    background-color: rgba(40, 167, 69, 0.1);  /* Fondo para el día actual */
  }

  .fc-event {
    background-color: #dc3545;
    border: none;
    border-radius: 5px;
    padding: 2px 5px;
  }

  .fc-event:hover {
    background-color: #c82333;
  }
</style>


<div class='dashboard-content'>
    <div class='container'>
        <div class="row">
            <div class="col-12">
            <div id='calendar'></div>
            </div>
            <div class="col-12">
                <div class='card'>
                    <div class='card-header'>
                        <h1>hola juan</h1>
                    </div>
                    <div class='card-body'>
                        <p>hola juan: veterinario</p>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium eos at voluptate dolores vel. Velit, facilis deserunt iure explicabo soluta blanditiis, porro saepe alias, maiores ducimus nam dolores odio a.
                            Repudiandae a sapiente sit eveniet quisquam ea magni voluptatem nobis dolore doloremque? Quo eos ipsum nisi ad blanditiis temporibus et mollitia, distinctio, fugiat veritatis aut, vitae voluptates odit? Eveniet, minus!
                            Debitis voluptate quo adipisci nemo maxime quibusdam maiores fugiat aliquam, rem impedit eligendi vero tempore atque voluptates vel amet velit, nulla laboriosam animi officiis non? Ut minima fugiat deserunt quas?
                            Ipsam eaque hic aut similique incidunt qui, voluptas ullam doloremque expedita eligendi pariatur veritatis magnam iusto ea placeat cupiditate sint labore beatae quasi quia repellendus totam. Minus nobis nihil explicabo.
                            Saepe officia repellat modi, id, dolores sed sit libero nesciunt provident officiis voluptatibus eligendi soluta molestiae sunt. Atque numquam laborum corrupti rerum, officiis temporibus maiores adipisci voluptatum, magni sint autem.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require 'template/footer.php';
?>