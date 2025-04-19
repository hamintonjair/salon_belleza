<div class="main-panel">
    <div class="content-wrapper">
        <h4>Egresos</h4>
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="monthFilterEgresos">Mes</label>
                    <select id="monthFilterEgresos" class="form-control">
                        <option value="">Todos</option>
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= sprintf('%02d', $m) ?>"><?= strftime('%B', mktime(0, 0, 0, $m, 1)) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="yearFilterEgresos">Año</label>
                    <select id="yearFilterEgresos" class="form-control">
                        <option value="">Todos</option>
                        <?php for ($y = date('Y') - 5; $y <= date('Y'); $y++): ?>
                            <option value="<?= $y ?>"><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Total Egresos</label>
                    <div id="totalEgresos" class="bg-danger text-white rounded p-2">$0.00</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Saldo Disponible</label>
                    <div id="saldoDisponible" class="bg-success text-white rounded p-2">$0.00</div>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary" id="btnRetirarEgreso">Retirar</button>
                </div>
            </div>

            <canvas id="chartEgresosMonthly" height="100"></canvas>
            <div class="table-responsive">
                <table id="tableEgresos" class="table table-striped display" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>Concepto</th>
                            <th>Tipo</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        if ($.fn.DataTable.isDataTable('#tableEgresos')) {
                            $('#tableEgresos').DataTable().destroy();
                        }
                        $('#tableEgresos').DataTable({
                            ajax: {
                                url: '<?= site_url('finanzas/getEgresos') ?>',
                                data: function(d) {
                                    d.month = $('#monthFilterEgresos').val();
                                    d.year = $('#yearFilterEgresos').val();
                                },
                                dataSrc: ''
                            },
                            columns: [{
                                    data: 'concepto'
                                },
                                {
                                    data: 'tipo'
                                },
                                {
                                    data: 'monto',
                                    render: function(data) {
                                        return '$' + parseFloat(data).toLocaleString(undefined, {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    }
                                },
                                {
                                    data: 'fecha'
                                }
                            ],
                            pageLength: 10,
                            lengthMenu: [10, 25, 50, 100],
                            searching: true,
                            ordering: true,
                            responsive: true,
                            bDestroy: true,
                            language: {
                                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                            },
                            drawCallback: function(settings) {
                                // Actualizar el total de egresos mostrado arriba
                                let api = this.api();
                                let total = api.column(2, {
                                    page: 'current'
                                }).data().reduce(function(a, b) {
                                    return parseFloat(a) + parseFloat(b.toString().replace(/[^\d.-]/g, ''));
                                }, 0);
                                $('#totalEgresos').text('$' + total.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));
                            }
                        });
                        $('#monthFilterEgresos, #yearFilterEgresos').on('change', function() {
                            $('#tableEgresos').DataTable().ajax.reload();
                        });
                    });
                </script>
            </div>
            <!-- Modal para Retirar Egreso -->
            <div class="modal fade" id="modalRetirarEgreso" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <form id="formEgreso" action="<?= base_url('egresos/guardar') ?>" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Registrar Egreso</h5>
                                <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="tipo_egreso">Tipo de egreso</label>
                                    <select name="tipo_egreso" id="tipo_egreso" class="form-control" required>
                                        <option value="general">General</option>
                                        <option value="prestamo">Préstamo a trabajador</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="empleado_prestamo" style="display:none;">
                                    <label for="empleado_id">Empleado</label>
                                    <div class="input-group">
                                        <select name="empleado_id" id="empleado_id" class="form-control">
                                            <option value="">Seleccione empleado</option>
                                            <?php foreach ($empleados as $empleado): ?>
                                                <option value="<?= $empleado['id'] ?>"><?= $empleado['nombre'] . ' ' . $empleado['apellidos'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-info" id="btnVerPrestamos" title="Ver historial de préstamos" style="margin-left:5px;">
                                            <i class="fas fa-list"></i> Ver Préstamos
                                        </button>
                                    </div>
                                </div>

                                <!-- Modal historial préstamos -->
                                <div class="modal fade" id="modalPrestamosEmpleado" tabindex="-1" aria-labelledby="modalPrestamosEmpleadoLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-green headerRegister">
                                                <h5 class="modal-title" id="modalPrestamosEmpleadoLabel">Historial de Préstamos del Empleado</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-striped">
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
                                <div class="mb-3">
                                    <label for="monto">Monto</label>
                                    <input type="number" class="form-control" name="monto" required>
                                </div>
                                <div class="mb-3">
                                    <label for="motivo">Motivo</label>
                                    <input type="text" class="form-control" name="motivo">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </form>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            $('#formEgreso').on('submit', function(e) {
                                e.preventDefault();
                                $.ajax({
                                    url: $(this).attr('action'),
                                    method: 'POST',
                                    data: $(this).serialize(),
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.success) {
                                            swal('Éxito', response.message, 'success');
                                            $('#modalRetirarEgreso').modal('hide');
                                            $('#tableEgresos').DataTable().ajax.reload();
                                        } else {
                                            swal('Error', response.message, 'error');
                                        }
                                    },
                                    error: function(xhr) {
                                        swal('Error', 'Ocurrió un error inesperado', 'error');
                                    }
                                });
                            });
                        });
                    </script>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let tipoEgreso = document.getElementById('tipo_egreso');
                    let empleadoPrestamo = document.getElementById('empleado_prestamo');
                    if (tipoEgreso && empleadoPrestamo) {
                        tipoEgreso.addEventListener('change', function() {
                            empleadoPrestamo.style.display = this.value === 'prestamo' ? 'block' : 'none';
                        });
                    }
                });
            </script>

            <script>
                document.getElementById("btnRetirarEgreso").addEventListener("click", function() {
                    let monthField = document.getElementById("monthEgresoHidden");
                    let yearField = document.getElementById("yearEgresoHidden");
                    let monthFilter = document.getElementById("monthFilterEgresos");
                    let yearFilter = document.getElementById("yearFilterEgresos");
                    if (monthField && monthFilter) monthField.value = monthFilter.value;
                    if (yearField && yearFilter) yearField.value = yearFilter.value;
                    $('#modalRetirarEgreso').modal('show');
                });
            </script>

            <script>
                function cargarIngresosRecientes() {
                    const month = $('#monthFilterEgresos').val();
                    const year = $('#yearFilterEgresos').val();
                    $.ajax({
                        url: '<?= site_url('finanzas/getIngresos') ?>',
                        method: 'GET',
                        data: {
                            month: month,
                            year: year
                        },
                        dataType: 'json',
                        success: function(data) {
                            const tbody = $('#tableIngresosRecientes tbody');
                            tbody.empty();
                            data.slice(0, 5).forEach(function(row) {
                                tbody.append('<tr>' +
                                    '<td>' + row.fecha_venta + '</td>' +
                                    '<td>' + row.producto_nombre + '</td>' +
                                    '<td>' + parseFloat(row.valor_total).toFixed(2) + '</td>' +
                                    '</tr>');
                            });
                        }
                    });
                }
                document.addEventListener("DOMContentLoaded", function() {
                    cargarIngresosRecientes();
                    $('#monthFilterEgresos, #yearFilterEgresos').on('change', cargarIngresosRecientes);
                });
            </script>

            <script>
                function cargarEgresos() {
                    const month = $('#monthFilterEgresos').val();
                    const year = $('#yearFilterEgresos').val();
                    $.ajax({
                        url: '<?= site_url('finanzas/getEgresos') ?>',
                        method: 'GET',
                        data: {
                            month: month,
                            year: year
                        },
                        dataType: 'json',
                        success: function(data) {
                            const tbody = $('#tableEgresos tbody');
                            tbody.empty();
                            let total = 0;
                            data.forEach(function(row) {
                                tbody.append('<tr>' +
                                    '<td>' + row.concepto + '</td>' +
                                    '<td>' + row.tipo + '</td>' +
                                    '<td>' + parseFloat(row.monto).toFixed(2) + '</td>' +
                                    '<td>' + row.fecha + '</td>' +
                                    '</tr>');
                                total += parseFloat(row.monto);
                            });
                            $('#totalEgresos').text(total.toFixed(2));
                        }
                    });
                    cargarSaldo();
                }

                function cargarSaldo() {
                    const month = $('#monthFilterEgresos').val();
                    const year = $('#yearFilterEgresos').val();
                    $.ajax({
                        url: '<?= site_url('finanzas/getSaldo') ?>',
                        method: 'GET',
                        data: {
                            month: month,
                            year: year
                        },
                        dataType: 'json',
                        success: function(resp) {
                            $('#saldoDisponible').text(parseFloat(resp.saldo).toFixed(2));
                        }
                    });
                }

                document.addEventListener("DOMContentLoaded", function() {
                    cargarEgresos();
                    $('#monthFilterEgresos, #yearFilterEgresos').on('change', function() {
                        cargarEgresos();
                        cargarIngresosRecientes();
                    });

                });
            </script>

        </div>
