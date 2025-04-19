<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ManualController extends BaseController
{
    /**
     * Muestra la vista del manual de usuario
     */
    public function index()
    {
        echo view('layout/admin/slider');
        echo view('layout/admin/nabvar');
        echo view('layout/admin/manual');
        echo view('layout/admin/footer');
    }
}
