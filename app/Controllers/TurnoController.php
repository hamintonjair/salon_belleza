<?php

namespace App\Controllers;

use App\Models\DetallesPermisosModel;
use App\Models\AgendaModel;
use App\Models\TurnoServiciosModel;
use App\Models\TurnoProductosModel;
use App\Models\EmpresaModel;
use App\Models\EmpleadoModel;
use App\Models\UsuarioModel;

use App\Models\ProductoModel;
use App\Models\ServicioModel;

use Dompdf\Dompdf;
use Dompdf\Options;

class TurnoController extends BaseController
{
    protected $permisos, $turno, $servicio, $producto, $turnoServicio, $turnoProducto, $empresa,
        $trabajadorModel, $usuario;
    function __construct()
    {
        $this->permisos = new DetallesPermisosModel();
        $this->turno = new AgendaModel();
        $this->servicio = new ServicioModel();
        $this->producto = new ProductoModel();
        $this->empresa = new EmpresaModel();
        $this->trabajadorModel = new EmpleadoModel();
        $this->usuario = new UsuarioModel();

        $this->turnoServicio = new TurnoServiciosModel();
        $this->turnoProducto = new TurnoProductosModel();

        date_default_timezone_set('America/Bogota');
        helper('pdf');
    }
    public function turnos()
    {
        $session = session();
        $userId = $session->get('idUsuario'); // Obtener el ID del usuario desde la sesión
        // Obtener permisos del usuario
        $permissions = $this->permisos->where('id_usuarios', $userId)->findAll();
        $data['permissions'] = array_column($permissions, 'id_permisos');

        if (in_array(6, $data['permissions'])) {
            echo view('layout/admin/slider');
            echo view('layout/admin/nabvar');
            echo view('layout/turnos/agendar');
            echo view('layout/admin/footer');
        } else {
            echo view('layout/usuario/no_permisos');
        }
    }

    // listar turnos agendados
    public function getTurnos()
    {
        $fecha_actual = date('Y-m-d');
        $turnos = $this->turno
            ->where('estado', 'Pendiente')
            ->orWhere('estado', 'Vencido')
            ->orWhere('estado', 'Atendido')
            ->where('date', $fecha_actual)
            ->findAll();

        $userIds = array_unique(array_column($turnos, 'idUsuario'));

        // Obtener roles de los usuarios
        if (!empty($userIds)) {
            $usuarios = $this->usuario->whereIn('id', $userIds)->findAll();
        } else {
            $usuarios = [];
        }
        $roles = [];

        foreach ($usuarios as $usuario) {
            $roles[$usuario['id']] = $usuario['rol'];
        }

        // Añadir el rol a los turnos
        foreach ($turnos as &$turno) {
            $turno['rol'] = isset($roles[$turno['idUsuario']]) ? $roles[$turno['idUsuario']] : 'Desconocido';
        }

        foreach ($turnos as &$turno) {
            // Obtener servicios y productos del modelo turno
            $servicios_y_productos = $this->turno->select('servicio, precio')
                ->where('id', $turno['id'])
                ->get()
                ->getResultArray();

            // Obtener servicios adicionales
            $servicios_adicionales = $this->turnoServicio->select('nombre_servicio, precio_servicio')
                ->where('turno_id', $turno['id'])
                ->get()
                ->getResultArray();

            // Obtener productos adicionales
            $productos_adicionales = $this->turnoProducto->select('nombre_producto, cantidad, precio_unitario, subtotal')
                ->where('turno_id', $turno['id'])
                ->get()
                ->getResultArray();

            // Combinar servicios y productos del modelo turno con los adicionales
            $servicios = array_merge(
                array_map(function ($item) {
                    return [
                        'nombre_servicio' => $item['servicio'],
                        'precio_servicio' => $item['precio']
                    ];
                }, $servicios_y_productos),
                $servicios_adicionales
            );

            $productos = $productos_adicionales;

            // Agrupar productos por nombre
            $productos_totales = [];
            foreach ($productos as $producto) {
                if (isset($productos_totales[$producto['nombre_producto']])) {
                    $productos_totales[$producto['nombre_producto']]['cantidad'] += $producto['cantidad'];
                    $productos_totales[$producto['nombre_producto']]['subtotal'] += $producto['subtotal'];
                } else {
                    $productos_totales[$producto['nombre_producto']] = [
                        'cantidad' => $producto['cantidad'],
                        'precio_unitario' => $producto['precio_unitario'],
                        'subtotal' => $producto['subtotal']
                    ];
                }
            }

            // Formatear servicios y productos
            // $servicios_str = array_map(function ($servicio) {
            //     return $servicio['nombre_servicio'] . ' ($' . number_format($servicio['precio_servicio'], 2) . ')';
            // }, $servicios);

            $servicios_str = array_map(function ($servicio) {
                return $servicio['nombre_servicio'] . ' ($' . number_format($servicio['precio_servicio'], 2) . ')';
            }, $servicios);

            $productos_str = array_map(function ($producto, $data) {
                return $producto . ' (' . $data['cantidad'] . ' x $' . number_format($data['precio_unitario'], 2) . ' = $' . number_format($data['subtotal'], 2) . ')';
            }, array_keys($productos_totales), $productos_totales);

            $turno['servicio'] = implode(', ', array_merge($servicios_str, $productos_str));
            $turno['precio'] = array_sum(array_column($servicios, 'precio_servicio')) + array_sum(array_column($productos_totales, 'subtotal'));

            // Configurar el estado y las acciones según el rol del usuario
            $session = session();
            if ($session->get('rol') == 'Administrador') {
                if ($turno['estado'] == 'Pendiente') {
                    $turno['estado'] = '<span class="badge badge-warning">Pendiente</span>';
                    $turno['accion'] = '<button class="btn btn-warning btn-sm atender" onclick="atenderTurno(' . $turno['id'] . ')"><i class="fas fa-check"></i></button> ';
                    if ($turno['trabajador_id'] == NULL) {
                        $turno['accion'] .= '<button class="btn btn-primary btn-sm agregar-servicio" onclick="mostrarModalAgregarServicio(' . $turno['id'] . ')" disabled><i class="fas fa-plus"></i></button>';
                    } else {
                        $turno['accion'] .= '<button class="btn btn-primary btn-sm agregar-servicio" onclick="mostrarModalAgregarServicio(' . $turno['id'] . ')"><i class="fas fa-plus"></i></button>';
                    }
                    $turno['accion'] .= '<button class="btn btn-secondary btn-sm asignar-trabajador" onclick="mostrarModalAsignarTrabajador(' . $turno['id'] . ')"><i class="fas fa-user"></i></button>';
                }
                if ($turno['estado'] == 'Atendido') {
                    $turno['estado'] = '<span class="badge badge-info">Atendido</span>';
                    $turno['accion'] = '<button class="btn btn-success btn-sm finalizar" onclick="finalizarTurno(' . $turno['id'] . ')"><i class="fas fa-check"></i></button> ';
                    if ($turno['trabajador_id'] == null) {
                        $turno['accion'] .= '<button class="btn btn-primary btn-sm agregar-servicio" onclick="mostrarModalAgregarServicio(' . $turno['id'] . ')" disabled><i class="fas fa-plus"></i></button>';
                    } else {
                        $turno['accion'] .= '<button class="btn btn-primary btn-sm agregar-servicio" onclick="mostrarModalAgregarServicio(' . $turno['id'] . ')"><i class="fas fa-plus"></i></button>';
                    }
                    $turno['accion'] .= '<button class="btn btn-secondary btn-sm asignar-trabajador" onclick="mostrarModalAsignarTrabajador(' . $turno['id'] . ')"><i class="fas fa-user"></i></button>';
                }
                if ($turno['estado'] == 'Vencido') {
                    $turno['estado'] = '<span class="badge badge-danger">Vencido</span>';
                    $turno['accion'] = '<button class="btn btn-danger btn-sm inactivar" onclick="inactivarTurno(' . $turno['id'] . ')"><i class="fas fa-ban"></i></button>';
                }
            } elseif ($session->get('rol') == 'Operador') {
                if ($turno['estado'] == 'Pendiente') {
                    $turno['estado'] = '<span class="badge badge-warning">Pendiente</span>';
                    $turno['accion'] = '<button class="btn btn-success btn-sm atender" onclick="atenderTurno(' . $turno['id'] . ')"><i class="fas fa-check"></i></button> ';
                    if ($turno['trabajador_id'] == null) {
                        $turno['accion'] .= '<button class="btn btn-primary btn-sm agregar-servicio" onclick="mostrarModalAgregarServicio(' . $turno['id'] . ')" disabled><i class="fas fa-plus"></i></button>';
                    } else {
                        $turno['accion'] .= '<button class="btn btn-primary btn-sm agregar-servicio" onclick="mostrarModalAgregarServicio(' . $turno['id'] . ')"><i class="fas fa-plus"></i></button>';
                    }
                    $turno['accion'] .= '<button class="btn btn-secondary btn-sm asignar-trabajador" onclick="mostrarModalAsignarTrabajador(' . $turno['id'] . ')"><i class="fas fa-user"></i></button>';
                }
                if ($turno['estado'] == 'Atendido') {
                    $turno['estado'] = '<span class="badge badge-success">Atendido</span>';
                    $turno['accion'] = '<button class="btn btn-success btn-sm finalizar" onclick="finalizarTurno(' . $turno['id'] . ')"><i class="fas fa-check"></i></button> ';
                    if ($turno['trabajador_id'] == null) {
                        $turno['accion'] .= '<button class="btn btn-primary btn-sm agregar-servicio" onclick="mostrarModalAgregarServicio(' . $turno['id'] . ')" disabled><i class="fas fa-plus"></i></button>';
                    } else {
                        $turno['accion'] .= '<button class="btn btn-primary btn-sm agregar-servicio" onclick="mostrarModalAgregarServicio(' . $turno['id'] . ')"><i class="fas fa-plus"></i></button>';
                    }
                    $turno['accion'] .= '<button class="btn btn-secondary btn-sm asignar-trabajador" onclick="mostrarModalAsignarTrabajador(' . $turno['id'] . ')"><i class="fas fa-user"></i></button>';
                }
                if ($turno['estado'] == 'Vencido') {
                    $turno['estado'] = '<span class="badge badge-danger">Vencido</span>';
                    $turno['accion'] = '<button class="btn btn-danger btn-sm inactivar" onclick="inactivarTurno(' . $turno['id'] . ')"><i class="fas fa-ban"></i></button>';
                }
            }
        }
        return $this->response->setJSON($turnos);
    }
    //    cargar productos y servicios a modal
    public function cargarDatosParaModal()
    {
        $servicios = $this->servicio->findAll();
        $productos = $this->producto->findAll();

        return $this->response->setJSON([
            'servicios' => $servicios,
            'productos' => $productos
        ]);
    }

    // agregar productos y servicios a clientes
    public function agregarServicioProducto()
    {
        $data = $this->request->getPost();
        $session = session();
        $userId = $session->get('idUsuario');
        // Obtener los datos del turno, servicio y producto
        $turnoId = $data['turno_id'];
        $servicioId = $data['servicio_id'] ?? null;
        $productoId = $data['producto_id'] ?? null;
        $cantidad = $data['cantidad'] ?? 1;

        if (empty($turnoId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID de turno es requerido.']);
        }

        $turno = $this->turno->find($turnoId);

        if (!$turno) {
            return $this->response->setJSON(['success' => false, 'message' => 'Turno no encontrado.']);
        }

        // $totalPrecio = 0;
        $fechaHoy = date('Y-m-d');

        // Procesar el servicio si existe
        if (!empty($servicioId)) {
            $servicio = $this->servicio->find($servicioId);
            $turnos = $this->turno->where('id', $turnoId)->find();

            if ($servicio) {
                // Guardar el servicio en la tabla turno_servicios
                $this->turnoServicio->insert([
                    'turno_id' => $turnoId,
                    'nombre_servicio' => $servicio['nombre'],
                    'precio_servicio' => $servicio['precio'],
                    'pago_empleado' => $servicio['pago_empleado'],
                    'trabajador_id' => $turnos[0]['trabajador_id'],
                    'fecha_servicio' => $fechaHoy,
                    'idUsuario' => $userId
                ]);
                // Acumulamos el precio del servicio
                // $totalPrecio += $servicio['precio'];
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Servicio no encontrado.']);
            }
        }

        // Procesar el producto si existe
        if (!empty($productoId)) {
            $producto = $this->producto->find($productoId);

            if ($producto) {
                $subtotal = $producto['v_venta'] * $cantidad;
                // Guardar el producto en la tabla turno_productos
                $this->turnoProducto->insert([
                    'turno_id' => $turnoId,
                    'nombre_producto' => $producto['nombre'],
                    'cantidad' => $cantidad,
                    'precio_unitario' => $producto['v_venta'],
                    'subtotal' => $subtotal,
                    'idUsuario' => $userId
                ]);
                // deisminuimos la cantidad del producto
                $this->producto->decrement('cantidad', $cantidad, ['nombre' => $producto['nombre']]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Producto no encontrado.']);
            }
        }

        // Actualizar el precio total del turno
        // $this->turno->update($turnoId, ['subtotal' => $turno['precio'] + $totalPrecio]);

        return $this->response->setJSON(['success' => true, 'message' => 'Servicio o producto agregado exitosamente.']);
    }

    // finalizar los turnos atendidos
    public function atenderTurno($id)
    {
        $data = $this->turno->where('id', $id)->first();
        if (!empty($data['trabajador_id'])) {
            // var_dump('entramos');exit;

            try {
                $this->turno->update($id, ['estado' => 'Atendido']);
                // $this->turnoServicio->where('turno_id', $data['id'])->set($data['trabajador_id'])->update();
                echo json_encode(['success' => true, 'message' => 'Turno marcado como atendido.']);
            } catch (\Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Error al marcar el turno como atendido.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No se puede marcar como atendido porque no ha asignado un empreado/a.']);
        }
    }

    // Asignar un trabajador a un turno

    public function asignarTrabajador()
    {
        $turnoId = $this->request->getPost('turno_idi');
        $trabajadorId = $this->request->getPost('trabajador_id');
        $session = session();
        // Actualizar el trabajador_id en la tabla de turnos
        $dataTurnos = [
            'trabajador_id' => $trabajadorId
        ];

        // Actualizar el trabajador_id en la tabla turno_servicio
        $dataTurnoServicio = [
            'trabajador_id' => $trabajadorId
        ];
        $idUsuario = $this->turno->where('id', $turnoId)->findAll();

        if ($idUsuario[0]['idUsuario'] == $session->get('idUsuario') ) {
            // Verificar si la transacción fue exitosa
            if ($this->turno->update($turnoId, $dataTurnos) && $this->turnoServicio->where('turno_id', $turnoId)->set($dataTurnoServicio)->update()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Trabajador asignado correctamente.']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Error al asignar el trabajador.']);
            }
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => "No se puede asignar el trabajador porque no pertenece a tu turno. Fue agendado por otro operador, Espera o agendala de nuevo."
            ]);

        }
    }
    // finalizar el turno despues de haber atendido al cliente
    public function finalizarTurno($id)
    {
        try {
            $this->turno->update($id, ['estado' => 'Finalizado']);
            echo json_encode(['success' => true, 'message' => 'Turno marcado como finalizado.']);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al marcar el turno como finalizado.']);
        }
    }
    // anular los turnos vencidos
    public function anularTurno($id)
    {
        $productos = $this->turnoProducto->where('id', $id)->findAll();

        try {
            $this->turno->update($id, ['estado' => 'Anulado']);
            // Devolver cantidad de producto
            foreach ($productos as $producto) {
                $this->producto->increment('cantidad', $producto['cantidad'], ['nombre' => $producto['nombre_producto']]);
            }
            echo json_encode(['success' => true, 'message' => 'Turno marcado como anulado.']);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al marcar el turno como anulado.']);
        }
    }
    // llamamod la funcion remover o actualizar turnos vencios despues de 20 minutos
    // public function removeExpiredTurnoss()
    // {
    //     // Llamar al método del modelo para actualizar los turnos vencidos
    //     $this->turno->updateExpiredTurnos();
    // }
    public function removeExpiredTurnos()
    {
        // Usar la fecha y hora actuales
        $twentyMinutesAgo = date('Y-m-d H:i:s', strtotime('-20 minutes'));
        // $twentyMinutesAgo = date('Y-m-d h:i:s a', strtotime('-20 minutes'));
        // var_dump($twentyMinutesAgo);
        // Actualizar los turnos vencidos en la base de datos
        $builder = $this->turno->builder();
        $builder->set('estado', 'Vencido');
        $builder->where("CONCAT(date, ' ', time) <= '{$twentyMinutesAgo}'");
        $builder->where('estado', 'Pendiente');
        $builder->update();
    }
    // vista turnos finalizados
    public function turnosFinalizados()
    {
        $session = session();
        $userId = $session->get('idUsuario'); // Obtener el ID del usuario desde la sesión
        $this->removeExpiredTurnos();
        // Obtener permisos del usuario
        $permissions = $this->permisos->where('id_usuarios', $userId)->findAll();
        $data['permissions'] = array_column($permissions, 'id_permisos');

        if (in_array(9, $data['permissions'])) {
            echo view('layout/admin/slider');
            echo view('layout/admin/nabvar');
            echo view('layout/turnos/turnos_finalizados');
            echo view('layout/admin/footer');
        } else {
            echo view('layout/usuario/no_permisos');
        }
    }
    // listar Trunos atendidos y anulados
    public function getTurnosFinalizados()
    {
        // Obtener los turnos con estado 'Finalizado' o 'Anulado'
        $turnos = $this->turno->whereIn('estado', ['Finalizado', 'Anulado'])->findAll();


        $userIds = array_unique(array_column($turnos, 'idUsuario'));

        // Obtener roles de los usuarios

        if (!empty($userIds)) {
            $usuarios = $this->usuario->whereIn('id', $userIds)->findAll();
        } else {
            $usuarios = [];
        }
        $roles = [];
        foreach ($usuarios as $usuario) {
            $roles[$usuario['id']] = $usuario['nombre'] . ' ' . $usuario['apellidos'];
        }
        

        // Añadir el rol a los turnos
        foreach ($turnos as &$turno) {
            $turno['usuario'] = isset($roles[$turno['idUsuario']]) ? $roles[$turno['idUsuario']] : 'Desconocido';
        }

        foreach ($turnos as &$turno) {
            // Obtener servicios y productos del modelo turno
            $servicios_y_productos = $this->turno->select('servicio, precio')
                ->where('id', $turno['id'])
                ->get()
                ->getResultArray();

            $servicios_adicionales = $this->turnoServicio->select('nombre_servicio, precio_servicio')
                ->where('turno_id', $turno['id'])
                ->get()
                ->getResultArray();

            $productos_adicionales = $this->turnoProducto->select('nombre_producto, cantidad, precio_unitario, subtotal')
                ->where('turno_id', $turno['id'])
                ->get()
                ->getResultArray();

            // Combinar servicios y productos del modelo turno con los adicionales
            $servicios = array_merge(
                array_map(function ($item) {
                    return [
                        'nombre_servicio' => $item['servicio'],
                        'precio_servicio' => $item['precio']
                    ];
                }, $servicios_y_productos),
                $servicios_adicionales
            );

            $productos = $productos_adicionales;

            // Agrupar productos por nombre
            $productos_totales = [];
            foreach ($productos as $producto) {
                if (isset($productos_totales[$producto['nombre_producto']])) {
                    $productos_totales[$producto['nombre_producto']]['cantidad'] += $producto['cantidad'];
                    $productos_totales[$producto['nombre_producto']]['subtotal'] += $producto['subtotal'];
                } else {
                    $productos_totales[$producto['nombre_producto']] = [
                        'cantidad' => $producto['cantidad'],
                        'precio_unitario' => $producto['precio_unitario'],
                        'subtotal' => $producto['subtotal']
                    ];
                }
            }

            // Formatear servicios y productos
            $servicios_str = array_map(function ($servicio) {
                return $servicio['nombre_servicio'] . ' ($' . number_format($servicio['precio_servicio'], 2) . ')';
            }, $servicios);

            $productos_str = array_map(function ($producto, $data) {
                return $producto . ' (' . $data['cantidad'] . ' x $' . number_format($data['precio_unitario'], 2) . ' = $' . number_format($data['subtotal'], 2) . ')';
            }, array_keys($productos_totales), $productos_totales);

            $turno['servicio'] = implode(', ', array_merge($servicios_str, $productos_str));
            $turno['precio'] = array_sum(array_column($servicios, 'precio_servicio')) + array_sum(array_column($productos_totales, 'subtotal'));

            // Formatear estado y acción
            if ($turno['estado'] == 'Finalizado') {
                $turno['estado'] = '<span class="badge badge-success">Finalizado</span>';
                $turno['accion'] = '<a class="btn btn-danger btn-sm" href="http://localhost/salon_belleza/turno/generatePdf/' . $turno['id'] . '" target="_blank"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($turno['estado'] == 'Anulado') {
                $turno['estado'] = '<span class="badge badge-warning">Anulado</span>';
                $turno['accion'] = '<button class="btn btn-warning btn-sm inactivar" onclick="inactivarTurno(' . $turno['id'] . ')" disabled><i class="fas fa-ban"></i></button>';
            }
        }

        // Establecer el tipo de contenido a JSON
        header('Content-Type: application/json');

        // Enviar el JSON
        echo json_encode($turnos, JSON_UNESCAPED_UNICODE);
    }

    // generar factura
    public function generatePdf($id)
    {
        $turno = $this->turno->find($id);
        $empresa = $this->empresa->find();

        if (!$turno) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Turno no encontrado');
        }

        // Obtener los servicios y productos del modelo turno
        $servicios_y_productos = $this->turno->select('servicio, precio')
            ->where('id', $turno['id'])
            ->get()
            ->getResultArray();

        // Obtener servicios adicionales
        $servicios_adicionales = $this->turnoServicio->select('nombre_servicio, precio_servicio')
            ->where('turno_id', $turno['id'])
            ->get()
            ->getResultArray();

        // Obtener productos adicionales
        $productos_adicionales = $this->turnoProducto->select('nombre_producto, cantidad, precio_unitario, subtotal')
            ->where('turno_id', $turno['id'])
            ->get()
            ->getResultArray();

        // Combinar servicios y productos del modelo turno con los adicionales
        $servicios = array_merge(
            array_map(function ($item) {
                return [
                    'nombre_servicio' => $item['servicio'],
                    'precio_servicio' => $item['precio']
                ];
            }, $servicios_y_productos),
            $servicios_adicionales
        );

        $productos = $productos_adicionales;

        // Agrupar productos por nombre
        $productos_totales = [];
        foreach ($productos as $producto) {
            if (isset($productos_totales[$producto['nombre_producto']])) {
                $productos_totales[$producto['nombre_producto']]['cantidad'] += $producto['cantidad'];
                $productos_totales[$producto['nombre_producto']]['subtotal'] += $producto['subtotal'];
            } else {
                $productos_totales[$producto['nombre_producto']] = [
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio_unitario'],
                    'subtotal' => $producto['subtotal']
                ];
            }
        }

        // Calcular el precio total
        $precio_total = array_sum(array_column($servicios, 'precio_servicio')) + array_sum(array_column($productos_totales, 'subtotal'));

        // Crear el contenido HTML del PDF
        $html = view('layout/turnos/pdf_template', [
            'turno' => $turno,
            'servicios' => $servicios,
            'productos_totales' => $productos_totales,
            'precio_total' => $precio_total,
            'empresa' => $empresa,
        ]);
        // Configurar DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true); // Permite cargar imágenes remotas

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('factura_' . $id . '.pdf', array('Attachment' => 0));
    }
    // Obtener la lista de trabajadores
    public function getTrabajadores()
    {
        $trabajadores = $this->trabajadorModel->findAll(); // Cambia esto según tu modelo

        // Devuelve los trabajadores como JSON
        return $this->response->setJSON(['trabajadores' => $trabajadores]);
    }
}
