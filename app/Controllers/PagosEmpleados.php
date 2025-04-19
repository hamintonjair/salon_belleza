<?php

namespace App\Controllers;

use App\Models\EmpleadoModel;
use App\Models\TurnoServiciosModel;
use App\Models\DetallesPermisosModel;
use App\Models\AgendaModel;
use App\Models\pagosEmpleadosModel;

class PagosEmpleados extends BaseController
{
    protected $empleadoModel;
    protected $turnoServicioModel, $permisos, $turno, $pagosEmpleadosModel;

    public function __construct()
    {
        $this->empleadoModel = new EmpleadoModel();
        $this->turnoServicioModel = new TurnoServiciosModel();
        $this->permisos = new DetallespermisosModel();
        $this->turno = new AgendaModel();
        $this->pagosEmpleadosModel = new pagosEmpleadosModel();
    }

    public function pagos()
    {
        try {
            $session = session();
            $userId = $session->get('idUsuario'); // Obtener el ID del usuario desde la sesión
            if (!$userId) {
                echo "Error: Sesión caducada. Inicie sesión de nuevo.";
                return;
            }
            // Obtener permisos del usuario
            $permissions = $this->permisos->where('id_usuarios', $userId)->findAll();
            $data['permissions'] = array_column($permissions, 'id_permisos');

            if (in_array(11, $data['permissions'])) {
                echo view('layout/admin/slider');
                echo view('layout/admin/nabvar');
                echo view('layout/usuario/pagos_empleados');
                echo view('layout/admin/footer');
            } else {
                echo view('layout/usuario/no_permisos');
            }
        } catch (\Throwable $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    // obtener los pagos a empleados
    public function getEmpleados()
    {
        $fechaHoy = date('Y-m-d');

        // Obtener todos los empleados con sus datos básicos
        $empleados = $this->empleadoModel
            ->select('empleado.id, empleado.nombre, empleado.apellidos, empleado.cedula, empleado.telefono, empleado.direccion, COUNT(DISTINCT agenda.id) AS agenda_asignados')
            ->join('agenda', 'agenda.trabajador_id = empleado.id', 'left')
            ->groupBy('empleado.id')
            ->findAll();

        // Obtener todos los servicios pendientes (no pagados) para cada empleado (agenda y turno_servicios)
        $serviciosPendientes = $this->turnoServicioModel
            ->select('turno_servicios.trabajador_id, COUNT(*) AS servicios_pendientes')
            ->join('agenda', 'agenda.id = turno_servicios.turno_id', 'inner')
            ->join('pagos_empleados', 'agenda.trabajador_id = pagos_empleados.empleado_id AND agenda.date = pagos_empleados.fecha_pago', 'left')
            ->where('turno_servicios.estado_pago', 'pendiente')
            ->groupBy('turno_servicios.trabajador_id')
            ->findAll();

        // --- Lógica de descuento automático de préstamos en pagos a empleados ---
        // Ejemplo: cuando se va a pagar a un empleado
        // (esto deberías integrarlo en tu método de registrar pago)
        /*
        $egresoModel = new \App\Models\EgresoModel();
        $prestamos = $egresoModel->where([
            'empleado_id' => $empleadoId,
            'tipo_egreso' => 'prestamo',
            'estado' => 'pendiente'
        ])->findAll();
        $totalPrestamos = array_sum(array_column($prestamos, 'monto'));
        $pagoNeto = $montoAPagar - $totalPrestamos;
        foreach ($prestamos as $prestamo) {
            $egresoModel->update($prestamo['id'], ['estado' => 'saldado']);
        }
        // Registrar el pago neto en pagosEmpleadosModel
        $this->pagosEmpleadosModel->insert([
            'empleado_id' => $empleadoId,
            'pago' => $pagoNeto,
            'fecha_pago' => date('Y-m-d'),
            // ...otros campos
        ]);
        */
        // --- Fin lógica préstamos ---
        // Obtener pagos realizados
        $pagos = $this->pagosEmpleadosModel
            ->select('empleado_id, SUM(pago) AS total_pagado, MAX(fecha_pago) AS ultima_fecha_pago')
            ->where('fecha_pago <=', $fechaHoy)
            ->groupBy('empleado_id')
            ->findAll();

        // Convertir resultados a arrays para fácil acceso
        $pagosArray = [];
        foreach ($pagos as $pago) {
            $pagosArray[$pago['empleado_id']] = [
                'total_pagado' => $pago['total_pagado'],
                'ultima_fecha_pago' => $pago['ultima_fecha_pago']
            ];
        }

        $serviciosArray = [];
        foreach ($serviciosPendientes as $servicio) {
            $serviciosArray[$servicio['trabajador_id']] = $servicio['servicios_pendientes'];
        }

        // Combinar la información de empleados, pagos y servicios pendientes
        foreach ($empleados as &$empleado) {
            $empleadoId = $empleado['id'];

            // Verificar si existen pagos para este empleado
            $totalPagado = isset($pagosArray[$empleadoId]) ? $pagosArray[$empleadoId]['total_pagado'] : 0;
            $ultimaFechaPago = isset($pagosArray[$empleadoId]) ? $pagosArray[$empleadoId]['ultima_fecha_pago'] : null;

            // Verificar si hay servicios pendientes para hoy
            $serviciosPendientes = isset($serviciosArray[$empleadoId]) ? $serviciosArray[$empleadoId] : 0;

            // Agregar información de pagos y pagos pendientes
            $empleado['total_pagado'] = $totalPagado;
            $empleado['ultima_fecha_pago'] = $ultimaFechaPago ? date('Y-m-d', strtotime($ultimaFechaPago)) : 'No disponible';
            $empleado['boton_ver_pagos'] = $totalPagado > 0;
            $empleado['pagos_pendientes'] = (int)$serviciosPendientes;
        }
        return $this->response->setJSON([
            'empleados' => $empleados
        ]);
    }

    // ver los servicios que realizo cada empleado
    public function getServiciosRealizados($empleadoId)
    {
        $turno = $this->turno;
        $turnoServicioModel = $this->turnoServicioModel;

        // Obtener servicios de agenda
        $serviciosAgendas = $turno
            ->select('agenda.id, agenda.servicio AS nombre_servicio, 
                CAST(agenda.precio AS DECIMAL(10,2)) AS precio_servicio, 
                CAST(agenda.pago_empleado AS DECIMAL(10,2)) AS pago_empleado, 
                agenda.date AS fecha_servicio, agenda.trabajador_id')
            ->join('pagos_empleados', 'agenda.trabajador_id = pagos_empleados.empleado_id AND agenda.date = pagos_empleados.fecha_pago', 'left')
            ->where('agenda.trabajador_id', $empleadoId)
            ->where('agenda.estado_pago', 'pendiente')
            ->where('(pagos_empleados.estado IS NULL OR pagos_empleados.estado != "pagado")')
            ->findAll();

        // Obtener servicios adicionales
        $serviciosAdicionales = $turnoServicioModel
            ->select('turno_servicios.id AS id, turno_servicios.nombre_servicio AS nombre_servicio, 
                CAST(turno_servicios.precio_servicio AS DECIMAL(10,2)) AS precio_servicio, 
                CAST(turno_servicios.pago_empleado AS DECIMAL(10,2)) AS pago_empleado, 
                agenda.date AS fecha_servicio, agenda.trabajador_id')
            ->join('agenda', 'agenda.id = turno_servicios.turno_id', 'inner')
            ->join('pagos_empleados', 'agenda.trabajador_id = pagos_empleados.empleado_id AND agenda.date = pagos_empleados.fecha_pago', 'left')
            ->where('agenda.trabajador_id', $empleadoId)
            ->where('agenda.estado_pago', 'pendiente')
            ->where('turno_servicios.estado_pago', 'pendiente')
            ->where('(pagos_empleados.estado IS NULL OR pagos_empleados.estado != "pagado")')
            ->findAll();

        $serviciosFinales = [];
        $totalPagar = 0;
        foreach ($serviciosAgendas as $servicio) {
            $servicio['origen'] = 'agenda';
            $serviciosFinales[] = $servicio;
            $totalPagar += $servicio['pago_empleado'];
        }
        foreach ($serviciosAdicionales as $servicio) {
            $servicio['origen'] = 'turno_servicios';
            $encontrado = false;
            foreach ($serviciosFinales as $finalServicio) {
                if (
                    $finalServicio['nombre_servicio'] === $servicio['nombre_servicio'] &&
                    $finalServicio['fecha_servicio'] === $servicio['fecha_servicio']
                ) {
                    $encontrado = true;
                    break;
                }
            }
            if (!$encontrado) {
                $serviciosFinales[] = $servicio;
                $totalPagar += $servicio['pago_empleado'];
            }
        }

        return $this->response->setJSON([
            'servicios' => $serviciosFinales,
            'total_pagar' => number_format($totalPagar, 2)
        ]);
    }

    // Nuevo: obtener resumen de pago antes de procesar
    public function getResumenPago($empleadoId)
    {
        // Obtener servicios de agenda y adicionales igual que en pagarEmpleado
        $turno = $this->turno;
        $turnoServicioModel = $this->turnoServicioModel;
        $egresoModel = new \App\Models\EgresoModel();

        $serviciosAgendas = $turno
            ->select('agenda.id, agenda.servicio AS nombre_servicio, 
                CAST(agenda.precio AS DECIMAL(10,2)) AS precio_servicio, 
                CAST(agenda.pago_empleado AS DECIMAL(10,2)) AS pago_empleado, 
                agenda.date AS fecha_servicio, agenda.trabajador_id')
            ->join('pagos_empleados', 'agenda.trabajador_id = pagos_empleados.empleado_id AND agenda.date = pagos_empleados.fecha_pago', 'left')
            ->where('agenda.trabajador_id', $empleadoId)
            ->where('agenda.estado_pago', 'pendiente')
            ->where('(pagos_empleados.estado IS NULL OR pagos_empleados.estado != "pagado")')
            ->findAll();

        $serviciosAdicionales = $turnoServicioModel
            ->select('turno_servicios.id AS id, turno_servicios.nombre_servicio AS nombre_servicio, 
                CAST(turno_servicios.precio_servicio AS DECIMAL(10,2)) AS precio_servicio, 
                CAST(turno_servicios.pago_empleado AS DECIMAL(10,2)) AS pago_empleado, 
                agenda.date AS fecha_servicio, agenda.trabajador_id')
            ->join('agenda', 'agenda.id = turno_servicios.turno_id', 'inner')
            ->join('pagos_empleados', 'agenda.trabajador_id = pagos_empleados.empleado_id AND agenda.date = pagos_empleados.fecha_pago', 'left')
            ->where('agenda.trabajador_id', $empleadoId)
            ->where('agenda.estado_pago', 'pendiente')
            ->where('(pagos_empleados.estado IS NULL OR pagos_empleados.estado != "pagado")')
       
            ->findAll();

        $serviciosFinales = [];
        $totalPagar = 0;
        foreach ($serviciosAgendas as $servicio) {
            $servicio['origen'] = 'agenda';
            $serviciosFinales[] = $servicio;
            $totalPagar += $servicio['pago_empleado'];
        }
        foreach ($serviciosAdicionales as $servicio) {
            $servicio['origen'] = 'turno_servicios';
            $encontrado = false;
            foreach ($serviciosFinales as $finalServicio) {
                if (
                    $finalServicio['nombre_servicio'] === $servicio['nombre_servicio'] &&
                    $finalServicio['fecha_servicio'] === $servicio['fecha_servicio']
                ) {
                    $encontrado = true;
                    break;
                }
            }
            if (!$encontrado) {
                $serviciosFinales[] = $servicio;
                $totalPagar += $servicio['pago_empleado'];
            }
        }

        // Préstamos pendientes
        $prestamos = $egresoModel->where([
            'empleado_id' => $empleadoId,
            'tipo_egreso' => 'prestamo',
            'estado' => 'pendiente'
        ])->orderBy('fecha', 'ASC')->findAll();

        $pagoNeto = $totalPagar;
        $totalDescontado = 0;
        $detallePrestamos = [];
        foreach ($prestamos as $prestamo) {
            if ($pagoNeto <= 0) break;
            if ($pagoNeto >= $prestamo['monto']) {
                $totalDescontado += $prestamo['monto'];
                $detallePrestamos[] = [
                    'id' => $prestamo['id'],
                    'monto' => floatval($prestamo['monto']),
                    'fecha' => $prestamo['fecha'],
                    'saldado' => true
                ];
                $pagoNeto -= $prestamo['monto'];
            } else {
                $totalDescontado += $pagoNeto;
                $detallePrestamos[] = [
                    'id' => $prestamo['id'],
                    'monto' => floatval($pagoNeto),
                    'fecha' => $prestamo['fecha'],
                    'saldado' => false
                ];
                $pagoNeto = 0;
            }
        }

        return $this->response->setJSON([
            'total_servicios' => floatval($totalPagar),
            'total_prestamos_descontados' => floatval($totalDescontado),
            'detalle_prestamos' => $detallePrestamos,
            'pago_neto' => floatval($pagoNeto)
        ]);
    }


    // Pago a empleados
    public function pagarEmpleado($empleadoId)
    {
        try {
            $session = session();
            $userId = $session->get('idUsuario');
            $fechaHoy = date('Y-m-d');

            $empleado = $this->empleadoModel->find($empleadoId);
            if (!$empleado) {
                return $this->response->setJSON(['success' => false, 'message' => 'Empleado no encontrado.']);
            }

            // Obtener servicios de agenda
            $turno = $this->turno;
            $turnoServicioModel = $this->turnoServicioModel;
            $egresoModel = new \App\Models\EgresoModel();

            $serviciosAgendas = $turno
                ->select('agenda.id, agenda.servicio AS nombre_servicio, 
                    CAST(agenda.precio AS DECIMAL(10,2)) AS precio_servicio, 
                    CAST(agenda.pago_empleado AS DECIMAL(10,2)) AS pago_empleado, 
                    agenda.date AS fecha_servicio, agenda.trabajador_id')
                ->join('pagos_empleados', 'agenda.trabajador_id = pagos_empleados.empleado_id AND agenda.date = pagos_empleados.fecha_pago', 'left')
                ->where('agenda.trabajador_id', $empleadoId)
            ->where('agenda.estado_pago', 'pendiente')
                ->where('(pagos_empleados.estado IS NULL OR pagos_empleados.estado != "pagado")')
                ->findAll();

            $serviciosAdicionales = $turnoServicioModel
                ->select('turno_servicios.id AS id, turno_servicios.nombre_servicio AS nombre_servicio, 
                    CAST(turno_servicios.precio_servicio AS DECIMAL(10,2)) AS precio_servicio, 
                    CAST(turno_servicios.pago_empleado AS DECIMAL(10,2)) AS pago_empleado, 
                    agenda.date AS fecha_servicio, agenda.trabajador_id')
                ->join('agenda', 'agenda.id = turno_servicios.turno_id', 'inner')
                ->join('pagos_empleados', 'agenda.trabajador_id = pagos_empleados.empleado_id AND agenda.date = pagos_empleados.fecha_pago', 'left')
                ->where('agenda.trabajador_id', $empleadoId)
            ->where('agenda.estado_pago', 'pendiente')
                ->where('(pagos_empleados.estado IS NULL OR pagos_empleados.estado != "pagado")')
                ->findAll();

            $serviciosFinales = [];
            $totalPagar = 0;
            foreach ($serviciosAgendas as $servicio) {
                $servicio['origen'] = 'agenda';
                $serviciosFinales[] = $servicio;
                $totalPagar += $servicio['pago_empleado'];
            }
            foreach ($serviciosAdicionales as $servicio) {
                $servicio['origen'] = 'turno_servicios';
                $encontrado = false;
                foreach ($serviciosFinales as $finalServicio) {
                    if (
                        $finalServicio['nombre_servicio'] === $servicio['nombre_servicio'] &&
                        $finalServicio['fecha_servicio'] === $servicio['fecha_servicio']
                    ) {
                        $encontrado = true;
                        break;
                    }
                }
                if (!$encontrado) {
                    $serviciosFinales[] = $servicio;
                    $totalPagar += $servicio['pago_empleado'];
                }
            }

            // Validar monto mayor a cero
            if ($totalPagar <= 0) {
                return $this->response->setJSON(['success' => false, 'message' => 'El monto a pagar debe ser mayor a cero.']);
            }
            // Validar saldo disponible antes de registrar el pago
            $ventaModel = new \App\Models\VentaModel();
            $month = date('m');
            $year = date('Y');
            $ingresos = $ventaModel->sumarIngresos($month, $year);
            $egresos = $egresoModel->sumarEgresos($month, $year);
            $saldoDisponible = $ingresos - $egresos;
            if ($totalPagar > $saldoDisponible) {
                return $this->response->setJSON(['success' => false, 'message' => 'Saldo insuficiente para realizar este pago.']);
            }
           
            // Préstamos pendientes
            $prestamos = $egresoModel->where([
                'empleado_id' => $empleadoId,
                'tipo_egreso' => 'prestamo',
                'estado' => 'pendiente'
            ])->orderBy('fecha', 'ASC')->findAll();

            $pagoNeto = $totalPagar;
            $totalDescontado = 0;
            $detallePrestamos = [];
            foreach ($prestamos as $prestamo) {
                if ($pagoNeto <= 0) break;
                if ($pagoNeto >= $prestamo['monto']) {
                    $totalDescontado += $prestamo['monto'];
                    $detallePrestamos[] = [
                        'id' => $prestamo['id'],
                        'monto' => floatval($prestamo['monto']),
                        'fecha' => $prestamo['fecha'],
                        'saldado' => true
                    ];
                    $pagoNeto -= $prestamo['monto'];
                    $egresoModel->update($prestamo['id'], [
                        'estado' => 'saldado',
                        'monto' => 0
                    ]);
                } else {
                    $totalDescontado += $pagoNeto;
                    $detallePrestamos[] = [
                        'id' => $prestamo['id'],
                        'monto' => floatval($pagoNeto),
                        'fecha' => $prestamo['fecha'],
                        'saldado' => false
                    ];
                    $egresoModel->update($prestamo['id'], [
                        'monto' => $prestamo['monto'] - $pagoNeto
                    ]);
                    $pagoNeto = 0;
                }
            }

            // Registrar el pago neto
            $this->pagosEmpleadosModel->insert([
                'empleado_id' => $empleadoId,
                'nombre' => $empleado['nombre'],
                'apellidos' => $empleado['apellidos'],
                'cedula' => $empleado['cedula'],
                'pago' => floatval($pagoNeto),
                'fecha_pago' => $fechaHoy,
                'idUsuario' => $userId
            ]);

            // Registrar el egreso asociado (por el neto pagado)
            $egresoModel->crearEgreso([
                'concepto' => 'Pago a empleado: ' . $empleado['nombre'] . ' ' . $empleado['apellidos'],
                'tipo_egreso' => 'Pago Empleado',
                'monto' => floatval($pagoNeto),
                'fecha' => $fechaHoy,
                'empleado_id' => $empleadoId
            ]);

            // Registrar movimiento en caja (por el neto pagado)
            $movimientoCajaModel = new \App\Models\MovimientoCajaModel();
            $movimientoCajaModel->insert([
                'tipo' => 'Pago a empleado',
                'monto' => floatval($pagoNeto),
                'descripcion' => 'Pago a empleado: ' . $empleado['nombre'] . ' ' . $empleado['apellidos'],
                'fecha' => $fechaHoy
            ]);


            if (!empty($serviciosFinales)) {
                $turnoId = $serviciosFinales[0]['id'];
            
                $serviciosRelacionados = $this->turnoServicioModel
                    ->where('turno_id', $turnoId)
                    ->findAll();
           
                // Actualizar el turno
                $turnoActualizado = $this->turno->update($turnoId, ['estado_pago' => 'pagado']);
                          
                // Actualizar servicios relacionados
                if (!empty($serviciosRelacionados)) {
                    $this->turnoServicioModel
                        ->where('turno_id', $turnoId)
                        ->set(['estado_pago' => 'pagado'])
                        ->update();
                } 
            
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Pago procesado correctamente.',
                    'prestamos_descontados' => $detallePrestamos,
                    'total_prestamos_descontados' => floatval($totalDescontado),
                    'pago_neto' => floatval($pagoNeto),
                    'servicios_pagos' => $serviciosFinales
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se encontraron servicios para procesar.',
                ]);
            }
         
            // Respuesta JSON
            return $this->response->setJSON([
                'success' => true,
                'prestamos_descontados' => $detallePrestamos,
                'total_prestamos_descontados' => floatval($totalDescontado),
                'neto_pagar' => floatval($pagoNeto)
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al procesar el pago: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function getPagosEmpleado($empleadoId)
    {

        $pagos = $this->pagosEmpleadosModel
            ->select('pago, fecha_pago')
            ->where('empleado_id', $empleadoId)
            ->findAll();
        return $this->response->setJSON([
            'pagos' => $pagos
        ]);
    }

    public function getPrestamosEmpleado($empleadoId)
    {
        $egresoModel = new \App\Models\EgresoModel();
        $fechaLimite = date('Y-m-d', strtotime('-15 days'));
        $prestamos = $egresoModel
            ->select('id, monto, estado, fecha')
            ->where('empleado_id', $empleadoId)
            ->where('tipo_egreso', 'prestamo')
            ->where('fecha >=', $fechaLimite)
            ->orderBy('fecha', 'DESC')
            ->findAll();
        return $this->response->setJSON([
            'prestamos' => $prestamos
        ]);
    }
}
