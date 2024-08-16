<?php

namespace App\Models;

use CodeIgniter\Model;

class VentaModel extends Model
{
    protected $table      = 'ventas';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['cliente_id', 'total', 'usuario_id'];

    public function obtenerReporte()
    {
        // Obtener los productos vendidos
        $productos = $this->db->table('ventas')
            ->select('usuarios.nombre AS nombre_usuario, usuarios.apellidos AS apellidos, venta_productos.producto_nombre, venta_productos.cantidad, venta_productos.valor_total, venta_productos.fecha_venta')
            ->join('usuarios', 'usuarios.id = ventas.usuario_id')
            ->join('venta_productos', 'venta_productos.venta_id = ventas.id')
            ->orderBy('venta_productos.fecha_venta', 'DESC')
            ->get()
            ->getResultArray();

        // Obtener los servicios realizados
        $servicios = $this->db->table('turno_servicios')
            ->select('usuarios.nombre AS nombre_usuario, usuarios.apellidos AS apellidos, turno_servicios.nombre_servicio AS producto_nombre,
             "N/A" AS cantidad, (turno_servicios.precio_servicio - turno_servicios.pago_empleado) AS valor_total,
              turno_servicios.fecha_servicio AS fecha_venta')
            ->join('usuarios', 'usuarios.id = turno_servicios.idUsuario')
            ->join('pagos_empleados', 'pagos_empleados.empleado_id = turno_servicios.trabajador_id')
            ->orderBy('turno_servicios.fecha_servicio', 'DESC')
            ->get()
            ->getResultArray();

        // Obtener productos vendidos en los turnos
        $productosTurnos = $this->db->table('turno_productos')
            ->select('usuarios.nombre AS nombre_usuario, usuarios.apellidos AS apellidos, turno_productos.nombre_producto AS producto_nombre, 
            turno_productos.cantidad, turno_productos.subtotal AS valor_total, turno_productos.fecha_venta')
            ->join('usuarios', 'usuarios.id = turno_productos.idUsuario')
            ->join('agenda', 'agenda.id = turno_productos.turno_id')
            ->orderBy('turno_productos.fecha_venta', 'DESC')
            ->get()
            ->getResultArray();

        // Obtener los servicios de la agenda
        $serviciosAgenda = $this->db->table('agenda')
            ->select('usuarios.nombre AS nombre_usuario, usuarios.apellidos AS apellidos, agenda.servicio AS producto_nombre,
             "N/A" AS cantidad, (agenda.precio - agenda.pago_empleado) AS valor_total, agenda.date AS fecha_venta')
            ->join('usuarios', 'usuarios.id = agenda.idUsuario')
            ->where('agenda.estado', 'Finalizado')
            ->orderBy('agenda.date', 'DESC')
            ->get()
            ->getResultArray();
        // Unir todos los datos (productos vendidos, servicios realizados, productos vendidos en turnos)
        $reportes = array_merge($productos, $servicios, $productosTurnos,  $serviciosAgenda);

        // Devolver los datos
        return $reportes;
    }


    public function obtenerReporteMensual()
    {
        // Obtener las ganancias mensuales de productos vendidos
        $gananciasProductos = $this->db->table('ventas')
            ->select('DATE_FORMAT(venta_productos.fecha_venta, "%Y-%m") AS mes, SUM(venta_productos.valor_total) AS ganancia_total, "producto" AS tipo')
            ->join('venta_productos', 'venta_productos.venta_id = ventas.id')
            ->groupBy('mes')
            ->orderBy('mes', 'ASC')
            ->get()
            ->getResultArray();

        // Obtener las ganancias mensuales de servicios realizados
        $gananciasServicios = $this->db->table('turno_servicios')
            ->select('DATE_FORMAT(turno_servicios.fecha_servicio, "%Y-%m") AS mes, SUM(turno_servicios.precio_servicio - turno_servicios.pago_empleado) AS ganancia_total, "servicio" AS tipo')
            ->groupBy('mes')
            ->orderBy('mes', 'ASC')
            ->get()
            ->getResultArray();

        // Obtener las ganancias mensuales de productos vendidos en turnos
        $gananciasProductosTurnos = $this->db->table('turno_productos')
            ->select('DATE_FORMAT(turno_productos.fecha_venta, "%Y-%m") AS mes, SUM(turno_productos.subtotal) AS ganancia_total, "producto" AS tipo')
            ->groupBy('mes')
            ->orderBy('mes', 'ASC')
            ->get()
            ->getResultArray();

        // Obtener las ganancias mensuales de los servicios de la agenda
        $gananciasServiciosAgenda = $this->db->table('agenda')
            ->select('DATE_FORMAT(agenda.date, "%Y-%m") AS mes, SUM(agenda.precio - agenda.pago_empleado) AS ganancia_total, "servicio" AS tipo')
            ->where('agenda.estado', 'Finalizado')
            ->groupBy('mes')
            ->orderBy('mes', 'ASC')
            ->get()
            ->getResultArray();

        // Combinar las ganancias de productos (de ventas y turnos)
        $gananciasProductosTotales = array_merge($gananciasProductos, $gananciasProductosTurnos);

        // Combinar las ganancias de servicios (de turnos y agenda)
        $gananciasServiciosTotales = array_merge($gananciasServicios, $gananciasServiciosAgenda);

        // Inicializar array para el resultado final
        $gananciasMensuales = [];

        // Acumular las ganancias mensuales de productos
        foreach ($gananciasProductosTotales as $producto) {
            $mes = $producto['mes'];
            if (!isset($gananciasMensuales[$mes]['producto'])) {
                $gananciasMensuales[$mes]['mes'] = $mes;
                $gananciasMensuales[$mes]['producto'] = 0;
                $gananciasMensuales[$mes]['servicio'] = 0;
            }
            $gananciasMensuales[$mes]['producto'] += $producto['ganancia_total'];
        }

        // Acumular las ganancias mensuales de servicios
        foreach ($gananciasServiciosTotales as $servicio) {
            $mes = $servicio['mes'];
            if (!isset($gananciasMensuales[$mes]['servicio'])) {
                $gananciasMensuales[$mes]['mes'] = $mes;
                $gananciasMensuales[$mes]['producto'] = 0;
                $gananciasMensuales[$mes]['servicio'] = 0;
            }
            $gananciasMensuales[$mes]['servicio'] += $servicio['ganancia_total'];
        }

        // Convertir el resultado a una lista de valores para retorno
        return array_values($gananciasMensuales);
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
