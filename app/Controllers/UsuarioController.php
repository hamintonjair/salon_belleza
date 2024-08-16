<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\DetallesPermisosModel;

class UsuarioController extends BaseController
{
    protected $usuario, $permisos;
    function __construct()
    {
        $this->usuario = new UsuarioModel();
        $this->permisos = new DetallespermisosModel();
    }
    public function User()
    {

        $session = session();
        $userId = $session->get('idUsuario'); // Obtener el ID del usuario desde la sesión

        // Obtener permisos del usuario
        $permissions = $this->permisos->where('id_usuarios', $userId)->findAll();
        $data['permissions'] = array_column($permissions, 'id_permisos');

        if (in_array(1, $data['permissions'])) {

            echo view('layout/admin/slider');
            echo view('layout/admin/nabvar');
            echo view('layout/usuario/user');
            echo view('layout/admin/footer');
        } else {
            echo view('layout/usuario/no_permisos');
        }
    }
    // listar usuarios
    public function getUsers()
    {
        $usuario = $this->usuario->where('estado', 1)->findAll();
        foreach ($usuario as &$item) {

            $session = session();
            if ($session->get('rol') == 'Administrador') {
                if ($item['rol'] == 'Administrador') {
                    $item['accion'] = '<button class="btn btn-danger btn-sm eliminar" onclick="eliminarUsuario(' . $item['id'] . ')" disabled><i class="fas fa-trash-alt"></i></button> ';
                } else {
                    $item['accion'] = '<button class="btn btn-danger btn-sm eliminar" onclick="eliminarUsuario(' . $item['id'] . ')" ><i class="fas fa-trash-alt"></i></button> ';
                }

                $item['accion'] .= '<button class="btn btn-primary btn-sm editar" onclick="editarUsuario(' . $item['id'] . ')"><i class="fas fa-edit"></i></button> ';
                $item['accion'] .= '<button class="btn btn-warning btn-sm permisos" onclick="openModalPermisos(' . $item['id'] . ')"><i class="fas fa-user-cog"></i> </button>';
            } else {
                $item['accion'] = '<button class="btn btn-danger btn-sm eliminar" onclick="eliminarUsuario(' . $item['id'] . ')" disabled><i class="fas fa-trash-alt"></i></button> ';
                $item['accion'] .= '<button class="btn btn-primary btn-sm editar" onclick="editarUsuario(' . $item['id'] . ')"><i class="fas fa-edit"></i></button> ';
                $item['accion'] .= '<button class="btn btn-warning btn-sm permisos" onclick="openModalPermisos(' . $item['id'] . ')"><i class="fas fa-user-cog"></i> </button>';
            }
        }
        echo json_encode($usuario, JSON_UNESCAPED_UNICODE);
    }
    //guardar usuarios
    public function guardar()
    {
        // Recibir datos del formulario
        $id = $this->request->getPost('idUsuario');
        $nombre = $this->request->getPost('nombre');
        $apellidos = $this->request->getPost('apellidos');
        $cedula = $this->request->getPost('cedula');
        $telefono = $this->request->getPost('telefono');
        $direccion = $this->request->getPost('direccion');
        $correo = $this->request->getPost('correo');
        $clave = $this->request->getPost('clave');
        $rol = $this->request->getPost('rol');


        if (!empty($id)) {
            $usuarioExistente = $this->usuario->where('id', $id)->first();

            if ($usuarioExistente['clave'] == $clave) {

                $nuevaContraseña = $usuarioExistente['clave'];
            } else {
                $nuevaContraseña = hash('sha256', $clave);
            }
        } else {
            $nuevaContraseña = hash('sha256', $clave);
        }

        $data = [
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'cedula' => $cedula,
            'telefono' => $telefono,
            'direccion' => $direccion,
            'correo' => $correo,
            'clave' => $nuevaContraseña,
            'rol' => $rol,
        ];
        $usuarioExistente = $this->usuario->where('cedula', $cedula)->orWhere('correo', $correo)->first();
        if ($usuarioExistente) {
            // Si ya existe un cliente con la misma cédula o correo, mostrar un mensaje de error
            $message = 'Ya existe un usuario con la misma cédula o correo.';
            $result = false;
        } else {
            if (empty($id)) {
                // Registrar nuevo usuario
                $result = $this->usuario->insert($data);
                $message = $result ? 'Usuario registrado correctamente.' : 'Error al registrar el usuario.';
            }
        }
        if (!empty($id)) {
            // Actualizar usuario existente         
            $result = $this->usuario->update($id, $data);
            $message = $result ? 'Usuario actualizado correctamente.' : 'Error al actualizar el usuario.';
        }
        $response = [
            'ok' => $result,
            'post' => $message
        ];

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    // obtener usuario
    public function getUser($id)
    {
        $usuario = $this->usuario->where('id', $id)->first();
        echo json_encode($usuario, JSON_UNESCAPED_UNICODE);
    }
    // eliminar usuario
    public function deleteUser($id)
    {
        $usuario = $this->usuario->where('id', $id)->find();

        if ($usuario) {
            // Actualizar el usuario en la base de datos
            $this->usuario->delete($id);

            $msg = array('ok' => true, 'post' => 'Usuario eliminado correctamente.');
        } else {
            $msg = array('ok' => false, 'post' => 'Usuario no encontrado.');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    }
}
