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

        <!-- Modal para historial de préstamos -->
        <div class="modal fade" id="modalPrestamosEmpleado" tabindex="-1" aria-labelledby="modalPrestamosEmpleadoLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-green headerRegister">
                        <h5 class="modal-title" id="modalPrestamosEmpleadoLabel">Historial de Préstamos del Empleado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table id="tabla-prestamos-empleado" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-prestamos-empleado">
                                <!-- JS llenará los préstamos -->
                            </tbody>
                        </table>
                    </div>
                </div>
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
                            <h6 class="text-danger" id="total-prestamos" style="display:none;">Préstamos descontados: $<span id="prestamos-descontados">0</span></h6>
                            <div id="detalle-prestamos" style="display:none;">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Monto</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-detalle-prestamos"></tbody>
                                </table>
                            </div>
                            <h5 id="neto-pagar" style="display:none;">Total Neto a Pagar: $<span id="neto-pago">0</span></h5>
                            <input type="hidden" id="empleado-id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" id="btnResumenPago">
                            <i class="fas fa-eye"></i> Ver Resumen de Pago
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="button" class="btn btn-success" id="btnPagar" disabled>
                            <i class="fas fa-check"></i> Pagar
                        </button>
                    </div>
                    <script>
                        $(document).on('click', '#btnResumenPago', function() {
                            let empleadoId = $('#empleado-id').val();
                            if (!empleadoId) {
                                swal('Seleccione un empleado primero', '', 'warning');
                                return;
                            }
                            $.ajax({
                                url: '/salon_belleza/pagos_empleados/getResumenPago/' + empleadoId,
                                method: 'GET',
                                dataType: 'json',
                                success: function(response) {
                                    $('#total-pagar').text(response.total_servicios);
                                    if (response.total_prestamos_descontados > 0) {
                                        $('#total-prestamos').show();
                                        $('#detalle-prestamos').show();
                                        $('#prestamos-descontados').text(parseFloat(response.total_prestamos_descontados).toLocaleString('es-CO', {
                                            minimumFractionDigits: 2
                                        }));
                                        let tbody = $('#tbody-detalle-prestamos');
                                        tbody.empty();
                                        response.detalle_prestamos.forEach(function(prestamo) {
                                            tbody.append('<tr><td>$' + parseFloat(prestamo.monto).toLocaleString('es-CO', {
                                                minimumFractionDigits: 2
                                            }) + '</td><td>' + prestamo.fecha + '</td></tr>');
                                        });
                                    } else {
                                        $('#total-prestamos').hide();
                                        $('#detalle-prestamos').hide();
                                    }
                                    $('#neto-pagar').show();
                                    $('#neto-pago').text(parseFloat(response.pago_neto).toLocaleString('es-CO', {
                                        minimumFractionDigits: 2
                                    }));
                                    $('#btnPagar').prop('disabled', false);
                                },
                                error: function() {
                                    swal('Error al obtener el resumen de pago', '', 'error');
                                }
                            });
                        });
                    </script>
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
                // ...
                // Después de cargar los servicios y actualizar el total a pagar:
                setTimeout(function() { // Espera a que #total-pagar esté actualizado
                    let total = parseFloat($('#total-pagar').text());
                    if (total > 0) {
                        $('#btnPagar').prop('disabled', false);
                    } else {
                        $('#btnPagar').prop('disabled', true);
                    }
                }, 200);

                $.ajax({
                    url: window.BASE_URL + "pagos_empleados/getServiciosRealizados/" + empleadoId,
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
                                $('#btnPagar').prop('disabled',
                                    false); // Habilita el botón si total a pagar es mayor que 0
                            } else {
                                $('#btnPagar').prop('disabled',
                                    true); // Deshabilita el botón si total a pagar es 0
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
                        url: window.BASE_URL + "pagos_empleados/getPagosEmpleado/" + empleadoId,
                        dataSrc: 'pagos' // Asumiendo que la respuesta tiene un objeto `pagos`
                    },
                    columns: [{
                            data: 'pago',
                            render: function(data, type, row) {
                                return '$' + parseFloat(data)
                                    .toLocaleString(); // Formatear el pago con signo de $
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
                        url: window.BASE_URL + "pagos_empleados/getEmpleados",
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
                                let badge = '';
                                if (row.pagos_pendientes && row.pagos_pendientes > 0) {
                                    badge = '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">' + row.pagos_pendientes + '<span class="visually-hidden">pagos pendientes</span></span>';
                                }
                                let botonVerServicios = '<button class="btn btn-warning btn-sm position-relative" onclick="mostrarServicios(' +
                                    row.id +
                                    ')" title="Ver servicios a pagar"><i class="fas fa-shopping-cart"></i>' + badge + '</button>';

                                let botonVerPagos = '<button class="btn btn-secondary btn-sm" onclick="verPagos(' + row.id + ')" title="Ver pagos realizados"><i class="fas fa-dollar-sign"></i></button>';

                                let botonVerPrestamos = '<button class="btn btn-info btn-sm" onclick="verPrestamosEmpleado(' +
                                    row.id +
                                    ')" title="Ver historial de préstamos"><i class="fas fa-list"></i></button>';

                                return botonVerServicios + ' ' + botonVerPagos + ' ' + botonVerPrestamos;
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
                    if (!empleadoId) {
                        swal('Seleccione un empleado primero', '', 'warning');
                        return;
                    }

                    swal({
                        title: '¿Está seguro?',
                        text: '¿Desea procesar el pago al empleado?',
                        icon: 'warning',
                        buttons: [true, 'Sí, pagar'],
                        dangerMode: true
                    }).then(function(confirmado) {
                        if (confirmado) {
                            $.ajax({
                                url: '/salon_belleza/pagos_empleados/pagarEmpleado/' + empleadoId,
                                method: 'POST',
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        swal({
                                            title: 'Pago realizado',
                                            text: 'El pago se procesó correctamente.',
                                            icon: 'success',
                                            button: 'OK'
                                        }).then(function() {
                                            // Para Bootstrap 4/5: cerrar modal
                                            $('#modalServiciosRealizados').modal('hide');

                                            // Actualiza tablas si usan AJAX
                                            if ($.fn.DataTable.isDataTable('#tablePagos')) {
                                                $('#tablePagos').DataTable().ajax.reload();
                                            }

                                            if ($.fn.DataTable.isDataTable('#tablePagos1')) {
                                                $('#tablePagos1').DataTable().ajax.reload();
                                            }

                                            // O como alternativa segura
                                            // location.reload();
                                        });
                                    } else {
                                        swal('Error', response.message || 'No se pudo procesar el pago.', 'error');
                                    }
                                },
                                error: function() {
                                    swal('Error', 'Ocurrió un error inesperado', 'error');
                                }
                            });
                        }
                    });
                });

            });
        </script>
        <script>
            // Función global para mostrar historial de préstamos desde botón
            window.verPrestamosEmpleado = function(empleadoId) {
                $.ajax({
                    url: '/salon_belleza/pagos_empleados/getPrestamosEmpleado/' + empleadoId,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        let tbody = $('#tbody-prestamos-empleado');
                        tbody.empty();
                        if (response.prestamos && response.prestamos.length > 0) {
                            response.prestamos.forEach(function(prestamo) {
                                let estadoBadge = prestamo.estado === 'saldado' ? '<span class="badge bg-success">Saldado</span>' : '<span class="badge bg-warning text-dark">Pendiente</span>';
                                tbody.append('<tr>' +
                                    '<td>$' + parseFloat(prestamo.monto).toLocaleString(undefined, {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }) + '</td>' +
                                    '<td>' + estadoBadge + '</td>' +
                                    '<td>' + prestamo.fecha + '</td>' +
                                    '</tr>');
                            });
                        } else {
                            tbody.append('<tr><td colspan="3" class="text-center">Sin préstamos registrados</td></tr>');
                        }
                        let modal = new bootstrap.Modal(document.getElementById('modalPrestamosEmpleado'));
                        modal.show();
                    },
                    error: function() {
                        swal('Error al cargar el historial de préstamos', '', 'error');
                    }
                });
            }
        </script>
        <style>
            .bg-green {
                background-color: #223e9c;
                color: white;
            }
        </style>