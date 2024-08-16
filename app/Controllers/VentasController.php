<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\DetallesPermisosModel;
use App\Models\ClienteModel;
use App\Models\EmpresaModel;

use App\Models\ProductoModel;
use App\Models\VentaModel;
use App\Models\VentaProductoModel;

use Dompdf\Dompdf;
use Dompdf\Options;

class VentasController extends BaseController
{
    protected $usuario, $permisos, $productoModel, $ventaModel, $ventaProductoModel, $cliente, $empresa;
    function __construct()
    {
        $this->usuario = new UsuarioModel();
        $this->permisos = new DetallespermisosModel();
        $this->productoModel = new ProductoModel();
        $this->ventaModel = new VentaModel();
        $this->ventaProductoModel = new VentaProductoModel();
        $this->cliente = new ClienteModel();
        $this->empresa = new EmpresaModel();
    }

    public function ventas()
    {
        $session = session();
        $userId = $session->get('idUsuario'); // Obtener el ID del usuario desde la sesión

        // Obtener permisos del usuario
        $permissions = $this->permisos->where('id_usuarios', $userId)->findAll();
        $data['permissions'] = array_column($permissions, 'id_permisos');

        if (in_array(12, $data['permissions'])) {

            echo view('layout/admin/slider');
            echo view('layout/admin/nabvar');
            echo view('layout/ventas/ventas');
            echo view('layout/admin/footer');
        } else {
            echo view('layout/usuario/no_permisos');
        }
    }
    public function ventasRealizadas()
    {

        $session = session();
        $userId = $session->get('idUsuario'); // Obtener el ID del usuario desde la sesión

        // Obtener permisos del usuario
        $permissions = $this->permisos->where('id_usuarios', $userId)->findAll();
        $data['permissions'] = array_column($permissions, 'id_permisos');

        if (in_array(13, $data['permissions'])) {
           
            echo view('layout/admin/slider');
            echo view('layout/admin/nabvar');
            echo view('layout/ventas/ventas_realizadas');
            echo view('layout/admin/footer');
        } else {
            echo view('layout/usuario/no_permisos');
        }
    }
    public function getVentas()
    {
        $ventas = $this->ventaModel->findAll(); // Asegúrate de ajustar esto según cómo obtienes las ventas
        foreach ($ventas as &$venta) {
            $venta['productos'] = $this->ventaProductoModel->where('venta_id', $venta['id'])->findAll();
            $venta['cliente'] = $this->cliente->find($venta['cliente_id']);
            $venta['usuario'] = $this->usuario->find($venta['usuario_id']);
        }
        $session = session();
        $venta['rol'] = $session->get('rol');

        return $this->response->setJSON([
            'ventas' => $ventas
        ]);
    }
    // realizar venta
    public function realizar()
    {
        $session = session();
        $usuarioId = $session->get('idUsuario');
        $clienteId = $this->request->getPost('cliente');
        $productos = $this->request->getPost('productos');
        $totalPagar = $this->request->getPost('montoPagado');

        // Calcular el total a pagar sumando los valores de todos los productos
        $totalPagar = 0;
        foreach ($productos as $producto) {
            $totalPagar += $producto['valorTotal'];
        }

        // Crear la venta
        $ventaId = $this->ventaModel->insert([
            'cliente_id' => $clienteId,
            'total' => $totalPagar,
            'usuario_id' => $usuarioId
        ]);

        // Guardar productos de la venta
        foreach ($productos as $producto) {
            $this->ventaProductoModel->insert([
                'venta_id' => $ventaId,
                'producto_nombre' => $producto['nombre'],
                'precio_unitario' => $producto['precioUnitario'],
                'cantidad' => $producto['cantidad'],
                'descuento' => $producto['descuento'],
                'valor_total' => $producto['valorTotal']
            ]);

            // Actualizar cantidad de producto
            $this->productoModel->decrement('cantidad', $producto['cantidad'], ['nombre' => $producto['nombre']]);
        }

        return $this->response->setJSON(['success' => true]);
    }
    // obtener producto para la venta
    public function obtenerProductos()
    {
        $productos = $this->productoModel->findAll();

        return $this->response->setJSON(['productos' => $productos]);
    }
    // obtener clientes para la venta
    public function obtenerClientes()
    {
        $clientes = $this->cliente->findAll();

        return $this->response->setJSON(['clientes' => $clientes]);
    }
    // anular la venta
    public function anular($ventaId)
    {
        // Obtener productos de la venta
        $productos = $this->ventaProductoModel->where('venta_id', $ventaId)->findAll();

        // Devolver cantidad de producto
        foreach ($productos as $producto) {
            $this->productoModel->increment('cantidad', $producto['cantidad'], ['nombre' => $producto['producto_nombre']]);
        }

        // Eliminar la venta y sus productos
        $this->ventaProductoModel->where('venta_id', $ventaId)->delete();
        if ($this->ventaModel->delete($ventaId)) {
            return $this->response->setJSON(['ok' => true, 'post' => 'Se anuló la venta']);
        } else {
            return $this->response->setJSON(['ok' => false, 'post' => 'No se pudo anular la venta']);
        };
    }
    // generar archivo pdf
    public function generarPFP($ventaId)
    {
        // Obtener datos de la venta
        $venta = $this->ventaModel->find($ventaId);
        $productos = $this->ventaProductoModel->where('venta_id', $ventaId)->findAll();
        $cliente = $this->cliente->find($venta['cliente_id']);
        $empresa = $this->empresa->find();


        // Cargar la vista HTML para el PDF
        $html = view('layout/ventas/pdf', [
            'venta' => $venta,
            'productos_totales' => $productos,
            'servicios' => [], // Si hay servicios, agrégales aquí
            'precio_total' => $venta['total'], // Ajusta según tu lógica
            'empresa' => $empresa[0],
            'cliente' => $cliente,
        ]);
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true); // Permite cargar imágenes remotas

        $dompdf = new Dompdf($options);

        // Configurar DOMPDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait'); // Tamaño y orientación del papel
        $dompdf->render(); // Renderizar el HTML a PDF

        // Enviar el PDF al navegador
        $dompdf->stream("venta_{$ventaId}.pdf", ['Attachment' => 0]);

        return $this->response;
    }
}
