<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\DetallesPermisosModel;

class ClienteController extends BaseController
{
    protected $cliente, $permisos;
    function __construct()
    {
        $this->cliente = new ClienteModel();
        $this->permisos = new DetallespermisosModel();
    }
    public function Client()
    {
        $session = session();
        $userId = $session->get('idUsuario'); // Obtener el ID del usuario desde la sesión

        // Obtener permisos del usuario
        $permissions = $this->permisos->where('id_usuarios', $userId)->findAll();
        $data['permissions'] = array_column($permissions, 'id_permisos');

        if (in_array(2, $data['permissions'])) {
            echo view('layout/admin/slider');
            echo view('layout/admin/nabvar');
            echo view('layout/cliente/client');
            echo view('layout/admin/footer');
        } else {
            echo view('layout/usuario/no_permisos');
        }
    }
    // listar usuarios
    public function getClients()
    {
        $cliente = $this->cliente->where('estado', 1)->findAll();
        foreach ($cliente as &$item) {

            $session = session();
            if ($session->get('rol') == 'Administrador') {
                if ($item['nombre'] == 'GENERICO') {
                    $item['accion'] = '<button class="btn btn-danger btn-sm eliminar" onclick="eliminarCliente(' . $item['id'] . ')" disabled><i class="fas fa-trash-alt"></i></button> ';
                    $item['accion'] .= '<button class="btn btn-primary btn-sm editar" onclick="editarCliente(' . $item['id'] . ')"><i class="fas fa-edit"></i></button> ';
                } else {
                    $item['accion'] = '<button class="btn btn-danger btn-sm eliminar" onclick="eliminarCliente(' . $item['id'] . ')"><i class="fas fa-trash-alt"></i></button> ';
                    $item['accion'] .= '<button class="btn btn-primary btn-sm editar" onclick="editarCliente(' . $item['id'] . ')"><i class="fas fa-edit"></i></button> ';
                }
            } else {
                $item['accion'] = '<button class="btn btn-danger btn-sm eliminar" onclick="eliminarCliente(' . $item['id'] . ')" disabled><i class="fas fa-trash-alt"></i></button> ';
                $item['accion'] .= '<button class="btn btn-primary btn-sm editar" onclick="editarCliente(' . $item['id'] . ')"><i class="fas fa-edit"></i></button> ';
            }
        }
        echo json_encode($cliente, JSON_UNESCAPED_UNICODE);
    }
    //guardar usuarios
    public function guardar()
    {
        // Recibir datos del formulario
        $id = $this->request->getPost('idCliente');
        $nombre = $this->request->getPost('nombre');
        $apellidos = $this->request->getPost('apellidos');
        $cedula = $this->request->getPost('cedula');
        $telefono = $this->request->getPost('telefono');
        $direccion = $this->request->getPost('direccion');


        if ($nombre == null) {
            $data = [

                'direccion' => $direccion,
            ];
            $usuarioExistente = $this->cliente->where('cedula', 999999999)->first();

        } else {
            $data = [
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'cedula' => $cedula,
                'telefono' => $telefono,
                'direccion' => $direccion,
            ];
            $usuarioExistente = $this->cliente->where('cedula', $cedula)->first();

        }

        if ($usuarioExistente) {
            // Si ya existe un cliente con la misma cédula o correo, mostrar un mensaje de error
            $message = 'Ya existe un cliente con la misma cédula o correo.';
            $result = false;
        } else {
            if (empty($id)) {
                // Registrar nuevo cliente
                $result = $this->cliente->insert($data);
                $message = $result ? 'Cliente registrado correctamente.' : 'Error al registrar el cliente.';
            }
        }
        if (!empty($id)) {
            // Actualizar cliente existente         
            $result = $this->cliente->update($id, $data);
            $message = $result ? 'Cliente actualizado correctamente.' : 'Error al actualizar el cliente.';
        }
        $response = [
            'ok' => $result,
            'post' => $message
        ];

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    // obtener cliente
    public function getClient($id)
    {
        $cliente = $this->cliente->where('id', $id)->first();
        echo json_encode($cliente, JSON_UNESCAPED_UNICODE);
    }
    // eliminar cliente
    public function deleteClient($id)
    {
        $cliente = $this->cliente->where('id', $id)->find();

        if ($cliente) {
            // Actualizar el cliente en la base de datos
            $this->cliente->delete($id);

            $msg = array('ok' => true, 'post' => 'Cliente eliminado correctamente.');
        } else {
            $msg = array('ok' => false, 'post' => 'Cliente no encontrado.');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    }
}
