<?php
namespace App\Controllers;

use App\Models\PermisosModel;
use App\Models\DetallesPermisosModel;

class PermisosController extends BaseController {

    protected $permisosModel;
    protected $detallePermisosModel;

    public function __construct() {
        $this->permisosModel = new PermisosModel();
        $this->detallePermisosModel = new DetallesPermisosModel();
    }

    public function obtenerPermisos($usuarioId) {
        $modulos = $this->permisosModel->findAll();
        $permisos = $this->detallePermisosModel->where('id_usuarios', $usuarioId)->findAll();
        $asignados = array_column($permisos, 'id_permisos');
        
        return $this->response->setJSON(['modulos' => $modulos, 'asignados' => $asignados]);
    }
// Guardar los permisos asignados
    public function guardarPermisos() {
        $usuarioId = $this->request->getPost('id_usuario');
        $modulos = $this->request->getPost('permisos');

        // Eliminar permisos existentes para el usuario
        $this->detallePermisosModel->where('id_usuarios', $usuarioId)->delete();

        // Insertar nuevos permisos
        if (!empty($modulos)) {
            foreach ($modulos as $moduloId) {
                $this->detallePermisosModel->insert([
                    'id_usuarios' => $usuarioId,
                    'id_permisos' => $moduloId
                ]);
            }
        }

        return $this->response->setJSON(['ok' => true, 'post' => 'Permisos guardados correctamente.']);
    }
}
