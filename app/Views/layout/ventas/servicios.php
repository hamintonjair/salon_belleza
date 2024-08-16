<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="d-flex justify-content-between mb-4">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" onclick="ModalServicio();" data-bs-target="#exampleModal">
                Nuevo
            </button>
        </div>
        <h4 class="modal-title" id="titleModal">Panel Servicios</h4>
        <hr>
        <!-- Container para la tabla -->
        <div class="container">
            <div class="table-responsive">
                <table id="tableServicio" class="table table-striped display" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <!-- <th>#</th> -->
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Valor a pagar</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tfoot>

                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modelServicio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header bg-green headerRegister">
                        <h5 class="modal-title" id="titleModal">Nuevo Servicio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="frmServicio" autocomplete="off">
                            <input type='hidden' id='idServicio' name='idServicio' value=''>

                            <div class="row">
                                <div class="col-md-12 mb-6">
                                    <label for="inputNombre" class="form-label">Servicio</label>
                                    <input type="text" class="form-control valid validText" id="nombre" name="nombre" placeholder="Servicio" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-6">
                                    <label for="inputPrecio" class="form-label">Precio</label>
                                    <input type="number" class="form-control valid validNumber" id="precio" name="precio" placeholder="Precio" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-6">
                                    <label for="inputPrecio" class="form-label">Valor que se paga</label>
                                    <input type="number" class="form-control valid validNumber" id="pago_empleado" name="pago_empleado" placeholder="valor que se paga" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button id="btnGuardarServicio" type="submit" class="btn btn-primary"><span id='btnText'>
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