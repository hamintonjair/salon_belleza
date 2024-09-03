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
        $session = session();
        $userId = $session->get('idUsuario'); // Obtener el ID del usuario desde la sesión

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

        // Obtener servicios pendientes para hoy
        $serviciosPendientes = $this->turnoServicioModel
            ->select('trabajador_id, COUNT(*) AS servicios_pendientes')
            ->where('fecha_servicio', $fechaHoy)
            ->groupBy('trabajador_id')
            ->findAll();

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

                   // Agregar información de pagos
            $empleado['total_pagado'] = $totalPagado;
            $empleado['ultima_fecha_pago'] = $ultimaFechaPago ? date('Y-m-d', strtotime($ultimaFechaPago)) : 'No disponible';
            $empleado['boton_ver_pagos'] = $totalPagado > 0;
        }
        return $this->response->setJSON([
            'empleados' => $empleados
        ]);
    }

    // ver los servicios que realizo cada empleado
    public function getServiciosRealizados($empleadoId)
    {
        $fechaHoy = date('Y-m-d');

        // Obtener servicios de la agenda para el trabajador en una fecha específica
        $serviciosAgendas = $this->turno
            ->select('agenda.id, agenda.servicio AS nombre_servicio, 
          CAST(agenda.precio AS DECIMAL(10,2)) AS precio_servicio, 
          CAST(agenda.pago_empleado AS DECIMAL(10,2)) AS pago_empleado, 
          agenda.date AS fecha_servicio,
          CASE 
              WHEN pagos_empleados.estado IS NOT NULL THEN pagos_empleados.estado
              ELSE \'No Pagado\'
          END AS estado_pago')
            ->join('pagos_empleados', 'agenda.trabajador_id = pagos_empleados.empleado_id AND pagos_empleados.fecha_pago = \'' . $fechaHoy . '\'', 'left')
            ->where('agenda.trabajador_id', $empleadoId)
            ->where('agenda.date', $fechaHoy)
            ->findAll();


        // Obtener servicios adicionales para el turno
        $serviciosAdicionales = $this->turnoServicioModel
            ->select('turno_servicios.turno_id AS id,turno_servicios.nombre_servicio AS nombre_servicio, 
          CAST(turno_servicios.precio_servicio AS DECIMAL(10,2)) AS precio_servicio, 
          CAST(turno_servicios.pago_empleado AS DECIMAL(10,2)) AS pago_empleado, 
          agenda.date AS fecha_servicio,
          CASE 
              WHEN pagos_empleados.estado IS NOT NULL THEN pagos_empleados.estado
              ELSE \'No Pagado\'
          END AS estado_pago')
            ->join('agenda', 'agenda.id = turno_servicios.turno_id', 'inner')
            ->join('pagos_empleados', 'agenda.trabajador_id = pagos_empleados.empleado_id AND pagos_empleados.fecha_pago = \'' . $fechaHoy . '\'', 'left')
            ->where('agenda.trabajador_id', $empleadoId)
            ->where('agenda.date', $fechaHoy)
            ->findAll();

        // Combinando y ajustando los resultados
        $serviciosFinales = [];
        $totalPagar = 0;

        // Agregar servicios de la agenda
        foreach ($serviciosAgendas as $servicio) {
            if ($servicio['estado_pago'] !== 'pagado') {
                $serviciosFinales[] = $servicio;
                $totalPagar += $servicio['pago_empleado'];
            }
        }

        // Agregar servicios adicionales si no están en el resultado anterior
        foreach ($serviciosAdicionales as $servicio) {
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
            if (!$encontrado && $servicio['estado_pago'] !== 'pagado') {
                $serviciosFinales[] = $servicio;
                $totalPagar += $servicio['pago_empleado'];
            }
        }
        // Ordenar o ajustar la lista final si es necesario
        usort($serviciosFinales, function ($a, $b) {
            return strcmp($a['fecha_servicio'], $b['fecha_servicio']);
        });

        if(!empty($serviciosFinales[0]['id'])){
            $turno_id =$serviciosFinales[0]['id'];
        }else
        {
            $turno_id = null;
        }
        // Devolver la respuesta en formato JSON
        return $this->response->setJSON([
            'servicios' => $serviciosFinales,
            'total_pagar' => number_format($totalPagar, 2),
            'turno_id' => $turno_id,
        ]);

        return $this->response->setJSON(['servicios' => $serviciosFinales]);
    }
    
    // pago a empleados
    public function pagarEmpleado()
    {
        $session = session();
        $userId = $session->get('idUsuario');
        $empleadoId = $this->request->getPost('empleado_id');
        $totalPagar = $this->request->getPost('total_pagar');
        $turno_id = $this->request->getPost('turno_id');

        $fechaHoy = date('Y-m-d');

        $empleado = $this->empleadoModel->find($empleadoId);
        $valor = str_replace(',', '', $totalPagar);
        $valor = floatval($valor);  // Convertir a número decimal

        $idTurno = $this->turno->where('id', $turno_id)->findAll();

        if ($idTurno[0]['idUsuario'] == $userId) {
            if ($empleado) {

                // Registrar el pago en la tabla pagos_empleados
                $this->pagosEmpleadosModel->insert([
                    'empleado_id' => $empleadoId,
                    'nombre' => $empleado['nombre'],
                    'apellidos' => $empleado['apellidos'],
                    'cedula' => $empleado['cedula'],
                    'pago' => $valor,
                    'fecha_pago' => $fechaHoy,
                    'idUsuario' => $userId
                ]);

                return $this->response->setJSON(['success' => true, 'message' => 'Pago realizado con éxito.']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Empleado no encontrado.']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No tienes permisos para pagar este empleado en tu turno']);
        }
    }

    public function getPagosEmpleado($empleadoId)
    {
        // Obtener todos los pagos realizados por el empleado
        $pagos = $this->pagosEmpleadosModel
            ->select('pago, fecha_pago')
            ->where('empleado_id', $empleadoId)
            ->findAll();

        return $this->response->setJSON([
            'pagos' => $pagos
        ]);
    }
}
