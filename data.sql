--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.10
-- Dumped by pg_dump version 9.6.10

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: _agenda; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._agenda (
    id smallint,
    nombre character varying(8) DEFAULT NULL::character varying,
    apellidos character varying(8) DEFAULT NULL::character varying,
    cedula integer,
    telefono integer,
    servicio character varying(18) DEFAULT NULL::character varying,
    precio numeric(7,2) DEFAULT NULL::numeric,
    pago_empleado numeric(6,1) DEFAULT NULL::numeric,
    date character varying(10) DEFAULT NULL::character varying,
    "time" character varying(8) DEFAULT NULL::character varying,
    estado character varying(10) DEFAULT NULL::character varying,
    trabajador_id character varying(1) DEFAULT NULL::character varying,
    "idUsuario" smallint,
    estado_pago character varying(9) DEFAULT NULL::character varying
);


ALTER TABLE public._agenda OWNER TO rebasedata;

--
-- Name: _clientes; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._clientes (
    id smallint,
    nombre character varying(8) DEFAULT NULL::character varying,
    apellidos character varying(8) DEFAULT NULL::character varying,
    cedula integer,
    telefono bigint,
    direccion character varying(19) DEFAULT NULL::character varying,
    estado smallint
);


ALTER TABLE public._clientes OWNER TO rebasedata;

--
-- Name: _detalle_permisos; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._detalle_permisos (
    id smallint,
    id_usuarios smallint,
    id_permisos smallint
);


ALTER TABLE public._detalle_permisos OWNER TO rebasedata;

--
-- Name: _egresos; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._egresos (
    id smallint,
    concepto character varying(42) DEFAULT NULL::character varying,
    tipo character varying(6) DEFAULT NULL::character varying,
    monto numeric(7,2) DEFAULT NULL::numeric,
    fecha character varying(19) DEFAULT NULL::character varying,
    tipo_egreso character varying(13) DEFAULT NULL::character varying,
    empleado_id character varying(1) DEFAULT NULL::character varying,
    estado character varying(9) DEFAULT NULL::character varying
);


ALTER TABLE public._egresos OWNER TO rebasedata;

--
-- Name: _empleado; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._empleado (
    id smallint,
    nombre character varying(12) DEFAULT NULL::character varying,
    apellidos character varying(12) DEFAULT NULL::character varying,
    cedula bigint,
    telefono integer,
    direccion character varying(14) DEFAULT NULL::character varying,
    estado smallint
);


ALTER TABLE public._empleado OWNER TO rebasedata;

--
-- Name: _empresa; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._empresa (
    id smallint,
    nombre character varying(28) DEFAULT NULL::character varying,
    nit character varying(10) DEFAULT NULL::character varying,
    direccion character varying(38) DEFAULT NULL::character varying,
    telefono bigint,
    email character varying(16) DEFAULT NULL::character varying,
    ciudad character varying(23) DEFAULT NULL::character varying
);


ALTER TABLE public._empresa OWNER TO rebasedata;

--
-- Name: _migrations; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._migrations (
    id smallint,
    version character varying(17) DEFAULT NULL::character varying,
    class character varying(45) DEFAULT NULL::character varying,
    "group" character varying(7) DEFAULT NULL::character varying,
    namespace character varying(3) DEFAULT NULL::character varying,
    "time" bigint,
    batch smallint
);


ALTER TABLE public._migrations OWNER TO rebasedata;

--
-- Name: _movimientos_caja; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._movimientos_caja (
    id smallint,
    fecha character varying(19) DEFAULT NULL::character varying,
    tipo character varying(7) DEFAULT NULL::character varying,
    monto numeric(7,2) DEFAULT NULL::numeric,
    descripcion character varying(60) DEFAULT NULL::character varying
);


ALTER TABLE public._movimientos_caja OWNER TO rebasedata;

--
-- Name: _pagos_empleados; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._pagos_empleados (
    id smallint,
    empleado_id smallint,
    nombre character varying(12) DEFAULT NULL::character varying,
    apellidos character varying(12) DEFAULT NULL::character varying,
    cedula integer,
    pago numeric(7,2) DEFAULT NULL::numeric,
    fecha_pago character varying(10) DEFAULT NULL::character varying,
    estado character varying(6) DEFAULT NULL::character varying,
    "idUsuario" smallint
);


ALTER TABLE public._pagos_empleados OWNER TO rebasedata;

--
-- Name: _permisos; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._permisos (
    id smallint,
    permiso character varying(17) DEFAULT NULL::character varying
);


ALTER TABLE public._permisos OWNER TO rebasedata;

--
-- Name: _productos; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._productos (
    id smallint,
    nombre character varying(16) DEFAULT NULL::character varying,
    cantidad smallint,
    v_compra numeric(5,1) DEFAULT NULL::numeric,
    v_venta numeric(5,1) DEFAULT NULL::numeric,
    estado smallint
);


ALTER TABLE public._productos OWNER TO rebasedata;

--
-- Name: _servicios; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._servicios (
    id smallint,
    nombre character varying(22) DEFAULT NULL::character varying,
    precio numeric(7,1) DEFAULT NULL::numeric,
    pago_empleado numeric(7,2) DEFAULT NULL::numeric,
    estado smallint
);


ALTER TABLE public._servicios OWNER TO rebasedata;

--
-- Name: _turno_productos; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._turno_productos (
    id smallint,
    turno_id smallint,
    nombre_producto character varying(16) DEFAULT NULL::character varying,
    cantidad smallint,
    precio_unitario numeric(6,2) DEFAULT NULL::numeric,
    subtotal numeric(6,2) DEFAULT NULL::numeric,
    "idUsuario" smallint,
    fecha_venta character varying(19) DEFAULT NULL::character varying
);


ALTER TABLE public._turno_productos OWNER TO rebasedata;

--
-- Name: _turno_servicios; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._turno_servicios (
    id smallint,
    turno_id smallint,
    nombre_servicio character varying(10) DEFAULT NULL::character varying,
    precio_servicio numeric(7,2) DEFAULT NULL::numeric,
    pago_empleado numeric(6,1) DEFAULT NULL::numeric,
    trabajador_id smallint,
    fecha_servicio character varying(10) DEFAULT NULL::character varying,
    "idUsuario" smallint,
    estado_pago character varying(6) DEFAULT NULL::character varying
);


ALTER TABLE public._turno_servicios OWNER TO rebasedata;

--
-- Name: _usuarios; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._usuarios (
    id smallint,
    nombre character varying(8) DEFAULT NULL::character varying,
    apellidos character varying(13) DEFAULT NULL::character varying,
    cedula integer,
    telefono bigint,
    direccion character varying(19) DEFAULT NULL::character varying,
    correo character varying(22) DEFAULT NULL::character varying,
    clave character varying(64) DEFAULT NULL::character varying,
    rol character varying(13) DEFAULT NULL::character varying,
    estado smallint
);


ALTER TABLE public._usuarios OWNER TO rebasedata;

--
-- Name: _venta_productos; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._venta_productos (
    id smallint,
    venta_id smallint,
    producto_nombre character varying(16) DEFAULT NULL::character varying,
    precio_unitario numeric(6,2) DEFAULT NULL::numeric,
    cantidad smallint,
    descuento numeric(3,2) DEFAULT NULL::numeric,
    valor_total numeric(6,2) DEFAULT NULL::numeric,
    fecha_venta character varying(19) DEFAULT NULL::character varying
);


ALTER TABLE public._venta_productos OWNER TO rebasedata;

--
-- Name: _ventas; Type: TABLE; Schema: public; Owner: rebasedata
--

CREATE TABLE public._ventas (
    id smallint,
    cliente_id smallint,
    total numeric(6,2) DEFAULT NULL::numeric,
    usuario_id smallint
);


ALTER TABLE public._ventas OWNER TO rebasedata;

--
-- Data for Name: _agenda; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._agenda (id, nombre, apellidos, cedula, telefono, servicio, precio, pago_empleado, date, "time", estado, trabajador_id, "idUsuario", estado_pago) FROM stdin;
1	GENERICO	GENERICO	999999999	5555555	Cepillado	25000.00	10000.0	2025-04-17	18:51:00	Finalizado	2	1	pagado
2	GENERICO	GENERICO	999999999	5555555	Peinado en trenzas	35000.00	13000.0	2025-04-18	14:33:00	Finalizado	3	1	pagado
3	GENERICO	GENERICO	999999999	5555555	Pestañas	12000.00	4000.0	2025-04-18	18:36:00	Anulado		1	pendiente
4	GENERICO	GENERICO	999999999	5555555	cejas	15000.00	5000.0	2025-04-18	18:36:00	Finalizado	1	1	pagado
5	GENERICO	GENERICO	999999999	5555555	Peinado en trenzas	35000.00	13000.0	2025-04-19	08:24:00	Finalizado	4	1	pendiente
8	GENERICO	GENERICO	999999999	5555555	Peluquería	8000.00	3000.0	2025-04-19	08:29:00	Finalizado	3	1	pendiente
\.


--
-- Data for Name: _clientes; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._clientes (id, nombre, apellidos, cedula, telefono, direccion, estado) FROM stdin;
1	Diana	Gamboa	22313	2147483647	Barrio los Angeles	1
3	Camila	Ortiz	12345	5555555	Barrio Buenos Aires	1
4	GENERICO	GENERICO	999999999	5555555	Quibdó Chocó	1
6	Karol	mena	123456	2147483647	buenos aires	1
\.


--
-- Data for Name: _detalle_permisos; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._detalle_permisos (id, id_usuarios, id_permisos) FROM stdin;
103	2	2
104	2	5
105	2	6
106	2	8
107	2	9
108	2	11
109	2	12
110	2	13
111	1	1
112	1	2
113	1	3
114	1	4
115	1	5
116	1	6
117	1	7
118	1	8
119	1	9
120	1	10
121	1	11
122	1	12
123	1	13
124	1	14
125	4	1
126	4	2
127	4	3
128	4	4
129	4	5
130	4	6
131	4	7
132	4	8
133	4	9
134	4	10
135	4	11
136	4	12
137	4	13
138	4	14
\.


--
-- Data for Name: _egresos; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._egresos (id, concepto, tipo, monto, fecha, tipo_egreso, empleado_id, estado) FROM stdin;
1	compra de basura	Retiro	10000.00	2025-04-18 00:00:00	general		pendiente
2	compra de basura	Retiro	10000.00	2025-04-18 00:00:00	general		pendiente
3	compra paga de agua	Retiro	5000.00	2025-04-18 00:00:00	general		pendiente
4	compra paga de agua	Retiro	5000.00	2025-04-18 00:00:00	general		pendiente
5	prueba	Retiro	2000.00	2025-04-18 00:00:00	general		pendiente
6	prestamo		0.00	2025-04-18 00:00:00	prestamo	2	saldado
7	prestamo		0.00	2025-04-18 00:00:00	prestamo	2	saldado
14	Pago a empleado: Carmensa Perea		28000.00	2025-04-18 00:00:00	Pago Empleado	3	pendiente
36	Pago a empleado: Leidy Asprilla		19000.00	2025-04-18 00:00:00	Pago Empleado	2	pendiente
37	prestamo		0.00	2025-04-18 00:00:00	prestamo	1	saldado
38	Pago a empleado: Sandra Paola Córdoba Mena		3000.00	2025-04-18 00:00:00	Pago Empleado	1	pendiente
\.


--
-- Data for Name: _empleado; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._empleado (id, nombre, apellidos, cedula, telefono, direccion, estado) FROM stdin;
1	Sandra Paola	Córdoba Mena	22222	5555555	Barrio kennedy	1
2	Leidy	Asprilla	12	66666666	Barrio obapo	1
3	Carmensa	Perea	13424	6666666	Centro	1
4	Paula	Hinestroza	1077345672	7777777	Centro	1
\.


--
-- Data for Name: _empresa; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._empresa (id, nombre, nit, direccion, telefono, email, ciudad) FROM stdin;
1	Beaunty Timesless - NAIL SPA	99999999-1	Carrera 12 #46-136 barrio Buenos Aires	3155555555	prueba@gmail.com	Quibdo  Choco  Colombía
\.


--
-- Data for Name: _migrations; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._migrations (id, version, class, "group", namespace, "time", batch) FROM stdin;
1	2025-04-17-151042	App\\Database\\Migrations\\CreateMovimientosCaja	default	App	1744920820	1
\.


--
-- Data for Name: _movimientos_caja; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._movimientos_caja (id, fecha, tipo, monto, descripcion) FROM stdin;
1	2025-04-17 18:53:10	ingreso	35000.00	Ingreso por finalización de turno #1 (Servicios y productos)
2	2025-04-18 00:08:09	ingreso	3000.00	Venta ID 1
3	2025-04-18 01:12:33	egreso	10000.00	compra de basura
4	2025-04-18 01:12:33	egreso	10000.00	compra de basura
5	2025-04-18 01:14:00	egreso	5000.00	compra paga de agua
6	2025-04-18 01:14:00	egreso	5000.00	compra paga de agua
7	2025-04-18 01:20:58	egreso	2000.00	prueba
8	2025-04-18 19:29:36	ingreso	9000.00	Venta ID 2
9	2025-04-18 14:36:22	ingreso	55000.00	Ingreso por finalización de turno #2 (Servicios y productos)
10	2025-04-18 00:00:00		28000.00	Pago a empleado: Carmensa Perea
32	2025-04-18 00:00:00		19000.00	Pago a empleado: Leidy Asprilla
33	2025-04-18 18:48:20	ingreso	17500.00	Ingreso por finalización de turno #4 (Servicios y productos)
34	2025-04-18 00:00:00		3000.00	Pago a empleado: Sandra Paola Córdoba Mena
35	2025-04-19 01:34:33	ingreso	5000.00	Venta ID 3
36	2025-04-19 13:24:06	ingreso	5000.00	Venta ID 4
37	2025-04-19 08:25:09	ingreso	24500.00	Ingreso por finalización de turno #5 (Servicios y productos)
38	2025-04-19 08:30:45	ingreso	7500.00	Ingreso por finalización de turno #8 (Servicios y productos)
\.


--
-- Data for Name: _pagos_empleados; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._pagos_empleados (id, empleado_id, nombre, apellidos, cedula, pago, fecha_pago, estado, "idUsuario") FROM stdin;
5	3	Carmensa	Perea	13424	28000.00	2025-04-18	pagado	1
27	2	Leidy	Asprilla	12	19000.00	2025-04-18	pagado	1
28	1	Sandra Paola	Córdoba Mena	22222	3000.00	2025-04-18	pagado	1
\.


--
-- Data for Name: _permisos; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._permisos (id, permiso) FROM stdin;
1	Usuarios
2	Clientes
3	Egresos
4	Reportes
5	Servicios
6	Turnos
7	Empresa
8	Productos
9	Facturas
10	Empleados
11	Pagos
12	Ventas
13	Ventas Realizadas
14	Ingresos
\.


--
-- Data for Name: _productos; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._productos (id, nombre, cantidad, v_compra, v_venta, estado) FROM stdin;
1	Canecanol	15	5500.0	8000.0	1
2	Gaseosa personal	15	2000.0	2500.0	1
3	cerveza	10	2500.0	3000.0	1
\.


--
-- Data for Name: _servicios; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._servicios (id, nombre, precio, pago_empleado, estado) FROM stdin;
1	Cepillado	25000.0	10000.00	1
2	Alisado	30000.0	13000.00	1
3	Uñas	45000.0	15000.00	1
4	Peinado en trenzas	35000.0	13000.00	1
5	Cejas semi permanentes	250000.0	10000.00	1
6	cejas	15000.0	5000.00	1
7	Pestañas	12000.0	4000.00	1
8	Maquillaje	30000.0	12000.00	1
10	Peluquería	8000.0	3000.00	1
\.


--
-- Data for Name: _turno_productos; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._turno_productos (id, turno_id, nombre_producto, cantidad, precio_unitario, subtotal, "idUsuario", fecha_venta) FROM stdin;
1	1	cerveza	1	3000.00	3000.00	1	2025-04-18 01:53:03
2	2	cerveza	1	3000.00	3000.00	1	2025-04-18 21:35:34
3	4	Gaseosa personal	1	2500.00	2500.00	1	2025-04-19 01:37:44
4	5	Gaseosa personal	1	2500.00	2500.00	1	2025-04-19 15:25:00
5	8	Gaseosa personal	1	2500.00	2500.00	1	2025-04-19 15:30:19
\.


--
-- Data for Name: _turno_servicios; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._turno_servicios (id, turno_id, nombre_servicio, precio_servicio, pago_empleado, trabajador_id, fecha_servicio, "idUsuario", estado_pago) FROM stdin;
1	1	Alisado	30000.00	13000.0	2	2025-04-17	1	pagado
2	2	Uñas	45000.00	15000.0	3	2025-04-18	1	pagado
3	4	Peluquería	8000.00	3000.0	1	2025-04-18	1	pagado
\.


--
-- Data for Name: _usuarios; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._usuarios (id, nombre, apellidos, cedula, telefono, direccion, correo, clave, rol, estado) FROM stdin;
1	Haminton	Mena Mena	2345234	3124942527	Barrio buenos aires	hamintonjair@gmail.com	1cb8e186302e7bf3df367cc99060b263be8b9dd565e6e24db4dd7e8ae153e524	Administrador	1
2	Anny	Gamboa	1234	3132435654	Barrio buenos aires	operador@gmail.com	8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92	Operador	1
4	Admin	Administrador	99999999	5555555	centro	admin@gmail.com	240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9	Administrador	1
\.


--
-- Data for Name: _venta_productos; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._venta_productos (id, venta_id, producto_nombre, precio_unitario, cantidad, descuento, valor_total, fecha_venta) FROM stdin;
1	1	cerveza	3000.00	1	0.00	3000.00	2025-04-18 02:08:09
2	2	cerveza	3000.00	3	0.00	9000.00	2025-04-18 21:29:36
3	3	Gaseosa personal	2500.00	2	0.00	5000.00	2025-04-19 03:34:33
4	4	Gaseosa personal	2500.00	2	0.00	5000.00	2025-04-19 15:24:06
\.


--
-- Data for Name: _ventas; Type: TABLE DATA; Schema: public; Owner: rebasedata
--

COPY public._ventas (id, cliente_id, total, usuario_id) FROM stdin;
1	4	3000.00	1
2	4	9000.00	1
3	4	5000.00	2
4	4	5000.00	1
\.


--
-- PostgreSQL database dump complete
--

