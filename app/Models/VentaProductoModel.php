<?php

namespace App\Models;

use CodeIgniter\Model;

class VentaProductoModel extends Model
{
    protected $table      = 'venta_productos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['venta_id', 'producto_nombre', 'precio_unitario', 'cantidad', 'descuento', 'valor_total'];

  
      
    public function obtenerGananciasMensuales($mes)
    {
        // Obtener las ganancias de productos vendidos durante el mes especificado
        $gananciasProductos = $this->db->table('venta_productos')
            ->select('DATE(venta_productos.fecha_venta) AS fecha, SUM((venta_productos.precio_unitario - productos.v_compra) * venta_productos.cantidad) AS ganancia_total, "producto" AS tipo')
            ->join('productos', 'productos.nombre = venta_productos.producto_nombre')
            ->join('ventas', 'ventas.id = venta_productos.venta_id')
            ->where('DATE_FORMAT(venta_productos.fecha_venta, "%Y-%m")', $mes)
            ->groupBy('DATE(venta_productos.fecha_venta)')
            ->orderBy('fecha', 'ASC')
            ->get()
            ->getResultArray();
    
        // Obtener las ganancias de servicios realizados durante el mes especificado
        $gananciasServicios = $this->db->table('turno_servicios')
            ->select('DATE(turno_servicios.fecha_servicio) AS fecha, SUM(turno_servicios.precio_servicio - turno_servicios.pago_empleado) AS ganancia_total, "servicio" AS tipo')
            ->join('agenda', 'agenda.id = turno_servicios.turno_id')
            ->where('DATE_FORMAT(turno_servicios.fecha_servicio, "%Y-%m")', $mes)
            ->groupBy('DATE(turno_servicios.fecha_servicio)')
            ->orderBy('fecha', 'ASC')
            ->get()
            ->getResultArray();
    
        // Combinar las ganancias de productos y servicios
        $gananciasCombinadas = [];
    
        foreach ($gananciasProductos as $producto) {
            $fecha = $producto['fecha'];
            if (!isset($gananciasCombinadas[$fecha])) {
                $gananciasCombinadas[$fecha] = ['fecha' => $fecha, 'ganancia_total' => 0];
            }
            $gananciasCombinadas[$fecha]['ganancia_total'] += $producto['ganancia_total'];
        }
    
        foreach ($gananciasServicios as $servicio) {
            $fecha = $servicio['fecha'];
            if (!isset($gananciasCombinadas[$fecha])) {
                $gananciasCombinadas[$fecha] = ['fecha' => $fecha, 'ganancia_total' => 0];
            }
            $gananciasCombinadas[$fecha]['ganancia_total'] += $servicio['ganancia_total'];
        }
    
        // Ordenar las ganancias combinadas por fecha
        ksort($gananciasCombinadas);
    
        // Acumular las ganancias día a día
        $gananciaAcumulada = 0;
        foreach ($gananciasCombinadas as &$ganancia) {
            $gananciaAcumulada += $ganancia['ganancia_total'];
            $ganancia['ganancia_acumulada'] = $gananciaAcumulada;
        }
    
        // Convertir a lista para retornar
        return array_values($gananciasCombinadas);
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