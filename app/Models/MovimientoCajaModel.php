<?php
namespace App\Models;

use CodeIgniter\Model;

class MovimientoCajaModel extends Model
{
    protected $table      = 'movimientos_caja';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['fecha', 'tipo', 'monto', 'descripcion'];

    /**
     * Inserta un nuevo movimiento en caja.
     * tipo: 'ingreso' o 'egreso'
     */
    public function crearMovimiento(string $tipo, float $monto, string $descripcion = null)
    {
        $data = [
            'fecha'       => date('Y-m-d H:i:s'),
            'tipo'        => $tipo,
            'monto'       => $monto,
            'descripcion' => $descripcion
        ];
        return $this->insert($data);
    }

    /**
     * Obtiene el saldo (ingresos - egresos) filtrado por mes y año.
     */
    public function obtenerSaldo(string $month = null, string $year = null): float
    {
        $builder = $this->db->table($this->table)
            ->select('SUM(CASE WHEN tipo = "ingreso" THEN monto ELSE -monto END) AS saldo');
        if ($month) {
            $builder->where('MONTH(fecha)', $month);
        }
        if ($year) {
            $builder->where('YEAR(fecha)', $year);
        }
        $row = $builder->get()->getRowArray();
        return isset($row['saldo']) ? floatval($row['saldo']) : 0.0;
    }
}
