<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-12">
        <h2 class="mb-4">Manual de Usuario: Documentación de Funcionalidades</h2>
        <div class="accordion" id="manualAccordion">
          <!-- Módulo Administración -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingAdmin">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdmin" aria-expanded="true" aria-controls="collapseAdmin">
                Módulo Administración
              </button>
            </h2>
            <div id="collapseAdmin" class="accordion-collapse collapse show" aria-labelledby="headingAdmin" data-bs-parent="#manualAccordion">
              <div class="accordion-body">
                <ul>
                  <li><strong>Empresa:</strong> Administra datos corporativos como nombre, dirección y contacto; configuración de logotipo y horarios de atención. <a href="<?= base_url('empresa') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Empresa</a></li>
                  <li><strong>Usuarios:</strong> Alta, baja y modificación de cuentas; asignación de roles y permisos detallados. <a href="<?= base_url('Users') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Usuarios</a></li>
                  <li><strong>Empleados:</strong> Registro de empleados con datos personales, historial de préstamos, horarios y cálculo de nómina. <a href="<?= base_url('empleado') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Empleados</a></li>
                  <li><strong>Clientes:</strong> Gestión de clientes con historial de citas, segmentación por frecuencia y datos de contacto. <a href="<?= base_url('Cliente') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Clientes</a></li>
                  <li><strong>Servicios:</strong> Creación y edición de servicios, fijación de precios, duración y disponibilidad por agenda. <a href="<?= base_url('servic') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Servicios</a></li>
                  <li><strong>Productos:</strong> Control de inventario, alertas de stock mínimo, categorías y proveedores. <a href="<?= base_url('product') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Productos</a></li>
                  <li><strong>Reportes:</strong> Indicadores clave como servicios más vendidos y clientes frecuentes con gráficos interactivos. <a href="<?= base_url('report') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Reportes</a></li>
                </ul>
              </div>
            </div>
          </div>
          <!-- Módulo Finanzas -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingFinance">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFinance" aria-expanded="false" aria-controls="collapseFinance">
                Módulo Finanzas
              </button>
            </h2>
            <div id="collapseFinance" class="accordion-collapse collapse" aria-labelledby="headingFinance" data-bs-parent="#manualAccordion">
              <div class="accordion-body">
                <ul>
                  <li><strong>Ingresos:</strong> Registro detallado de ventas de servicios y productos, con filtrado por fechas y categorías. <a href="<?= base_url('finanzas/ingresos') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Ingresos</a></li>
                  <li><strong>Egresos:</strong> Registro de gastos, desembolsos y préstamos a empleados; carga de comprobantes y descripciones. <a href="<?= base_url('finanzas/egresos') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Egresos</a></li>
                  <li><strong>Pagos:</strong> Generación y gestión de nóminas con cálculo automático de deducciones de préstamos e impuestos. <a href="<?= base_url('pagos') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Pagos</a></li>
                  <li><strong>Exportar:</strong> Exportación de reportes financieros en Excel y PDF con opciones de personalización. <a href="<?= base_url('finanzas/exportIngresos') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Exportar</a></li>
                </ul>
              </div>
            </div>
          </div>
          <!-- Módulo Agenda y Turnos -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingTurns">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTurns" aria-expanded="false" aria-controls="collapseTurns">
                Módulo Agenda y Turnos
              </button>
            </h2>
            <div id="collapseTurns" class="accordion-collapse collapse" aria-labelledby="headingTurns" data-bs-parent="#manualAccordion">
              <div class="accordion-body">
                <ul>
                  <li><strong>Calendario:</strong> Vista mensual, semanal o diaria con eventos de citas; permite arrastrar y soltar para reprogramar. <a href="<?= base_url('dashboard') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Agenda</a></li>
                  <li><strong>Agendar Turno:</strong> Formulario de reserva con búsqueda de cliente, servicio, trabajador y selección de hora disponible.</li>
                  <li><strong>Estado:</strong> Seguimiento en tiempo real de la cita: pendiente, atendiendo, finalizado o anulado, con códigos de color. <a href="<?= base_url('turnos') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Turnos</a></li>
                  <li><strong>Asignar Trabajador:</strong> Asignación de personal a turnos, control de carga horaria y disponibilidad.</li>
                </ul>
              </div>
            </div>
          </div>
          <!-- Módulo Ventas y Facturas -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingSales">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSales" aria-expanded="false" aria-controls="collapseSales">
                Módulo Ventas y Facturas
              </button>
            </h2>
            <div id="collapseSales" class="accordion-collapse collapse" aria-labelledby="headingSales" data-bs-parent="#manualAccordion">
              <div class="accordion-body">
                <ul>
                  <li><strong>Vender:</strong> Interfaz intuitiva para facturar servicios y productos, con cálculos automáticos de totales. <a href="<?= base_url('vent') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Ventas</a></li>
                  <li><strong>Historial:</strong> Consulta de transacciones previas con filtros avanzados por fecha, cliente y vendedor.<a href="<?= base_url('list') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Historial</a></li>
                  <li><strong>Anular Venta:</strong> Proceso seguro de devolución y actualiza inventario automáticamente.</li>
                  <li><strong>Generar PDF:</strong> Emisión de factura digital con logo personalizado y datos de cliente para imprimir o enviar por email.</li>
                </ul>
              </div>
            </div>
          </div>
          <!-- Módulo Reportes -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingReports">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReports" aria-expanded="false" aria-controls="collapseReports">
                Módulo Reportes
              </button>
            </h2>
            <div id="collapseReports" class="accordion-collapse collapse" aria-labelledby="headingReports" data-bs-parent="#manualAccordion">
              <div class="accordion-body">
                <ul>
                  <li><strong>Ganancias:</strong> Gráficos interactivos y tabla de ingresos netos por mes, trimestre o año con comparativas. <a href="<?= base_url('reportes/ganancias') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Ganancias</a></li>
                  <li><strong>Actividades:</strong> Registro de auditoría de usuarios: accesos, altas, modificaciones y exportaciones. <a href="<?= base_url('report') ?>" class="btn btn-sm btn-primary" target="_blank">Ir a Auditoría</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
