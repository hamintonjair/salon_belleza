<div class="main-panel">
    <div class="content-wrapper">
        <h4 class="modal-title" id="titleModal">Panel Pagos a Empleados</h4>
        <hr>
        <!-- Container para la tabla -->
        <div class="container">
            <div class="table-responsive">
                <table id="tablePagos" class="table table-striped display" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nombre</th>
                            <!-- <th>Estado</th> -->
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-empleados">
                        <!-- Los datos se llenarán mediante JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para ver servicios realizados -->
        <div id="modalServiciosRealizados" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-green headerRegister">
                        <h5 class="modal-title" id="titleModal">Servicios Realizados</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table id="tablePagos1" class="table table-striped display">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Servicio</th>
                                    <th>Precio</th>
                                    <th>Pago</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-servicios">
                                <!-- Los datos se llenarán mediante JavaScript -->
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <h5>Total a Pagar: $<span id="total-pagar">0</span></h5>
                            <input type="hidden" id="empleado-id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="button" class="btn btn-success" id="btnPagar">
                            <i class="fas fa-check"></i> Pagar
                        </button>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Ver Pagos -->
        <div class="modal fade" id="modalPagos" tabindex="-1" aria-labelledby="modalPagosLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header bg-green headerRegister">
                        <h5 class="modal-title" id="titleModal">Pagos del Empleado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table id="tablePagosEmpleado" class="table table-striped display">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Pago</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-pagos">
                                <!-- Aquí se llenarán los datos con JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let turno_idi;
            // Función global para mostrar los servicios realizados
            function mostrarServicios(empleadoId) {
                $.ajax({
                    url: 'http://localhost/salon_belleza/pagos_empleados/getServiciosRealizados/' + empleadoId,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        let tbody = $('#tbody-servicios');
                        tbody.empty();

                        // Asegúrate de que totalPagar sea un número

                        $('#total-pagar').text(response.total_pagar);

                        // Función para actualizar el estado del botón según el total a pagar
                        function actualizarBotonPagar() {
                            const totalPagar = parseFloat($('#total-pagar').text());
                            if (totalPagar > 0) {
                                $('#btnPagar').prop('disabled', false); // Habilita el botón si total a pagar es mayor que 0
                            } else {
                                $('#btnPagar').prop('disabled', true); // Deshabilita el botón si total a pagar es 0
                            }
                        }

                        // Llama a la función para ajustar el estado del botón
                        actualizarBotonPagar();
                        response.servicios.forEach(function(servicio) {

                            tbody.append(
                                '<tr>' +
                                '<td>' + servicio.nombre_servicio + '</td>' +
                                '<td>$' + servicio.precio_servicio + '</td>' +
                                '<td>$' + servicio.pago_empleado + '</td>' +
                                '<td>' + servicio.fecha_servicio + '</td>' +
                                '</tr>'
                            );

                        });

                        turno_idi = response.turno_id;
                        // Asegúrate de que totalPagar sea un número antes de usar toFixed
                        // $('#total-pagar').text(totalPagar.toFixed(2));

                        $('#empleado-id').val(empleadoId);


                        let modal = new bootstrap.Modal(document.getElementById('modalServiciosRealizados'));
                        modal.show();
                    },
                    error: function() {
                        alert('Error al cargar los servicios.');
                    }
                });
            }


            window.verPagos = function(empleadoId) {
                // Destruir el DataTable si ya ha sido inicializado previamente

                // Inicializar el DataTable con la URL específica para obtener los pagos del empleado
                let pagosTable = new DataTable("#tablePagosEmpleado", {
                    ajax: {
                        url: 'http://localhost/salon_belleza/pagos_empleados/getPagosEmpleado/' + empleadoId,
                        dataSrc: 'pagos' // Asumiendo que la respuesta tiene un objeto `pagos`
                    },
                    columns: [{
                            data: 'pago',
                            render: function(data, type, row) {
                                return '$' + parseFloat(data).toLocaleString(); // Formatear el pago con signo de $
                            }
                        },
                        {
                            data: 'fecha_pago',
                            render: function(data, type, row) {
                                // Formatear la fecha si es necesario
                                return data;
                            }
                        }
                    ],
                    columnDefs: [{
                        className: 'text-center',
                        targets: [0, 1]
                    }, ],
                    responsive: true,
                    bDestroy: true,
                    iDisplayLength: 10,
                    order: [
                        [1, 'desc']
                    ] // Ordenar por la fecha de pago (columna índice 1) de forma descendente
                });

                // Mostrar el modal después de inicializar el DataTable
                let modal = new bootstrap.Modal(document.getElementById('modalPagos'));
                modal.show();
            }

            document.addEventListener("DOMContentLoaded", function() {
                //   CARGAR DATOS DE EMPLEADOS

                let pagoempleado = new DataTable("#tablePagos", {
                    ajax: {
                        url: 'http://localhost/salon_belleza/pagos_empleados/getEmpleados',
                        dataSrc: 'empleados'
                    },
                    columns: [{
                            data: null,
                            render: function(data, type, row) {
                                return row.nombre + ' ' + row.apellidos;
                            }
                        },

                        {
                            data: null,
                            render: function(data, type, row) {
                                let botonVerServicios = row.boton_ver_servicios ?
                                    '' :
                                    '<button class="btn btn-warning btn-sm" onclick="mostrarServicios(' + row.id + ')"><i class="fas fa-shopping-cart"></i></button>';

                                let botonVerPagos = row.boton_ver_pagos ?
                                    '<button class="btn btn-success btn-sm" onclick="verPagos(' + row.id + ')"><i class="fas fa-dollar-sign"></i></button>' :
                                    '';

                                return botonVerServicios + ' ' + botonVerPagos;
                            }
                        }
                    ],
                    columnDefs: [{
                            className: 'text-center',
                            targets: [1]
                        },
                        {
                            className: 'text-left',
                            targets: [0]
                        },
                    ],
                    responsive: true,
                    bDestroy: true,
                    iDisplayLength: 10,
                    order: [
                        [0, 'desc']
                    ]
                });
                //  PAGO DE EMPLEADOS
                $('#btnPagar').on('click', function() {
                    let empleadoId = $('#empleado-id').val();
                    let totalPagar = $('#total-pagar').text();
                    $.ajax({
                        url: 'http://localhost/salon_belleza/pagos_empleados/pagarEmpleado',
                        method: 'POST',
                        data: {
                            turno_id: turno_idi,
                            empleado_id: empleadoId,
                            total_pagar: totalPagar
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#modalServiciosRealizados').modal('hide');
                                pagoempleado.ajax.reload(null, false);
                                swal({
                                    title: "Success",
                                    text: response.message,
                                    icon: "success",
                                    button: "OK",
                                });
                            } else {
                                swal({
                                    title: "Error",
                                    text: response.message,
                                    icon: "error",
                                    button: "OK",
                                });
                            }
                        },
                        error: function() {
                            alert('Error al procesar el pago.');
                        }
                    });
                });
            });
        </script>
        <style>
            .bg-green {
                background-color: #223e9c;
                color: white;
            }
        </style>