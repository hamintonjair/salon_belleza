<?php
namespace App\Controllers;
use App\Models\VentaModel;
use App\Models\EgresoModel;
use App\Models\MovimientoCajaModel;
use App\Models\DetallesPermisosModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class FinanzasController extends BaseController
{
    protected $ventaModel;
    protected $egresoModel;
    protected $movimientoCajaModel, $permisos;

    public function __construct()
    {
        $this->ventaModel = new VentaModel();
        $this->egresoModel = new EgresoModel();
        $this->movimientoCajaModel = new MovimientoCajaModel();
        $this->permisos = new DetallespermisosModel();
    }

    public function ingresos()
    {
        $session = session();
        $userId = $session->get('idusuario'); // Obtener el ID del usuario desde la sesión

        // Obtener permisos del usuario
        $permissions = $this->permisos->where('id_usuarios', $userId)->findAll();
        $data['permissions'] = array_column($permissions, 'id_permisos');

        if (in_array(14, $data['permissions'])) {
            $data['ingresos'] = $this->ventaModel->obtenerReporte();
            echo view('layout/admin/slider');
            echo view('layout/admin/nabvar');
            echo view('layout/finanzas/ingresos', $data);
            echo view('layout/admin/footer');
        } else {
            echo view('layout/usuario/no_permisos');
        } 
    }

    // Endpoint JSON para ingresos (ventas de productos y servicios)
    public function getIngresos()
    {
        $month = $this->request->getGet('month');
        $year = $this->request->getGet('year');
        $data = $this->ventaModel->obtenerReporte();
        // Filtrar por mes y año si se envían
        if ($month) {
            $data = array_filter($data, fn($row) => date('m', strtotime($row['fecha_venta'])) === $month);
        }
        if ($year) {
            $data = array_filter($data, fn($row) => date('Y', strtotime($row['fecha_venta'])) === $year);
        }
        // Reindexar para JSON
        $data = array_values($data);
        return $this->response->setJSON($data);
    }

    // Endpoint JSON para datos mensuales de ingresos
    public function getIngresosMensual()
    {
        $year = $this->request->getGet('year');
        $month = $this->request->getGet('month');
        $data = $this->ventaModel->obtenerReporteMensual();
        // Filtrar por año si se envía
        if ($year) {
            $data = array_filter($data, fn($row) => substr($row['mes'], 0, 4) === $year);
        }
        // Filtrar por mes si se envía
        if ($month) {
            $data = array_filter($data, fn($row) => substr($row['mes'], 5, 2) === $month);
        }
        // Reindexar array para JSON
        $data = array_values($data);
        return $this->response->setJSON($data);
    }

    // Endpoint JSON para egresos (gastos de operación)
    public function getEgresos()
    {
        $month = $this->request->getGet('month');
        $year  = $this->request->getGet('year');
        $data  = $this->egresoModel->obtenerReporte();
        if ($month) {
            $data = array_filter($data, fn($row) => date('m', strtotime($row['fecha'])) === $month);
        }
        if ($year) {
            $data = array_filter($data, fn($row) => date('Y', strtotime($row['fecha'])) === $year);
        }
        $data = array_values($data);
        return $this->response->setJSON($data);
    }

    // Endpoint JSON para datos mensuales de egresos
    public function getEgresosMensual()
    {
        $year  = $this->request->getGet('year');
        $month = $this->request->getGet('month');
        $data  = $this->egresoModel->obtenerReporteMensual();
        if ($year) {
            $data = array_filter($data, fn($row) => substr($row['mes'],0,4) === $year);
        }
        if ($month) {
            $data = array_filter($data, fn($row) => substr($row['mes'],5,2) === $month);
        }
        $data = array_values($data);
        return $this->response->setJSON($data);
    }

    public function egresos()
    {
        $session = session();
        $userId = $session->get('idusuario'); // Obtener el ID del usuario desde la sesión

        // Obtener permisos del usuario
        $permissions = $this->permisos->where('id_usuarios', $userId)->findAll();
        $data['permissions'] = array_column($permissions, 'id_permisos');

        if (in_array(3, $data['permissions'])) {
            $empleadoModel = new \App\Models\EmpleadoModel();
            $data['empleados'] = $empleadoModel->findAll();
            echo view('layout/admin/slider');
            echo view('layout/admin/nabvar');
            echo view('layout/finanzas/egresos', $data);
            echo view('layout/admin/footer');
        } else {
            echo view('layout/usuario/no_permisos');
        }
       
    }

    /**
     * Endpoint JSON para obtener saldo disponible (Ingresos - Egresos).
     */
    public function getSaldo()
    {
        $month = $this->request->getGet('month');
        $year  = $this->request->getGet('year');
        // Obtener saldo desde movimientos de caja (ingresos y egresos)
        $saldo = $this->movimientoCajaModel->obtenerSaldo($month, $year);
        return $this->response->setJSON(['saldo' => $saldo]);
    }

    /**
     * Crear un retiro (egreso) validando saldo disponible.
     */
    public function postEgreso()
    {
        $descr  = $this->request->getPost('descripcion');
        $monto  = floatval($this->request->getPost('monto'));
        $month  = $this->request->getPost('month');
        $year   = $this->request->getPost('year');
        // Saldo anterior (antes del retiro)
        $saldoAnterior = $this->movimientoCajaModel->obtenerSaldo($month, $year);
        if ($monto <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'El monto debe ser mayor a cero.']);
        }
        if ($monto > $saldoAnterior) {
            return $this->response->setJSON(['success' => false, 'message' => 'Monto supera el saldo disponible.']);
        }
        // Registrar en tabla de egresos
        $this->egresoModel->crearEgreso([
            'concepto' => $descr,
            'tipo'      => 'Retiro',
            'monto'     => $monto,
            'fecha'     => date('Y-m-d')
        ]);
        // Registrar movimiento en caja
        $this->movimientoCajaModel->crearMovimiento('egreso', $monto, $descr);
        // Saldo nuevo (después del retiro)
        $saldoNuevo = $this->movimientoCajaModel->obtenerSaldo($month, $year);
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Egreso registrado correctamente.',
            'saldo_anterior' => $saldoAnterior,
            'saldo_nuevo' => $saldoNuevo
        ]);
    }
        /**
     * Guardar egreso (general o préstamo a empleado)
     */
    public function guardarEgreso()
    {
        $tipo_egreso = $this->request->getPost('tipo_egreso');
        $empleado_id = $this->request->getPost('empleado_id');
        $monto = $this->request->getPost('monto');
        $motivo = $this->request->getPost('motivo');
        $fecha = date('Y-m-d H:i:s'); // Guarda fecha y hora (ej: 2025-04-18 14:35:22)

        if (!$tipo_egreso || !$monto) {
            return $this->response->setJSON(['success'=>false, 'message'=>'Datos incompletos.']);
        }

        // Validación de saldo suficiente
        $saldoDisponible = $this->egresoModel->getSaldoDisponible();
        if ($monto > $saldoDisponible) {
            return $this->response->setJSON(['success'=>false, 'message'=>'No hay recursos suficientes para este retiro.']);
        }

        $data = [
            'tipo_egreso' => $tipo_egreso,
            'monto' => $monto,
            'concepto' => $motivo,
            'fecha' => $fecha,
            'estado' => ($tipo_egreso === 'prestamo') ? 'pendiente' : null,
        ];
        if ($tipo_egreso === 'prestamo' && $empleado_id) {
            $data['empleado_id'] = $empleado_id;
        }

        $insert = $this->egresoModel->insert($data);
        if ($insert) {
            return $this->response->setJSON(['success'=>true, 'message'=>'Egreso registrado correctamente.']);
        } else {
            return $this->response->setJSON(['success'=>false, 'message'=>'Error al guardar el egreso.']);
        }
    }

    /**
     * Exportar ingresos a XLSX con formato: wrap text, encabezados en negrita y ancho ajustado.
     */
    public function exportIngresos()
    {
        $month = $this->request->getGet('month');
        $year  = $this->request->getGet('year');
        $data  = $this->ventaModel->obtenerReporte();
        if ($month) {
            $data = array_filter($data, fn($row) => date('m', strtotime($row['fecha_venta'])) === $month);
        }
        if ($year) {
            $data = array_filter($data, fn($row) => date('Y', strtotime($row['fecha_venta'])) === $year);
        }
        // Crear spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Encabezados
        $headers = ['Usuario','Producto/Servicio','Cantidad','Valor Total','Fecha'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col.'1', $header);
            $sheet->getStyle($col.'1')->getFont()->setBold(true);
            $sheet->getStyle($col.'1')->getAlignment()->setWrapText(true);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        // Datos
        $rowNum = 2;
        foreach ($data as $row) {
            $col = 'A';
            $sheet->setCellValue($col.$rowNum, $row['nombre_usuario'].' '.$row['apellidos']); $sheet->getStyle($col.$rowNum)->getAlignment()->setWrapText(true); $col++;
            $sheet->setCellValue($col.$rowNum, $row['producto_nombre']); $sheet->getStyle($col.$rowNum)->getAlignment()->setWrapText(true); $col++;
            $sheet->setCellValue($col.$rowNum, $row['cantidad']); $sheet->getStyle($col.$rowNum)->getAlignment()->setWrapText(true); $col++;
            $sheet->setCellValue($col.$rowNum, $row['valor_total']); $sheet->getStyle($col.$rowNum)->getAlignment()->setWrapText(true); $col++;
            $sheet->setCellValue($col.$rowNum, $row['fecha_venta']); $sheet->getStyle($col.$rowNum)->getAlignment()->setWrapText(true);
            $rowNum++;
        }
        // Salida
        $filename = 'ingresos_'.($year?:'todos').'_'.($month?:'todos').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
