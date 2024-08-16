<?php

namespace App\Controllers;

use App\Models\ServicioModel;
use App\Models\DetallespermisosModel;
class ServicioController extends BaseController
{
    protected $servicio, $permisos;
    function __construct()
    {
        $this->servicio = new ServicioModel();
        $this->permisos = new DetallespermisosModel();

    }
    public function services()
    {
        $session = session();
        $userId = $session->get('idUsuario'); // Obtener el ID del servicio desde la sesión

        // Obtener permisos del servicio
        $permissions = $this->permisos->where('id_usuarios', $userId)->findAll();
        $data['permissions'] = array_column($permissions, 'id_permisos');

        if (in_array(5, $data['permissions'])) {
            echo view('layout/admin/slider');
            echo view('layout/admin/nabvar');
            echo view('layout/ventas/servicios');
            echo view('layout/admin/footer');
        } else {
            echo view('layout/usuario/no_permisos');
        }
    }
    // listar servicios
    public function getServices()
    {
        $servicio = $this->servicio->where('estado', 1)->findAll();
        foreach ($servicio as &$item) {

            $session = session();
            if ($session->get('rol') == 'Administrador') {
                $item['accion'] = '<button class="btn btn-danger btn-sm eliminar" onclick="eliminarServicio(' . $item['id'] . ')"><i class="fas fa-trash-alt"></i></button> ';
                $item['accion'] .= '<button class="btn btn-primary btn-sm editar" onclick="editarServicio(' . $item['id'] . ')"><i class="fas fa-edit"></i></button> ';
            } else {
                $item['accion'] = '<button class="btn btn-danger btn-sm eliminar" onclick="eliminarServicio(' . $item['id'] . ')" disabled><i class="fas fa-trash-alt"></i></button> ';
                $item['accion'] .= '<button class="btn btn-primary btn-sm editar" onclick="editarServicio(' . $item['id'] . ')"><i class="fas fa-edit"></i></button> ';
            }
        }
        echo json_encode($servicio, JSON_UNESCAPED_UNICODE);
    }
    //guardar servicios
    public function guardar()
    {
        // Recibir datos del formulario
        $id = $this->request->getPost('idServicio');
        $nombre = $this->request->getPost('nombre');
        $precio = $this->request->getPost('precio');
        $pago_empleado = $this->request->getPost('pago_empleado');


        $data = [
            'nombre' => $nombre,
            'precio' => $precio,
            'pago_empleado' => $pago_empleado,
        ];
        $servicioExistente = $this->servicio->where('nombre', $nombre)->first();
        if ($servicioExistente) {
            // Si ya existe un servicio con la misma cédula o correo, mostrar un mensaje de error
            $message = 'Ya existe un servicio con el mismo nombre.';
            $result = false;
        } else {
            if (empty($id)) {
                // Registrar nuevo servicio
                $result = $this->servicio->insert($data);
                $message = $result ? 'Servicio registrado correctamente.' : 'Error al registrar el servicio.';
            }
        }
        if (!empty($id)) {
            // Actualizar servicio existente         
            $result = $this->servicio->update($id, $data);
            $message = $result ? 'Servicio actualizado correctamente.' : 'Error al actualizar el servicio.';
        }
        $response = [
            'ok' => $result,
            'post' => $message
        ];

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    // obtener servicio
    public function getService($id)
    {
        $servicio = $this->servicio->where('id', $id)->first();
        echo json_encode($servicio, JSON_UNESCAPED_UNICODE);
    }
    // eliminar servicio
    public function deleteService($id)
    {
        $servicio = $this->servicio->where('id', $id)->find();

        if ($servicio) {
            // Actualizar el servicio en la base de datos
            $this->servicio->delete($id);

            $msg = array('ok' => true, 'post' => 'Servicio eliminado correctamente.');
        } else {
            $msg = array('ok' => false, 'post' => 'Servicio no encontrado.');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    }
}
