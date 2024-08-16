<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LoginController::index');
$routes->get('logout', 'LoginController::logout');
$routes->post('login/validar', 'LoginController::validar');

// DASBOARD
$routes->get('dashboard', 'Home::dashboard');
// USUARIO
$routes->get('Users', 'UsuarioController::user');
$routes->get('getUsers', 'UsuarioController::getUsers');
$routes->post('usuarios/setUsers', 'UsuarioController::guardar');
$routes->post('usuarios/updateUsers', 'UsuarioController::guardar');
$routes->get('usuarios/getUser/(:num)', 'UsuarioController::getUser/$1');
$routes->post('usuarios/deleteUsers/(:num)', 'UsuarioController::deleteUser/$1');

// CLIENTE
$routes->get('Cliente', 'ClienteController::client');
$routes->get('getClients', 'ClienteController::getClients');
$routes->post('clientes/setClient', 'ClienteController::guardar');
$routes->post('clientes/updateClient', 'ClienteController::guardar');
$routes->get('clientes/getClient/(:num)', 'ClienteController::getclient/$1');
$routes->post('clientes/deleteClient/(:num)', 'ClienteController::deleteclient/$1');

// EMPLEADOS
$routes->get('Empleado', 'EmpleadoController::empleado');
$routes->get('getEmpleados', 'EmpleadoController::getEmpleados');
$routes->post('empleado/setEmpleado', 'EmpleadoController::guardar');
$routes->post('empleado/updateEmpleado', 'EmpleadoController::guardar');
$routes->get('empleado/getEmpleado/(:num)', 'EmpleadoController::getEmpleado/$1');
$routes->post('empleado/deleteEmpleado/(:num)', 'EmpleadoController::deleteEmpleado/$1');

//PERMISOS
$routes->get('usuarios/obtenerPermisos/(:num)', 'PermisosController::obtenerPermisos/$1');
$routes->post('usuarios/guardarPermisos', 'PermisosController::guardarPermisos');

// SERVICIOS
$routes->get('servic', 'ServicioController::services');
$routes->get('getServices', 'ServicioController::getServices');
$routes->post('services/setServices', 'ServicioController::guardar');
$routes->post('services/updateServices', 'ServicioController::guardar');
$routes->get('services/getService/(:num)', 'ServicioController::getService/$1');
$routes->post('services/deleteServices/(:num)', 'ServicioController::deleteService/$1');

// PRODUCTOS
$routes->get('product', 'ProductoController::products');
$routes->get('getProducts', 'ProductoController::getProducts');
$routes->post('products/setProducts', 'ProductoController::guardar');
$routes->post('products/updateProducts', 'ProductoController::guardar');
$routes->get('products/getProduct/(:num)', 'ProductoController::getProduct/$1');
$routes->post('products/deleteProducts/(:num)', 'ProductoController::deleteProduct/$1');

// CALENDARIO
$routes->post('booking/save', 'CalendarioController::save');
$routes->get('calendario/getBookings', 'CalendarioController::getBookings');
$routes->get('cliente/getClientById/(:num)', 'CalendarioController::getClientById/$1');

// TURNOS
$routes->get('turnos', 'TurnoController::turnos');
$routes->get('getTurnos', 'TurnoController::getTurnos');
// $routes->post('turnos/setTurnos', 'TurnoController::guardar');
// $routes->post('turnos/updateTurnos', 'TurnoController::guardar');
// $routes->get('turnos/getTurno/(:num)', 'TurnoController::getTurno/$1');
$routes->post('turnos/deleteTurnos/(:num)', 'TurnoController::deleteTurno/$1');
$routes->get('finalizados', 'TurnoController::turnosFinalizados');
$routes->get('getTurnos/finalizados', 'TurnoController::getTurnosFinalizados');
$routes->get('turno/generatePdf/(:num)', 'TurnoController::generatePdf/$1');
$routes->get('cargarDatosParaModal', 'TurnoController::cargarDatosParaModal');
$routes->post('agregarServicioProducto', 'TurnoController::agregarServicioProducto');
$routes->post('turno/atenderTurno/(:num)', 'TurnoController::atenderTurno/$1');
$routes->post('turno/anularTurno/(:num)', 'TurnoController::anularTurno/$1');
$routes->post('turno/finalizarTurno/(:num)', 'TurnoController::finalizarTurno/$1');
$routes->get('turno/getTrabajadores', 'TurnoController::getTrabajadores');
$routes->post('turno/asignarTrabajador', 'TurnoController::asignarTrabajador');
$routes->get('turno/removeExpiredTurnos', 'TurnoController::removeExpiredTurnos');

// EMPRESA
$routes->get('empresa', 'EmpresaController::empresa');
$routes->get('empresa/getDatos', 'EmpresaController::getDatos');
$routes->post('empresa/actualizar', 'EmpresaController::actualizar');
$routes->get('empresa/getEmpresa/(:num)', 'EmpresaController::getEmpresa/$1');

// PAGOS
$routes->get('pagos', 'PagosEmpleados::pagos');
$routes->get('pagos_empleados/getEmpleados', 'PagosEmpleados::getEmpleados');
$routes->post('pagos_empleados/pagarEmpleado', 'PagosEmpleados::pagarEmpleado');
$routes->get('pagos_empleados/getServiciosRealizados/(:num)', 'PagosEmpleados::getServiciosRealizados/$1');
$routes->get('pagos_empleados/getPagosRealizados/(:num)', 'PagosEmpleados::getPagosRealizados/$1');
$routes->get('pagos_empleados/getPagosEmpleado/(:num)', 'PagosEmpleados::getPagosEmpleado/$1');

// VENTAS
$routes->get('vent', 'VentasController::ventas');
$routes->get('ventas/productos', 'VentasController::obtenerProductos');
$routes->get('ventas/clientes', 'VentasController::obtenerClientes');
$routes->post('ventas/confirmar', 'VentasController::realizar');
$routes->get('list', 'VentasController::ventasRealizadas');
$routes->get('ventas/anular/(:num)', 'VentasController::anular/$1');
$routes->get('ventas/generarPFP/(:num)', 'VentasController::generarPFP/$1');
$routes->get('ventas/getVentas', 'VentasController::getVentas');

// REPORTES
// $routes->get('report', 'ReportesController::reportes');
// $routes->get('reportes/clientes', 'ReportesController::reporteClientes');
// $routes->get('reportes/servicios', 'ReportesController::reporteServicios');
// $routes->get('reportes/pagos', 'ReportesController::reportePagos');
// $routes->get('reportes/pagosEmpleados', 'ReportesController::reportePagosEmpleados');
// $routes->get('reportes/ventas', 'ReportesController::reporteVentas');

$routes->get('report', 'ReportesController::reporte');
$routes->get('reportes/ganancias', 'ReportesController::ganancias');
