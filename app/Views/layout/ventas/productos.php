<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="d-flex justify-content-between mb-4">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" onclick="ModalProducto();" data-bs-target="#exampleModal">
                Nuevo
            </button>
        </div>
        <h4 class="modal-title" id="titleModal">Panel Productos</h4>
        <hr>
        <!-- Container para la tabla -->
        <div class="container">
            <div class="table-responsive">
                <table id="tableProducto" class="table table-striped display" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <!-- <th>#</th> -->
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>V. Compra</th>
                            <th>V. Venta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tfoot>

                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modelProducto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-green headerRegister">
                        <h5 class="modal-title" id="titleModal">Nuevo Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="frmProducto" autocomplete="off">
                            <input type='hidden' id='idProducto' name='idProducto' value=''>

                            <div class="row">
                                <div class="col-md-6 mb-6">
                                    <label for="inputNombre" class="form-label">Producto</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Producto" required>
                                </div>
                     
                                <div class="col-md-6 mb-6">
                                    <label for="inputCantidad" class="form-label">Cantidad</label>
                                    <input type="number" class="form-control valid validNumber" id="cantidad" name="cantidad" placeholder="Cantidad" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-6">
                                    <label for="inputCompra" class="form-label">Precio Compra</label>
                                    <input type="number" class="form-control valid validNumber" id="v_compra" name="v_compra" placeholder="Valor de compra" required>
                                </div>                         
                                <div class="col-md-6 mb-6">
                                    <label for="inputVenta" class="form-label">Precio Venta</label>
                                    <input type="number" class="form-control valid validNumber" id="v_venta" name="v_venta" placeholder="Valor de venta" required>
                                </div>
                            </div>                         
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button id="btnGuardarProducto" type="submit" class="btn btn-primary"><span id='btnText'>
                                        Guardar</span></button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>


        <style>
            .bg-green {
                background-color: #223e9c;
                /* Cambia el valor según el tono de verde que prefieras */
                color: white;
                /* Color del texto, puedes ajustarlo según tu preferencia */
            }
        </style>