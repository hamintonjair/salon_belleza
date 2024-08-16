<div class="main-panel">
    <div class="content-wrapper">
        <h4 class="modal-title" id="titleModal">Ganancias Diarias acumuladas en el Mes según el tipo de Productos/Servicios.</h4>
        <!-- Botones de navegación -->
        <br>
        <div class="mb-3">
            <a href="<?= base_url('report') ?>" class="btn btn-primary">Volver a reportes</a>
        </div>
        <hr>
        <!-- Tabla de ganancias -->
        <div class="container">
            <div class="table-responsive">
                <table id="tableGanancias" class="table table-striped display" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">Fecha de venta</th>
                            <th class="text-center">Productos</th>
                            <th class="text-center">Servicios</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ganancias_mensuales as $ganancia): ?>
                            <tr>
                                <td class="text-center"><?= $ganancia['mes'] ?></td>
                                <td class="text-center">$<?= number_format($ganancia['producto'], 2) ?></td>
                                <td class="text-center">$<?= number_format($ganancia['servicio'], 2) ?></td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

