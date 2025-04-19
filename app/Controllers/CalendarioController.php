<?php

namespace App\Controllers;
use App\Models\AgendaModel;
use App\Models\ClienteModel;
use App\Models\ServicioModel;
use App\Models\pagosEmpleadosModel;

class CalendarioController extends BaseController
{
    protected $eventsModel, $cliente, $servicio, $pagosEmpleadosModel;
    function __construct()
    {
        $this->eventsModel = new AgendaModel();
        $this->cliente = new ClienteModel();
        $this->servicio = new ServicioModel();
        $this->pagosEmpleadosModel = new pagosEmpleadosModel();


    }
    // guardar turno agendado
    public function save() {
        $data = $this->request->getJSON();

        $client = $this->cliente->where('id', $data->idCliente)->first();
        $servicio = $this->servicio->where('id', $data->service)->first();

        $session = session();
        $idUsuario = $session->get('idUsuario');
        // Preparar datos para la inserción
        $appointment = [
            'nombre' => $client['nombre'],
            'apellidos' =>  $client['apellidos'],
            'cedula' => $client['cedula'],
            'telefono' => $client['telefono'],
            'servicio' => $servicio['nombre'],
            'precio' => $servicio['precio'],
            'pago_empleado' => $servicio['pago_empleado'],
            'date' => $data->date,
            'time' => $data->time,
            'idUsuario' => $idUsuario,
        ];
       
        if( $this->eventsModel->insert($appointment)){  

            //  $insertID = $this->eventsModel->insertID();
            //  $data = [
            //     'turno_id' => $insertID
            // ];
            //  $this->pagosEmpleadosModel->insert($data);
             return $this->response->setJSON(['success' => true, 'message' => 'Turno agendado exitosamente.']);
        }  

       
    }
    // Obtener datos del calendario
    public function getBookings()
    {
        // Obtener todos los turnos
        $data = $this->eventsModel->where('estado','Pendiente')->findAll();
        return $this->response->setJSON($data);
    } 
//    obtener cliente mediante su id
    public function getClientById($idNumber) {
        // Establecer el tipo de contenido como JSON
        header('Content-Type: application/json');
        // Asegúrate de que la cédula es un número
        if (!is_numeric($idNumber)) {
            echo json_encode(['success' => false, 'message' => 'Cédula inválida']);
            return;
        }
        $client = $this->cliente->where('cedula', $idNumber)->first();
        if ($client) {
            return $this->response->setJSON(['success' => true, 'client' => $client]);
        } else {
             return $this->response->setJSON(['success' => false, 'message' => 'Cliente no encontrado']);
        }
    }
    
}
