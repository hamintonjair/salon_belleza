<?php

namespace App\Controllers;

use App\Models\EmpleadoModel;
use App\Models\DetallesPermisosModel;

class EmpleadoController extends BaseController
{
    protected $empleado, $permisos;
    function __construct()
    {
        $this->empleado = new EmpleadoModel();
        $this->permisos = new DetallespermisosModel();
    }
    public function empleado()
    {

        $session = session();
        $userId = $session->get('idUsuario'); // Obtener el ID del empleado desde la sesión

        // Obtener permisos del empleado
        $permissions = $this->permisos->where('id_usuarios', $userId)->findAll();
        $data['permissions'] = array_column($permissions, 'id_permisos');

        if (in_array(10, $data['permissions'])) {

            echo view('layout/admin/slider');
            echo view('layout/admin/nabvar');
            echo view('layout/empleado/empleado');
            echo view('layout/admin/footer');
        } else {
            echo view('layout/usuario/no_permisos');
        }
    }
    // listar empleados
    public function getEmpleados()
    {
        $empleado = $this->empleado->where('estado', 1)->findAll();
        foreach ($empleado as &$item) {

            $session = session();
            if ($session->get('rol') == 'Administrador') {
             
                $item['accion'] = '<button class="btn btn-danger btn-sm eliminar" onclick="eliminarEmpleado(' . $item['id'] . ')" ><i class="fas fa-trash-alt"></i></button> ';
                $item['accion'] .= '<button class="btn btn-primary btn-sm editar" onclick="editarEmpleado(' . $item['id'] . ')"><i class="fas fa-edit"></i></button> ';
            } else {
                $item['accion'] = '<button class="btn btn-danger btn-sm eliminar" onclick="eliminarEmpleado(' . $item['id'] . ')" disabled><i class="fas fa-trash-alt"></i></button> ';
                $item['accion'] .= '<button class="btn btn-primary btn-sm editar" onclick="editarEmpleado(' . $item['id'] . ')"><i class="fas fa-edit"></i></button> ';
            }
        }
        echo json_encode($empleado, JSON_UNESCAPED_UNICODE);
    }
    //guardar empleados
    public function guardar()
    {
        // Recibir datos del formulario
        $id = $this->request->getPost('idEmpleado');
        $nombre = $this->request->getPost('nombre');
        $apellidos = $this->request->getPost('apellidos');
        $cedula = $this->request->getPost('cedula');
        $telefono = $this->request->getPost('telefono');
        $direccion = $this->request->getPost('direccion');

        $data = [
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'cedula' => $cedula,
            'telefono' => $telefono,
            'direccion' => $direccion,
        ];
        $empleadoExistente = $this->empleado->where('cedula', $cedula)->first();
        if ($empleadoExistente) {
            // Si ya existe un cliente con la misma cédula o correo, mostrar un mensaje de error
            $message = 'Ya existe un empleado con la misma cédula o correo.';
            $result = false;
        } else {
            if (empty($id)) {
                // Registrar nuevo empleado
                $result = $this->empleado->insert($data);
                $message = $result ? 'Empleado registrado correctamente.' : 'Error al registrar el empleado.';
            }
        }
        if (!empty($id)) {
            // Actualizar empleado existente         
            $result = $this->empleado->update($id, $data);
            $message = $result ? 'Empleado actualizado correctamente.' : 'Error al actualizar el empleado.';
        }
        $response = [
            'ok' => $result,
            'post' => $message
        ];

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    // obtener empleado
    public function getEmpleado($id)
    {
        $empleado = $this->empleado->where('id', $id)->first();
        echo json_encode($empleado, JSON_UNESCAPED_UNICODE);
    }
    // eliminar empleado
    public function deleteEmpleado($id)
    {
        $empleado = $this->empleado->where('id', $id)->find();

        if ($empleado) {
            // Actualizar el empleado en la base de datos
            $this->empleado->delete($id);

            $msg = array('ok' => true, 'post' => 'Empleado eliminado correctamente.');
        } else {
            $msg = array('ok' => false, 'post' => 'Empleado no encontrado.');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    }
}
