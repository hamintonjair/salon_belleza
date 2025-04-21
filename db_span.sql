-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-04-2025 a las 16:01:43
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_span`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agenda`
--

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `cedula` int(10) NOT NULL,
  `telefono` int(10) NOT NULL,
  `servicio` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `pago_empleado` double(10,2) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'Pendiente',
  `trabajador_id` int(11) DEFAULT NULL,
  `idusuario` int(11) NOT NULL,
  `estado_pago` varchar(20) DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `agenda`
--

INSERT INTO `agenda` (`id`, `nombre`, `apellidos`, `cedula`, `telefono`, `servicio`, `precio`, `pago_empleado`, `date`, `time`, `estado`, `trabajador_id`, `idusuario`, `estado_pago`) VALUES
(1, 'GENERICO', 'GENERICO', 999999999, 5555555, 'Cepillado', 25000.00, 10000.00, '2025-04-17', '18:51:00', 'Finalizado', 2, 1, 'pagado'),
(2, 'GENERICO', 'GENERICO', 999999999, 5555555, 'Peinado en trenzas', 35000.00, 13000.00, '2025-04-18', '14:33:00', 'Finalizado', 3, 1, 'pagado'),
(3, 'GENERICO', 'GENERICO', 999999999, 5555555, 'Pestañas', 12000.00, 4000.00, '2025-04-18', '18:36:00', 'Anulado', NULL, 1, 'pendiente'),
(4, 'GENERICO', 'GENERICO', 999999999, 5555555, 'cejas', 15000.00, 5000.00, '2025-04-18', '18:36:00', 'Finalizado', 1, 1, 'pagado'),
(5, 'GENERICO', 'GENERICO', 999999999, 5555555, 'Peinado en trenzas', 35000.00, 13000.00, '2025-04-19', '08:24:00', 'Finalizado', 4, 1, 'pendiente'),
(8, 'GENERICO', 'GENERICO', 999999999, 5555555, 'Peluquería', 8000.00, 3000.00, '2025-04-19', '08:29:00', 'Finalizado', 3, 1, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `cedula` int(10) NOT NULL,
  `telefono` int(10) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellidos`, `cedula`, `telefono`, `direccion`, `estado`) VALUES
(1, 'Diana', 'Gamboa', 22313, 2147483647, 'Barrio los Angeles', 1),
(3, 'Camila', 'Ortiz', 12345, 5555555, 'Barrio Buenos Aires', 1),
(4, 'GENERICO', 'GENERICO', 999999999, 5555555, 'Quibdó Chocó', 1),
(6, 'Karol', 'mena', 123456, 2147483647, 'buenos aires', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_permisos`
--

CREATE TABLE `detalle_permisos` (
  `id` int(11) NOT NULL,
  `id_usuarios` int(11) NOT NULL,
  `id_permisos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_permisos`
--

INSERT INTO `detalle_permisos` (`id`, `id_usuarios`, `id_permisos`) VALUES
(103, 2, 2),
(104, 2, 5),
(105, 2, 6),
(106, 2, 8),
(107, 2, 9),
(108, 2, 11),
(109, 2, 12),
(110, 2, 13),
(111, 1, 1),
(112, 1, 2),
(113, 1, 3),
(114, 1, 4),
(115, 1, 5),
(116, 1, 6),
(117, 1, 7),
(118, 1, 8),
(119, 1, 9),
(120, 1, 10),
(121, 1, 11),
(122, 1, 12),
(123, 1, 13),
(124, 1, 14),
(125, 4, 1),
(126, 4, 2),
(127, 4, 3),
(128, 4, 4),
(129, 4, 5),
(130, 4, 6),
(131, 4, 7),
(132, 4, 8),
(133, 4, 9),
(134, 4, 10),
(135, 4, 11),
(136, 4, 12),
(137, 4, 13),
(138, 4, 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresos`
--

CREATE TABLE `egresos` (
  `id` int(10) UNSIGNED NOT NULL,
  `concepto` varchar(255) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `monto` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `tipo_egreso` varchar(50) DEFAULT 'general',
  `empleado_id` int(11) DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `egresos`
--

INSERT INTO `egresos` (`id`, `concepto`, `tipo`, `monto`, `fecha`, `tipo_egreso`, `empleado_id`, `estado`) VALUES
(1, 'compra de basura', 'Retiro', 10000.00, '2025-04-18 00:00:00', 'general', NULL, 'pendiente'),
(2, 'compra de basura', 'Retiro', 10000.00, '2025-04-18 00:00:00', 'general', NULL, 'pendiente'),
(3, 'compra paga de agua', 'Retiro', 5000.00, '2025-04-18 00:00:00', 'general', NULL, 'pendiente'),
(4, 'compra paga de agua', 'Retiro', 5000.00, '2025-04-18 00:00:00', 'general', NULL, 'pendiente'),
(5, 'prueba', 'Retiro', 2000.00, '2025-04-18 00:00:00', 'general', NULL, 'pendiente'),
(6, 'prestamo', '', 0.00, '2025-04-18 00:00:00', 'prestamo', 2, 'saldado'),
(7, 'prestamo', '', 0.00, '2025-04-18 00:00:00', 'prestamo', 2, 'saldado'),
(14, 'Pago a empleado: Carmensa Perea', '', 28000.00, '2025-04-18 00:00:00', 'Pago Empleado', 3, 'pendiente'),
(36, 'Pago a empleado: Leidy Asprilla', '', 19000.00, '2025-04-18 00:00:00', 'Pago Empleado', 2, 'pendiente'),
(37, 'prestamo', '', 0.00, '2025-04-18 00:00:00', 'prestamo', 1, 'saldado'),
(38, 'Pago a empleado: Sandra Paola Córdoba Mena', '', 3000.00, '2025-04-18 00:00:00', 'Pago Empleado', 1, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `cedula` int(10) NOT NULL,
  `telefono` int(10) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id`, `nombre`, `apellidos`, `cedula`, `telefono`, `direccion`, `estado`) VALUES
(1, 'Sandra Paola', 'Córdoba Mena', 22222, 5555555, 'Barrio kennedy', 1),
(2, 'Leidy', 'Asprilla', 12, 66666666, 'Barrio obapo', 1),
(3, 'Carmensa', 'Perea', 13424, 6666666, 'Centro', 1),
(4, 'Paula', 'Hinestroza', 1077345672, 7777777, 'Centro', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `nit` varchar(50) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ciudad` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id`, `nombre`, `nit`, `direccion`, `telefono`, `email`, `ciudad`) VALUES
(1, 'Beaunty Timesless - NAIL SPA', '99999999-1', 'Carrera 12 #46-136 barrio Buenos Aires', '3155555555', 'prueba@gmail.com', 'Quibdo  Choco  Colombía');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2025-04-17-151042', 'App\\Database\\Migrations\\CreateMovimientosCaja', 'default', 'App', 1744920820, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_caja`
--

CREATE TABLE `movimientos_caja` (
  `id` int(10) UNSIGNED NOT NULL,
  `fecha` datetime NOT NULL,
  `tipo` enum('ingreso','egreso') NOT NULL DEFAULT 'ingreso',
  `monto` decimal(10,2) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos_caja`
--

INSERT INTO `movimientos_caja` (`id`, `fecha`, `tipo`, `monto`, `descripcion`) VALUES
(1, '2025-04-17 18:53:10', 'ingreso', 35000.00, 'Ingreso por finalización de turno #1 (Servicios y productos)'),
(2, '2025-04-18 00:08:09', 'ingreso', 3000.00, 'Venta ID 1'),
(3, '2025-04-18 01:12:33', 'egreso', 10000.00, 'compra de basura'),
(4, '2025-04-18 01:12:33', 'egreso', 10000.00, 'compra de basura'),
(5, '2025-04-18 01:14:00', 'egreso', 5000.00, 'compra paga de agua'),
(6, '2025-04-18 01:14:00', 'egreso', 5000.00, 'compra paga de agua'),
(7, '2025-04-18 01:20:58', 'egreso', 2000.00, 'prueba'),
(8, '2025-04-18 19:29:36', 'ingreso', 9000.00, 'Venta ID 2'),
(9, '2025-04-18 14:36:22', 'ingreso', 55000.00, 'Ingreso por finalización de turno #2 (Servicios y productos)'),
(10, '2025-04-18 00:00:00', '', 28000.00, 'Pago a empleado: Carmensa Perea'),
(32, '2025-04-18 00:00:00', '', 19000.00, 'Pago a empleado: Leidy Asprilla'),
(33, '2025-04-18 18:48:20', 'ingreso', 17500.00, 'Ingreso por finalización de turno #4 (Servicios y productos)'),
(34, '2025-04-18 00:00:00', '', 3000.00, 'Pago a empleado: Sandra Paola Córdoba Mena'),
(35, '2025-04-19 01:34:33', 'ingreso', 5000.00, 'Venta ID 3'),
(36, '2025-04-19 13:24:06', 'ingreso', 5000.00, 'Venta ID 4'),
(37, '2025-04-19 08:25:09', 'ingreso', 24500.00, 'Ingreso por finalización de turno #5 (Servicios y productos)'),
(38, '2025-04-19 08:30:45', 'ingreso', 7500.00, 'Ingreso por finalización de turno #8 (Servicios y productos)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_empleados`
--

CREATE TABLE `pagos_empleados` (
  `id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `cedula` varchar(50) NOT NULL,
  `pago` decimal(10,2) NOT NULL,
  `fecha_pago` date NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'pagado',
  `idusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos_empleados`
--

INSERT INTO `pagos_empleados` (`id`, `empleado_id`, `nombre`, `apellidos`, `cedula`, `pago`, `fecha_pago`, `estado`, `idusuario`) VALUES
(5, 3, 'Carmensa', 'Perea', '13424', 28000.00, '2025-04-18', 'pagado', 1),
(27, 2, 'Leidy', 'Asprilla', '12', 19000.00, '2025-04-18', 'pagado', 1),
(28, 1, 'Sandra Paola', 'Córdoba Mena', '22222', 3000.00, '2025-04-18', 'pagado', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `permiso` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `permiso`) VALUES
(1, 'Usuarios'),
(2, 'Clientes'),
(3, 'Egresos'),
(4, 'Reportes'),
(5, 'Servicios'),
(6, 'Turnos'),
(7, 'Empresa'),
(8, 'Productos'),
(9, 'Facturas'),
(10, 'Empleados'),
(11, 'Pagos'),
(12, 'Ventas'),
(13, 'Ventas Realizadas'),
(14, 'Ingresos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `cantidad` varchar(20) NOT NULL,
  `v_compra` double(10,2) NOT NULL,
  `v_venta` double(10,2) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `cantidad`, `v_compra`, `v_venta`, `estado`) VALUES
(1, 'Canecanol', '15', 5500.00, 8000.00, 1),
(2, 'Gaseosa personal', '15', 2000.00, 2500.00, 1),
(3, 'cerveza', '10', 2500.00, 3000.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `precio` double(10,2) NOT NULL,
  `pago_empleado` decimal(10,2) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `precio`, `pago_empleado`, `estado`) VALUES
(1, 'Cepillado', 25000.00, 10000.00, 1),
(2, 'Alisado', 30000.00, 13000.00, 1),
(3, 'Uñas', 45000.00, 15000.00, 1),
(4, 'Peinado en trenzas', 35000.00, 13000.00, 1),
(5, 'Cejas semi permanentes', 250000.00, 10000.00, 1),
(6, 'cejas', 15000.00, 5000.00, 1),
(7, 'Pestañas', 12000.00, 4000.00, 1),
(8, 'Maquillaje', 30000.00, 12000.00, 1),
(10, 'Peluquería', 8000.00, 3000.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno_productos`
--

CREATE TABLE `turno_productos` (
  `id` int(11) NOT NULL,
  `turno_id` int(11) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `fecha_venta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turno_productos`
--

INSERT INTO `turno_productos` (`id`, `turno_id`, `nombre_producto`, `cantidad`, `precio_unitario`, `subtotal`, `idusuario`, `fecha_venta`) VALUES
(1, 1, 'cerveza', 1, 3000.00, 3000.00, 1, '2025-04-17 23:53:03'),
(2, 2, 'cerveza', 1, 3000.00, 3000.00, 1, '2025-04-18 19:35:34'),
(3, 4, 'Gaseosa personal', 1, 2500.00, 2500.00, 1, '2025-04-18 23:37:44'),
(4, 5, 'Gaseosa personal', 1, 2500.00, 2500.00, 1, '2025-04-19 13:25:00'),
(5, 8, 'Gaseosa personal', 1, 2500.00, 2500.00, 1, '2025-04-19 13:30:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno_servicios`
--

CREATE TABLE `turno_servicios` (
  `id` int(11) NOT NULL,
  `turno_id` int(11) NOT NULL,
  `nombre_servicio` varchar(255) NOT NULL,
  `precio_servicio` decimal(10,2) NOT NULL,
  `pago_empleado` double(10,2) NOT NULL,
  `trabajador_id` int(11) DEFAULT NULL,
  `fecha_servicio` date DEFAULT NULL,
  `idusuario` int(11) NOT NULL,
  `estado_pago` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turno_servicios`
--

INSERT INTO `turno_servicios` (`id`, `turno_id`, `nombre_servicio`, `precio_servicio`, `pago_empleado`, `trabajador_id`, `fecha_servicio`, `idusuario`, `estado_pago`) VALUES
(1, 1, 'Alisado', 30000.00, 13000.00, 2, '2025-04-17', 1, 'pagado'),
(2, 2, 'Uñas', 45000.00, 15000.00, 3, '2025-04-18', 1, 'pagado'),
(3, 4, 'Peluquería', 8000.00, 3000.00, 1, '2025-04-18', 1, 'pagado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `cedula` int(10) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `clave` text NOT NULL,
  `rol` varchar(15) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `cedula`, `telefono`, `direccion`, `correo`, `clave`, `rol`, `estado`) VALUES
(1, 'Haminton', 'Mena Mena', 2345234, '3124942527', 'Barrio buenos aires', 'hamintonjair@gmail.com', '1cb8e186302e7bf3df367cc99060b263be8b9dd565e6e24db4dd7e8ae153e524', 'Administrador', 1),
(2, 'Anny', 'Gamboa', 1234, '3132435654', 'Barrio buenos aires', 'operador@gmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'Operador', 1),
(4, 'Admin', 'Administrador', 99999999, '5555555', 'centro', 'admin@gmail.com', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'Administrador', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `cliente_id`, `total`, `usuario_id`) VALUES
(1, 4, 3000.00, 1),
(2, 4, 9000.00, 1),
(3, 4, 5000.00, 2),
(4, 4, 5000.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_productos`
--

CREATE TABLE `venta_productos` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `producto_nombre` varchar(255) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `descuento` decimal(10,2) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `fecha_venta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `venta_productos`
--

INSERT INTO `venta_productos` (`id`, `venta_id`, `producto_nombre`, `precio_unitario`, `cantidad`, `descuento`, `valor_total`, `fecha_venta`) VALUES
(1, 1, 'cerveza', 3000.00, 1, 0.00, 3000.00, '2025-04-18 00:08:09'),
(2, 2, 'cerveza', 3000.00, 3, 0.00, 9000.00, '2025-04-18 19:29:36'),
(3, 3, 'Gaseosa personal', 2500.00, 2, 0.00, 5000.00, '2025-04-19 01:34:33'),
(4, 4, 'Gaseosa personal', 2500.00, 2, 0.00, 5000.00, '2025-04-19 13:24:06');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_permisos`
--
ALTER TABLE `detalle_permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimientos_caja`
--
ALTER TABLE `movimientos_caja`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos_empleados`
--
ALTER TABLE `pagos_empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `turno_productos`
--
ALTER TABLE `turno_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turno_id` (`turno_id`);

--
-- Indices de la tabla `turno_servicios`
--
ALTER TABLE `turno_servicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turno_id` (`turno_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `venta_productos`
--
ALTER TABLE `venta_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `detalle_permisos`
--
ALTER TABLE `detalle_permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT de la tabla `egresos`
--
ALTER TABLE `egresos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `movimientos_caja`
--
ALTER TABLE `movimientos_caja`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `pagos_empleados`
--
ALTER TABLE `pagos_empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `turno_productos`
--
ALTER TABLE `turno_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `turno_servicios`
--
ALTER TABLE `turno_servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `venta_productos`
--
ALTER TABLE `venta_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `turno_productos`
--
ALTER TABLE `turno_productos`
  ADD CONSTRAINT `turno_productos_ibfk_1` FOREIGN KEY (`turno_id`) REFERENCES `agenda` (`id`);

--
-- Filtros para la tabla `turno_servicios`
--
ALTER TABLE `turno_servicios`
  ADD CONSTRAINT `turno_servicios_ibfk_1` FOREIGN KEY (`turno_id`) REFERENCES `agenda` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `venta_productos`
--
ALTER TABLE `venta_productos`
  ADD CONSTRAINT `venta_productos_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
