-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-11-2025 a las 18:19:58
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
-- Base de datos: `panaderia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE `cajas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_hora_apertura` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_hora_cierre` timestamp NULL DEFAULT NULL,
  `saldo_inicial` decimal(12,2) NOT NULL DEFAULT 0.00,
  `saldo_final` decimal(12,2) NOT NULL DEFAULT 0.00,
  `estado` varchar(255) NOT NULL DEFAULT 'abierta',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cajas`
--

INSERT INTO `cajas` (`id`, `user_id`, `fecha_hora_apertura`, `fecha_hora_cierre`, `saldo_inicial`, `saldo_final`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-10-30 19:03:58', '2025-10-31 01:03:58', 1000.00, 35.00, 'cerrada', '2025-10-23 23:41:28', '2025-10-31 01:03:58'),
(2, 3, '2025-10-30 18:03:02', '2025-10-31 00:03:02', 2000.00, 2000.00, 'cerrada', '2025-10-24 00:23:48', '2025-10-31 00:03:02'),
(3, 2, '2025-10-30 16:34:48', '2025-10-30 22:34:48', 1000.00, 1093.50, 'cerrada', '2025-10-28 01:45:59', '2025-10-30 22:34:48'),
(4, 2, '2025-10-30 23:16:07', NULL, 1000.00, 0.00, 'abierta', '2025-10-30 23:16:07', '2025-10-30 23:16:07'),
(5, 1, '2025-11-04 18:59:42', '2025-11-05 00:59:42', 1000.00, 1020.00, 'cerrada', '2025-11-05 00:58:16', '2025-11-05 00:59:42'),
(6, 1, '2025-11-05 22:30:29', NULL, 1000.00, 0.00, 'abierta', '2025-11-05 22:30:29', '2025-11-05 22:30:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--

CREATE TABLE `cargos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cargos`
--

INSERT INTO `cargos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Super Administrador', '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(2, 'Administrador', '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(3, 'Cajero / Vendedor', '2025-10-23 23:40:40', '2025-10-23 23:40:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Básico', '2025-10-23 23:49:48', '2025-10-23 23:49:48'),
(2, 'Plus', '2025-10-24 00:07:36', '2025-10-24 00:07:36')
(3, 'Premium', '2025-10-24 00:07:36', '2025-10-24 00:07:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `idCli` bigint(20) UNSIGNED NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `identificacion` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`idCli`, `Nombre`, `created_at`, `updated_at`) VALUES
(1, 'Saul', '2025-10-28 01:09:25', '2025-10-28 01:09:25'),
(2, 'Terrenos valle', '2025-11-04 02:12:39', '2025-11-04 02:12:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proveedor_id` bigint(20) UNSIGNED NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `metodo_pago` varchar(255) NOT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `proveedor_id`, `descripcion`, `metodo_pago`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bolillo caliente', 'efectivo', 200.00, '2025-10-24 00:05:40', '2025-10-24 00:05:40'),
(2, 1, 'Telera', 'efectivo', 500.00, '2025-10-28 23:49:27', '2025-10-28 23:49:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `venta_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `importe` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id`, `venta_id`, `producto_id`, `cantidad`, `descripcion`, `precio_unitario`, `importe`, `created_at`, `updated_at`) VALUES
(1, 7, 4, 7, NULL, 5.00, 35.00, '2025-10-28 01:43:53', '2025-10-28 01:43:53'),
(2, 8, 4, 2, NULL, 5.00, 10.00, '2025-10-28 23:48:11', '2025-10-28 23:48:11'),
(3, 8, 5, 1, NULL, 3.50, 3.50, '2025-10-28 23:48:11', '2025-10-28 23:48:11'),
(4, 9, 5, 2, NULL, 3.50, 7.00, '2025-10-29 22:45:37', '2025-10-29 22:45:37'),
(5, 10, 5, 1, NULL, 3.50, 3.50, '2025-10-29 23:02:44', '2025-10-29 23:02:44'),
(6, 11, 4, 1, NULL, 5.00, 5.00, '2025-10-29 23:13:51', '2025-10-29 23:13:51'),
(7, 12, 5, 1, NULL, 3.50, 3.50, '2025-10-29 23:15:03', '2025-10-29 23:15:03'),
(8, 13, 5, 1, NULL, 3.50, 3.50, '2025-10-29 23:20:29', '2025-10-29 23:20:29'),
(9, 14, 5, 1, NULL, 3.50, 3.50, '2025-10-29 23:21:36', '2025-10-29 23:21:36'),
(10, 15, 5, 1, NULL, 3.50, 3.50, '2025-10-29 23:21:48', '2025-10-29 23:21:48'),
(11, 16, 5, 1, NULL, 3.50, 3.50, '2025-10-30 00:24:06', '2025-10-30 00:24:06'),
(12, 17, 5, 1, NULL, 3.50, 3.50, '2025-10-30 00:29:55', '2025-10-30 00:29:55'),
(13, 18, 4, 1, NULL, 5.00, 5.00, '2025-10-30 00:41:45', '2025-10-30 00:41:45'),
(14, 19, 4, 1, NULL, 5.00, 5.00, '2025-10-30 00:54:49', '2025-10-30 00:54:49'),
(15, 19, 5, 1, NULL, 3.50, 3.50, '2025-10-30 00:54:49', '2025-10-30 00:54:49'),
(16, 20, 4, 1, NULL, 5.00, 5.00, '2025-10-30 01:12:02', '2025-10-30 01:12:02'),
(17, 21, 4, 1, NULL, 5.00, 5.00, '2025-10-30 01:22:52', '2025-10-30 01:22:52'),
(18, 22, 4, 1, NULL, 5.00, 5.00, '2025-10-30 21:57:22', '2025-10-30 21:57:22'),
(19, 23, 4, 1, NULL, 5.00, 5.00, '2025-10-30 22:03:03', '2025-10-30 22:03:03'),
(20, 24, 4, 1, NULL, 5.00, 5.00, '2025-10-30 22:03:16', '2025-10-30 22:03:16'),
(21, 25, 4, 1, NULL, 5.00, 5.00, '2025-10-30 22:04:11', '2025-10-30 22:04:11'),
(22, 26, 5, 1, NULL, 3.50, 3.50, '2025-10-30 23:34:06', '2025-10-30 23:34:06'),
(23, 27, 4, 9, NULL, 5.00, 45.00, '2025-11-04 02:12:54', '2025-11-04 02:12:54'),
(24, 27, 5, 3, NULL, 3.50, 10.50, '2025-11-04 02:12:54', '2025-11-04 02:12:54'),
(25, 28, 4, 5, NULL, 5.00, 25.00, '2025-11-04 02:13:30', '2025-11-04 02:13:30'),
(26, 29, 4, 2, NULL, 5.00, 10.00, '2025-11-04 02:16:28', '2025-11-04 02:16:28'),
(27, 30, 4, 4, NULL, 5.00, 20.00, '2025-11-05 00:58:40', '2025-11-05 00:58:40'),
(28, 31, 4, 3, NULL, 5.00, 15.00, '2025-11-05 22:53:38', '2025-11-05 22:53:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `idEmp` bigint(20) UNSIGNED NOT NULL,
  `idUserFK` bigint(20) UNSIGNED NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`idEmp`, `idUserFK`, `telefono`, `direccion`, `created_at`, `updated_at`) VALUES
(1, 2, '5641291360', 'Valle de chalco', '2025-10-23 23:49:40', '2025-10-23 23:49:40'),
(2, 3, '5641291360', 'Valle de chalco', '2025-10-24 00:22:27', '2025-10-24 00:22:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventarios`
--

CREATE TABLE `inventarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad_minima` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `cantidad_maxima` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `stock` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inventarios`
--

INSERT INTO `inventarios` (`id`, `producto_id`, `cantidad_minima`, `cantidad_maxima`, `stock`, `created_at`, `updated_at`) VALUES
(1, 4, 5, 99999, 977, '2025-10-24 00:03:08', '2025-11-05 22:53:38'),
(2, 5, 5, 99999, 5, '2025-10-24 00:08:03', '2025-11-04 02:12:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_14_203000_create_empleados_table', 1),
(5, '2025_10_15_001122_create_clientes_table', 1),
(6, '2025_10_16_184425_create_categorias_table', 1),
(7, '2025_10_16_184642_create_productos_table', 1),
(8, '2025_10_16_184730_create_inventarios_table', 1),
(9, '2025_10_16_184809_create_proveedores_table', 1),
(10, '2025_10_16_185015_create_compras_table', 1),
(11, '2025_10_16_185109_create_ventas_table', 1),
(12, '2025_10_16_185441_create_detalle_ventas_table', 1),
(13, '2025_10_16_185548_create_cajas_table', 1),
(14, '2025_10_16_185627_create_movimientos_caja_table', 1),
(15, '2025_10_16_185942_create_cargos_table', 1),
(16, '2025_10_16_190020_create_modulos_table', 1),
(17, '2025_10_16_190212_create_permisos_table', 1),
(18, '2025_10_16_190323_add_idcargfk_to_users_table', 1),
(19, '2025_10_30_165151_add_imagen_to_productos_table', 2),
(20, '2025_10_30_185756_add_user_id_to_movimientos_caja_table', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'usuarios', '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(2, 'cargos', '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(3, 'categorias', '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(4, 'productos', '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(5, 'inventario', '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(6, 'proveedores', '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(7, 'ventas', '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(8, 'compras', '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(9, 'cajas', '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(10, 'clientes', '2025-10-23 23:40:40', '2025-10-23 23:40:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_caja`
--

CREATE TABLE `movimientos_caja` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `caja_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tipo` varchar(255) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `monto` decimal(12,2) NOT NULL,
  `metodo_pago` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `movimientos_caja`
--

INSERT INTO `movimientos_caja` (`id`, `caja_id`, `user_id`, `tipo`, `descripcion`, `monto`, `metodo_pago`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, 'ingreso', 'Ventas en Efectivo del Turno', 93.50, 'sistema', '2025-10-30 22:34:48', '2025-10-30 22:34:48'),
(2, 1, 1, 'egreso', 'pago a proveedor', 1000.00, 'Efectivo (Manual)', '2025-10-31 01:00:10', '2025-10-31 01:00:10'),
(3, 1, 1, 'ingreso', 'Ventas en Efectivo del Turno', 35.00, 'sistema', '2025-10-31 01:03:58', '2025-10-31 01:03:58'),
(4, 5, 1, 'ingreso', 'Ventas en Efectivo del Turno', 20.00, 'sistema', '2025-11-05 00:59:42', '2025-11-05 00:59:42'),
(5, 6, 1, 'egreso', 'pago', 50.00, 'Efectivo (Manual)', '2025-11-05 23:04:29', '2025-11-05 23:04:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cargo_id` bigint(20) UNSIGNED NOT NULL,
  `modulo_id` bigint(20) UNSIGNED NOT NULL,
  `mostrar` tinyint(1) NOT NULL DEFAULT 1,
  `alta` tinyint(1) NOT NULL DEFAULT 0,
  `detalle` tinyint(1) NOT NULL DEFAULT 1,
  `editar` tinyint(1) NOT NULL DEFAULT 0,
  `eliminar` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `cargo_id`, `modulo_id`, `mostrar`, `alta`, `detalle`, `editar`, `eliminar`, `created_at`, `updated_at`) VALUES
(1, 1, 9, 1, 1, 1, 1, 1, '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(2, 1, 2, 1, 1, 1, 1, 1, '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(3, 1, 3, 1, 1, 1, 1, 1, '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(4, 1, 10, 1, 1, 1, 1, 1, '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(5, 1, 8, 1, 1, 1, 1, 1, '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(6, 1, 5, 1, 1, 1, 1, 1, '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(7, 1, 4, 1, 1, 1, 1, 1, '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(8, 1, 6, 1, 1, 1, 1, 1, '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(9, 1, 1, 1, 1, 1, 1, 1, '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(10, 1, 7, 1, 1, 1, 1, 1, '2025-10-23 23:40:40', '2025-10-23 23:40:40'),
(11, 3, 7, 1, 1, 1, 0, 1, '2025-10-23 23:40:40', '2025-10-31 00:02:53'),
(12, 3, 9, 1, 1, 1, 0, 1, '2025-10-23 23:40:40', '2025-10-31 00:02:53'),
(15, 2, 9, 1, 1, 1, 1, 1, '2025-10-27 23:36:46', '2025-10-27 23:36:46'),
(16, 2, 2, 1, 1, 1, 1, 1, '2025-10-27 23:36:46', '2025-10-28 01:45:25'),
(17, 2, 3, 1, 1, 1, 1, 1, '2025-10-27 23:36:46', '2025-10-28 01:45:25'),
(18, 2, 10, 1, 1, 1, 1, 1, '2025-10-27 23:36:46', '2025-10-28 01:45:25'),
(19, 2, 8, 1, 1, 1, 1, 1, '2025-10-27 23:36:46', '2025-10-28 01:45:25'),
(20, 2, 5, 1, 1, 1, 1, 1, '2025-10-27 23:36:46', '2025-10-28 01:45:25'),
(21, 2, 4, 1, 1, 1, 1, 1, '2025-10-27 23:36:46', '2025-10-28 01:45:25'),
(22, 2, 6, 1, 1, 1, 1, 1, '2025-10-27 23:36:46', '2025-10-28 01:45:25'),
(23, 2, 1, 1, 1, 1, 1, 1, '2025-10-27 23:36:46', '2025-10-28 01:45:25'),
(24, 2, 7, 1, 1, 1, 1, 1, '2025-10-27 23:36:46', '2025-10-28 01:47:22'),
(25, 3, 2, 0, 0, 0, 0, 0, '2025-10-31 00:02:53', '2025-10-31 00:02:53'),
(26, 3, 3, 0, 0, 0, 0, 0, '2025-10-31 00:02:53', '2025-10-31 00:02:53'),
(27, 3, 10, 0, 0, 0, 0, 0, '2025-10-31 00:02:53', '2025-10-31 00:02:53'),
(28, 3, 8, 0, 0, 0, 0, 0, '2025-10-31 00:02:53', '2025-10-31 00:02:53'),
(29, 3, 5, 0, 0, 0, 0, 0, '2025-10-31 00:02:53', '2025-10-31 00:02:53'),
(30, 3, 4, 0, 0, 0, 0, 0, '2025-10-31 00:02:53', '2025-10-31 00:02:53'),
(31, 3, 6, 0, 0, 0, 0, 0, '2025-10-31 00:02:53', '2025-10-31 00:02:53'),
(32, 3, 1, 0, 0, 0, 0, 0, '2025-10-31 00:02:53', '2025-10-31 00:02:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `categoria_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO productos
(id, categoria_id, nombre, descripcion, precio, imagen, created_at, updated_at)
VALUES(4, 1, 'Básico 1', 'Casa Basica 1', 1000000.00, 'productos/hV6Gq45F2XcrhWEhH6rHZUNIbmihsMWrdUzoYzyE.jpg', '2025-10-23 18:03:08', '2025-12-13 05:16:56');
INSERT INTO productos
(id, categoria_id, nombre, descripcion, precio, imagen, created_at, updated_at)
VALUES(5, 3, 'Premium', 'ESTA ES UNA CASA PREMIUM', 9000000.00, 'productos/XqkwOehACGrGJrA5C2JYsfgxxQiNHjryaUuILqQg.jpg', '2025-10-23 18:08:03', '2025-12-13 05:17:20');
INSERT INTO productos
(id, categoria_id, nombre, descripcion, precio, imagen, created_at, updated_at)
VALUES(8, 2, 'Casa Media', 'Esta es una casa Media con calidad alta para venta', 4000000.00, 'productos/Jfceeaoy2BJraqUgT1Q5HO9cZVYllZ49TVchrqz9.jpg', '2025-12-13 05:18:11', '2025-12-13 05:21:06');
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `empresa` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `empresa`, `telefono`, `correo`, `created_at`, `updated_at`) VALUES
(1, 'Esperanza', 'Panaderia Esperanza', '5641291360', 'panaderiaesperenza@gmail.com', '2025-10-24 00:05:10', '2025-10-24 00:05:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ATYEzrnK8bAGlosRDUyyuwjLim65NDWn8ztdsxrs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYlJuSk1mTW83V09ROXdQbTdhT1pLZFc4RGJETGJtZkw5eEpaVjlmZCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXJnb3MiO319', 1762362936);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `cargo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `cargo_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Administrador', 'admin@panaderia.com', NULL, '$2y$12$PCzS1cBpq7iV20QaKNiO1eYblC87IPurCNJkmHQx9d0nmP3SdgxzK', 1, NULL, '2025-10-23 23:40:41', '2025-10-23 23:40:41'),
(2, 'Josue', 'josue@panaderia.com', NULL, '$2y$12$fKeoOyDcVkv7Rt0akWCqC.aMRGTDCPYhUjB7XiAleJ9itctV5VzG6', 2, NULL, '2025-10-23 23:49:40', '2025-10-23 23:49:40'),
(3, 'Saul', 'saul@panaderia.com', NULL, '$2y$12$r1iKkHss81qbob.O4MBHB.5jDKTTLxux7kL5NLWI/r8/x5XlolW3.', 3, NULL, '2025-10-24 00:22:27', '2025-11-05 00:29:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cliente_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `metodo_pago` varchar(255) NOT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `monto_recibido` decimal(12,2) NOT NULL DEFAULT 0.00,
  `monto_entregado` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `cliente_id`, `user_id`, `fecha_hora`, `metodo_pago`, `total`, `monto_recibido`, `monto_entregado`, `created_at`, `updated_at`) VALUES
(7, NULL, 1, '2025-10-28 01:43:53', 'efectivo', 35.00, 35.00, 0.00, '2025-10-28 01:43:53', '2025-10-28 01:43:53'),
(8, NULL, 2, '2025-10-28 23:48:11', 'efectivo', 13.50, 100.00, 86.50, '2025-10-28 23:48:11', '2025-10-28 23:48:11'),
(9, NULL, 2, '2025-10-29 22:45:37', 'efectivo', 7.00, 78.00, 71.00, '2025-10-29 22:45:37', '2025-10-29 22:45:37'),
(10, NULL, 2, '2025-10-29 23:02:44', 'efectivo', 3.50, 33.00, 29.50, '2025-10-29 23:02:44', '2025-10-29 23:02:44'),
(11, NULL, 2, '2025-10-29 23:13:51', 'efectivo', 5.00, 23.00, 18.00, '2025-10-29 23:13:51', '2025-10-29 23:13:51'),
(12, NULL, 2, '2025-10-29 23:15:03', 'efectivo', 3.50, 87.00, 83.50, '2025-10-29 23:15:03', '2025-10-29 23:15:03'),
(13, NULL, 2, '2025-10-29 23:20:29', 'efectivo', 3.50, 45.00, 41.50, '2025-10-29 23:20:29', '2025-10-29 23:20:29'),
(14, 3, 2, '2025-10-29 23:21:36', 'efectivo', 3.50, 32.00, 28.50, '2025-10-29 23:21:36', '2025-10-29 23:21:36'),
(15, NULL, 2, '2025-10-29 23:21:48', 'efectivo', 3.50, 32.00, 28.50, '2025-10-29 23:21:48', '2025-10-29 23:21:48'),
(16, NULL, 2, '2025-10-30 00:24:06', 'efectivo', 3.50, 45.00, 41.50, '2025-10-30 00:24:06', '2025-10-30 00:24:06'),
(17, NULL, 2, '2025-10-30 00:29:55', 'efectivo', 3.50, 56.00, 52.50, '2025-10-30 00:29:55', '2025-10-30 00:29:55'),
(18, NULL, 2, '2025-10-30 00:41:45', 'efectivo', 5.00, 75.00, 70.00, '2025-10-30 00:41:45', '2025-10-30 00:41:45'),
(19, NULL, 2, '2025-10-30 00:54:49', 'efectivo', 8.50, 35.00, 26.50, '2025-10-30 00:54:49', '2025-10-30 00:54:49'),
(20, NULL, 2, '2025-10-30 01:12:02', 'efectivo', 5.00, 45.00, 40.00, '2025-10-30 01:12:02', '2025-10-30 01:12:02'),
(21, NULL, 2, '2025-10-30 01:22:52', 'efectivo', 5.00, 30.00, 25.00, '2025-10-30 01:22:52', '2025-10-30 01:22:52'),
(22, 3, 2, '2025-10-30 21:57:22', 'efectivo', 5.00, 40.00, 35.00, '2025-10-30 21:57:22', '2025-10-30 21:57:22'),
(23, NULL, 2, '2025-10-30 22:03:03', 'efectivo', 5.00, 20.00, 15.00, '2025-10-30 22:03:03', '2025-10-30 22:03:03'),
(24, NULL, 2, '2025-10-30 22:03:16', 'efectivo', 5.00, 23.00, 18.00, '2025-10-30 22:03:16', '2025-10-30 22:03:16'),
(25, NULL, 2, '2025-10-30 22:04:11', 'efectivo', 5.00, 75.00, 70.00, '2025-10-30 22:04:11', '2025-10-30 22:04:11'),
(26, NULL, 2, '2025-10-30 23:34:06', 'efectivo', 3.50, 10.00, 6.50, '2025-10-30 23:34:06', '2025-10-30 23:34:06'),
(27, 4, 2, '2025-11-04 02:12:54', 'efectivo', 55.50, 500.00, 444.50, '2025-11-04 02:12:54', '2025-11-04 02:12:54'),
(28, NULL, 2, '2025-11-04 02:13:30', 'efectivo', 25.00, 500.00, 475.00, '2025-11-04 02:13:30', '2025-11-04 02:13:30'),
(29, NULL, 2, '2025-11-04 02:16:28', 'efectivo', 10.00, 201.00, 191.00, '2025-11-04 02:16:28', '2025-11-04 02:16:28'),
(30, NULL, 1, '2025-11-05 00:58:40', 'efectivo', 20.00, 50.00, 30.00, '2025-11-05 00:58:40', '2025-11-05 00:58:40'),
(31, NULL, 1, '2025-11-05 22:53:38', 'efectivo', 15.00, 50.00, 35.00, '2025-11-05 22:53:38', '2025-11-05 22:53:38');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cajas_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cargos_nombre_unique` (`nombre`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categorias_nombre_unique` (`nombre`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idCli`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compras_proveedor_id_foreign` (`proveedor_id`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_ventas_venta_id_foreign` (`venta_id`),
  ADD KEY `detalle_ventas_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`idEmp`),
  ADD KEY `empleados_iduserfk_foreign` (`idUserFK`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventarios_producto_id_unique` (`producto_id`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `modulos_nombre_unique` (`nombre`);

--
-- Indices de la tabla `movimientos_caja`
--
ALTER TABLE `movimientos_caja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movimientos_caja_caja_id_foreign` (`caja_id`),
  ADD KEY `movimientos_caja_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permisos_cargo_id_modulo_id_unique` (`cargo_id`,`modulo_id`),
  ADD KEY `permisos_modulo_id_foreign` (`modulo_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `productos_categoria_id_nombre_unique` (`categoria_id`,`nombre`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_cargo_id_foreign` (`cargo_id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ventas_cliente_id_foreign` (`cliente_id`),
  ADD KEY `ventas_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idCli` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `idEmp` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `movimientos_caja`
--
ALTER TABLE `movimientos_caja`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD CONSTRAINT `cajas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `detalle_ventas_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `empleados_iduserfk_foreign` FOREIGN KEY (`idUserFK`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD CONSTRAINT `inventarios_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `movimientos_caja`
--
ALTER TABLE `movimientos_caja`
  ADD CONSTRAINT `movimientos_caja_caja_id_foreign` FOREIGN KEY (`caja_id`) REFERENCES `cajas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `movimientos_caja_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_cargo_id_foreign` FOREIGN KEY (`cargo_id`) REFERENCES `cargos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permisos_modulo_id_foreign` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_cargo_id_foreign` FOREIGN KEY (`cargo_id`) REFERENCES `cargos` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`idCli`) ON DELETE SET NULL,
  ADD CONSTRAINT `ventas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
