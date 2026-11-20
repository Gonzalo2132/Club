-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-11-2026 a las 21:49:07
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `club`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE `cajas` (
  `id` int(11) NOT NULL,
  `denominacion` varchar(120) NOT NULL,
  `descripcion` varchar(80) NOT NULL,
  `codigointerno` varchar(50) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_alta` datetime DEFAULT current_timestamp(),
  `fecha_edicion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_borrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cajas`
--

INSERT INTO `cajas` (`id`, `denominacion`, `descripcion`, `codigointerno`, `activo`, `fecha_alta`, `fecha_edicion`, `fecha_borrado`) VALUES
(15, 'Caja Central', 'Administración central', 'DEMO-CAJA-01', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL),
(16, 'Caja Norte', 'Sede de zona norte', 'DEMO-CAJA-02', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL),
(17, 'Caja Sur', 'Sede de zona sur', 'DEMO-CAJA-03', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL),
(18, 'Caja Deportiva', 'Actividades deportivas', 'DEMO-CAJA-04', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL),
(19, 'Gonzalo Arreseygor', 'Caja personal del recaudador', 'REC- Gonzalo Arreseygor', 1, '2026-11-20 17:31:30', '2026-11-20 17:32:03', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disciplinas`
--

CREATE TABLE `disciplinas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_edicion` datetime DEFAULT NULL,
  `fecha_borrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `disciplinas`
--

INSERT INTO `disciplinas` (`id`, `nombre`, `descripcion`, `activo`, `fecha_alta`, `fecha_edicion`, `fecha_borrado`) VALUES
(12, 'Fútbol', 'Actividad de demostración.', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL),
(13, 'Básquet', 'Actividad de demostración.', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL),
(14, 'Vóley', 'Actividad de demostración.', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos_caja`
--

CREATE TABLE `gastos_caja` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_caja` int(10) UNSIGNED NOT NULL,
  `categoria` varchar(40) NOT NULL,
  `concepto` varchar(255) NOT NULL,
  `destinatario` varchar(120) DEFAULT NULL,
  `monto` decimal(12,2) NOT NULL,
  `fecha_gasto` date NOT NULL,
  `comprobante` varchar(255) DEFAULT NULL,
  `registrado_por` int(10) UNSIGNED NOT NULL,
  `fecha_alta` datetime NOT NULL,
  `anulado_por` int(10) UNSIGNED DEFAULT NULL,
  `fecha_anulacion` datetime DEFAULT NULL,
  `motivo_anulacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `gastos_caja`
--

INSERT INTO `gastos_caja` (`id`, `id_caja`, `categoria`, `concepto`, `destinatario`, `monto`, `fecha_gasto`, `comprobante`, `registrado_por`, `fecha_alta`, `anulado_por`, `fecha_anulacion`, `motivo_anulacion`) VALUES
(12, 15, 'servicios', 'Servicio de electricidad', 'Cooperativa local', 7500.00, '2026-11-19', 'demo-recibo-8FEE66EBBD.pdf', 15, '2026-11-20 00:28:30', NULL, NULL, NULL),
(13, 18, 'materiales', 'Elementos para entrenamiento', 'Comercial Demo', 8400.00, '2026-11-18', NULL, 15, '2026-11-20 00:28:30', NULL, NULL, NULL),
(14, 16, 'limpieza', 'Insumos de limpieza', 'Proveeduría de muestra', 9300.00, '2026-11-17', NULL, 15, '2026-11-20 00:28:30', NULL, NULL, NULL),
(15, 17, 'reparaciones', 'Reparación de vestuario', 'Taller de ejemplo', 10200.00, '2026-11-16', NULL, 15, '2026-11-20 00:28:30', NULL, NULL, NULL),
(16, 18, 'transporte', 'Traslado de divisiones formativas', 'Transporte Demo', 11100.00, '2026-11-15', NULL, 15, '2026-11-20 00:28:30', 14, '2026-11-20 00:28:30', 'Registro ficticio anulado para mostrar el historial.'),
(17, 15, 'otros', 'Compra duplicada anulada', 'Proveedor de prueba', 12000.00, '2026-11-14', NULL, 15, '2026-11-20 00:28:30', 14, '2026-11-20 00:28:30', 'Registro ficticio anulado para mostrar el historial.'),
(18, 15, 'servicios', 'Servicio de internet', 'Conectividad Demo', 12900.00, '2026-11-13', NULL, 15, '2026-11-20 00:28:30', NULL, NULL, NULL),
(19, 17, 'materiales', 'Material para mantenimiento', 'Ferretería de muestra', 13800.00, '2026-11-12', NULL, 15, '2026-11-20 00:28:30', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `liquidaciones`
--

CREATE TABLE `liquidaciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `monto` double NOT NULL,
  `fecha_vencimiento` date DEFAULT curdate(),
  `activo` tinyint(1) DEFAULT 1,
  `fecha_alta` datetime DEFAULT current_timestamp(),
  `fecha_edicion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_borrado` datetime DEFAULT NULL,
  `id_plan` int(11) DEFAULT NULL,
  `nombre_plan` varchar(255) DEFAULT 'Sin plan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `liquidaciones`
--

INSERT INTO `liquidaciones` (`id`, `nombre`, `monto`, `fecha_vencimiento`, `activo`, `fecha_alta`, `fecha_edicion`, `fecha_borrado`, `id_plan`, `nombre_plan`) VALUES
(39, 'Vencida - Cuota social 11/2026', 12000, '2026-11-05', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 13, 'Cuota social'),
(40, 'Próxima - Cuota social 11/2026', 12000, '2026-11-25', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 13, 'Cuota social'),
(41, 'Futura - Cuota social 12/2026', 12000, '2026-12-12', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 13, 'Cuota social'),
(42, 'Vencida - Fútbol 11/2026', 18500, '2026-11-05', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 14, 'Fútbol'),
(43, 'Próxima - Fútbol 11/2026', 18500, '2026-11-25', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 14, 'Fútbol'),
(44, 'Futura - Fútbol 12/2026', 18500, '2026-12-12', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 14, 'Fútbol'),
(45, 'Vencida - Básquet 11/2026', 16500, '2026-11-05', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 15, 'Básquet'),
(46, 'Próxima - Básquet 11/2026', 16500, '2026-11-25', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 15, 'Básquet'),
(47, 'Futura - Básquet 12/2026', 16500, '2026-12-12', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 15, 'Básquet'),
(48, 'Vencida - Vóley 11/2026', 15000, '2026-11-05', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 16, 'Vóley'),
(49, 'Próxima - Vóley 11/2026', 15000, '2026-11-25', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 16, 'Vóley'),
(50, 'Futura - Vóley 12/2026', 15000, '2026-12-12', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 16, 'Vóley');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `liquidaciones_planes`
--

CREATE TABLE `liquidaciones_planes` (
  `id_liquidacion` int(11) NOT NULL,
  `id_plan` int(11) NOT NULL,
  `monto` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `liquidaciones_planes`
--

INSERT INTO `liquidaciones_planes` (`id_liquidacion`, `id_plan`, `monto`) VALUES
(39, 13, 12000.00),
(40, 13, 12000.00),
(41, 13, 12000.00),
(42, 14, 18500.00),
(43, 14, 18500.00),
(44, 14, 18500.00),
(45, 15, 16500.00),
(46, 15, 16500.00),
(47, 15, 16500.00),
(48, 16, 15000.00),
(49, 16, 15000.00),
(50, 16, 15000.00);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '20260914190000', 'App\\Database\\Migrations\\NormalizarFechasPagos', 'default', 'App', 1791240199, 1),
(2, '20260914190100', 'App\\Database\\Migrations\\AgregarDestinoMovimientos', 'default', 'App', 1791240199, 1),
(3, '20260928210000', 'App\\Database\\Migrations\\CrearLiquidacionesPlanes', 'default', 'App', 1791240199, 1),
(4, '20260928220000', 'App\\Database\\Migrations\\AgregarMontoPlanes', 'default', 'App', 1791240199, 1),
(5, '20260928230000', 'App\\Database\\Migrations\\AgregarMontoLiquidacionPlan', 'default', 'App', 1791240199, 1),
(6, '20260928235900', 'App\\Database\\Migrations\\QuitarMontoPlanes', 'default', 'App', 1791240199, 1),
(7, '20260928250000', 'App\\Database\\Migrations\\AumentarNombreLiquidacion', 'default', 'App', 1791240199, 1),
(8, '20261005193000', 'App\\Database\\Migrations\\SoftDeleteSociosPlanes', 'default', 'App', 1791240199, 1),
(9, '20261006100000', 'App\\Database\\Migrations\\CrearControlAcceso', 'default', 'App', 1791240199, 1),
(10, '20261019190000', 'App\\Database\\Migrations\\CrearDisciplinasYTutores', 'default', 'App', 1792448684, 2),
(11, '20261019193000', 'App\\Database\\Migrations\\PermitirMultiplesTutores', 'default', 'App', 1792449833, 3),
(12, '20261023190000', 'App\\Database\\Migrations\\CrearPermisosPorAccion', 'default', 'App', 1792792169, 4),
(13, '20261026193000', 'App\\Database\\Migrations\\AsignarRecaudadorASocioYPago', 'default', 'App', 1793057375, 5),
(14, '20261026203500', 'App\\Database\\Migrations\\AmpliarNombreCaja', 'default', 'App', 1793057723, 6),
(15, '20261026210000', 'App\\Database\\Migrations\\CrearRegistroGastosCaja', 'default', 'App', 1793059313, 7),
(16, '20261120001000', 'App\\Database\\Migrations\\CrearAuditoriaFinanciera', 'default', 'App', 1795144369, 8),
(17, '20261120100000', 'App\\Database\\Migrations\\EliminarAuditoriaFinanciera', 'default', 'App', 1795201855, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_caja`
--

CREATE TABLE `movimientos_caja` (
  `id` int(11) UNSIGNED NOT NULL,
  `caja_origen_id` int(11) UNSIGNED DEFAULT NULL,
  `caja_destino_id` int(11) UNSIGNED DEFAULT NULL,
  `tipo_movimiento` enum('ingreso','egreso','transferencia') NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `concepto` varchar(255) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos_caja`
--

INSERT INTO `movimientos_caja` (`id`, `caja_origen_id`, `caja_destino_id`, `tipo_movimiento`, `monto`, `concepto`, `fecha`) VALUES
(193, NULL, 15, 'ingreso', 6000.00, 'Rojas Joaquín -> Caja Central | Pago: Vencida - Cuota social 11/2026 - 6000.00 | Socio: Rojas Joaquín | Forma: Débito', '2026-11-03 18:39:00'),
(194, NULL, 15, 'ingreso', 6000.00, 'Rojas Joaquín -> Caja Central | Pago: Vencida - Cuota social 11/2026 - 6000.00 | Socio: Rojas Joaquín | Forma: Efectivo', '2026-11-04 14:16:00'),
(195, NULL, 15, 'ingreso', 8280.00, 'Rojas Joaquín -> Caja Central | Pago: Próxima - Cuota social 11/2026 - 8280.00 | Socio: Rojas Joaquín | Forma: Efectivo', '2026-11-20 13:22:00'),
(196, NULL, 15, 'ingreso', 9250.00, 'Rojas Joaquín -> Caja Central | Pago: Vencida - Fútbol 11/2026 - 9250.00 | Socio: Rojas Joaquín | Forma: Transferencia', '2026-11-03 15:11:00'),
(197, NULL, 15, 'ingreso', 9250.00, 'Rojas Joaquín -> Caja Central | Pago: Vencida - Fútbol 11/2026 - 9250.00 | Socio: Rojas Joaquín | Forma: Efectivo', '2026-11-04 12:59:00'),
(198, NULL, 15, 'ingreso', 12580.00, 'Rojas Joaquín -> Caja Central | Pago: Próxima - Fútbol 11/2026 - 12580.00 | Socio: Rojas Joaquín | Forma: Transferencia', '2026-11-20 15:31:00'),
(199, NULL, 15, 'ingreso', 8250.00, 'Rojas Joaquín -> Caja Central | Pago: Vencida - Básquet 11/2026 - 8250.00 | Socio: Rojas Joaquín | Forma: Transferencia', '2026-11-03 17:20:00'),
(200, NULL, 15, 'ingreso', 8250.00, 'Rojas Joaquín -> Caja Central | Pago: Vencida - Básquet 11/2026 - 8250.00 | Socio: Rojas Joaquín | Forma: Transferencia', '2026-11-04 14:54:00'),
(201, NULL, 15, 'ingreso', 9405.00, 'Rojas Joaquín -> Caja Central | Pago: Próxima - Básquet 11/2026 - 9405.00 | Socio: Rojas Joaquín | Forma: Transferencia', '2026-11-20 16:07:00'),
(202, NULL, 16, 'ingreso', 6840.00, 'Torres Thiago -> Caja Norte | Pago: Vencida - Cuota social 11/2026 - 6840.00 | Socio: Torres Thiago | Forma: Débito', '2026-11-01 13:45:00'),
(203, NULL, 16, 'ingreso', 12000.00, 'Torres Thiago -> Caja Norte | Pago: Futura - Cuota social 12/2026 - 12000.00 | Socio: Torres Thiago | Forma: Débito', '2026-11-20 17:28:00'),
(204, NULL, 16, 'ingreso', 6765.00, 'Torres Thiago -> Caja Norte | Pago: Vencida - Básquet 11/2026 - 6765.00 | Socio: Torres Thiago | Forma: Débito', '2026-11-02 17:43:00'),
(205, NULL, 16, 'ingreso', 16500.00, 'Torres Thiago -> Caja Norte | Pago: Futura - Básquet 12/2026 - 16500.00 | Socio: Torres Thiago | Forma: Efectivo', '2026-11-20 09:02:00'),
(206, NULL, 17, 'ingreso', 12000.00, 'Martínez Bruno -> Caja Sur | Pago: Próxima - Cuota social 11/2026 - 12000.00 | Socio: Martínez Bruno | Forma: Débito', '2026-11-20 17:35:00'),
(207, NULL, 17, 'ingreso', 12000.00, 'Martínez Bruno -> Caja Sur | Pago: Futura - Cuota social 12/2026 - 12000.00 | Socio: Martínez Bruno | Forma: Transferencia', '2026-11-20 12:04:00'),
(208, NULL, 17, 'ingreso', 18500.00, 'Martínez Bruno -> Caja Sur | Pago: Próxima - Fútbol 11/2026 - 18500.00 | Socio: Martínez Bruno | Forma: Transferencia', '2026-11-20 12:34:00'),
(209, NULL, 17, 'ingreso', 18500.00, 'Martínez Bruno -> Caja Sur | Pago: Futura - Fútbol 12/2026 - 18500.00 | Socio: Martínez Bruno | Forma: Transferencia', '2026-11-20 12:52:00'),
(210, NULL, 18, 'ingreso', 6000.00, 'Gómez Camila -> Caja Deportiva | Pago: Vencida - Cuota social 11/2026 - 6000.00 | Socio: Gómez Camila | Forma: Transferencia', '2026-11-05 09:37:00'),
(211, NULL, 18, 'ingreso', 6000.00, 'Gómez Camila -> Caja Deportiva | Pago: Vencida - Cuota social 11/2026 - 6000.00 | Socio: Gómez Camila | Forma: Débito', '2026-11-02 12:34:00'),
(212, NULL, 18, 'ingreso', 6000.00, 'Gómez Camila -> Caja Deportiva | Pago: Próxima - Cuota social 11/2026 - 6000.00 | Socio: Gómez Camila | Forma: Efectivo', '2026-11-20 11:31:00'),
(213, NULL, 18, 'ingreso', 6000.00, 'Gómez Camila -> Caja Deportiva | Pago: Próxima - Cuota social 11/2026 - 6000.00 | Socio: Gómez Camila | Forma: Débito', '2026-11-20 16:30:00'),
(214, NULL, 18, 'ingreso', 5880.00, 'Gómez Camila -> Caja Deportiva | Pago: Futura - Cuota social 12/2026 - 5880.00 | Socio: Gómez Camila | Forma: Efectivo', '2026-11-20 17:24:00'),
(215, NULL, 18, 'ingreso', 8250.00, 'Gómez Camila -> Caja Deportiva | Pago: Vencida - Básquet 11/2026 - 8250.00 | Socio: Gómez Camila | Forma: Transferencia', '2026-11-05 17:23:00'),
(216, NULL, 18, 'ingreso', 8250.00, 'Gómez Camila -> Caja Deportiva | Pago: Vencida - Básquet 11/2026 - 8250.00 | Socio: Gómez Camila | Forma: Débito', '2026-11-06 13:47:00'),
(217, NULL, 18, 'ingreso', 8250.00, 'Gómez Camila -> Caja Deportiva | Pago: Próxima - Básquet 11/2026 - 8250.00 | Socio: Gómez Camila | Forma: Transferencia', '2026-11-20 13:37:00'),
(218, NULL, 18, 'ingreso', 8250.00, 'Gómez Camila -> Caja Deportiva | Pago: Próxima - Básquet 11/2026 - 8250.00 | Socio: Gómez Camila | Forma: Débito', '2026-11-20 10:34:00'),
(219, NULL, 18, 'ingreso', 6105.00, 'Gómez Camila -> Caja Deportiva | Pago: Futura - Básquet 12/2026 - 6105.00 | Socio: Gómez Camila | Forma: Efectivo', '2026-11-20 18:43:00'),
(220, NULL, 15, 'ingreso', 12000.00, 'Acosta Sofía -> Caja Central | Pago: Vencida - Cuota social 11/2026 - 12000.00 | Socio: Acosta Sofía | Forma: Transferencia', '2026-11-05 10:25:00'),
(221, NULL, 15, 'ingreso', 8160.00, 'Acosta Sofía -> Caja Central | Pago: Próxima - Cuota social 11/2026 - 8160.00 | Socio: Acosta Sofía | Forma: Transferencia', '2026-11-20 15:42:00'),
(222, NULL, 15, 'ingreso', 16500.00, 'Acosta Sofía -> Caja Central | Pago: Vencida - Básquet 11/2026 - 16500.00 | Socio: Acosta Sofía | Forma: Transferencia', '2026-11-02 16:12:00'),
(223, NULL, 15, 'ingreso', 10560.00, 'Acosta Sofía -> Caja Central | Pago: Próxima - Básquet 11/2026 - 10560.00 | Socio: Acosta Sofía | Forma: Transferencia', '2026-11-20 18:00:00'),
(224, NULL, 16, 'ingreso', 7440.00, 'Molina Lucía -> Caja Norte | Pago: Vencida - Cuota social 11/2026 - 7440.00 | Socio: Molina Lucía | Forma: Transferencia', '2026-11-02 18:48:00'),
(225, NULL, 16, 'ingreso', 12000.00, 'Molina Lucía -> Caja Norte | Pago: Futura - Cuota social 12/2026 - 12000.00 | Socio: Molina Lucía | Forma: Débito', '2026-11-20 16:25:00'),
(226, NULL, 16, 'ingreso', 8140.00, 'Molina Lucía -> Caja Norte | Pago: Vencida - Fútbol 11/2026 - 8140.00 | Socio: Molina Lucía | Forma: Transferencia', '2026-11-01 10:40:00'),
(227, NULL, 16, 'ingreso', 18500.00, 'Molina Lucía -> Caja Norte | Pago: Futura - Fútbol 12/2026 - 18500.00 | Socio: Molina Lucía | Forma: Efectivo', '2026-11-20 13:29:00'),
(228, NULL, 17, 'ingreso', 6000.00, 'Gómez Mateo -> Caja Sur | Pago: Próxima - Cuota social 11/2026 - 6000.00 | Socio: Gómez Mateo | Forma: Transferencia', '2026-11-20 10:45:00'),
(229, NULL, 17, 'ingreso', 6000.00, 'Gómez Mateo -> Caja Sur | Pago: Próxima - Cuota social 11/2026 - 6000.00 | Socio: Gómez Mateo | Forma: Transferencia', '2026-11-20 09:33:00'),
(230, NULL, 17, 'ingreso', 6000.00, 'Gómez Mateo -> Caja Sur | Pago: Futura - Cuota social 12/2026 - 6000.00 | Socio: Gómez Mateo | Forma: Débito', '2026-11-20 14:14:00'),
(231, NULL, 17, 'ingreso', 6000.00, 'Gómez Mateo -> Caja Sur | Pago: Futura - Cuota social 12/2026 - 6000.00 | Socio: Gómez Mateo | Forma: Transferencia', '2026-11-20 09:36:00'),
(232, NULL, 17, 'ingreso', 8250.00, 'Gómez Mateo -> Caja Sur | Pago: Próxima - Básquet 11/2026 - 8250.00 | Socio: Gómez Mateo | Forma: Efectivo', '2026-11-20 14:12:00'),
(233, NULL, 17, 'ingreso', 8250.00, 'Gómez Mateo -> Caja Sur | Pago: Próxima - Básquet 11/2026 - 8250.00 | Socio: Gómez Mateo | Forma: Débito', '2026-11-20 14:36:00'),
(234, NULL, 17, 'ingreso', 8250.00, 'Gómez Mateo -> Caja Sur | Pago: Futura - Básquet 12/2026 - 8250.00 | Socio: Gómez Mateo | Forma: Débito', '2026-11-20 10:17:00'),
(235, NULL, 17, 'ingreso', 8250.00, 'Gómez Mateo -> Caja Sur | Pago: Futura - Básquet 12/2026 - 8250.00 | Socio: Gómez Mateo | Forma: Débito', '2026-11-20 16:27:00'),
(236, NULL, 18, 'ingreso', 12000.00, 'Pérez Benjamín -> Caja Deportiva | Pago: Vencida - Cuota social 11/2026 - 12000.00 | Socio: Pérez Benjamín | Forma: Débito', '2026-11-04 17:21:00'),
(237, NULL, 18, 'ingreso', 12000.00, 'Pérez Benjamín -> Caja Deportiva | Pago: Próxima - Cuota social 11/2026 - 12000.00 | Socio: Pérez Benjamín | Forma: Transferencia', '2026-11-20 11:38:00'),
(238, NULL, 18, 'ingreso', 7800.00, 'Pérez Benjamín -> Caja Deportiva | Pago: Futura - Cuota social 12/2026 - 7800.00 | Socio: Pérez Benjamín | Forma: Efectivo', '2026-11-20 13:50:00'),
(239, NULL, 18, 'ingreso', 18500.00, 'Pérez Benjamín -> Caja Deportiva | Pago: Vencida - Fútbol 11/2026 - 18500.00 | Socio: Pérez Benjamín | Forma: Efectivo', '2026-11-02 14:23:00'),
(240, NULL, 18, 'ingreso', 18500.00, 'Pérez Benjamín -> Caja Deportiva | Pago: Próxima - Fútbol 11/2026 - 18500.00 | Socio: Pérez Benjamín | Forma: Transferencia', '2026-11-20 09:01:00'),
(241, NULL, 18, 'ingreso', 7770.00, 'Pérez Benjamín -> Caja Deportiva | Pago: Futura - Fútbol 12/2026 - 7770.00 | Socio: Pérez Benjamín | Forma: Transferencia', '2026-11-20 10:29:00'),
(242, NULL, 18, 'ingreso', 16500.00, 'Pérez Benjamín -> Caja Deportiva | Pago: Vencida - Básquet 11/2026 - 16500.00 | Socio: Pérez Benjamín | Forma: Efectivo', '2026-11-01 11:31:00'),
(243, NULL, 18, 'ingreso', 16500.00, 'Pérez Benjamín -> Caja Deportiva | Pago: Próxima - Básquet 11/2026 - 16500.00 | Socio: Pérez Benjamín | Forma: Transferencia', '2026-11-20 12:21:00'),
(244, NULL, 18, 'ingreso', 8745.00, 'Pérez Benjamín -> Caja Deportiva | Pago: Futura - Básquet 12/2026 - 8745.00 | Socio: Pérez Benjamín | Forma: Débito', '2026-11-20 18:46:00'),
(245, NULL, 15, 'ingreso', 12000.00, 'Acosta Sofía -> Caja Central | Pago: Vencida - Cuota social 11/2026 - 12000.00 | Socio: Acosta Sofía | Forma: Efectivo', '2026-11-03 13:39:00'),
(246, NULL, 15, 'ingreso', 4440.00, 'Acosta Sofía -> Caja Central | Pago: Próxima - Cuota social 11/2026 - 4440.00 | Socio: Acosta Sofía | Forma: Débito', '2026-11-20 09:14:00'),
(247, NULL, 15, 'ingreso', 15000.00, 'Acosta Sofía -> Caja Central | Pago: Vencida - Vóley 11/2026 - 15000.00 | Socio: Acosta Sofía | Forma: Transferencia', '2026-11-02 14:32:00'),
(248, NULL, 15, 'ingreso', 6450.00, 'Acosta Sofía -> Caja Central | Pago: Próxima - Vóley 11/2026 - 6450.00 | Socio: Acosta Sofía | Forma: Transferencia', '2026-11-20 15:29:00'),
(249, NULL, 16, 'ingreso', 4320.00, 'Molina Emma -> Caja Norte | Pago: Vencida - Cuota social 11/2026 - 4320.00 | Socio: Molina Emma | Forma: Transferencia', '2026-11-01 13:19:00'),
(250, NULL, 16, 'ingreso', 6000.00, 'Molina Emma -> Caja Norte | Pago: Futura - Cuota social 12/2026 - 6000.00 | Socio: Molina Emma | Forma: Débito', '2026-11-20 11:39:00'),
(251, NULL, 16, 'ingreso', 6000.00, 'Molina Emma -> Caja Norte | Pago: Futura - Cuota social 12/2026 - 6000.00 | Socio: Molina Emma | Forma: Débito', '2026-11-20 17:51:00'),
(252, NULL, 16, 'ingreso', 6475.00, 'Molina Emma -> Caja Norte | Pago: Vencida - Fútbol 11/2026 - 6475.00 | Socio: Molina Emma | Forma: Débito', '2026-11-05 11:36:00'),
(253, NULL, 16, 'ingreso', 9250.00, 'Molina Emma -> Caja Norte | Pago: Futura - Fútbol 12/2026 - 9250.00 | Socio: Molina Emma | Forma: Débito', '2026-11-20 16:37:00'),
(254, NULL, 16, 'ingreso', 9250.00, 'Molina Emma -> Caja Norte | Pago: Futura - Fútbol 12/2026 - 9250.00 | Socio: Molina Emma | Forma: Transferencia', '2026-11-20 18:33:00'),
(255, NULL, 17, 'ingreso', 12000.00, 'Navarro Renata -> Caja Sur | Pago: Próxima - Cuota social 11/2026 - 12000.00 | Socio: Navarro Renata | Forma: Efectivo', '2026-11-20 10:22:00'),
(256, NULL, 17, 'ingreso', 12000.00, 'Navarro Renata -> Caja Sur | Pago: Futura - Cuota social 12/2026 - 12000.00 | Socio: Navarro Renata | Forma: Efectivo', '2026-11-20 16:22:00'),
(257, NULL, 17, 'ingreso', 18500.00, 'Navarro Renata -> Caja Sur | Pago: Próxima - Fútbol 11/2026 - 18500.00 | Socio: Navarro Renata | Forma: Efectivo', '2026-11-20 18:58:00'),
(258, NULL, 17, 'ingreso', 18500.00, 'Navarro Renata -> Caja Sur | Pago: Futura - Fútbol 12/2026 - 18500.00 | Socio: Navarro Renata | Forma: Débito', '2026-11-20 12:05:00'),
(259, NULL, 18, 'ingreso', 12000.00, 'Pérez Martina -> Caja Deportiva | Pago: Vencida - Cuota social 11/2026 - 12000.00 | Socio: Pérez Martina | Forma: Efectivo', '2026-11-03 11:44:00'),
(260, NULL, 18, 'ingreso', 12000.00, 'Pérez Martina -> Caja Deportiva | Pago: Próxima - Cuota social 11/2026 - 12000.00 | Socio: Pérez Martina | Forma: Efectivo', '2026-11-20 12:42:00'),
(261, NULL, 18, 'ingreso', 5520.00, 'Pérez Martina -> Caja Deportiva | Pago: Futura - Cuota social 12/2026 - 5520.00 | Socio: Pérez Martina | Forma: Débito', '2026-11-20 09:13:00'),
(262, NULL, 18, 'ingreso', 18500.00, 'Pérez Martina -> Caja Deportiva | Pago: Vencida - Fútbol 11/2026 - 18500.00 | Socio: Pérez Martina | Forma: Efectivo', '2026-11-04 10:20:00'),
(263, NULL, 18, 'ingreso', 18500.00, 'Pérez Martina -> Caja Deportiva | Pago: Próxima - Fútbol 11/2026 - 18500.00 | Socio: Pérez Martina | Forma: Efectivo', '2026-11-20 16:52:00'),
(264, NULL, 18, 'ingreso', 11840.00, 'Pérez Martina -> Caja Deportiva | Pago: Futura - Fútbol 12/2026 - 11840.00 | Socio: Pérez Martina | Forma: Débito', '2026-11-20 10:34:00'),
(265, NULL, 15, 'ingreso', 6000.00, 'Romero Mora -> Caja Central | Pago: Vencida - Cuota social 11/2026 - 6000.00 | Socio: Romero Mora | Forma: Efectivo', '2026-11-05 15:38:00'),
(266, NULL, 15, 'ingreso', 6000.00, 'Romero Mora -> Caja Central | Pago: Vencida - Cuota social 11/2026 - 6000.00 | Socio: Romero Mora | Forma: Efectivo', '2026-11-04 12:44:00'),
(267, NULL, 15, 'ingreso', 7920.00, 'Romero Mora -> Caja Central | Pago: Próxima - Cuota social 11/2026 - 7920.00 | Socio: Romero Mora | Forma: Débito', '2026-11-20 09:56:00'),
(268, NULL, 15, 'ingreso', 8250.00, 'Romero Mora -> Caja Central | Pago: Vencida - Básquet 11/2026 - 8250.00 | Socio: Romero Mora | Forma: Transferencia', '2026-11-03 12:28:00'),
(269, NULL, 15, 'ingreso', 8250.00, 'Romero Mora -> Caja Central | Pago: Vencida - Básquet 11/2026 - 8250.00 | Socio: Romero Mora | Forma: Débito', '2026-11-03 14:13:00'),
(270, NULL, 15, 'ingreso', 6600.00, 'Romero Mora -> Caja Central | Pago: Próxima - Básquet 11/2026 - 6600.00 | Socio: Romero Mora | Forma: Débito', '2026-11-20 15:10:00'),
(271, NULL, 16, 'ingreso', 8280.00, 'Castro Felipe -> Caja Norte | Pago: Vencida - Cuota social 11/2026 - 8280.00 | Socio: Castro Felipe | Forma: Transferencia', '2026-11-02 16:10:00'),
(272, NULL, 16, 'ingreso', 12000.00, 'Castro Felipe -> Caja Norte | Pago: Futura - Cuota social 12/2026 - 12000.00 | Socio: Castro Felipe | Forma: Débito', '2026-11-20 11:35:00'),
(273, NULL, 16, 'ingreso', 8880.00, 'Castro Felipe -> Caja Norte | Pago: Vencida - Fútbol 11/2026 - 8880.00 | Socio: Castro Felipe | Forma: Transferencia', '2026-11-05 12:15:00'),
(274, NULL, 16, 'ingreso', 18500.00, 'Castro Felipe -> Caja Norte | Pago: Futura - Fútbol 12/2026 - 18500.00 | Socio: Castro Felipe | Forma: Transferencia', '2026-11-20 11:03:00'),
(275, NULL, 17, 'ingreso', 12000.00, 'López Renata -> Caja Sur | Pago: Próxima - Cuota social 11/2026 - 12000.00 | Socio: López Renata | Forma: Efectivo', '2026-11-20 14:38:00'),
(276, NULL, 17, 'ingreso', 12000.00, 'López Renata -> Caja Sur | Pago: Futura - Cuota social 12/2026 - 12000.00 | Socio: López Renata | Forma: Débito', '2026-11-20 16:31:00'),
(277, NULL, 17, 'ingreso', 18500.00, 'López Renata -> Caja Sur | Pago: Próxima - Fútbol 11/2026 - 18500.00 | Socio: López Renata | Forma: Transferencia', '2026-11-20 18:20:00'),
(278, NULL, 17, 'ingreso', 18500.00, 'López Renata -> Caja Sur | Pago: Futura - Fútbol 12/2026 - 18500.00 | Socio: López Renata | Forma: Transferencia', '2026-11-20 17:13:00'),
(279, NULL, 17, 'ingreso', 16500.00, 'López Renata -> Caja Sur | Pago: Próxima - Básquet 11/2026 - 16500.00 | Socio: López Renata | Forma: Efectivo', '2026-11-20 12:02:00'),
(280, NULL, 17, 'ingreso', 16500.00, 'López Renata -> Caja Sur | Pago: Futura - Básquet 12/2026 - 16500.00 | Socio: López Renata | Forma: Efectivo', '2026-11-20 15:08:00'),
(281, NULL, 18, 'ingreso', 6000.00, 'Díaz Lucía -> Caja Deportiva | Pago: Vencida - Cuota social 11/2026 - 6000.00 | Socio: Díaz Lucía | Forma: Débito', '2026-11-01 15:30:00'),
(282, NULL, 18, 'ingreso', 6000.00, 'Díaz Lucía -> Caja Deportiva | Pago: Vencida - Cuota social 11/2026 - 6000.00 | Socio: Díaz Lucía | Forma: Transferencia', '2026-11-07 11:53:00'),
(283, NULL, 18, 'ingreso', 6000.00, 'Díaz Lucía -> Caja Deportiva | Pago: Próxima - Cuota social 11/2026 - 6000.00 | Socio: Díaz Lucía | Forma: Efectivo', '2026-11-20 17:31:00'),
(284, NULL, 18, 'ingreso', 6000.00, 'Díaz Lucía -> Caja Deportiva | Pago: Próxima - Cuota social 11/2026 - 6000.00 | Socio: Díaz Lucía | Forma: Efectivo', '2026-11-20 10:07:00'),
(285, NULL, 18, 'ingreso', 7200.00, 'Díaz Lucía -> Caja Deportiva | Pago: Futura - Cuota social 12/2026 - 7200.00 | Socio: Díaz Lucía | Forma: Efectivo', '2026-11-20 16:33:00'),
(286, NULL, 18, 'ingreso', 9250.00, 'Díaz Lucía -> Caja Deportiva | Pago: Vencida - Fútbol 11/2026 - 9250.00 | Socio: Díaz Lucía | Forma: Efectivo', '2026-11-03 09:20:00'),
(287, NULL, 18, 'ingreso', 9250.00, 'Díaz Lucía -> Caja Deportiva | Pago: Vencida - Fútbol 11/2026 - 9250.00 | Socio: Díaz Lucía | Forma: Transferencia', '2026-11-05 10:50:00'),
(288, NULL, 18, 'ingreso', 9250.00, 'Díaz Lucía -> Caja Deportiva | Pago: Próxima - Fútbol 11/2026 - 9250.00 | Socio: Díaz Lucía | Forma: Débito', '2026-11-20 13:33:00'),
(289, NULL, 18, 'ingreso', 9250.00, 'Díaz Lucía -> Caja Deportiva | Pago: Próxima - Fútbol 11/2026 - 9250.00 | Socio: Díaz Lucía | Forma: Transferencia', '2026-11-20 18:38:00'),
(290, NULL, 18, 'ingreso', 11655.00, 'Díaz Lucía -> Caja Deportiva | Pago: Futura - Fútbol 12/2026 - 11655.00 | Socio: Díaz Lucía | Forma: Débito', '2026-11-20 18:42:00'),
(291, NULL, 15, 'ingreso', 12000.00, 'Navarro Lautaro -> Caja Central | Pago: Vencida - Cuota social 11/2026 - 12000.00 | Socio: Navarro Lautaro | Forma: Transferencia', '2026-11-05 13:31:00'),
(292, NULL, 15, 'ingreso', 6000.00, 'Navarro Lautaro -> Caja Central | Pago: Próxima - Cuota social 11/2026 - 6000.00 | Socio: Navarro Lautaro | Forma: Transferencia', '2026-11-20 16:00:00'),
(293, NULL, 15, 'ingreso', 16500.00, 'Navarro Lautaro -> Caja Central | Pago: Vencida - Básquet 11/2026 - 16500.00 | Socio: Navarro Lautaro | Forma: Débito', '2026-11-03 12:41:00'),
(294, NULL, 15, 'ingreso', 6435.00, 'Navarro Lautaro -> Caja Central | Pago: Próxima - Básquet 11/2026 - 6435.00 | Socio: Navarro Lautaro | Forma: Transferencia', '2026-11-20 15:01:00'),
(295, NULL, 16, 'ingreso', 7080.00, 'Pérez Lautaro -> Caja Norte | Pago: Vencida - Cuota social 11/2026 - 7080.00 | Socio: Pérez Lautaro | Forma: Efectivo', '2026-11-03 11:46:00'),
(296, NULL, 16, 'ingreso', 12000.00, 'Pérez Lautaro -> Caja Norte | Pago: Futura - Cuota social 12/2026 - 12000.00 | Socio: Pérez Lautaro | Forma: Transferencia', '2026-11-20 13:31:00'),
(297, NULL, 16, 'ingreso', 10230.00, 'Pérez Lautaro -> Caja Norte | Pago: Vencida - Básquet 11/2026 - 10230.00 | Socio: Pérez Lautaro | Forma: Transferencia', '2026-11-01 18:50:00'),
(298, NULL, 16, 'ingreso', 16500.00, 'Pérez Lautaro -> Caja Norte | Pago: Futura - Básquet 12/2026 - 16500.00 | Socio: Pérez Lautaro | Forma: Transferencia', '2026-11-20 13:32:00'),
(299, NULL, 17, 'ingreso', 6000.00, 'Díaz Lucía -> Caja Sur | Pago: Próxima - Cuota social 11/2026 - 6000.00 | Socio: Díaz Lucía | Forma: Transferencia', '2026-11-20 09:23:00'),
(300, NULL, 17, 'ingreso', 6000.00, 'Díaz Lucía -> Caja Sur | Pago: Próxima - Cuota social 11/2026 - 6000.00 | Socio: Díaz Lucía | Forma: Débito', '2026-11-20 13:00:00'),
(301, NULL, 17, 'ingreso', 6000.00, 'Díaz Lucía -> Caja Sur | Pago: Futura - Cuota social 12/2026 - 6000.00 | Socio: Díaz Lucía | Forma: Transferencia', '2026-11-20 12:40:00'),
(302, NULL, 17, 'ingreso', 6000.00, 'Díaz Lucía -> Caja Sur | Pago: Futura - Cuota social 12/2026 - 6000.00 | Socio: Díaz Lucía | Forma: Efectivo', '2026-11-20 17:34:00'),
(303, NULL, 17, 'ingreso', 8250.00, 'Díaz Lucía -> Caja Sur | Pago: Próxima - Básquet 11/2026 - 8250.00 | Socio: Díaz Lucía | Forma: Efectivo', '2026-11-20 12:09:00'),
(304, NULL, 17, 'ingreso', 8250.00, 'Díaz Lucía -> Caja Sur | Pago: Próxima - Básquet 11/2026 - 8250.00 | Socio: Díaz Lucía | Forma: Débito', '2026-11-20 16:58:00'),
(305, NULL, 17, 'ingreso', 8250.00, 'Díaz Lucía -> Caja Sur | Pago: Futura - Básquet 12/2026 - 8250.00 | Socio: Díaz Lucía | Forma: Efectivo', '2026-11-20 13:00:00'),
(306, NULL, 17, 'ingreso', 8250.00, 'Díaz Lucía -> Caja Sur | Pago: Futura - Básquet 12/2026 - 8250.00 | Socio: Díaz Lucía | Forma: Efectivo', '2026-11-20 18:16:00'),
(307, NULL, 18, 'ingreso', 12000.00, 'Gómez Emma -> Caja Deportiva | Pago: Vencida - Cuota social 11/2026 - 12000.00 | Socio: Gómez Emma | Forma: Transferencia', '2026-11-05 10:11:00'),
(308, NULL, 18, 'ingreso', 12000.00, 'Gómez Emma -> Caja Deportiva | Pago: Próxima - Cuota social 11/2026 - 12000.00 | Socio: Gómez Emma | Forma: Efectivo', '2026-11-20 17:48:00'),
(309, NULL, 18, 'ingreso', 7080.00, 'Gómez Emma -> Caja Deportiva | Pago: Futura - Cuota social 12/2026 - 7080.00 | Socio: Gómez Emma | Forma: Transferencia', '2026-11-20 11:10:00'),
(310, NULL, 18, 'ingreso', 18500.00, 'Gómez Emma -> Caja Deportiva | Pago: Vencida - Fútbol 11/2026 - 18500.00 | Socio: Gómez Emma | Forma: Transferencia', '2026-11-01 14:17:00'),
(311, NULL, 18, 'ingreso', 18500.00, 'Gómez Emma -> Caja Deportiva | Pago: Próxima - Fútbol 11/2026 - 18500.00 | Socio: Gómez Emma | Forma: Débito', '2026-11-20 12:51:00'),
(312, NULL, 18, 'ingreso', 11285.00, 'Gómez Emma -> Caja Deportiva | Pago: Futura - Fútbol 12/2026 - 11285.00 | Socio: Gómez Emma | Forma: Efectivo', '2026-11-20 12:13:00'),
(313, NULL, 15, 'ingreso', 12000.00, 'Alonso Benjamín -> Caja Central | Pago: Vencida - Cuota social 11/2026 - 12000.00 | Socio: Alonso Benjamín | Forma: Transferencia', '2026-11-02 16:58:00'),
(314, NULL, 15, 'ingreso', 7680.00, 'Alonso Benjamín -> Caja Central | Pago: Próxima - Cuota social 11/2026 - 7680.00 | Socio: Alonso Benjamín | Forma: Débito', '2026-11-20 17:49:00'),
(315, NULL, 15, 'ingreso', 15000.00, 'Alonso Benjamín -> Caja Central | Pago: Vencida - Vóley 11/2026 - 15000.00 | Socio: Alonso Benjamín | Forma: Débito', '2026-11-05 14:52:00'),
(316, NULL, 15, 'ingreso', 7650.00, 'Alonso Benjamín -> Caja Central | Pago: Próxima - Vóley 11/2026 - 7650.00 | Socio: Alonso Benjamín | Forma: Efectivo', '2026-11-20 14:09:00'),
(317, NULL, 16, 'ingreso', 7080.00, 'Molina Renata -> Caja Norte | Pago: Vencida - Cuota social 11/2026 - 7080.00 | Socio: Molina Renata | Forma: Débito', '2026-11-04 16:45:00'),
(318, NULL, 16, 'ingreso', 6000.00, 'Molina Renata -> Caja Norte | Pago: Futura - Cuota social 12/2026 - 6000.00 | Socio: Molina Renata | Forma: Efectivo', '2026-11-20 12:02:00'),
(319, NULL, 16, 'ingreso', 6000.00, 'Molina Renata -> Caja Norte | Pago: Futura - Cuota social 12/2026 - 6000.00 | Socio: Molina Renata | Forma: Transferencia', '2026-11-20 17:48:00'),
(320, NULL, 16, 'ingreso', 9435.00, 'Molina Renata -> Caja Norte | Pago: Vencida - Fútbol 11/2026 - 9435.00 | Socio: Molina Renata | Forma: Débito', '2026-11-04 11:15:00'),
(321, NULL, 16, 'ingreso', 9250.00, 'Molina Renata -> Caja Norte | Pago: Futura - Fútbol 12/2026 - 9250.00 | Socio: Molina Renata | Forma: Transferencia', '2026-11-20 16:32:00'),
(322, NULL, 16, 'ingreso', 9250.00, 'Molina Renata -> Caja Norte | Pago: Futura - Fútbol 12/2026 - 9250.00 | Socio: Molina Renata | Forma: Efectivo', '2026-11-20 10:15:00'),
(323, NULL, 16, 'ingreso', 6300.00, 'Molina Renata -> Caja Norte | Pago: Vencida - Vóley 11/2026 - 6300.00 | Socio: Molina Renata | Forma: Efectivo', '2026-11-02 17:48:00'),
(324, NULL, 16, 'ingreso', 7500.00, 'Molina Renata -> Caja Norte | Pago: Futura - Vóley 12/2026 - 7500.00 | Socio: Molina Renata | Forma: Débito', '2026-11-20 14:25:00'),
(325, NULL, 16, 'ingreso', 7500.00, 'Molina Renata -> Caja Norte | Pago: Futura - Vóley 12/2026 - 7500.00 | Socio: Molina Renata | Forma: Débito', '2026-11-20 17:43:00'),
(326, NULL, 17, 'ingreso', 12000.00, 'Castro Lucía -> Caja Sur | Pago: Próxima - Cuota social 11/2026 - 12000.00 | Socio: Castro Lucía | Forma: Transferencia', '2026-11-20 11:52:00'),
(327, NULL, 17, 'ingreso', 12000.00, 'Castro Lucía -> Caja Sur | Pago: Futura - Cuota social 12/2026 - 12000.00 | Socio: Castro Lucía | Forma: Efectivo', '2026-11-20 11:32:00'),
(328, NULL, 17, 'ingreso', 15000.00, 'Castro Lucía -> Caja Sur | Pago: Próxima - Vóley 11/2026 - 15000.00 | Socio: Castro Lucía | Forma: Débito', '2026-11-20 17:22:00'),
(329, NULL, 17, 'ingreso', 15000.00, 'Castro Lucía -> Caja Sur | Pago: Futura - Vóley 12/2026 - 15000.00 | Socio: Castro Lucía | Forma: Efectivo', '2026-11-20 16:02:00'),
(330, NULL, 18, 'ingreso', 12000.00, 'Romero Mateo -> Caja Deportiva | Pago: Vencida - Cuota social 11/2026 - 12000.00 | Socio: Romero Mateo | Forma: Efectivo', '2026-11-04 09:32:00'),
(331, NULL, 18, 'ingreso', 12000.00, 'Romero Mateo -> Caja Deportiva | Pago: Próxima - Cuota social 11/2026 - 12000.00 | Socio: Romero Mateo | Forma: Efectivo', '2026-11-20 09:02:00'),
(332, NULL, 18, 'ingreso', 7680.00, 'Romero Mateo -> Caja Deportiva | Pago: Futura - Cuota social 12/2026 - 7680.00 | Socio: Romero Mateo | Forma: Transferencia', '2026-11-20 11:48:00'),
(333, NULL, 18, 'ingreso', 18500.00, 'Romero Mateo -> Caja Deportiva | Pago: Vencida - Fútbol 11/2026 - 18500.00 | Socio: Romero Mateo | Forma: Efectivo', '2026-11-03 16:16:00'),
(334, NULL, 18, 'ingreso', 18500.00, 'Romero Mateo -> Caja Deportiva | Pago: Próxima - Fútbol 11/2026 - 18500.00 | Socio: Romero Mateo | Forma: Transferencia', '2026-11-20 13:22:00'),
(335, NULL, 18, 'ingreso', 7770.00, 'Romero Mateo -> Caja Deportiva | Pago: Futura - Fútbol 12/2026 - 7770.00 | Socio: Romero Mateo | Forma: Efectivo', '2026-11-20 11:05:00'),
(336, NULL, 15, 'ingreso', 6000.00, 'Martínez Felipe -> Caja Central | Pago: Vencida - Cuota social 11/2026 - 6000.00 | Socio: Martínez Felipe | Forma: Efectivo', '2026-11-05 14:15:00'),
(337, NULL, 15, 'ingreso', 6000.00, 'Martínez Felipe -> Caja Central | Pago: Vencida - Cuota social 11/2026 - 6000.00 | Socio: Martínez Felipe | Forma: Efectivo', '2026-11-03 18:14:00'),
(338, NULL, 15, 'ingreso', 6720.00, 'Martínez Felipe -> Caja Central | Pago: Próxima - Cuota social 11/2026 - 6720.00 | Socio: Martínez Felipe | Forma: Débito', '2026-11-20 11:30:00'),
(339, NULL, 15, 'ingreso', 8250.00, 'Martínez Felipe -> Caja Central | Pago: Vencida - Básquet 11/2026 - 8250.00 | Socio: Martínez Felipe | Forma: Transferencia', '2026-11-04 17:18:00'),
(340, NULL, 15, 'ingreso', 8250.00, 'Martínez Felipe -> Caja Central | Pago: Vencida - Básquet 11/2026 - 8250.00 | Socio: Martínez Felipe | Forma: Transferencia', '2026-11-05 16:27:00'),
(341, NULL, 15, 'ingreso', 5940.00, 'Martínez Felipe -> Caja Central | Pago: Próxima - Básquet 11/2026 - 5940.00 | Socio: Martínez Felipe | Forma: Efectivo', '2026-11-20 18:28:00'),
(342, NULL, 16, 'ingreso', 7560.00, 'Fernández Martina -> Caja Norte | Pago: Vencida - Cuota social 11/2026 - 7560.00 | Socio: Fernández Martina | Forma: Débito', '2026-11-01 14:35:00'),
(343, NULL, 16, 'ingreso', 12000.00, 'Fernández Martina -> Caja Norte | Pago: Futura - Cuota social 12/2026 - 12000.00 | Socio: Fernández Martina | Forma: Débito', '2026-11-20 17:16:00'),
(344, NULL, 16, 'ingreso', 7030.00, 'Fernández Martina -> Caja Norte | Pago: Vencida - Fútbol 11/2026 - 7030.00 | Socio: Fernández Martina | Forma: Efectivo', '2026-11-04 17:06:00'),
(345, NULL, 16, 'ingreso', 18500.00, 'Fernández Martina -> Caja Norte | Pago: Futura - Fútbol 12/2026 - 18500.00 | Socio: Fernández Martina | Forma: Efectivo', '2026-11-20 11:25:00'),
(346, NULL, 17, 'ingreso', 12000.00, 'Rojas Thiago -> Caja Sur | Pago: Próxima - Cuota social 11/2026 - 12000.00 | Socio: Rojas Thiago | Forma: Efectivo', '2026-11-20 12:44:00'),
(347, NULL, 17, 'ingreso', 12000.00, 'Rojas Thiago -> Caja Sur | Pago: Futura - Cuota social 12/2026 - 12000.00 | Socio: Rojas Thiago | Forma: Débito', '2026-11-20 11:28:00'),
(348, NULL, 17, 'ingreso', 18500.00, 'Rojas Thiago -> Caja Sur | Pago: Próxima - Fútbol 11/2026 - 18500.00 | Socio: Rojas Thiago | Forma: Efectivo', '2026-11-20 12:42:00'),
(349, NULL, 17, 'ingreso', 18500.00, 'Rojas Thiago -> Caja Sur | Pago: Futura - Fútbol 12/2026 - 18500.00 | Socio: Rojas Thiago | Forma: Efectivo', '2026-11-20 11:06:00'),
(350, NULL, 18, 'ingreso', 6000.00, 'Torres Thiago -> Caja Deportiva | Pago: Vencida - Cuota social 11/2026 - 6000.00 | Socio: Torres Thiago | Forma: Transferencia', '2026-11-04 16:53:00'),
(351, NULL, 18, 'ingreso', 6000.00, 'Torres Thiago -> Caja Deportiva | Pago: Vencida - Cuota social 11/2026 - 6000.00 | Socio: Torres Thiago | Forma: Débito', '2026-11-06 18:34:00'),
(352, NULL, 18, 'ingreso', 6000.00, 'Torres Thiago -> Caja Deportiva | Pago: Próxima - Cuota social 11/2026 - 6000.00 | Socio: Torres Thiago | Forma: Débito', '2026-11-20 12:59:00'),
(353, NULL, 18, 'ingreso', 6000.00, 'Torres Thiago -> Caja Deportiva | Pago: Próxima - Cuota social 11/2026 - 6000.00 | Socio: Torres Thiago | Forma: Efectivo', '2026-11-20 12:31:00'),
(354, NULL, 18, 'ingreso', 7680.00, 'Torres Thiago -> Caja Deportiva | Pago: Futura - Cuota social 12/2026 - 7680.00 | Socio: Torres Thiago | Forma: Transferencia', '2026-11-20 15:04:00'),
(355, NULL, 18, 'ingreso', 8250.00, 'Torres Thiago -> Caja Deportiva | Pago: Vencida - Básquet 11/2026 - 8250.00 | Socio: Torres Thiago | Forma: Transferencia', '2026-11-05 16:22:00'),
(356, NULL, 18, 'ingreso', 8250.00, 'Torres Thiago -> Caja Deportiva | Pago: Vencida - Básquet 11/2026 - 8250.00 | Socio: Torres Thiago | Forma: Efectivo', '2026-11-05 16:16:00'),
(357, NULL, 18, 'ingreso', 8250.00, 'Torres Thiago -> Caja Deportiva | Pago: Próxima - Básquet 11/2026 - 8250.00 | Socio: Torres Thiago | Forma: Débito', '2026-11-20 14:06:00'),
(358, NULL, 18, 'ingreso', 8250.00, 'Torres Thiago -> Caja Deportiva | Pago: Próxima - Básquet 11/2026 - 8250.00 | Socio: Torres Thiago | Forma: Efectivo', '2026-11-20 10:31:00'),
(359, NULL, 18, 'ingreso', 5940.00, 'Torres Thiago -> Caja Deportiva | Pago: Futura - Básquet 12/2026 - 5940.00 | Socio: Torres Thiago | Forma: Efectivo', '2026-11-20 16:26:00'),
(360, NULL, 15, 'ingreso', 12000.00, 'Gómez Thiago -> Caja Central | Pago: Vencida - Cuota social 11/2026 - 12000.00 | Socio: Gómez Thiago | Forma: Transferencia', '2026-11-02 13:15:00'),
(361, NULL, 15, 'ingreso', 5640.00, 'Gómez Thiago -> Caja Central | Pago: Próxima - Cuota social 11/2026 - 5640.00 | Socio: Gómez Thiago | Forma: Efectivo', '2026-11-20 15:28:00'),
(362, NULL, 15, 'ingreso', 18500.00, 'Gómez Thiago -> Caja Central | Pago: Vencida - Fútbol 11/2026 - 18500.00 | Socio: Gómez Thiago | Forma: Efectivo', '2026-11-02 17:36:00'),
(363, NULL, 15, 'ingreso', 12580.00, 'Gómez Thiago -> Caja Central | Pago: Próxima - Fútbol 11/2026 - 12580.00 | Socio: Gómez Thiago | Forma: Débito', '2026-11-20 16:45:00'),
(364, NULL, 15, 'ingreso', 15000.00, 'Gómez Thiago -> Caja Central | Pago: Vencida - Vóley 11/2026 - 15000.00 | Socio: Gómez Thiago | Forma: Transferencia', '2026-11-02 11:33:00'),
(365, NULL, 15, 'ingreso', 7350.00, 'Gómez Thiago -> Caja Central | Pago: Próxima - Vóley 11/2026 - 7350.00 | Socio: Gómez Thiago | Forma: Transferencia', '2026-11-20 13:06:00'),
(366, NULL, 16, 'ingreso', 8040.00, 'Pérez Valentina -> Caja Norte | Pago: Vencida - Cuota social 11/2026 - 8040.00 | Socio: Pérez Valentina | Forma: Transferencia', '2026-11-05 14:52:00'),
(367, NULL, 16, 'ingreso', 12000.00, 'Pérez Valentina -> Caja Norte | Pago: Futura - Cuota social 12/2026 - 12000.00 | Socio: Pérez Valentina | Forma: Transferencia', '2026-11-20 15:14:00'),
(368, NULL, 16, 'ingreso', 6475.00, 'Pérez Valentina -> Caja Norte | Pago: Vencida - Fútbol 11/2026 - 6475.00 | Socio: Pérez Valentina | Forma: Efectivo', '2026-11-02 13:28:00'),
(369, NULL, 16, 'ingreso', 18500.00, 'Pérez Valentina -> Caja Norte | Pago: Futura - Fútbol 12/2026 - 18500.00 | Socio: Pérez Valentina | Forma: Efectivo', '2026-11-20 10:14:00'),
(370, 15, 16, 'transferencia', 5000.00, 'Caja Central -> Caja Norte | Reposición de cambio de caja', '2026-11-20 00:28:30'),
(371, 15, 17, 'transferencia', 3500.00, 'Caja Central -> Caja Sur | Distribución para gastos de sede', '2026-11-20 00:28:30'),
(372, 15, 18, 'transferencia', 2500.00, 'Caja Central -> Caja Deportiva | Fondo para actividades deportivas', '2026-11-20 00:28:30'),
(373, NULL, 16, 'ingreso', 12000.00, 'Castro Felipe -> Caja Central | Pago: Vencida - Cuota social 11/2026 - 12000.00 | Socio: Castro Felipe | Forma: Efectivo', '2026-11-20 16:08:21'),
(374, NULL, 17, 'ingreso', 12000.00, 'Martínez Bruno -> Caja Central | Pago: Vencida - Cuota social 11/2026 - 12000.00 | Socio: Martínez Bruno | Forma: Efectivo', '2026-11-20 17:29:01'),
(375, NULL, 16, 'ingreso', 12000.00, 'Torres Thiago -> Caja Central | Pago: Vencida - Cuota social 11/2026 - 12000.00 | Socio: Torres Thiago | Forma: Efectivo', '2026-11-20 17:33:40'),
(376, NULL, 16, 'ingreso', 12000.00, 'Molina Lucía -> Caja Central | Pago: Vencida - Cuota social 11/2026 - 12000.00 | Socio: Molina Lucía | Forma: Efectivo', '2026-11-20 17:40:07'),
(377, NULL, 17, 'ingreso', 12000.00, 'Gómez Mateo -> Caja Central | Pago: Vencida - Cuota social 11/2026 - 12000.00 | Socio: Gómez Mateo | Forma: Efectivo', '2026-11-20 17:40:43'),
(378, NULL, 19, 'ingreso', 12000.00, 'Rojas Joaquín -> Gonzalo Arreseygor | Pago: Próxima - Cuota social 11/2026 - 12000.00 | Socio: Rojas Joaquín | Forma: Efectivo', '2026-11-20 17:48:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `id_socio` int(11) NOT NULL,
  `id_liquidacion` int(11) NOT NULL,
  `id_caja` int(11) NOT NULL,
  `forma_pago` varchar(50) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_edicion` datetime DEFAULT NULL,
  `fecha_borrado` datetime DEFAULT NULL,
  `id_recaudador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `id_socio`, `id_liquidacion`, `id_caja`, `forma_pago`, `monto`, `fecha_pago`, `fecha_alta`, `fecha_edicion`, `fecha_borrado`, `id_recaudador`) VALUES
(197, 70, 39, 15, 'Débito', 6000.00, '2026-11-03 18:39:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(198, 70, 39, 15, 'Efectivo', 6000.00, '2026-11-04 14:16:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(199, 70, 40, 15, 'Efectivo', 8280.00, '2026-11-20 13:22:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(200, 70, 42, 15, 'Transferencia', 9250.00, '2026-11-03 15:11:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(201, 70, 42, 15, 'Efectivo', 9250.00, '2026-11-04 12:59:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(202, 70, 43, 15, 'Transferencia', 12580.00, '2026-11-20 15:31:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(203, 70, 45, 15, 'Transferencia', 8250.00, '2026-11-03 17:20:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(204, 70, 45, 15, 'Transferencia', 8250.00, '2026-11-04 14:54:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(205, 70, 46, 15, 'Transferencia', 9405.00, '2026-11-20 16:07:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(206, 71, 39, 16, 'Débito', 6840.00, '2026-11-01 13:45:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(207, 71, 41, 16, 'Débito', 12000.00, '2026-11-20 17:28:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(208, 71, 45, 16, 'Débito', 6765.00, '2026-11-02 17:43:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(209, 71, 47, 16, 'Efectivo', 16500.00, '2026-11-20 09:02:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(210, 72, 40, 17, 'Débito', 12000.00, '2026-11-20 17:35:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(211, 72, 41, 17, 'Transferencia', 12000.00, '2026-11-20 12:04:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(212, 72, 43, 17, 'Transferencia', 18500.00, '2026-11-20 12:34:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(213, 72, 44, 17, 'Transferencia', 18500.00, '2026-11-20 12:52:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(214, 73, 39, 18, 'Transferencia', 6000.00, '2026-11-05 09:37:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(215, 73, 39, 18, 'Débito', 6000.00, '2026-11-02 12:34:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(216, 73, 40, 18, 'Efectivo', 6000.00, '2026-11-20 11:31:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(217, 73, 40, 18, 'Débito', 6000.00, '2026-11-20 16:30:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(218, 73, 41, 18, 'Efectivo', 5880.00, '2026-11-20 17:24:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(219, 73, 45, 18, 'Transferencia', 8250.00, '2026-11-05 17:23:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(220, 73, 45, 18, 'Débito', 8250.00, '2026-11-06 13:47:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(221, 73, 46, 18, 'Transferencia', 8250.00, '2026-11-20 13:37:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(222, 73, 46, 18, 'Débito', 8250.00, '2026-11-20 10:34:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(223, 73, 47, 18, 'Efectivo', 6105.00, '2026-11-20 18:43:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(224, 74, 39, 15, 'Transferencia', 12000.00, '2026-11-05 10:25:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(225, 74, 40, 15, 'Transferencia', 8160.00, '2026-11-20 15:42:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(226, 74, 45, 15, 'Transferencia', 16500.00, '2026-11-02 16:12:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(227, 74, 46, 15, 'Transferencia', 10560.00, '2026-11-20 18:00:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(228, 75, 39, 16, 'Transferencia', 7440.00, '2026-11-02 18:48:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(229, 75, 41, 16, 'Débito', 12000.00, '2026-11-20 16:25:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(230, 75, 42, 16, 'Transferencia', 8140.00, '2026-11-01 10:40:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(231, 75, 44, 16, 'Efectivo', 18500.00, '2026-11-20 13:29:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(232, 76, 40, 17, 'Transferencia', 6000.00, '2026-11-20 10:45:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(233, 76, 40, 17, 'Transferencia', 6000.00, '2026-11-20 09:33:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(234, 76, 41, 17, 'Débito', 6000.00, '2026-11-20 14:14:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(235, 76, 41, 17, 'Transferencia', 6000.00, '2026-11-20 09:36:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(236, 76, 46, 17, 'Efectivo', 8250.00, '2026-11-20 14:12:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(237, 76, 46, 17, 'Débito', 8250.00, '2026-11-20 14:36:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(238, 76, 47, 17, 'Débito', 8250.00, '2026-11-20 10:17:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(239, 76, 47, 17, 'Débito', 8250.00, '2026-11-20 16:27:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(240, 77, 39, 18, 'Débito', 12000.00, '2026-11-04 17:21:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(241, 77, 40, 18, 'Transferencia', 12000.00, '2026-11-20 11:38:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(242, 77, 41, 18, 'Efectivo', 7800.00, '2026-11-20 13:50:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(243, 77, 42, 18, 'Efectivo', 18500.00, '2026-11-02 14:23:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(244, 77, 43, 18, 'Transferencia', 18500.00, '2026-11-20 09:01:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(245, 77, 44, 18, 'Transferencia', 7770.00, '2026-11-20 10:29:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(246, 77, 45, 18, 'Efectivo', 16500.00, '2026-11-01 11:31:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(247, 77, 46, 18, 'Transferencia', 16500.00, '2026-11-20 12:21:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(248, 77, 47, 18, 'Débito', 8745.00, '2026-11-20 18:46:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(249, 78, 39, 15, 'Efectivo', 12000.00, '2026-11-03 13:39:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(250, 78, 40, 15, 'Débito', 4440.00, '2026-11-20 09:14:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(251, 78, 48, 15, 'Transferencia', 15000.00, '2026-11-02 14:32:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(252, 78, 49, 15, 'Transferencia', 6450.00, '2026-11-20 15:29:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(253, 79, 39, 16, 'Transferencia', 4320.00, '2026-11-01 13:19:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(254, 79, 41, 16, 'Débito', 6000.00, '2026-11-20 11:39:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(255, 79, 41, 16, 'Débito', 6000.00, '2026-11-20 17:51:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(256, 79, 42, 16, 'Débito', 6475.00, '2026-11-05 11:36:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(257, 79, 44, 16, 'Débito', 9250.00, '2026-11-20 16:37:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(258, 79, 44, 16, 'Transferencia', 9250.00, '2026-11-20 18:33:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(259, 80, 40, 17, 'Efectivo', 12000.00, '2026-11-20 10:22:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(260, 80, 41, 17, 'Efectivo', 12000.00, '2026-11-20 16:22:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(261, 80, 43, 17, 'Efectivo', 18500.00, '2026-11-20 18:58:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(262, 80, 44, 17, 'Débito', 18500.00, '2026-11-20 12:05:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(263, 81, 39, 18, 'Efectivo', 12000.00, '2026-11-03 11:44:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(264, 81, 40, 18, 'Efectivo', 12000.00, '2026-11-20 12:42:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(265, 81, 41, 18, 'Débito', 5520.00, '2026-11-20 09:13:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(266, 81, 42, 18, 'Efectivo', 18500.00, '2026-11-04 10:20:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(267, 81, 43, 18, 'Efectivo', 18500.00, '2026-11-20 16:52:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(268, 81, 44, 18, 'Débito', 11840.00, '2026-11-20 10:34:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(269, 82, 39, 15, 'Efectivo', 6000.00, '2026-11-05 15:38:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(270, 82, 39, 15, 'Efectivo', 6000.00, '2026-11-04 12:44:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(271, 82, 40, 15, 'Débito', 7920.00, '2026-11-20 09:56:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(272, 82, 45, 15, 'Transferencia', 8250.00, '2026-11-03 12:28:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(273, 82, 45, 15, 'Débito', 8250.00, '2026-11-03 14:13:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(274, 82, 46, 15, 'Débito', 6600.00, '2026-11-20 15:10:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(275, 83, 39, 16, 'Transferencia', 8280.00, '2026-11-02 16:10:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(276, 83, 41, 16, 'Débito', 12000.00, '2026-11-20 11:35:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(277, 83, 42, 16, 'Transferencia', 8880.00, '2026-11-05 12:15:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(278, 83, 44, 16, 'Transferencia', 18500.00, '2026-11-20 11:03:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(279, 84, 40, 17, 'Efectivo', 12000.00, '2026-11-20 14:38:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(280, 84, 41, 17, 'Débito', 12000.00, '2026-11-20 16:31:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(281, 84, 43, 17, 'Transferencia', 18500.00, '2026-11-20 18:20:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(282, 84, 44, 17, 'Transferencia', 18500.00, '2026-11-20 17:13:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(283, 84, 46, 17, 'Efectivo', 16500.00, '2026-11-20 12:02:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(284, 84, 47, 17, 'Efectivo', 16500.00, '2026-11-20 15:08:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(285, 85, 39, 18, 'Débito', 6000.00, '2026-11-01 15:30:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(286, 85, 39, 18, 'Transferencia', 6000.00, '2026-11-07 11:53:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(287, 85, 40, 18, 'Efectivo', 6000.00, '2026-11-20 17:31:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(288, 85, 40, 18, 'Efectivo', 6000.00, '2026-11-20 10:07:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(289, 85, 41, 18, 'Efectivo', 7200.00, '2026-11-20 16:33:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(290, 85, 42, 18, 'Efectivo', 9250.00, '2026-11-03 09:20:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(291, 85, 42, 18, 'Transferencia', 9250.00, '2026-11-05 10:50:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(292, 85, 43, 18, 'Débito', 9250.00, '2026-11-20 13:33:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(293, 85, 43, 18, 'Transferencia', 9250.00, '2026-11-20 18:38:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(294, 85, 44, 18, 'Débito', 11655.00, '2026-11-20 18:42:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(295, 86, 39, 15, 'Transferencia', 12000.00, '2026-11-05 13:31:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(296, 86, 40, 15, 'Transferencia', 6000.00, '2026-11-20 16:00:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(297, 86, 45, 15, 'Débito', 16500.00, '2026-11-03 12:41:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(298, 86, 46, 15, 'Transferencia', 6435.00, '2026-11-20 15:01:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(299, 87, 39, 16, 'Efectivo', 7080.00, '2026-11-03 11:46:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(300, 87, 41, 16, 'Transferencia', 12000.00, '2026-11-20 13:31:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(301, 87, 45, 16, 'Transferencia', 10230.00, '2026-11-01 18:50:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(302, 87, 47, 16, 'Transferencia', 16500.00, '2026-11-20 13:32:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(303, 88, 40, 17, 'Transferencia', 6000.00, '2026-11-20 09:23:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(304, 88, 40, 17, 'Débito', 6000.00, '2026-11-20 13:00:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(305, 88, 41, 17, 'Transferencia', 6000.00, '2026-11-20 12:40:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(306, 88, 41, 17, 'Efectivo', 6000.00, '2026-11-20 17:34:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(307, 88, 46, 17, 'Efectivo', 8250.00, '2026-11-20 12:09:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(308, 88, 46, 17, 'Débito', 8250.00, '2026-11-20 16:58:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(309, 88, 47, 17, 'Efectivo', 8250.00, '2026-11-20 13:00:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(310, 88, 47, 17, 'Efectivo', 8250.00, '2026-11-20 18:16:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(311, 89, 39, 18, 'Transferencia', 12000.00, '2026-11-05 10:11:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(312, 89, 40, 18, 'Efectivo', 12000.00, '2026-11-20 17:48:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(313, 89, 41, 18, 'Transferencia', 7080.00, '2026-11-20 11:10:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(314, 89, 42, 18, 'Transferencia', 18500.00, '2026-11-01 14:17:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(315, 89, 43, 18, 'Débito', 18500.00, '2026-11-20 12:51:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(316, 89, 44, 18, 'Efectivo', 11285.00, '2026-11-20 12:13:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(317, 90, 39, 15, 'Transferencia', 12000.00, '2026-11-02 16:58:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(318, 90, 40, 15, 'Débito', 7680.00, '2026-11-20 17:49:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(319, 90, 48, 15, 'Débito', 15000.00, '2026-11-05 14:52:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(320, 90, 49, 15, 'Efectivo', 7650.00, '2026-11-20 14:09:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(321, 91, 39, 16, 'Débito', 7080.00, '2026-11-04 16:45:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(322, 91, 41, 16, 'Efectivo', 6000.00, '2026-11-20 12:02:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(323, 91, 41, 16, 'Transferencia', 6000.00, '2026-11-20 17:48:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(324, 91, 42, 16, 'Débito', 9435.00, '2026-11-04 11:15:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(325, 91, 44, 16, 'Transferencia', 9250.00, '2026-11-20 16:32:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(326, 91, 44, 16, 'Efectivo', 9250.00, '2026-11-20 10:15:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(327, 91, 48, 16, 'Efectivo', 6300.00, '2026-11-02 17:48:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(328, 91, 50, 16, 'Débito', 7500.00, '2026-11-20 14:25:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(329, 91, 50, 16, 'Débito', 7500.00, '2026-11-20 17:43:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(330, 92, 40, 17, 'Transferencia', 12000.00, '2026-11-20 11:52:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(331, 92, 41, 17, 'Efectivo', 12000.00, '2026-11-20 11:32:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(332, 92, 49, 17, 'Débito', 15000.00, '2026-11-20 17:22:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(333, 92, 50, 17, 'Efectivo', 15000.00, '2026-11-20 16:02:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(334, 93, 39, 18, 'Efectivo', 12000.00, '2026-11-04 09:32:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(335, 93, 40, 18, 'Efectivo', 12000.00, '2026-11-20 09:02:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(336, 93, 41, 18, 'Transferencia', 7680.00, '2026-11-20 11:48:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(337, 93, 42, 18, 'Efectivo', 18500.00, '2026-11-03 16:16:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(338, 93, 43, 18, 'Transferencia', 18500.00, '2026-11-20 13:22:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(339, 93, 44, 18, 'Efectivo', 7770.00, '2026-11-20 11:05:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(340, 94, 39, 15, 'Efectivo', 6000.00, '2026-11-05 14:15:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(341, 94, 39, 15, 'Efectivo', 6000.00, '2026-11-03 18:14:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(342, 94, 40, 15, 'Débito', 6720.00, '2026-11-20 11:30:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(343, 94, 45, 15, 'Transferencia', 8250.00, '2026-11-04 17:18:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(344, 94, 45, 15, 'Transferencia', 8250.00, '2026-11-05 16:27:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(345, 94, 46, 15, 'Efectivo', 5940.00, '2026-11-20 18:28:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(346, 95, 39, 16, 'Débito', 7560.00, '2026-11-01 14:35:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(347, 95, 41, 16, 'Débito', 12000.00, '2026-11-20 17:16:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(348, 95, 42, 16, 'Efectivo', 7030.00, '2026-11-04 17:06:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(349, 95, 44, 16, 'Efectivo', 18500.00, '2026-11-20 11:25:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(350, 96, 40, 17, 'Efectivo', 12000.00, '2026-11-20 12:44:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(351, 96, 41, 17, 'Débito', 12000.00, '2026-11-20 11:28:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(352, 96, 43, 17, 'Efectivo', 18500.00, '2026-11-20 12:42:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(353, 96, 44, 17, 'Efectivo', 18500.00, '2026-11-20 11:06:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 21),
(354, 97, 39, 18, 'Transferencia', 6000.00, '2026-11-04 16:53:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(355, 97, 39, 18, 'Débito', 6000.00, '2026-11-06 18:34:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(356, 97, 40, 18, 'Débito', 6000.00, '2026-11-20 12:59:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(357, 97, 40, 18, 'Efectivo', 6000.00, '2026-11-20 12:31:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(358, 97, 41, 18, 'Transferencia', 7680.00, '2026-11-20 15:04:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(359, 97, 45, 18, 'Transferencia', 8250.00, '2026-11-05 16:22:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(360, 97, 45, 18, 'Efectivo', 8250.00, '2026-11-05 16:16:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(361, 97, 46, 18, 'Débito', 8250.00, '2026-11-20 14:06:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(362, 97, 46, 18, 'Efectivo', 8250.00, '2026-11-20 10:31:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(363, 97, 47, 18, 'Efectivo', 5940.00, '2026-11-20 16:26:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 22),
(364, 98, 39, 15, 'Transferencia', 12000.00, '2026-11-02 13:15:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(365, 98, 40, 15, 'Efectivo', 5640.00, '2026-11-20 15:28:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(366, 98, 42, 15, 'Efectivo', 18500.00, '2026-11-02 17:36:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(367, 98, 43, 15, 'Débito', 12580.00, '2026-11-20 16:45:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(368, 98, 48, 15, 'Transferencia', 15000.00, '2026-11-02 11:33:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(369, 98, 49, 15, 'Transferencia', 7350.00, '2026-11-20 13:06:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 19),
(370, 99, 39, 16, 'Transferencia', 8040.00, '2026-11-05 14:52:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(371, 99, 41, 16, 'Transferencia', 12000.00, '2026-11-20 15:14:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(372, 99, 42, 16, 'Efectivo', 6475.00, '2026-11-02 13:28:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(373, 99, 44, 16, 'Efectivo', 18500.00, '2026-11-20 10:14:00', '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 20),
(374, 83, 39, 16, 'Efectivo', 12000.00, '2026-11-20 00:00:00', '2026-11-20 16:08:21', '2026-11-20 16:08:21', NULL, 20),
(375, 72, 39, 17, 'Efectivo', 12000.00, '2026-11-20 00:00:00', '2026-11-20 17:29:01', '2026-11-20 17:29:01', NULL, 21),
(376, 71, 39, 16, 'Efectivo', 12000.00, '2026-11-20 00:00:00', '2026-11-20 17:33:40', '2026-11-20 17:33:40', NULL, 20),
(377, 75, 39, 16, 'Efectivo', 12000.00, '2026-11-20 00:00:00', '2026-11-20 17:40:07', '2026-11-20 17:40:07', NULL, 20),
(378, 76, 39, 17, 'Efectivo', 12000.00, '2026-11-20 00:00:00', '2026-11-20 17:40:42', '2026-11-20 17:40:42', NULL, 21),
(379, 70, 40, 19, 'Efectivo', 12000.00, '2026-11-20 00:00:00', '2026-11-20 17:48:30', '2026-11-20 17:48:30', NULL, 23);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes`
--

CREATE TABLE `planes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `monto` decimal(12,2) NOT NULL DEFAULT 0.00,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_edicion` datetime DEFAULT NULL,
  `fecha_borrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `planes`
--

INSERT INTO `planes` (`id`, `nombre`, `descripcion`, `monto`, `activo`, `fecha_alta`, `fecha_edicion`, `fecha_borrado`) VALUES
(13, 'Cuota social', 'Abono social mensual.', 12000.00, 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL),
(14, 'Fútbol', 'Actividad deportiva de fútbol.', 18500.00, 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL),
(15, 'Básquet', 'Actividad deportiva de básquet.', 16500.00, 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL),
(16, 'Vóley', 'Actividad deportiva de vóley.', 15000.00, 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recaudadores`
--

CREATE TABLE `recaudadores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `dni` varchar(30) NOT NULL,
  `id_caja` int(11) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_alta` datetime DEFAULT current_timestamp(),
  `fecha_edicion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_borrado` datetime DEFAULT NULL,
  `telefono` varchar(30) NOT NULL,
  `id_zona` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recaudadores`
--

INSERT INTO `recaudadores` (`id`, `nombre`, `apellido`, `dni`, `id_caja`, `activo`, `fecha_alta`, `fecha_edicion`, `fecha_borrado`, `telefono`, `id_zona`) VALUES
(19, 'María', 'Demo', 'DEMO-R-0001', 15, 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, '000-000-0001', 15),
(20, 'Diego', 'Muestra', 'DEMO-R-0002', 16, 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, '000-000-0002', 16),
(21, 'Lucía', 'Prueba', 'DEMO-R-0003', 17, 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, '000-000-0003', 17),
(22, 'Tomás', 'Ficticio', 'DEMO-R-0004', 18, 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, '000-000-0004', 18),
(23, 'Gonzalo', 'Arreseygor', '455684123', 19, 1, '2026-11-20 17:31:30', '2026-11-20 17:31:30', NULL, '03436472886', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `es_administrador` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `es_administrador`) VALUES
(14, 'Administrador', 1),
(15, 'Tesorería', 0),
(16, 'Consulta', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_acciones`
--

CREATE TABLE `roles_acciones` (
  `rol_id` int(10) UNSIGNED NOT NULL,
  `modulo` varchar(40) NOT NULL,
  `accion` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `roles_acciones`
--

INSERT INTO `roles_acciones` (`rol_id`, `modulo`, `accion`) VALUES
(14, 'cajas', 'crear'),
(14, 'cajas', 'editar'),
(14, 'cajas', 'eliminar'),
(14, 'cajas', 'ver'),
(14, 'disciplinas', 'crear'),
(14, 'disciplinas', 'editar'),
(14, 'disciplinas', 'eliminar'),
(14, 'disciplinas', 'ver'),
(14, 'liquidaciones', 'crear'),
(14, 'liquidaciones', 'editar'),
(14, 'liquidaciones', 'eliminar'),
(14, 'liquidaciones', 'ver'),
(14, 'pagos', 'crear'),
(14, 'pagos', 'editar'),
(14, 'pagos', 'eliminar'),
(14, 'pagos', 'ver'),
(14, 'planes', 'crear'),
(14, 'planes', 'editar'),
(14, 'planes', 'eliminar'),
(14, 'planes', 'ver'),
(14, 'recaudadores', 'crear'),
(14, 'recaudadores', 'editar'),
(14, 'recaudadores', 'eliminar'),
(14, 'recaudadores', 'ver'),
(14, 'socios', 'crear'),
(14, 'socios', 'editar'),
(14, 'socios', 'eliminar'),
(14, 'socios', 'ver'),
(14, 'usuarios', 'crear'),
(14, 'usuarios', 'editar'),
(14, 'usuarios', 'eliminar'),
(14, 'usuarios', 'ver'),
(14, 'zonas', 'crear'),
(14, 'zonas', 'editar'),
(14, 'zonas', 'eliminar'),
(14, 'zonas', 'ver'),
(15, 'cajas', 'crear'),
(15, 'cajas', 'editar'),
(15, 'cajas', 'ver'),
(15, 'disciplinas', 'ver'),
(15, 'liquidaciones', 'ver'),
(15, 'pagos', 'crear'),
(15, 'pagos', 'ver'),
(15, 'planes', 'ver'),
(15, 'recaudadores', 'ver'),
(15, 'socios', 'ver'),
(15, 'zonas', 'ver'),
(16, 'cajas', 'ver'),
(16, 'disciplinas', 'ver'),
(16, 'liquidaciones', 'ver'),
(16, 'pagos', 'ver'),
(16, 'planes', 'ver'),
(16, 'recaudadores', 'ver'),
(16, 'socios', 'ver'),
(16, 'usuarios', 'ver'),
(16, 'zonas', 'ver');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_modulos`
--

CREATE TABLE `roles_modulos` (
  `rol_id` int(10) UNSIGNED NOT NULL,
  `modulo` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `roles_modulos`
--

INSERT INTO `roles_modulos` (`rol_id`, `modulo`) VALUES
(14, 'cajas'),
(14, 'disciplinas'),
(14, 'liquidaciones'),
(14, 'pagos'),
(14, 'planes'),
(14, 'recaudadores'),
(14, 'socios'),
(14, 'usuarios'),
(14, 'zonas'),
(15, 'cajas'),
(15, 'disciplinas'),
(15, 'liquidaciones'),
(15, 'pagos'),
(15, 'planes'),
(15, 'recaudadores'),
(15, 'socios'),
(15, 'zonas'),
(16, 'cajas'),
(16, 'disciplinas'),
(16, 'liquidaciones'),
(16, 'pagos'),
(16, 'planes'),
(16, 'recaudadores'),
(16, 'socios'),
(16, 'usuarios'),
(16, 'zonas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `socios`
--

CREATE TABLE `socios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `id_plan` int(11) DEFAULT NULL,
  `domicilio` varchar(80) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `fecha_nac` date DEFAULT curdate(),
  `dni` varchar(30) NOT NULL,
  `email` varchar(80) DEFAULT NULL,
  `id_zona` int(11) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_alta` datetime DEFAULT current_timestamp(),
  `fecha_edicion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_borrado` datetime DEFAULT NULL,
  `id_tutor` int(11) DEFAULT NULL,
  `id_recaudador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `socios`
--

INSERT INTO `socios` (`id`, `nombre`, `apellido`, `id_plan`, `domicilio`, `telefono`, `fecha_nac`, `dni`, `email`, `id_zona`, `activo`, `fecha_alta`, `fecha_edicion`, `fecha_borrado`, `id_tutor`, `id_recaudador`) VALUES
(70, 'Joaquín', 'Rojas', 13, 'Calle de muestra 383', '000-200-0001', '1998-07-23', 'DEMO-S-00001', 'socio01@example.invalid', 15, 1, '2025-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 19),
(71, 'Thiago', 'Torres', 13, 'Calle de muestra 313', '000-200-0002', '2015-06-30', 'DEMO-S-00002', 'socio02@example.invalid', 16, 1, '2022-11-20 00:00:00', '2026-11-20 00:28:30', NULL, 24, 20),
(72, 'Bruno', 'Martínez', 13, 'Calle de muestra 922', '000-200-0003', '1992-07-10', 'DEMO-S-00003', 'socio03@example.invalid', 17, 1, '2024-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 21),
(73, 'Camila', 'Gómez', 13, 'Calle de muestra 428', '000-200-0004', '1985-11-05', 'DEMO-S-00004', 'socio04@example.invalid', 18, 1, '2025-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 22),
(74, 'Sofía', 'Acosta', 13, 'Calle de muestra 899', '000-200-0005', '1993-10-14', 'DEMO-S-00005', 'socio05@example.invalid', 15, 1, '2025-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 19),
(75, 'Lucía', 'Molina', 13, 'Calle de muestra 743', '000-200-0006', '1994-11-03', 'DEMO-S-00006', 'socio06@example.invalid', 16, 1, '2022-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 20),
(76, 'Mateo', 'Gómez', 13, 'Calle de muestra 444', '000-200-0007', '2008-01-20', 'DEMO-S-00007', 'socio07@example.invalid', 17, 1, '2021-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 21),
(77, 'Benjamín', 'Pérez', 13, 'Calle de muestra 521', '000-200-0008', '1982-07-21', 'DEMO-S-00008', 'socio08@example.invalid', 18, 1, '2024-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 22),
(78, 'Sofía', 'Acosta', 13, 'Calle de muestra 475', '000-200-0009', '1988-04-14', 'DEMO-S-00009', 'socio09@example.invalid', 15, 1, '2022-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 19),
(79, 'Emma', 'Molina', 13, 'Calle de muestra 315', '000-200-0010', '1972-02-06', 'DEMO-S-00010', 'socio10@example.invalid', 16, 1, '2024-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 20),
(80, 'Renata', 'Navarro', 13, 'Calle de muestra 884', '000-200-0011', '2014-07-21', 'DEMO-S-00011', 'socio11@example.invalid', 17, 1, '2022-11-20 00:00:00', '2026-11-20 00:28:30', NULL, 25, 21),
(81, 'Martina', 'Pérez', 13, 'Calle de muestra 99', '000-200-0012', '1988-05-09', 'DEMO-S-00012', 'socio12@example.invalid', 18, 1, '2021-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 22),
(82, 'Mora', 'Romero', 13, 'Calle de muestra 845', '000-200-0013', '1974-05-16', 'DEMO-S-00013', 'socio13@example.invalid', 15, 1, '2023-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 19),
(83, 'Felipe', 'Castro', 13, 'Calle de muestra 236', '000-200-0014', '1982-02-07', 'DEMO-S-00014', 'socio14@example.invalid', 16, 1, '2023-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 20),
(84, 'Renata', 'López', 13, 'Calle de muestra 905', '000-200-0015', '1986-09-30', 'DEMO-S-00015', 'socio15@example.invalid', 17, 1, '2024-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 21),
(85, 'Lucía', 'Díaz', 13, 'Calle de muestra 233', '000-200-0016', '2015-09-26', 'DEMO-S-00016', 'socio16@example.invalid', 18, 1, '2025-11-20 00:00:00', '2026-11-20 00:28:30', NULL, 30, 22),
(86, 'Lautaro', 'Navarro', 13, 'Calle de muestra 559', '000-200-0017', '1982-09-29', 'DEMO-S-00017', 'socio17@example.invalid', 15, 1, '2022-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 19),
(87, 'Lautaro', 'Pérez', 13, 'Calle de muestra 570', '000-200-0018', '1970-05-27', 'DEMO-S-00018', 'socio18@example.invalid', 16, 1, '2025-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 20),
(88, 'Lucía', 'Díaz', 13, 'Calle de muestra 718', '000-200-0019', '1973-03-31', 'DEMO-S-00019', 'socio19@example.invalid', 17, 1, '2022-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 21),
(89, 'Emma', 'Gómez', 13, 'Calle de muestra 890', '000-200-0020', '1999-08-29', 'DEMO-S-00020', 'socio20@example.invalid', 18, 1, '2023-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 22),
(90, 'Benjamín', 'Alonso', 13, 'Calle de muestra 968', '000-200-0021', '1979-04-06', 'DEMO-S-00021', 'socio21@example.invalid', 15, 1, '2024-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 19),
(91, 'Renata', 'Molina', 13, 'Calle de muestra 911', '000-200-0022', '2018-02-02', 'DEMO-S-00022', 'socio22@example.invalid', 16, 1, '2023-11-20 00:00:00', '2026-11-20 00:28:30', NULL, 28, 20),
(92, 'Lucía', 'Castro', 13, 'Calle de muestra 712', '000-200-0023', '1968-05-01', 'DEMO-S-00023', 'socio23@example.invalid', 17, 1, '2022-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 21),
(93, 'Mateo', 'Romero', 13, 'Calle de muestra 516', '000-200-0024', '1989-11-16', 'DEMO-S-00024', 'socio24@example.invalid', 18, 1, '2022-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 22),
(94, 'Felipe', 'Martínez', 13, 'Calle de muestra 756', '000-200-0025', '1981-04-08', 'DEMO-S-00025', 'socio25@example.invalid', 15, 1, '2022-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 19),
(95, 'Martina', 'Fernández', 13, 'Calle de muestra 444', '000-200-0026', '1993-02-06', 'DEMO-S-00026', 'socio26@example.invalid', 16, 1, '2022-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 20),
(96, 'Thiago', 'Rojas', 13, 'Calle de muestra 913', '000-200-0027', '1993-04-20', 'DEMO-S-00027', 'socio27@example.invalid', 17, 1, '2021-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 21),
(97, 'Thiago', 'Torres', 13, 'Calle de muestra 544', '000-200-0028', '2017-09-07', 'DEMO-S-00028', 'socio28@example.invalid', 18, 1, '2025-11-20 00:00:00', '2026-11-20 00:28:30', NULL, 26, 22),
(98, 'Thiago', 'Gómez', 13, 'Calle de muestra 94', '000-200-0029', '2003-11-17', 'DEMO-S-00029', 'socio29@example.invalid', 15, 1, '2024-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 19),
(99, 'Valentina', 'Pérez', 13, 'Calle de muestra 575', '000-200-0030', '1988-06-18', 'DEMO-S-00030', 'socio30@example.invalid', 16, 1, '2023-11-20 00:00:00', '2026-11-20 00:28:30', NULL, NULL, 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `socios_disciplinas`
--

CREATE TABLE `socios_disciplinas` (
  `id` int(11) NOT NULL,
  `id_socio` int(11) NOT NULL,
  `id_disciplina` int(11) NOT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_borrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `socios_disciplinas`
--

INSERT INTO `socios_disciplinas` (`id`, `id_socio`, `id_disciplina`, `fecha_alta`, `fecha_borrado`) VALUES
(46, 70, 12, '2026-11-20 00:28:30', NULL),
(47, 70, 13, '2026-11-20 00:28:30', NULL),
(48, 71, 13, '2026-11-20 00:28:30', NULL),
(49, 72, 12, '2026-11-20 00:28:30', NULL),
(50, 73, 13, '2026-11-20 00:28:30', NULL),
(51, 74, 13, '2026-11-20 00:28:30', NULL),
(52, 75, 12, '2026-11-20 00:28:30', NULL),
(53, 76, 13, '2026-11-20 00:28:30', NULL),
(54, 77, 12, '2026-11-20 00:28:30', NULL),
(55, 77, 13, '2026-11-20 00:28:30', NULL),
(56, 78, 14, '2026-11-20 00:28:30', NULL),
(57, 79, 12, '2026-11-20 00:28:30', NULL),
(58, 80, 12, '2026-11-20 00:28:30', NULL),
(59, 81, 12, '2026-11-20 00:28:30', NULL),
(60, 82, 13, '2026-11-20 00:28:30', NULL),
(61, 83, 12, '2026-11-20 00:28:30', NULL),
(62, 84, 12, '2026-11-20 00:28:30', NULL),
(63, 84, 13, '2026-11-20 00:28:30', NULL),
(64, 85, 12, '2026-11-20 00:28:30', NULL),
(65, 86, 13, '2026-11-20 00:28:30', NULL),
(66, 87, 13, '2026-11-20 00:28:30', NULL),
(67, 88, 13, '2026-11-20 00:28:30', NULL),
(68, 89, 12, '2026-11-20 00:28:30', NULL),
(69, 90, 14, '2026-11-20 00:28:30', NULL),
(70, 91, 14, '2026-11-20 00:28:30', NULL),
(71, 91, 12, '2026-11-20 00:28:30', NULL),
(72, 92, 14, '2026-11-20 00:28:30', NULL),
(73, 93, 12, '2026-11-20 00:28:30', NULL),
(74, 94, 13, '2026-11-20 00:28:30', NULL),
(75, 95, 12, '2026-11-20 00:28:30', NULL),
(76, 96, 12, '2026-11-20 00:28:30', NULL),
(77, 97, 13, '2026-11-20 00:28:30', NULL),
(78, 98, 14, '2026-11-20 00:28:30', NULL),
(79, 98, 12, '2026-11-20 00:28:30', NULL),
(80, 99, 12, '2026-11-20 00:28:30', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `socios_planes`
--

CREATE TABLE `socios_planes` (
  `id` int(11) NOT NULL,
  `id_socio` int(11) NOT NULL,
  `id_plan` int(11) NOT NULL,
  `fecha_borrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `socios_planes`
--

INSERT INTO `socios_planes` (`id`, `id_socio`, `id_plan`, `fecha_borrado`) VALUES
(167, 70, 13, NULL),
(168, 70, 14, NULL),
(169, 70, 15, NULL),
(170, 71, 13, NULL),
(171, 71, 15, NULL),
(172, 72, 13, NULL),
(173, 72, 14, NULL),
(174, 73, 13, NULL),
(175, 73, 15, NULL),
(176, 74, 13, NULL),
(177, 74, 15, NULL),
(178, 75, 13, NULL),
(179, 75, 14, NULL),
(180, 76, 13, NULL),
(181, 76, 15, NULL),
(182, 77, 13, NULL),
(183, 77, 14, NULL),
(184, 77, 15, NULL),
(185, 78, 13, NULL),
(186, 78, 16, NULL),
(187, 79, 13, NULL),
(188, 79, 14, NULL),
(189, 80, 13, NULL),
(190, 80, 14, NULL),
(191, 81, 13, NULL),
(192, 81, 14, NULL),
(193, 82, 13, NULL),
(194, 82, 15, NULL),
(195, 83, 13, NULL),
(196, 83, 14, NULL),
(197, 84, 13, NULL),
(198, 84, 14, NULL),
(199, 84, 15, NULL),
(200, 85, 13, NULL),
(201, 85, 14, NULL),
(202, 86, 13, NULL),
(203, 86, 15, NULL),
(204, 87, 13, NULL),
(205, 87, 15, NULL),
(206, 88, 13, NULL),
(207, 88, 15, NULL),
(208, 89, 13, NULL),
(209, 89, 14, NULL),
(210, 90, 13, NULL),
(211, 90, 16, NULL),
(212, 91, 13, NULL),
(213, 91, 16, NULL),
(214, 91, 14, NULL),
(215, 92, 13, NULL),
(216, 92, 16, NULL),
(217, 93, 13, NULL),
(218, 93, 14, NULL),
(219, 94, 13, NULL),
(220, 94, 15, NULL),
(221, 95, 13, NULL),
(222, 95, 14, NULL),
(223, 96, 13, NULL),
(224, 96, 14, NULL),
(225, 97, 13, NULL),
(226, 97, 15, NULL),
(227, 98, 13, NULL),
(228, 98, 16, NULL),
(229, 98, 14, NULL),
(230, 99, 13, NULL),
(231, 99, 14, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `socios_tutores`
--

CREATE TABLE `socios_tutores` (
  `id` int(11) NOT NULL,
  `id_socio` int(11) NOT NULL,
  `id_tutor` int(11) NOT NULL,
  `fecha_borrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `socios_tutores`
--

INSERT INTO `socios_tutores` (`id`, `id_socio`, `id_tutor`, `fecha_borrado`) VALUES
(10, 71, 24, NULL),
(11, 80, 25, NULL),
(12, 85, 30, NULL),
(13, 91, 28, NULL),
(14, 97, 26, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tutores`
--

CREATE TABLE `tutores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `dni` varchar(30) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `email` varchar(80) DEFAULT NULL,
  `parentesco` varchar(40) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_edicion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tutores`
--

INSERT INTO `tutores` (`id`, `nombre`, `apellido`, `dni`, `telefono`, `email`, `parentesco`, `fecha_alta`, `fecha_edicion`) VALUES
(23, 'Carolina', 'Demo', 'DEMO-T-0001', '000-100-0001', 'tutor01@example.invalid', 'Madre', '2026-11-20 00:28:30', '2026-11-20 00:28:30'),
(24, 'Pablo', 'Muestra', 'DEMO-T-0002', '000-100-0002', 'tutor02@example.invalid', 'Padre', '2026-11-20 00:28:30', '2026-11-20 00:28:30'),
(25, 'Andrea', 'Ejemplo', 'DEMO-T-0003', '000-100-0003', 'tutor03@example.invalid', 'Madre', '2026-11-20 00:28:30', '2026-11-20 00:28:30'),
(26, 'Sergio', 'Ficticio', 'DEMO-T-0004', '000-100-0004', 'tutor04@example.invalid', 'Padre', '2026-11-20 00:28:30', '2026-11-20 00:28:30'),
(27, 'Natalia', 'Prueba', 'DEMO-T-0005', '000-100-0005', 'tutor05@example.invalid', 'Madre', '2026-11-20 00:28:30', '2026-11-20 00:28:30'),
(28, 'Hugo', 'Simulado', 'DEMO-T-0006', '000-100-0006', 'tutor06@example.invalid', 'Padre', '2026-11-20 00:28:30', '2026-11-20 00:28:30'),
(29, 'Mónica', 'Modelo', 'DEMO-T-0007', '000-100-0007', 'tutor07@example.invalid', 'Madre', '2026-11-20 00:28:30', '2026-11-20 00:28:30'),
(30, 'Raúl', 'Inventado', 'DEMO-T-0008', '000-100-0008', 'tutor08@example.invalid', 'Padre', '2026-11-20 00:28:30', '2026-11-20 00:28:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `username` varchar(80) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `dni` varchar(30) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_alta` datetime DEFAULT current_timestamp(),
  `fecha_edicion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_borrado` datetime DEFAULT NULL,
  `rol_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `username`, `clave`, `dni`, `activo`, `fecha_alta`, `fecha_edicion`, `fecha_borrado`, `rol_id`) VALUES
(14, 'Admin', 'Demo', 'demo_admin', '$2y$10$TZHrOmjGIl.PNlPP/0IAPunud1pb3YrOGdn3ruccR2/t7PkUj4Eqi', 'DEMO-U-001', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 14),
(15, 'Tesorería', 'Demo', 'demo_tesoreria', '$2y$10$TZHrOmjGIl.PNlPP/0IAPunud1pb3YrOGdn3ruccR2/t7PkUj4Eqi', 'DEMO-U-002', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 15),
(16, 'Consulta', 'Demo', 'demo_consulta', '$2y$10$TZHrOmjGIl.PNlPP/0IAPunud1pb3YrOGdn3ruccR2/t7PkUj4Eqi', 'DEMO-U-003', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL, 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zonas`
--

CREATE TABLE `zonas` (
  `id` int(11) NOT NULL,
  `denominacion` varchar(80) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_alta` datetime DEFAULT current_timestamp(),
  `fecha_edicion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_borrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `zonas`
--

INSERT INTO `zonas` (`id`, `denominacion`, `activo`, `fecha_alta`, `fecha_edicion`, `fecha_borrado`) VALUES
(15, 'Centro', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL),
(16, 'Norte', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL),
(17, 'Sur', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL),
(18, 'Sede deportiva', 1, '2026-11-20 00:28:30', '2026-11-20 00:28:30', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `disciplinas`
--
ALTER TABLE `disciplinas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gastos_caja`
--
ALTER TABLE `gastos_caja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_caja_fecha_gasto` (`id_caja`,`fecha_gasto`),
  ADD KEY `fecha_anulacion_fecha_gasto` (`fecha_anulacion`,`fecha_gasto`);

--
-- Indices de la tabla `liquidaciones`
--
ALTER TABLE `liquidaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `liquidaciones_planes`
--
ALTER TABLE `liquidaciones_planes`
  ADD PRIMARY KEY (`id_liquidacion`,`id_plan`),
  ADD KEY `id_plan` (`id_plan`);

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
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `planes`
--
ALTER TABLE `planes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recaudadores`
--
ALTER TABLE `recaudadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_caja` (`id_caja`),
  ADD KEY `recaudadores_zonas_fk` (`id_zona`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `roles_acciones`
--
ALTER TABLE `roles_acciones`
  ADD PRIMARY KEY (`rol_id`,`modulo`,`accion`);

--
-- Indices de la tabla `roles_modulos`
--
ALTER TABLE `roles_modulos`
  ADD PRIMARY KEY (`rol_id`,`modulo`);

--
-- Indices de la tabla `socios`
--
ALTER TABLE `socios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_zona` (`id_zona`);

--
-- Indices de la tabla `socios_disciplinas`
--
ALTER TABLE `socios_disciplinas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_socio_id_disciplina` (`id_socio`,`id_disciplina`),
  ADD KEY `id_disciplina` (`id_disciplina`);

--
-- Indices de la tabla `socios_planes`
--
ALTER TABLE `socios_planes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `socios_tutores`
--
ALTER TABLE `socios_tutores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_socio_id_tutor` (`id_socio`,`id_tutor`),
  ADD KEY `id_tutor` (`id_tutor`);

--
-- Indices de la tabla `tutores`
--
ALTER TABLE `tutores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `zonas`
--
ALTER TABLE `zonas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `gastos_caja`
--
ALTER TABLE `gastos_caja`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `liquidaciones`
--
ALTER TABLE `liquidaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `movimientos_caja`
--
ALTER TABLE `movimientos_caja`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=379;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=380;

--
-- AUTO_INCREMENT de la tabla `planes`
--
ALTER TABLE `planes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `recaudadores`
--
ALTER TABLE `recaudadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `socios`
--
ALTER TABLE `socios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT de la tabla `socios_disciplinas`
--
ALTER TABLE `socios_disciplinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de la tabla `socios_planes`
--
ALTER TABLE `socios_planes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- AUTO_INCREMENT de la tabla `socios_tutores`
--
ALTER TABLE `socios_tutores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `tutores`
--
ALTER TABLE `tutores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `zonas`
--
ALTER TABLE `zonas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `recaudadores`
--
ALTER TABLE `recaudadores`
  ADD CONSTRAINT `recaudadores_ibfk_1` FOREIGN KEY (`id_caja`) REFERENCES `cajas` (`id`),
  ADD CONSTRAINT `recaudadores_zonas_fk` FOREIGN KEY (`id_zona`) REFERENCES `zonas` (`id`),
  ADD CONSTRAINT `recuadadores_zonas_fk` FOREIGN KEY (`id_zona`) REFERENCES `zonas` (`id`);

--
-- Filtros para la tabla `socios`
--
ALTER TABLE `socios`
  ADD CONSTRAINT `socios_ibfk_1` FOREIGN KEY (`id_zona`) REFERENCES `zonas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
