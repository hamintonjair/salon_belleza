<style>
    .acciones-col {
        width: 100px;
        /* Ajusta el ancho según tus necesidades */
        text-align: center;
        /* Centra el contenido de la columna */
    }
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <h4 class="modal-title" id="titleModal">Panel ventas realizadas</h4>
        <hr>
        <div class="container">
            <div class="table-responsive">
                <table id="tableVentas" class="table table-striped display" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>Usuario</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Productos</th>
                            <th class="acciones-col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- La tabla se llenará mediante DataTables -->
                    </tbody>
                </table>

            </div>
        </div>



        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const tableVentas = new DataTable("#tableVentas", {
                    ajax: {
                        url: 'http://localhost/salon_belleza/ventas/getVentas',
                        dataSrc: 'ventas'
                    },
                    columns: [{
                            data: null,
                            render: function(data, type, row) {
                                return row.usuario.nombre + ' ' + row.usuario.apellidos;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return row.cliente.nombre + ' ' + row.cliente.apellidos;
                            }
                        },
                        {
                            data: 'total'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return new Date(row.productos[0].fecha_venta).toISOString().split('T')[0];
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return new Date(row.productos[0].fecha_venta).toISOString().split('T')[1].split('.')[0];
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                let productosHtml = '<ul>';
                                row.productos.forEach(producto => {
                                    productosHtml += `<li>${producto.producto_nombre} - ${producto.cantidad} x ${producto.precio_unitario} (Descuento: ${producto.descuento}) = ${producto.valor_total}</li>`;
                                });
                                productosHtml += '</ul>';
                                return productosHtml;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                let baseUrl = 'http://localhost/salon_belleza/';
                                let actions = '';

                                // Verificar el rol
                           
                                    actions += `
                                           
                                                <a href="#" onclick="confirmarAnulacion(${row.id}); return false;" class="btn btn-warning" >
                                                    <i class="fas fa-ban"></i>
                                                </a>
                                                <a href="${baseUrl}ventas/generarPFP/${row.id}" target="_blank" class="btn btn-danger" >
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            `;
                                

                                return actions; // Devolver las acciones generadas
                            }
                        }
                    ],
                    columnDefs: [{
                            className: 'text-center',
                            targets: [1, 2, 6]
                        },
                        {
                            className: 'text-left',
                            targets: [0, 3, 4, 5]
                        }
                    ],
                    responsive: true,
                    bDestroy: true,
                    iDisplayLength: 10,
                    order: [
                        [3, 'desc']
                    ]
                });


                window.confirmarAnulacion = function(id) {
                    swal({
                        title: "¿Estás seguro?",
                        text: "La venta será anulada y la cantidad volverá a productos.",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: `http://localhost/salon_belleza/ventas/anular/${id}`,
                                type: "GET",
                                dataType: "json",
                                success: function(response) {
                                    if (response.ok) {
                                        swal({
                                            title: "Venta Anulada",
                                            text: response.post,
                                            icon: "success",
                                            button: "OK",
                                        }).then(() => {
                                            tableVentas.ajax.reload();
                                        });
                                    } else {
                                        swal({
                                            title: "Error",
                                            text: response.post,
                                            icon: "error",
                                            button: "OK",
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    swal(
                                        "Error",
                                        "No se pudo anular la venta. Intente nuevamente.",
                                        "error"
                                    );
                                }
                            });
                        }
                    });
                }
            });
        </script>