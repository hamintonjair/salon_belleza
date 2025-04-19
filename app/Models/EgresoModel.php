<?php

namespace App\Models;

use CodeIgniter\Model;

class EgresoModel extends Model
{
    protected $table      = 'egresos';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'tipo_egreso',
        'empleado_id',
        'estado',
        'concepto',
        'tipo',
        'monto',
        'fecha'
    ];

    public function obtenerReporte()
    {
        // Lista todos los egresos donde el monto sea mayor a 0, ordenados por fecha descendente
        return $this->where('monto >', 0)
            ->orderBy('fecha', 'DESC')
            ->findAll();
    }

    public function obtenerReporteMensual()
    {
        // Agrupa egresos por mes y suma montos
        return $this->db->table($this->table)
            ->select("DATE_FORMAT(fecha, '%Y-%m') AS mes, SUM(monto) AS total")
            ->groupBy('mes')
            ->orderBy('mes', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Suma todos los egresos filtrados por mes/año.
     */
    public function sumarEgresos($month = null, $year = null)
    {
        $data = $this->obtenerReporte();
        if ($month) {
            $data = array_filter($data, fn($row) => date('m', strtotime($row['fecha'])) === $month);
        }
        if ($year) {
            $data = array_filter($data, fn($row) => date('Y', strtotime($row['fecha'])) === $year);
        }
        return array_reduce($data, fn($carry, $item) => $carry + floatval($item['monto']), 0);
    }

    /**
     * Inserta un nuevo egreso.
     */
    public function crearEgreso(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Retorna el saldo disponible (ingresos totales - egresos totales)
     */
    public function getSaldoDisponible()
    {
        $ventaModel = new \App\Models\VentaModel();
        $ingresos = $ventaModel->sumarIngresos();
        $egresos = $this->sumarEgresos();
        return $ingresos - $egresos;
    }
}
