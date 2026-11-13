-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-11-2026 a las 23:49:14
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
(1, 'Caja Central', 'Caja central del club', 'CENTRAL-001', 1, '2026-06-29 20:54:58', '2026-10-26 20:33:23', '2026-10-26 20:33:23'),
(2, 'cobrador Julio', 'caja cobrador julio - barrio abadia', 'caja Julio', 1, '2026-08-03 19:25:00', '2026-10-26 20:33:21', '2026-10-26 20:33:21'),
(3, 'caja septiembre', 'cobrador septiembre', '001001011100', 1, '2026-09-07 20:46:40', '2026-10-26 20:33:18', '2026-10-26 20:33:18'),
(5, 'Gonzalo Arreseygor', 'Caja personal del recaudador', 'REC-4d1d52a193c9dd7c', 1, '2026-10-26 20:37:05', '2026-10-26 20:37:37', '2026-10-26 20:37:37'),
(6, 'Gonzalo Arreseygor', 'Caja personal del recaudador', 'REC-e2980a6477b91f56', 1, '2026-10-26 20:39:01', '2026-10-26 20:39:01', NULL);

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
(1, 'handball sub21 masculino', 'mayors +18', 1, '2026-10-19 19:26:07', '2026-10-26 19:06:13', NULL),
(5, 'handba sub15 fem', NULL, 1, '2026-10-26 19:06:28', '2026-10-26 19:06:28', NULL);

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
(2, 6, 'servicios', 'luz octubre', NULL, 20000.00, '2026-10-26', NULL, 2, '2026-10-26 21:09:37', NULL, NULL, NULL),
(3, 6, 'servicios', 'luz octubre', 'enersa', 20000.00, '2026-10-26', NULL, 2, '2026-10-26 22:12:31', 2, '2026-10-26 22:13:39', 'lo subi mal al gasto');

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
(13, 'Sin plan', 20000, '2026-10-06', 1, '2026-10-05 18:56:53', '2026-10-05 22:06:36', '2026-10-05 22:06:36', NULL, 'Sin plan'),
(14, 'Cuota General', 40000, '2026-10-06', 1, '2026-10-05 18:58:47', '2026-10-26 19:44:25', '2026-10-26 19:44:25', 3, 'Cuota General'),
(15, 'Sin plan', 2123, '0000-00-00', 1, '2026-10-05 22:06:45', '2026-10-26 19:44:26', '2026-10-26 19:44:26', NULL, 'Sin plan'),
(16, 'Cuota General', 20000, '0323-02-23', 1, '2026-10-05 22:10:54', '2026-10-26 19:44:28', '2026-10-26 19:44:28', 3, 'Cuota General'),
(17, 'Cuota General', 20000, '0000-00-00', 1, '2026-10-26 19:24:41', '2026-10-26 19:44:30', '2026-10-26 19:44:30', 3, 'Cuota General'),
(22, 'Cuota General', 20000, '2026-10-24', 1, '2026-10-26 19:44:55', '2026-10-26 19:44:55', NULL, 3, 'Cuota General'),
(23, 'Cuota General Jubilados', 1000, '2026-10-25', 1, '2026-10-26 20:16:26', '2026-10-26 20:16:26', NULL, 4, 'Cuota General Jubilados'),
(24, 'Cuota General', 20000, '2026-10-27', 1, '2026-10-26 20:39:52', '2026-10-26 20:39:52', NULL, 3, 'Cuota General'),
(25, 'Cuota General', 900000, '2026-10-26', 1, '2026-10-26 22:11:35', '2026-10-26 22:11:35', NULL, 3, 'Cuota General');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `liquidaciones_planes`
--

CREATE TABLE `liquidaciones_planes` (
  `id_liquidacion` int(11) NOT NULL,
  `id_plan` int(11) NOT NULL,
  `monto` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(15, '20261026210000', 'App\\Database\\Migrations\\CrearRegistroGastosCaja', 'default', 'App', 1793059313, 7);

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
(1, 2, NULL, 'transferencia', 30000.00, 'Segunda Caja -> Caja Central | pago sep', '2026-08-24 23:52:59'),
(2, 2, NULL, 'transferencia', 2500.00, 'cobrador Julio -> Caja Central | rendicion septiembre', '2026-08-31 22:12:21'),
(3, 2, NULL, 'transferencia', 225510.00, 'cobrador Julio -> Caja Central | rendicion septiembre', '2026-08-31 22:13:58'),
(4, 2, NULL, 'transferencia', 937000.00, 'cobrador Julio -> Caja Central | rendicion septiembre', '2026-08-31 23:32:07'),
(5, 2, NULL, 'transferencia', 1000.00, 'cobrador Julio -> Caja Central | rendicion septiembre', '2026-08-31 23:32:59'),
(6, 0, NULL, 'transferencia', 1000.00, 'Caja Central -> cobrador Julio | rendicion septiembre', '2026-09-01 00:05:23'),
(7, 0, NULL, 'ingreso', 5000.00, 'Castro Delfor -> Caja Central | Pago: 202610 Cuota General - 5000.00 | Socio: Castro Delfor | Forma: Efectivo', '2026-09-28 20:57:05'),
(8, 0, NULL, 'ingreso', 40000.00, 'Arreseygor gonzalo -> Caja Central | Pago: Handball - 40000.00 | Socio: Arreseygor gonzalo | Forma: Efectivo', '2026-10-05 19:29:16'),
(9, NULL, 2, 'ingreso', 20000.00, 'Arreseygor Gonzalo -> cobrador Julio | Pago: Cuota General - 20000.00 | Socio: Arreseygor Gonzalo | Forma: Efectivo', '2026-10-26 19:45:32'),
(10, NULL, 2, 'ingreso', 1000.00, 'Arreseygor Gonzalo -> cobrador Julio | Pago: Cuota General Jubilados - 1000.00 | Socio: Arreseygor Gonzalo | Forma: Efectivo', '2026-10-26 20:17:07'),
(11, NULL, 6, 'ingreso', 20000.00, 'Arreseygor Gonzalo -> Gonzalo Arreseygor | Pago: Cuota General - 20000.00 | Socio: Arreseygor Gonzalo | Forma: Efectivo', '2026-10-26 20:40:15'),
(12, NULL, 6, 'ingreso', 900000.00, 'Arreseygor Gonzalo -> Gonzalo Arreseygor | Pago: Cuota General - 900000.00 | Socio: Arreseygor Gonzalo | Forma: Efectivo', '2026-10-26 22:11:50');

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
(1, 1, 5, 2, NULL, 10000.00, '2026-08-17 00:00:00', '2026-08-17 19:06:40', '2026-08-17 19:06:40', NULL, NULL),
(2, 1, 6, 2, 'Transferencia', 40000.00, NULL, '2026-08-17 21:34:21', '2026-08-17 21:34:21', NULL, NULL),
(3, 1, 3, 2, 'Efectivo', 30000.00, '2026-08-21 00:00:00', '2026-08-22 00:05:15', '2026-08-22 00:05:15', NULL, NULL),
(4, 1, 3, 2, 'Efectivo', 30000.00, '2026-08-21 00:00:00', '2026-08-22 00:05:26', '2026-08-22 00:05:26', NULL, NULL),
(5, 1, 3, 2, 'Efectivo', 10.00, '2026-08-21 00:00:00', '2026-08-22 00:06:43', '2026-08-22 00:06:43', NULL, NULL),
(6, 1, 7, 2, 'Tarjeta', 100000.00, '2026-08-21 00:00:00', '2026-08-22 00:08:21', '2026-08-22 00:08:21', NULL, NULL),
(7, 1, 8, 2, 'Efectivo', 2500.00, '2026-08-21 00:00:00', '2026-08-22 00:15:29', '2026-08-22 00:15:29', NULL, NULL),
(8, 1, 9, 2, 'Efectivo', 10000.00, '2026-08-24 00:00:00', '2026-08-24 23:35:17', '2026-08-24 23:35:17', NULL, NULL),
(9, 2, 8, 2, 'Efectivo', 2500.00, '2026-08-31 00:00:00', '2026-08-31 22:10:31', '2026-08-31 22:10:31', NULL, NULL),
(10, 2, 6, 2, 'Efectivo', 40000.00, '2026-08-31 00:00:00', '2026-08-31 23:31:22', '2026-08-31 23:31:22', NULL, NULL),
(11, 2, 5, 2, 'Efectivo', 900000.00, '2026-08-31 00:00:00', '2026-08-31 23:31:40', '2026-08-31 23:31:40', NULL, NULL),
(12, 4, 11, 1, 'Efectivo', 5000.00, '2026-09-28 00:00:00', '2026-09-28 20:57:04', '2026-09-28 20:57:04', NULL, NULL),
(13, 7, 14, 1, 'Efectivo', 40000.00, '2026-10-05 00:00:00', '2026-10-05 19:29:16', '2026-10-05 19:29:16', NULL, NULL),
(16, 7, 22, 2, 'Efectivo', 20000.00, '2026-10-26 00:00:00', '2026-10-26 19:45:32', '2026-10-26 19:45:32', NULL, NULL),
(17, 7, 23, 2, 'Efectivo', 1000.00, '2026-10-26 00:00:00', '2026-10-26 20:17:07', '2026-10-26 20:17:07', NULL, NULL),
(18, 7, 24, 6, 'Efectivo', 20000.00, '2026-10-26 00:00:00', '2026-10-26 20:40:15', '2026-10-26 20:40:15', NULL, 10),
(19, 7, 25, 6, 'Efectivo', 900000.00, '2026-10-26 00:00:00', '2026-10-26 22:11:50', '2026-10-26 22:11:50', NULL, 10);

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
(1, 'Plan Futbol', 'Futbol masc/fem', 0.00, 1, '2026-09-14 20:34:01', '2026-10-05 19:28:03', '2026-10-05 19:28:03'),
(2, 'Handball', 'masc/fem', 0.00, 1, '2026-09-14 20:38:08', '2026-10-05 22:06:49', '2026-10-05 22:06:49'),
(3, 'Cuota General', 'Cuota ', 0.00, 1, '2026-09-28 20:51:54', '2026-09-28 20:51:54', NULL),
(4, 'Cuota General Jubilados', 'Cuota ', 0.00, 1, '2026-09-28 20:52:17', '2026-09-28 20:52:17', NULL);

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
(4, 'julio', 'Arreseygor', '45388731', 2, 1, '2026-10-26 19:23:13', '2026-10-26 20:38:49', '2026-10-26 20:38:49', '03436474518', 2),
(5, 'pepe', 'santos', '214124124124', 2, 1, '2026-10-26 20:18:32', '2026-10-26 20:38:50', '2026-10-26 20:38:50', '03436474518', 2),
(7, 'santino', 'aonso', '45388731', 1, 1, '2026-10-26 20:26:28', '2026-10-26 20:38:51', '2026-10-26 20:38:51', '03436474518', 5),
(9, 'Gonzalo', 'Arreseygor', '45388731', 5, 1, '2026-10-26 20:37:05', '2026-10-26 20:37:23', '2026-10-26 20:37:23', '03436474518', 6),
(10, 'Gonzalo', 'Arreseygor', '45388731', 6, 1, '2026-10-26 20:39:01', '2026-10-26 20:39:01', NULL, '03436474518', 2);

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
(1, 'Administrador', 1),
(2, 'Acceso completo', 0),
(4, 'Socios', 0),
(7, 'cobrador', 0);

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
(1, 'cajas', 'crear'),
(1, 'cajas', 'editar'),
(1, 'cajas', 'eliminar'),
(1, 'cajas', 'ver'),
(1, 'disciplinas', 'crear'),
(1, 'disciplinas', 'editar'),
(1, 'disciplinas', 'eliminar'),
(1, 'disciplinas', 'ver'),
(1, 'liquidaciones', 'crear'),
(1, 'liquidaciones', 'editar'),
(1, 'liquidaciones', 'eliminar'),
(1, 'liquidaciones', 'ver'),
(1, 'pagos', 'crear'),
(1, 'pagos', 'editar'),
(1, 'pagos', 'eliminar'),
(1, 'pagos', 'ver'),
(1, 'planes', 'crear'),
(1, 'planes', 'editar'),
(1, 'planes', 'eliminar'),
(1, 'planes', 'ver'),
(1, 'recaudadores', 'crear'),
(1, 'recaudadores', 'editar'),
(1, 'recaudadores', 'eliminar'),
(1, 'recaudadores', 'ver'),
(1, 'socios', 'crear'),
(1, 'socios', 'editar'),
(1, 'socios', 'eliminar'),
(1, 'socios', 'ver'),
(1, 'usuarios', 'crear'),
(1, 'usuarios', 'editar'),
(1, 'usuarios', 'eliminar'),
(1, 'usuarios', 'ver'),
(1, 'zonas', 'crear'),
(1, 'zonas', 'editar'),
(1, 'zonas', 'eliminar'),
(1, 'zonas', 'ver'),
(2, 'cajas', 'crear'),
(2, 'cajas', 'editar'),
(2, 'cajas', 'eliminar'),
(2, 'cajas', 'ver'),
(2, 'disciplinas', 'crear'),
(2, 'disciplinas', 'editar'),
(2, 'disciplinas', 'eliminar'),
(2, 'disciplinas', 'ver'),
(2, 'liquidaciones', 'crear'),
(2, 'liquidaciones', 'editar'),
(2, 'liquidaciones', 'eliminar'),
(2, 'liquidaciones', 'ver'),
(2, 'pagos', 'crear'),
(2, 'pagos', 'editar'),
(2, 'pagos', 'eliminar'),
(2, 'pagos', 'ver'),
(2, 'planes', 'crear'),
(2, 'planes', 'editar'),
(2, 'planes', 'eliminar'),
(2, 'planes', 'ver'),
(2, 'recaudadores', 'crear'),
(2, 'recaudadores', 'editar'),
(2, 'recaudadores', 'eliminar'),
(2, 'recaudadores', 'ver'),
(2, 'socios', 'crear'),
(2, 'socios', 'editar'),
(2, 'socios', 'eliminar'),
(2, 'socios', 'ver'),
(2, 'usuarios', 'crear'),
(2, 'usuarios', 'editar'),
(2, 'usuarios', 'eliminar'),
(2, 'usuarios', 'ver'),
(2, 'zonas', 'crear'),
(2, 'zonas', 'editar'),
(2, 'zonas', 'eliminar'),
(2, 'zonas', 'ver'),
(4, 'socios', 'crear'),
(4, 'socios', 'ver'),
(7, 'pagos', 'crear'),
(7, 'pagos', 'ver');

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
(1, 'cajas'),
(1, 'disciplinas'),
(1, 'liquidaciones'),
(1, 'pagos'),
(1, 'planes'),
(1, 'recaudadores'),
(1, 'socios'),
(1, 'usuarios'),
(1, 'zonas'),
(2, 'cajas'),
(2, 'disciplinas'),
(2, 'liquidaciones'),
(2, 'pagos'),
(2, 'planes'),
(2, 'recaudadores'),
(2, 'socios'),
(2, 'usuarios'),
(2, 'zonas'),
(4, 'socios'),
(7, 'pagos');

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
(6, 'valentin', 'padularrosa', NULL, 'tata', '213123', '2004-03-21', '213124124', '124124124@gmail.com', 2, 1, '2026-10-05 18:57:44', '2026-10-26 18:41:34', '2026-10-26 18:41:34', NULL, NULL),
(7, 'Gonzalo', 'Arreseygor', NULL, '1asdasdasd', '03436474518', '2004-03-21', '45388731', 'arreseygorgonza@gmail.com', 2, 1, '2026-10-05 18:58:06', '2026-10-26 20:39:25', NULL, NULL, 10);

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
(6, 7, 1, '2026-10-26 18:44:06', NULL),
(7, 7, 5, '2026-10-26 19:06:57', NULL);

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
(12, 6, 1, '2026-10-05 19:28:28'),
(14, 7, 1, '2026-10-26 18:44:06'),
(15, 7, 2, '2026-10-26 18:44:06'),
(16, 7, 3, '2026-10-26 18:44:06'),
(18, 6, 3, '2026-10-19 21:20:42'),
(19, 6, 3, '2026-10-19 21:20:43'),
(20, 6, 3, '2026-10-26 18:40:59'),
(21, 6, 3, NULL),
(22, 7, 3, '2026-10-26 18:49:01'),
(23, 7, 3, '2026-10-26 18:53:48'),
(24, 7, 3, '2026-10-26 18:59:51'),
(25, 7, 3, '2026-10-26 19:00:47'),
(26, 7, 3, '2026-10-26 19:04:29'),
(27, 7, 3, '2026-10-26 19:06:57'),
(28, 7, 3, '2026-10-26 19:25:03'),
(29, 7, 3, '2026-10-26 19:35:24'),
(30, 7, 4, '2026-10-26 19:35:24'),
(31, 7, 3, '2026-10-26 20:31:50'),
(32, 7, 4, '2026-10-26 20:31:50'),
(33, 7, 3, '2026-10-26 20:39:25'),
(34, 7, 4, '2026-10-26 20:39:25'),
(35, 7, 3, NULL),
(36, 7, 4, NULL);

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
(2, 'Admin', 'Admin', 'admin', '$2y$10$KDWR61anUKO0/9Ozi4ePbOtnijM/ZB5EY9bXn6Tlx5Kvcn4LEbLkW', '00000000', 1, '2025-11-03 19:25:54', '2026-10-05 19:43:19', NULL, 1),
(4, 'padulince', 'padulin', 'padulince', '$2y$10$6VWP1r2BAb6/VSZ760X9WuFnIlO9FoYys./NDVthlMdqhuapXtG7S', '45388731', 1, '2026-06-29 20:43:29', '2026-10-05 19:47:43', NULL, 2),
(6, 'santino', 'alonso', 'roooot', '$2y$10$614v53n8xm0qafzASrSlN.lL1jRJDGAckvT8hzXJpJd9P8LFWOJeu', '45388731', 1, '2026-09-04 20:50:47', '2026-10-05 21:59:03', NULL, 4),
(7, 'Gonzalo', 'Arreseygor', 'GON', '$2y$10$8EztkHNBCeQOlUoIcNFiCejPHpprfQNnV34mYnOobEUmp1n4ktdA2', '214124124124', 1, '2026-10-05 20:29:52', '2026-10-05 20:31:06', NULL, 4);

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
(1, 'primer cuartel', 0, '2026-06-29 20:58:19', '2026-06-29 20:58:22', NULL),
(2, 'Primer cuartel', 1, '2026-06-29 21:07:31', '2026-08-24 22:45:06', NULL),
(3, 'Segundo Cuartel', 1, '2026-08-24 22:45:27', '2026-08-24 22:45:27', NULL),
(4, 'Tercer Cuartel', 1, '2026-08-24 22:45:40', '2026-08-24 22:45:40', NULL),
(5, 'Cuarto Cuartel', 1, '2026-08-24 22:45:55', '2026-08-24 22:45:55', NULL),
(6, 'Quinto Cuartel', 1, '2026-08-24 22:46:47', '2026-08-24 22:46:47', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `gastos_caja`
--
ALTER TABLE `gastos_caja`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `liquidaciones`
--
ALTER TABLE `liquidaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `movimientos_caja`
--
ALTER TABLE `movimientos_caja`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `planes`
--
ALTER TABLE `planes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `recaudadores`
--
ALTER TABLE `recaudadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `socios`
--
ALTER TABLE `socios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `socios_disciplinas`
--
ALTER TABLE `socios_disciplinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `socios_planes`
--
ALTER TABLE `socios_planes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `socios_tutores`
--
ALTER TABLE `socios_tutores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tutores`
--
ALTER TABLE `tutores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `zonas`
--
ALTER TABLE `zonas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
