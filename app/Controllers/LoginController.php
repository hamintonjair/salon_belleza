<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class LoginController extends BaseController
{
    protected $usuario, $permisos;

    public function __construct()
    {
        $this->usuario = new UsuarioModel();
    }
    public function index()
    {
        $session = session();

        if ($session->has('email')) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('layout/login/login');
    }
    // validar
    public function validar()
    {
        $correo = $this->request->getPost('email');
        $clave = $this->request->getPost('password');
        $session = session();

        $data = $this->usuario->where('correo', $correo)->first();
      
        if (empty($data)) {
            $session->setFlashdata('error', 'El correo con el que se loguea no está registrado');
            return redirect()->to(base_url('/'));
            return;  // Salimos del método para evitar ejecutar el código después de la redirección
        } else {
            if (hash('sha256', $clave) === $data['clave']) {
                $dato = [
                    'idUsuario' => $data['id'],
                    'name' => $data['nombre'] . ' ' . $data['apellidos'], // Concatenar nombre y apellidos
                    'rol' => $data['rol'],
                    'email' => $data['correo'],
                    'activo' => true,
                ];

                // var_dump($dato);exit;

                $session->set($dato);
                return redirect()->to(base_url('dashboard'));
            } else {
                $session->setFlashdata('error', 'La contraseña ingresada es incorrecta');
                return redirect()->to(base_url('/'));
            }
        }
    }
    public function logout()
    {
        $session = session();
        session_unset(); // Elimina todas las variables de sesión
        $session->destroy();
        return redirect()->to(base_url('/'));
    }
}
