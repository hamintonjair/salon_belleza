<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table      = 'productos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre', 'cantidad', 'v_compra', 'v_venta', 'estado'];

    public function decrement($field, $amount, $where)
    {
        $builder = $this->builder();
        $builder->where($where);
        $builder->set($field, "$field - $amount", false);
        return $builder->update();
    }
    // Método para incrementar la cantidad de un producto

    public function increment($field, $amount, $where)
    {
        $builder = $this->builder(); // Asegúrate de usar el método correcto para obtener el constructor de consultas.
        $builder->set($field, "$field + $amount", false);
        $builder->where($where);
        return $builder->update();
    }

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
