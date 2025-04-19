<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\DetallespermisosModel;
class ProductoController extends BaseController
{
    protected $producto, $permisos;
    function __construct()
    {
        $this->producto = new ProductoModel();
        $this->permisos = new DetallespermisosModel();

    }
    public function products()
    {
        $session = session();
        $userId = $session->get('idUsuario'); // Obtener el ID del producto desde la sesión

        // Obtener permisos del producto
        $permissions = $this->permisos->where('id_usuarios', $userId)->findAll();
        $data['permissions'] = array_column($permissions, 'id_permisos');

        if (in_array(8, $data['permissions'])) {
            echo view('layout/admin/slider');
            echo view('layout/admin/nabvar');
            echo view('layout/ventas/productos');
            echo view('layout/admin/footer');
        } else {
            echo view('layout/usuario/no_permisos');
        }
    }
    // listar productos
    public function getProducts()
    {
        $producto = $this->producto->where('estado', 1)->findAll();
        foreach ($producto as &$item) {

            $session = session();
            if ($session->get('rol') == 'Administrador') {
                $item['accion'] = '<button class="btn btn-danger btn-sm eliminar" onclick="eliminarProducto(' . $item['id'] . ')"><i class="fas fa-trash-alt"></i></button> ';
                $item['accion'] .= '<button class="btn btn-primary btn-sm editar" onclick="editarProducto(' . $item['id'] . ')"><i class="fas fa-edit"></i></button> ';
            } else {
                $item['accion'] = '<button class="btn btn-danger btn-sm eliminar" onclick="eliminarProducto(' . $item['id'] . ')" disabled><i class="fas fa-trash-alt"></i></button> ';
                $item['accion'] .= '<button class="btn btn-primary btn-sm editar" onclick="editarProducto(' . $item['id'] . ')"><i class="fas fa-edit"></i></button> ';
            }
        }
        echo json_encode($producto, JSON_UNESCAPED_UNICODE);
    }
    //guardar productos
    public function guardar()
    {
        // Recibir datos del formulario
        $id = $this->request->getPost('idProducto');
        $nombre = $this->request->getPost('nombre');
        $cantidad = $this->request->getPost('cantidad');
        $v_compra = $this->request->getPost('v_compra');
        $v_venta = $this->request->getPost('v_venta');

        $data = [
            'nombre' => $nombre,
            'cantidad' => $cantidad,
            'v_compra' => $v_compra,
            'v_venta' => $v_venta,
            
        ];

        $productoExistente = $this->producto->where('nombre', $nombre)->first();
        if ($productoExistente) {
            // Si ya existe un producto con la misma cédula o correo, mostrar un mensaje de error
            $message = 'Ya existe un producto con el mismo nombre.';
            $result = false;
        } else {
            if (empty($id)) {
                // Registrar nuevo producto
                $result = $this->producto->insert($data);
                $message = $result ? 'Producto registrado correctamente.' : 'Error al registrar el producto.';
            }
        }
        if (!empty($id)) {
            // Actualizar producto existente         
            $result = $this->producto->update($id, $data);
            $message = $result ? 'Producto actualizado correctamente.' : 'Error al actualizar el producto.';
        }
        $response = [
            'ok' => $result,
            'post' => $message
        ];

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    // obtener producto
    public function getProduct($id)
    {
        $producto = $this->producto->where('id', $id)->first();
        echo json_encode($producto, JSON_UNESCAPED_UNICODE);
    }
    // eliminar producto
    public function deleteProduct($id)
    {
        $producto = $this->producto->where('id', $id)->find();

        if ($producto) {
            // Actualizar el producto en la base de datos
            $this->producto->delete($id);

            $msg = array('ok' => true, 'post' => 'Producto eliminado correctamente.');
        } else {
            $msg = array('ok' => false, 'post' => 'Producto no encontrado.');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    }
}
