      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
    
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Calendario de agendas</h4>
                  <div class="table-responsive">
                    <div id="calendar"></div>

                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal -->
          <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="bookingModalLabel">Agendar Turno</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form id="bookingForm" autocomplete="off">
                    <input type="hidden" id="idCliente">
                    <div class="mb-3">
                      <label for="idNumber" class="form-label">Cédula</label>
                      <input type="text" class="form-control" id="idNumber" name="idNumber" required>
                      <button type="button" class="btn btn-primary mt-2" id="searchButton">Buscar</button>
                    </div>
                    <div class="mb-3">
                      <label for="name" class="form-label">Nombre</label>
                      <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                      <label for="surname" class="form-label">Apellidos</label>
                      <input type="text" class="form-control" id="surname" name="surname" required>
                    </div>
                    <div class="mb-3">
                      <label for="selectServicio" class="form-label">Servicios</label>
                      <select class="form-control" id="service" name="service" required>
                        <option value="0">Seleccionar..</option>
                        <?php foreach ($servicios as $services) : ?>
                          <option value="<?= $services['id']; ?>"><?= $services['nombre']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>

                    <div class="mb-3">
                      <label for="bookingTime" class="form-label">Selecciona la hora</label>
                      <input type="time" class="form-control" id="bookingTime" name="bookingTime" required>
                    </div>

                    <input type="hidden" id="selectedDate">
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <script>
            // obtener registro y mostrar en el calendario
            document.addEventListener('DOMContentLoaded', function() {
              let calendarEl = document.getElementById('calendar');
              let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                editable: true,
                selectable: true,
                events: function(fetchInfo, successCallback, failureCallback) {
                  fetch('http://localhost/salon_belleza/calendario/getBookings')
                    .then(response => response.json())
                    .then(data => {
                      let events = data.map(booking => ({
                        id: booking.id,
                        title: `${booking.nombre} ${booking.apellidos} - ${booking.servicio}`,
                        start: booking.date + 'T' + booking.time,
                        allDay: false,
                        extendedProps: {
                          name: booking.nombre,
                          surname: booking.apellidos,
                          service: booking.servicio,
                          date: booking.date,
                          time: booking.time,
                          status: booking.estado
                        }
                      }));
                      successCallback(events);
                    })
                    .catch(error => {
                      console.error('Error:', error);
                      failureCallback(error);
                    });
                },
                dateClick: function(info) {
                  openBookingModal(info.dateStr);
                },
                // Mostrar informacion al pasar el cursor 
                eventDidMount: function(info) {
                  tippy(info.el, {
                    content: `
                    <strong>Nombre:</strong> ${info.event.extendedProps.name}<br>
                    <strong>Apellidos:</strong> ${info.event.extendedProps.surname}<br>
                    <strong>Servicio:</strong> ${info.event.extendedProps.service}<br>
                    <strong>Fecha:</strong> ${info.event.extendedProps.date}<br>
                    <strong>Hora:</strong> ${info.event.extendedProps.time}<br>
                    <strong>Estado:</strong> ${info.event.extendedProps.status}<br>
                `,
                    allowHTML: true,
                    placement: 'top',
                    theme: 'light'
                  });
                },
                eventClick: function(info) {
                  showEventModal(info.event.extendedProps);
                }
              });
              calendar.render();
              // Función para refrescar los eventos del calendario
              function refreshCalendar() {
                calendar.refetchEvents(); // Recarga los eventos desde la fuente de eventos
              }

              function openBookingModal(dateStr) {
                let modal = new bootstrap.Modal(document.getElementById('bookingModal'));
                document.getElementById('selectedDate').value = dateStr;
                document.getElementById('idNumber').value = ''; // Limpiar el campo de cédula
                document.getElementById('idNumber').disabled = false; // Habilitar el campo de cédula
                document.getElementById('name').value = '';
                document.getElementById('surname').value = '';
                document.getElementById('service').value = '';
                document.getElementById('bookingTime').value = '';
                document.getElementById('name').disabled = false;
                document.getElementById('surname').disabled = false;
                document.getElementById('service').disabled = false;
                document.getElementById('bookingTime').disabled = false;
                modal.show();
              }

              // Mostrar registro en el modal al der clic
              function showEventModal(eventProps) {
                document.getElementById('eventName').textContent = eventProps.name;
                document.getElementById('eventSurname').textContent = eventProps.surname;
                document.getElementById('eventService').textContent = eventProps.service;
                document.getElementById('eventDate').textContent = eventProps.date;
                document.getElementById('eventTime').textContent = formatTime(eventProps.time);

                // Mostrar el estado con color rojo si está pendiente
                const statusElement = document.getElementById('eventStatus');
                statusElement.textContent = eventProps.status;
                statusElement.style.color = eventProps.status === 'pendiente' ? 'red' : 'black';

                function formatTime(timeStr) {
                  // Asumiendo que timeStr está en formato "HH:mm:ss"
                  const [hours, minutes] = timeStr.split(':');
                  const hours12 = (parseInt(hours) % 12) || 12; // Convertir a formato 12 horas
                  const ampm = parseInt(hours) >= 12 ? 'PM' : 'AM'; // Determinar AM/PM
                  return `${hours12}:${minutes} ${ampm}`;
                }
                let modal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
                modal.show();
              }

              function formatCurrency(amount) {
                return new Intl.NumberFormat('es-CO', {
                  style: 'currency',
                  currency: 'COP'
                }).format(amount);
              }
              // Buscar cliente por cédula
              document.getElementById('searchButton').addEventListener('click', function() {
                const idNumber = document.getElementById('idNumber').value;
                let base_url = "http://localhost/salon_belleza/";
                $.ajax({
                  url: base_url + "cliente/getClientById/" + idNumber,
                  type: "GET",
                  dataType: "json",
                  success: function(resp) {
                    if (resp.success) {
                      $("#idCliente").val(resp.client.id);
                      $("#name").val(resp.client.nombre);
                      $("#surname").val(resp.client.apellidos);
                      // Deshabilitar los campos
                      $("#name").prop('disabled', true);
                      $("#surname").prop('disabled', true);

                      // Mostrar el modal
                      $('#bookingModal').modal('show');
                    } else {
                      swal({
                        title: "Error",
                        text: resp.message, // Cambiado de 'data.message' a 'resp.message'
                        icon: "error",
                        button: "OK",
                      });
                      // Limpiar los campos si el cliente no se encuentra
                      $("#name").val('');
                      $("#surname").val('');
                      $("#service").val('');
                    }
                  },
                  error: function() {
                    swal({
                      title: "Error",
                      text: "No se pudo obtener la información del Cliente",
                      icon: "error",
                      button: "OK",
                    });
                  }
                });
              });

              // Guardar agenda desde el calendario
              document.getElementById('bookingForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const idCliente = document.getElementById('idCliente').value;
                const name = document.getElementById('name').value;
                const surname = document.getElementById('surname').value;
                const service = document.getElementById('service').value;
                const date = document.getElementById('selectedDate').value;
                const time = document.getElementById('bookingTime').value;
                fetch('http://localhost/salon_belleza/booking/save', {
                    method: 'POST',
                    headers: {
                      'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                      idCliente: idCliente,
                      name: name,
                      surname: surname,
                      service: service,
                      date: date,
                      time: time,
                    })
                  })
                  .then(response => response.json())
                  .then(data => {
                    if (data.success) {
                      swal({
                        title: "Éxito",
                        text: "La reserva se ha guardado exitosamente.",
                        icon: "success",
                        button: "OK",
                      }).then(() => {
                        // Cerrar el modal y refrescar el calendario
                        const modal = bootstrap.Modal.getInstance(document.getElementById('bookingModal'));
                        modal.hide();
                        refreshCalendar();
                      });
                    } else {
                      swal({
                        title: "Info",
                        text: data.message,
                        icon: "info",
                        button: "OK",
                      });
                    }
                  })
                  .catch(error => {
                    swal({
                      title: "Error",
                      text: error,
                      icon: "error",
                      button: "OK",
                    });
                  });
              });
            });
          </script>

          <!-- Modal para detalles del evento -->
          <div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-green">
                  <h5 class="modal-title" id="eventDetailsModalLabel">Detalles del Turno</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p><strong>Nombre:</strong> <span id="eventName"></span></p>
                  <p><strong>Apellidos:</strong> <span id="eventSurname"></span></p>
                  <p><strong>Servicio:</strong> <span id="eventService"></span></p>
                  <p><strong>Fecha:</strong> <span id="eventDate"></span></p>
                  <p><strong>Hora:</strong> <span id="eventTime"></span></p>
                  <p><strong>Estado:</strong> <span id="eventStatus"></span></p>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <style>
            .bg-green {
              background-color: royalblue;
              /* Cambia el valor según el tono de verde que prefieras */
              color: white;
              /* Color del texto, puedes ajustarlo según tu preferencia */
            }
          </style>