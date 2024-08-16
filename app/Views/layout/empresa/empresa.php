<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <h4 class="modal-title" id="titleModal">Panel Empresa</h4>
        <hr>
        <div class="container-fluid px-4 dataTable-container">
            <div class="card-body">
                <form id="frmConfiguracion">
                    <input type="hidden" id="idempresa" name="idempresa">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre de la Empresa</label>
                                <input type="text" id="nombre" name="nombre" class="form-control " disabled>
                            </div>
                            <div class="form-group">
                                <label for="correo">Correo</label>
                                <input type="email" id="correo" name="correo" class="form-control valid validEmail" disabled>
                            </div>
                            <div class="form-group">
                                <label for="nit">Nit</label>
                                <input type="text" id="nit" name="nit" class="form-control" disabled>
                            </div>
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input type="text" id="direccion" name="direccion" class="form-control" disabled>
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="number" id="telefono" name="telefono" class="form-control valid validNumber" disabled>
                            </div>
                            <div class="form-group">
                                <label for="ciudad">Ciudad</label>
                                <input type="text" id="ciudad" name="ciudad" class="form-control " disabled>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="btnHabilitar" class="btn btn-secondary">Habilitar Edición</button>
                    <button type="submit" class="btn btn-success" id="btnGuardar" disabled>Actualizar</button>
                </form>
            </div>
        </div>
  

<script>
    // Obtener los datos de la empresa cuando se carga la página
    document.addEventListener('DOMContentLoaded', function() {
        fetch('http://localhost/salon_belleza/empresa/getDatos')
            .then(response => response.json())
            .then(data => {
                // Rellenar los campos con los datos obtenidos
                document.getElementById('idempresa').value = data.id;
                document.getElementById('nombre').value = data.nombre;
                document.getElementById('correo').value = data.email;
                document.getElementById('nit').value = data.nit;
                document.getElementById('direccion').value = data.direccion;
                document.getElementById('telefono').value = data.telefono;
                document.getElementById('ciudad').value = data.ciudad;
            })
            .catch(error => console.error('Error fetching data:', error));
    });

    // Habilitar campos para edición
    document.getElementById('btnHabilitar').addEventListener('click', function() {
        const inputs = document.querySelectorAll('#frmConfiguracion input');
        inputs.forEach(input => input.removeAttribute('disabled'));
        document.getElementById('btnGuardar').removeAttribute('disabled');
        document.getElementById('btnHabilitar').setAttribute('disabled', 'true');
    });

    // Enviar datos al servidor
    document.getElementById('frmConfiguracion').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);

        fetch('http://localhost/salon_belleza/empresa/actualizar', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Datos actualizados correctamente.');
                // Deshabilitar campos después de guardar
                const inputs = document.querySelectorAll('#frmConfiguracion input');
                inputs.forEach(input => input.setAttribute('disabled', 'true'));
                document.getElementById('btnHabilitar').removeAttribute('disabled');
                document.getElementById('btnGuardar').setAttribute('disabled', 'true');
            } else {
                alert('Error al actualizar los datos.');
            }
        })
        .catch(error => console.error('Error updating data:', error));
    });
</script>
