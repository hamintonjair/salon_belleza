<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <h4 class="modal-title" id="titleModal">Panel Agenda</h4>
        <hr>tableAgenda
        <!-- Container para la tabla -->
        <div class="container">
            <div class="table-responsive">
                <table id="tableAgenda" class="table table-striped display" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Cédula</th>
                            <th>Teléfono</th>
                            <th>Servicios/Productos</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tfoot>

                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Modal para Agregar Servicios y Productos -->
        <div class="modal fade" id="modalAgregarServicioProducto" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                  
                    <div class="modal-header bg-green headerRegister">
                        <h5 class="modal-title" id="titleModal">Agregar Servicio o Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="turno_id"> <!-- Formulario para seleccionar servicio -->
                        <div class="form-group">
                            <label for="servicio_id">Servicio</label>
                            <select id="servicio_id" class="form-control">
                                <option value="">Seleccione un servicio</option>
                                <!-- Opciones serán cargadas por JavaScript -->
                            </select>
                        </div>

                        <!-- Formulario para seleccionar producto -->
                        <div class="form-group">
                            <label for="producto_id">Producto</label>
                            <select id="producto_id" class="form-control">
                                <option value="">Seleccione un producto</option>
                                <!-- Opciones serán cargadas por JavaScript -->
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="guardarServicioOProducto()">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para asignar trabajador -->
        <!-- Modal para asignar trabajador -->
        <div id="modalAsignarTrabajador" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-green headerRegister">
                        <h5 class="modal-title" id="titleModal">Asignar Trabajador</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formAsignarTrabajador">
                            <input type="hidden" id="turno_idi" name="turno_idi">
                            <div class="form-group">
                                <label for="trabajador_id">Trabajador</label>
                                <select id="trabajador_id" name="trabajador_id" class="form-control">
                                    <option value="">Seleccione un trabajador</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Asignar Trabajador</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <script>

        </script>

        <style>
            .bg-green {
                background-color: #223e9c;
                /* Cambia el valor según el tono de verde que prefieras */
                color: white;
                /* Color del texto, puedes ajustarlo según tu preferencia */
            }
        </style>