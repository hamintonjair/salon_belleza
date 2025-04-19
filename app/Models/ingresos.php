<div class="main-panel">
    <div class="content-wrapper">
        <h4>Ingresos</h4>
        <!-- FILTROS y TOTAL -->
        <div class="row mb-3">
          <div class="col-md-3">
            <label for="monthFilter">Mes</label>
            <select id="monthFilter" class="form-control">
              <option value="">Todos</option>
              <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= sprintf('%02d', $m) ?>"><?= strftime('%B', mktime(0,0,0,$m,1)) ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label for="yearFilter">Año</label>
            <select id="yearFilter" class="form-control">
              <option value="">Todos</option>
              <?php for ($y = date('Y') - 5; $y <= date('Y'); $y++): ?>
                <option value="<?= $y ?>"><?= $y ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label>Total Ingresos</label>
            <div id="totalIngresos">$0.00</div>
          </div>
        </div>
        <canvas id="chartIngresosMonthly" height="100"></canvas>
        <div class="container">
            <div class="table-responsive">
                <table id="tableIngresos" class="table table-striped display" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>Usuario</th>
                            <th>Producto/Servicio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
