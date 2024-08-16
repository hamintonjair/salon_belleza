-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-08-2024 a las 15:30:50
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
  `idUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `agenda`
--

INSERT INTO `agenda` (`id`, `nombre`, `apellidos`, `cedula`, `telefono`, `servicio`, `precio`, `pago_empleado`, `date`, `time`, `estado`, `trabajador_id`, `idUsuario`) VALUES
(1, 'Gabriel', 'Perea', 35989203, 4444444, 'Uñas', 45000.00, 15000.00, '2024-08-15', '09:25:00', 'Anulado', NULL, 1),
(2, 'Camila', 'Ortiz', 12345, 5555555, 'Pestañas', 12000.00, 4000.00, '2024-08-15', '10:25:00', 'Finalizado', 2, 1),
(3, 'Diana', 'Gamboa', 22313, 2147483647, 'Cepillado', 25000.00, 10000.00, '2024-08-15', '10:25:00', 'Finalizado', 1, 1),
(4, 'Camila', 'Ortiz', 12345, 5555555, 'Maquillaje', 30000.00, 12000.00, '2024-08-15', '10:41:00', 'Finalizado', 4, 2),
(5, 'Diana', 'Gamboa', 22313, 2147483647, 'Cejas semi permanentes', 250000.00, 10000.00, '2024-08-16', '09:27:00', 'Finalizado', 1, 2),
(6, 'Camila', 'Ortiz', 12345, 5555555, 'Uñas', 45000.00, 15000.00, '2024-08-16', '08:27:00', 'Finalizado', 3, 2),
(7, 'Camila', 'Ortiz', 12345, 5555555, 'Alisado', 30000.00, 13000.00, '2024-08-16', '08:05:00', 'Anulado', NULL, 2);

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
(4, 'GENERICO', 'GENERICO', 999999999, 5555555, 'Quibdó Chocó', 1);

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
(84, 1, 1),
(85, 1, 2),
(86, 1, 3),
(87, 1, 4),
(88, 1, 5),
(89, 1, 6),
(90, 1, 7),
(91, 1, 8),
(92, 1, 9),
(93, 1, 10),
(94, 1, 11),
(95, 1, 12),
(96, 1, 13),
(103, 2, 2),
(104, 2, 5),
(105, 2, 6),
(106, 2, 8),
(107, 2, 9),
(108, 2, 11),
(109, 2, 12),
(110, 2, 13);

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
  `idUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos_empleados`
--

INSERT INTO `pagos_empleados` (`id`, `empleado_id`, `nombre`, `apellidos`, `cedula`, `pago`, `fecha_pago`, `estado`, `idUsuario`) VALUES
(1, 4, 'Paula', 'Hinestroza', '1077345672', 25000.00, '2024-08-15', 'pagado', 2),
(2, 1, 'Sandra Paola', 'Córdoba Mena', '22222', 25000.00, '2024-08-15', 'pagado', 1),
(3, 2, 'Leidy', 'Asprilla', '12', 17000.00, '2024-08-15', 'pagado', 1),
(4, 3, 'Carmensa', 'Perea', '13424', 15000.00, '2024-08-16', 'pagado', 2),
(5, 1, 'Sandra Paola', 'Córdoba Mena', '22222', 23000.00, '2024-08-16', 'pagado', 2);

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
(3, 'Administracion'),
(4, 'Reportes'),
(5, 'Servicios'),
(6, 'Turnos'),
(7, 'Empresa'),
(8, 'Productos'),
(9, 'Facturas'),
(10, 'Empleados'),
(11, 'Pagos'),
(12, 'Ventas'),
(13, 'Ventas Realizadas');

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
(1, 'Canecanol', '25', 5500.00, 8000.00, 1),
(2, 'Gaseosa personal', '25', 2000.00, 2500.00, 1),
(3, 'cerveza', '25', 2500.00, 3000.00, 1);

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
  `idUsuario` int(11) NOT NULL,
  `fecha_venta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turno_productos`
--

INSERT INTO `turno_productos` (`id`, `turno_id`, `nombre_producto`, `cantidad`, `precio_unitario`, `subtotal`, `idUsuario`, `fecha_venta`) VALUES
(1, 3, 'cerveza', 1, 3000.00, 3000.00, 1, '2024-08-15 13:33:09'),
(2, 2, 'Canecanol', 1, 8000.00, 8000.00, 1, '2024-08-15 13:33:50'),
(3, 4, 'cerveza', 1, 3000.00, 3000.00, 2, '2024-08-15 14:37:15'),
(4, 5, 'cerveza', 1, 3000.00, 3000.00, 2, '2024-08-16 12:28:18');

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
  `idUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turno_servicios`
--

INSERT INTO `turno_servicios` (`id`, `turno_id`, `nombre_servicio`, `precio_servicio`, `pago_empleado`, `trabajador_id`, `fecha_servicio`, `idUsuario`) VALUES
(1, 3, 'Uñas', 45000.00, 15000.00, 1, '2024-08-15', 1),
(2, 2, 'Peinado en trenzas', 35000.00, 13000.00, 2, '2024-08-15', 1),
(3, 4, 'Alisado', 30000.00, 13000.00, 4, '2024-08-15', 2),
(4, 5, 'Alisado', 30000.00, 13000.00, 1, '2024-08-16', 2);

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
(1, 'Haminton', 'Mena Mena', 2345234, '3124942527', 'Barrio buenos aires', 'hamintonjair@gmail.com', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 'Administrador', 1),
(2, 'Anny', 'Gamboa', 1234, '3132435654', 'Barrio buenos aires', 'operador@gmail.com', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 'Operador', 1);

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
(9, 3, 12500.00, 1),
(10, 4, 40100.00, 2);

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
(1, 9, 'Gaseosa personal', 2500.00, 5, 0.00, 12500.00, '2024-08-15 15:55:24'),
(2, 10, 'cerveza', 3000.00, 3, 900.00, 8100.00, '2024-08-16 12:45:11'),
(3, 10, 'Canecanol', 8000.00, 4, 0.00, 32000.00, '2024-08-16 12:45:11');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalle_permisos`
--
ALTER TABLE `detalle_permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

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
-- AUTO_INCREMENT de la tabla `pagos_empleados`
--
ALTER TABLE `pagos_empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `turno_servicios`
--
ALTER TABLE `turno_servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `venta_productos`
--
ALTER TABLE `venta_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
