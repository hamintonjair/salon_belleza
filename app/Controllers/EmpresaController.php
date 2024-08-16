<?php

// app/Controllers/Empresa.php
namespace App\Controllers;

use App\Models\EmpresaModel;
use App\Models\DetallesPermisosModel;

class EmpresaController extends BaseController
{
    protected $empresaModel, $permisos;

    public function __construct()
    {
        $this->empresaModel = new EmpresaModel();
        $this->permisos = new DetallespermisosModel();
    }

    public function empresa()
    {
        $session = session();
        $userId = $session->get('idUsuario');
        // Obtener el ID del usuario desde la sesión

        // Obtener permisos del usuario
        $permissions = $this->permisos->where('id_usuarios', $userId)->findAll();
        $data['permissions'] = array_column($permissions, 'id_permisos');

        if (in_array(7, $data['permissions'])) {
            echo view('layout/admin/slider');
            echo view('layout/admin/nabvar');
            echo view('layout/empresa/empresa');
            echo view('layout/admin/footer');
        } else {
            echo view('layout/usuario/no_permisos');
        }
    }
    // Obtener los datos de la empresa

    public function getDatos()
    {
        $empresa = $this->empresaModel->find(1);
        // Asumiendo que hay solo una empresa, con ID 1
        return $this->response->setJSON($empresa);
    }

    // Actualizar los datos de la empresa

    public function actualizar()
    {
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'nit' => $this->request->getPost('nit'),
            'direccion' => $this->request->getPost('direccion'),
            'telefono' => $this->request->getPost('telefono'),
            'email' => $this->request->getPost('correo'),
            'ciudad' => $this->request->getPost('ciudad'),
        ];

        $id = $this->request->getPost('idempresa');

        if ($this->empresaModel->update($id, $data)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }
}
