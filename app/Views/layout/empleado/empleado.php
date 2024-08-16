<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="d-flex justify-content-between mb-4">
            <!-- Button trigger modal -->     
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" onclick="ModalEmpleado();" data-bs-target="#exampleModal">
                Nuevo
            </button>
        </div>
        <h4 class="modal-title" id="titleModal">Panel Empleados</h4>
     <hr>
        <!-- Container para la tabla -->
        <div class="container">
            <div class="table-responsive">
                <table id="tableEmpleados" class="table table-striped display" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Cédula</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tfoot>

                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modelEmpleado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-green headerRegister">
                        <h5 class="modal-title" id="titleModal">Nuevo Empleado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="frmEmpleado" autocomplete="off">
                            <input type='hidden' id='idEmpleado' name='idEmpleado' value=''>

                            <div class="row">
                                <div class="col-md-6 mb-6">
                                    <label for="inputNombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control valid validText" id="nombre" name="nombre" placeholder="Nombres" required>
                                </div>
                                <div class="col-md-6 mb-6">
                                    <label for="inputApellidos" class="form-label">Apellidos</label>
                                    <input type="text" class="form-control valid validText" id="apellidos" name="apellidos" placeholder="Apellidos" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="inputTelefono" class="form-label">Cédula</label>
                                    <input type="number" class="form-control valiod validNumber" id="cedula" name="cedula" placeholder="Cédula" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="inputTelefono" class="form-label">Teléfono</label>
                                    <input type="number" class="form-control valiod validNumber" id="telefono" name="telefono" placeholder="Teléfono" required>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="inputDireccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" required>
                                </div>
                            </div>
                                                
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button id="btnGuardarEmpleado" type="submit" class="btn btn-primary"><span id='btnText'>
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