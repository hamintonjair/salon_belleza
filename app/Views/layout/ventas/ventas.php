<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <h4 class="modal-title" id="titleModal">Panel Ventas</h4>
        <hr>
        <!-- Container para la tabla -->
        <div class="container">
            <!-- Formulario para selección de productos y cantidad -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="productoSelect">Seleccionar Producto</label>
                        <select id="productoSelect" class="form-control" onchange="actualizarPrecio()">
                            <!-- Opciones de productos se llenarán con JavaScript -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cantidadInput">Cantidad</label>
                        <input type="number" id="cantidadInput" class="form-control" placeholder="Ingrese cantidad" onkeyup="agregarProducto(event)" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="clienteSelect">Seleccionar Cliente</label>
                        <select id="clienteSelect" class="form-control">
                            <!-- Opciones de clientes se llenarán con JavaScript -->
                        </select>
                    </div>
                </div>
            </div>
            <!-- Tabla para mostrar productos agregados -->
            <div class="table-responsive">
                <table id="tablaProductos" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>V. Unitario</th>
                            <th>Cantidad</th>
                            <th>Descuento</th>
                            <th>Valor Total</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyProductos">
                        <!-- Filas de productos se llenarán con JavaScript -->
                    </tbody>
                </table>
            </div>
            <!-- Botón para finalizar la venta -->
            <button id="finalizarVentaBtn" class="btn btn-primary" onclick="finalizarVenta()">Finalizar Venta</button>
        </div>

        <!-- Modal para mostrar resumen de la venta -->
        <div class="modal fade" id="resumenModal" tabindex="-1" aria-labelledby="resumenModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resumenModalLabel">Resumen de Venta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Total a pagar: <span id="totalPagar"></span></p>
                        <p>Monto pagado: <input type="number" id="montoPagado" class="form-control" placeholder="Ingrese monto pagado" onkeyup="actualizarMontoPagado(event)" /></p>
                        <p>Excedente a devolver: <span id="excedenteDevolver"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="confirmarVenta()">Confirmar Venta</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Your JavaScript functions -->
        <script>
            let productos = [];
            let clientes = [];
            let carrito = [];

            document.addEventListener("DOMContentLoaded", function() {
                cargarProductos();
                cargarClientes();
            });

            function cargarProductos() {
                $.ajax({
                    url: 'http://localhost/salon_belleza/ventas/productos',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        productos = response.productos;
                        let select = $('#productoSelect');
                        select.empty();
                        select.append('<option value="">Seleccionar...</option>');
                        productos.forEach(producto => {
                            select.append(`<option value="${producto.id}" data-precio="${producto.v_venta}">${producto.nombre}</option>`);
                        });
                    },
                    error: function() {
                        swal({
                            title: "Error",
                            text: 'Error al cargar productos.',
                            icon: "error",
                            button: "OK",
                        });
                    }
                });
            }

            function cargarClientes() {
                $.ajax({
                    url: 'http://localhost/salon_belleza/ventas/clientes',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        clientes = response.clientes;
                        let select = $('#clienteSelect');
                        select.empty();
                        select.append('<option value="">Seleccionar...</option>');
                        clientes.forEach(cliente => {
                            select.append(`<option value="${cliente.id}">${cliente.nombre}</option>`);
                        });
                    },
                    error: function() {
                        swal({
                            title: "Error",
                            text: 'Error al cargar clientes.',
                            icon: "error",
                            button: "OK",
                        });
                    }
                });
            }

            function actualizarPrecio() {
                let productoId = $('#productoSelect').val();
                let producto = productos.find(p => p.id == productoId);
                $('#cantidadInput').attr('data-precio', producto ? producto.v_venta : 0);
            }

            function agregarProducto(event) {
                if (event.key === 'Enter') {
                    let productoId = $('#productoSelect').val();
                    let cantidad = parseInt($('#cantidadInput').val(), 10);
                    let producto = productos.find(p => p.id == productoId);
                    let precioUnitario = parseFloat($('#cantidadInput').attr('data-precio'));

                    if (producto && cantidad > 0) {
                        let index = carrito.findIndex(p => p.nombre === producto.nombre);
                        if (index > -1) {
                            carrito[index].cantidad += cantidad;
                            actualizarValorTotal(index);
                        } else {
                            carrito.push({
                                nombre: producto.nombre,
                                precioUnitario: precioUnitario,
                                cantidad: cantidad,
                                descuento: 0,
                                valorTotal: cantidad * precioUnitario
                            });
                        }

                        mostrarCarrito();
                    }

                    $('#cantidadInput').val('');
                }
            }

            function actualizarValorTotal(index) {
                let item = carrito[index];
                item.valorTotal = item.cantidad * item.precioUnitario - item.descuento;
            }

            function mostrarCarrito() {
                let tbody = $('#tbodyProductos');
                tbody.empty();
                carrito.forEach((item, index) => {
                    tbody.append(`
                        <tr>
                            <td>${item.nombre}</td>
                            <td>${item.precioUnitario.toFixed(2)}</td>
                            <td>${item.cantidad}</td>
 <td><input type="number" class="descuento form-control" data-index="${index}" value="${item.descuento > 0 ? (item.descuento / (item.precioUnitario * item.cantidad) * 100).toFixed(2) : ''}" onkeyup="actualizarDescuento(event)" /></td>                            <td class="valor-total">${item.valorTotal.toFixed(2)}</td>
                            <td><button class="btn btn-danger btn-sm" onclick="eliminarProduct(this)">Eliminar</button></td>
                        </tr>
                    `);
                });
                actualizarTotal();
            }

            function eliminarProduct(button) {
                let row = $(button).closest('tr');
                let index = $(row).find('.descuento').data('index');
                carrito.splice(index, 1);
                mostrarCarrito();
            }

            function actualizarTotal() {
                let totalPagar = carrito.reduce((sum, item) => sum + item.valorTotal, 0);
                $('#totalPagar').text(totalPagar.toFixed(2));
            }

            function actualizarMontoPagado(event) {
                let montoPagado = parseFloat($('#montoPagado').val().replace(/[^0-9.]/g, ''));
                let totalPagar = carrito.reduce((sum, item) => sum + item.valorTotal, 0);
                let excedente = montoPagado - totalPagar;

                if (isNaN(montoPagado) || montoPagado < 0) {
                    excedente = -totalPagar; // Si el monto pagado es inválido, mostrar todo como excedente negativo
                }

                $('#excedenteDevolver').text(excedente.toFixed(2));
            }

            function finalizarVenta() {
                let totalPagar = carrito.reduce((sum, item) => sum + item.valorTotal, 0);
                $('#totalPagar').text(totalPagar.toFixed(2));
                $('#excedenteDevolver').text('0.00'); // Inicialmente, no hay excedente

                // Mostrar el modal de resumen
                $('#resumenModal').modal('show');
            }

            function actualizarDescuento(event) {
                let valor = $(event.target).val().replace(/[^0-9.]/g, ''); // Permitir solo números y puntos decimales
                $(event.target).val(valor);

                let porcentajeDescuento = parseFloat(valor);
                let index = $(event.target).data('index');
                let item = carrito[index];

                if (isNaN(porcentajeDescuento) || porcentajeDescuento < 0) {
                    porcentajeDescuento = 0;
                }

                let descuento = (porcentajeDescuento / 100) * (item.precioUnitario * item.cantidad);
                item.descuento = descuento;
                actualizarValorTotal(index);
                mostrarCarrito();
            }


            function confirmarVenta() {
                let montoPagado = parseFloat($('#montoPagado').val().replace(/[^0-9.]/g, ''));
                let totalPagar = carrito.reduce((sum, item) => sum + item.valorTotal, 0);

                if (isNaN(montoPagado) || montoPagado < totalPagar) {
                    swal({
                            title: "No se puede procesar",
                            text: 'El monto pagado debe ser al menos el total a pagar.',
                            icon: "info",
                            button: "OK",
                        });
                    return;
                }

                // Aquí enviar la venta al servidor
                $.ajax({
                    url: 'http://localhost/salon_belleza/ventas/confirmar',
                    method: 'POST',
                    data: {
                        cliente: $('#clienteSelect').val(),
                        productos: carrito,
                        montoPagado: montoPagado
                    },
                    success: function(response) {
                        swal({
                            title: "Venta realizada",
                            text: 'Venta confirmada con éxito.',
                            icon: "success",
                            button: "OK",
                        });
                        carrito = [];
                        mostrarCarrito();
                        $('#resumenModal').modal('hide');
                    },
                    error: function() {
                        swal({
                            title: "Error",
                            text: 'Error al confirmar la venta.',
                            icon: "error",
                            button: "OK",
                        });
                    }
                });
            }
        </script>

        <!-- partial -->