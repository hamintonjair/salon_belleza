<div class="main-panel">
    <div class="content-wrapper">
        <h4 class="modal-title" id="titleModal">Reportes de Ventas y Servicios</h4>
        <br>
        <!-- Botones de navegación -->
        <div class="mb-3">
            <a href="<?= base_url('reportes/ganancias') ?>" class="btn btn-primary">Ver Ganancias</a>
        </div>
        <hr>
        <!-- Tabla de reportes -->
        <div class="container">
            <div class="table-responsive">
                <table id="tableReportes" class="table table-striped display" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>Usuario</th>
                            <th>Productos/Servicios</th>
                            <th>Cantidad</th>
                            <th>Valor Total</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportes as $reporte): ?>
                            <tr>
                                <td><?= $reporte['nombre_usuario'].' '.$reporte['apellidos'] ?></td>
                                <td><?= $reporte['producto_nombre'] ?></td>
                                <td><?= $reporte['cantidad'] ?></td>
                                <td>$<?= number_format($reporte['valor_total'], 2) ?></td>
                                <td><?= $reporte['fecha_venta'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
<script>
    new DataTable("#tableReportes", {
        responsive: true,
        destroy: true,
        lengthMenu: [10, 25, 50, 75, 100],
        order: [
            [4, 'desc'] // Ordena por la columna Fecha (índice 4)
        ]
    });
</script>
