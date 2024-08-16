<?php

namespace App\Models;

use CodeIgniter\Model;

class GananciaModel extends Model
{
    protected $table = 'ventas'; // Cambia 'ventas' al nombre de tu tabla de ventas
    protected $primaryKey = 'id';
    protected $allowedFields = ['producto_id', 'cantidad', 'precio_compra', 'precio_venta', 'fecha'];

    // public function obtenerGananciasDiarias($fecha)
    // {
    //     return $this->select('SUM((precio_venta - precio_compra) * cantidad) AS ganancia_total')
    //                 ->where('fecha', $fecha)
    //                 ->get()
    //                 ->getRowArray();
    // }
    
    // public function obtenerGananciasMensuales($mes)
    // {
    //     return $this->select('fecha, SUM((precio_venta - precio_compra) * cantidad) AS ganancia_total')
    //                 ->where('DATE_FORMAT(fecha, "%Y-%m")', $mes)
    //                 ->groupBy('fecha')
    //                 ->orderBy('fecha', 'ASC')
    //                 ->findAll();
    // }

    
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
