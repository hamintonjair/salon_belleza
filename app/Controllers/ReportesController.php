<?php

namespace App\Controllers;

use App\Models\VentaModel;
use App\Models\VentaProductoModel;
use App\Models\DetallesPermisosModel;
use App\Models\TurnoProductosModel;

class ReportesController extends BaseController
{
    protected $reporteModel, $gananciaModel, $permisos, $turnoP;
    function __construct()
    {
        $this->reporteModel = new VentaModel();
        $this->gananciaModel = new VentaProductoModel();
        $this->permisos = new DetallesPermisosModel();
        $this->turnoP = new TurnoProductosModel();
    }
    public function reporte()
    {
        $session = session();
        $userId = $session->get('idUsuario'); // Obtener el ID del usuario desde la sesión
        // Obtener permisos del usuario
        $permissions = $this->permisos->where('id_usuarios', $userId)->findAll();
        $data['permissions'] = array_column($permissions, 'id_permisos');
        $data['reportes'] = $this->reporteModel->obtenerReporte();

        if (in_array(4, $data['permissions'])) {
            echo view('layout/admin/slider');
            echo view('layout/admin/nabvar');
            echo view('layout/reportes/listar', $data);
            echo view('layout/admin/footer');
        } else {
            echo view('layout/usuario/no_permisos');
        }
    }

    public function ganancias()
    {

        $data['ganancias_mensuales'] = $this->reporteModel->obtenerReporteMensual();

        echo view('layout/admin/slider');
        echo view('layout/admin/nabvar');
        echo view('layout/reportes/ganancias', $data);
        echo view('layout/admin/footer');
    }
   
}
