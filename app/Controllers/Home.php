<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\ServicioModel;

class Home extends BaseController
{
    protected $usuario, $servicio;
    function __construct()
    {
        $this->usuario = new UsuarioModel();
        $this->servicio = new ServicioModel();
    }
    public function dashboard()
    {
        $session = session();

        $session = session();

        if ($session->get('activo') == true) {

            $email = $session->get('email');
            $picture = $session->get('picture');
            $data = $this->usuario->where('correo', $email)->first();
            if (empty($data)) {
                // Establece un mensaje flash en la sesión
                $session->setFlashdata('error', 'El correo con el que se loguea no está registrado');
                // Redirige al usuario a la página de inicio de sesión
                return redirect()->to(base_url('/'));
            } else {
                if (isset($picture)) {
                    $dato = [
                        'idUsuario' => $data['id'],
                        'rol' => $data['rol']
                    ];
                    $session->set($dato);
                }
                $servicio['servicios'] = $this->servicio->findAll();
                // Muestra las vistas si el usuario está registrado
                echo view('layout/admin/slider');
                echo view('layout/admin/nabvar');
                echo view('layout/admin/body', $servicio);
                echo view('layout/admin/footer');
            }
        } else {
            // Establece un mensaje flash en la sesión
            $session->setFlashdata('error', 'Debes iniciar sesión para acceder a esta área');
            // Redirige al usuario a la página de inicio de sesión
            return redirect()->to(base_url('/'));
        }
    }
}
