-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2025 at 02:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `seumpamapointv2`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `clock_in` timestamp NULL DEFAULT NULL,
  `clock_out` timestamp NULL DEFAULT NULL,
  `shift` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `user_id`, `clock_in`, `clock_out`, `shift`, `latitude`, `longitude`, `status`, `created_at`, `updated_at`) VALUES
(5, 7, '2025-10-21 05:41:29', '2025-10-21 05:41:56', 'shift 1', NULL, NULL, 'late', '2025-10-21 05:41:29', '2025-10-21 05:41:56'),
(6, 4, '2025-10-21 05:43:34', '2025-10-21 05:44:02', 'shift 1', NULL, NULL, 'late', '2025-10-21 05:43:34', '2025-10-21 05:44:02'),
(7, 7, '2025-10-23 01:28:57', NULL, 'shift 1', NULL, NULL, 'present', '2025-10-23 01:28:57', '2025-10-23 01:28:57'),
(8, 7, '2025-10-26 03:19:52', NULL, 'shift 1', NULL, NULL, 'late', '2025-10-26 03:19:52', '2025-10-26 03:19:52'),
(9, 7, '2025-10-31 11:19:08', NULL, 'shift 2', NULL, NULL, 'late', '2025-10-31 11:19:08', '2025-10-31 11:19:08'),
(10, 7, '2025-11-01 08:17:38', NULL, 'shift 2', NULL, NULL, 'late', '2025-11-01 08:17:38', '2025-11-01 08:17:38'),
(11, 7, '2025-11-02 02:27:45', '2025-11-02 07:35:52', 'shift 1', NULL, NULL, 'late', '2025-11-02 02:27:45', '2025-11-02 07:35:52'),
(12, 7, '2025-11-03 06:20:18', NULL, 'shift 1', NULL, NULL, 'late', '2025-11-03 06:20:18', '2025-11-03 06:20:18'),
(13, 7, '2025-11-08 03:04:58', NULL, 'shift 1', NULL, NULL, 'late', '2025-11-08 03:04:58', '2025-11-08 03:04:58'),
(14, 4, '2025-11-09 08:03:12', NULL, 'shift 2', NULL, NULL, 'late', '2025-11-09 08:03:12', '2025-11-09 08:03:12'),
(15, 7, '2025-11-29 03:55:56', NULL, 'shift 1', NULL, NULL, 'late', '2025-11-29 03:55:56', '2025-11-29 03:55:56'),
(16, 7, '2025-12-13 05:27:47', NULL, 'shift 1', NULL, NULL, 'late', '2025-12-13 05:27:47', '2025-12-13 05:27:47');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('seumpamapoint-cache-livewire-rate-limiter:75a036ac0fdcb20b35ac6fd6a3875ea2dbe60af1', 'i:1;', 1765626233),
('seumpamapoint-cache-livewire-rate-limiter:75a036ac0fdcb20b35ac6fd6a3875ea2dbe60af1:timer', 'i:1765626233;', 1765626233),
('seumpamapoint-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1765109589),
('seumpamapoint-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1765109589;', 1765109589),
('seumpamapoint-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:0:{}s:11:\"permissions\";a:0:{}s:5:\"roles\";a:0:{}}', 1765714395);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Koffee', NULL, 1, '2025-10-21 06:31:25', '2025-10-21 06:31:25'),
(2, 'Non Koffee', NULL, 1, '2025-10-21 09:38:24', '2025-10-21 09:38:24'),
(3, 'Food', NULL, 1, '2025-10-21 09:38:37', '2025-10-21 09:38:37'),
(4, 'Snack', NULL, 1, '2025-10-21 09:38:51', '2025-10-21 09:38:51'),
(5, 'Accessories', NULL, 0, '2025-10-21 09:39:09', '2025-10-21 09:39:49');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Fariz Afdilah Muhamad', '081282612603', 'Jl. Sigma raya No.404 Rt/Rw. 005/005 Kel. Karawaci Baru Kec. Karawaci', '2025-10-18 01:12:13', '2025-10-18 01:12:13'),
(2, 'Sheva Ardiansyah', '081282612603', 'Jl. Sigma raya No.404 Rt/Rw. 005/005 Kel. Karawaci Baru', '2025-10-18 14:02:54', '2025-10-18 14:02:54'),
(3, 'Muhamad Febryansyah', '081282612603', 'Jl. Sigma raya No.404 Rt/Rw. 005/005 Kel. Karawaci Baru', '2025-10-18 14:03:34', '2025-10-18 14:03:34');

-- --------------------------------------------------------

--
-- Table structure for table `exports`
--

CREATE TABLE `exports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_disk` varchar(255) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `exporter` varchar(255) NOT NULL,
  `processed_rows` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_rows` int(10) UNSIGNED NOT NULL,
  `successful_rows` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_import_rows`
--

CREATE TABLE `failed_import_rows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `import_id` bigint(20) UNSIGNED NOT NULL,
  `validation_error` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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

--
-- Dumping data for table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, '7437d71c-b3da-4d6e-8661-61116b8ff639', 'database', 'default', '{\"uuid\":\"7437d71c-b3da-4d6e-8661-61116b8ff639\",\"displayName\":\"Illuminate\\\\Bus\\\\ChainedBatch\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Bus\\\\ChainedBatch\",\"command\":\"O:27:\\\"Illuminate\\\\Bus\\\\ChainedBatch\\\":17:{s:4:\\\"jobs\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:0;O:46:\\\"Filament\\\\Actions\\\\Exports\\\\Jobs\\\\PrepareCsvExport\\\":7:{s:11:\\\"\\u0000*\\u0000exporter\\\";O:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\":3:{s:9:\\\"\\u0000*\\u0000export\\\";O:38:\\\"Filament\\\\Actions\\\\Exports\\\\Models\\\\Export\\\":33:{s:13:\\\"\\u0000*\\u0000connection\\\";s:5:\\\"mysql\\\";s:8:\\\"\\u0000*\\u0000table\\\";N;s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:19:\\\"preventsLazyLoading\\\";b:0;s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:1;s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:8:{s:7:\\\"user_id\\\";i:1;s:8:\\\"exporter\\\";s:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\";s:10:\\\"total_rows\\\";i:5;s:9:\\\"file_disk\\\";s:5:\\\"local\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-10-19 17:31:29\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-10-19 17:31:29\\\";s:2:\\\"id\\\";i:4;s:9:\\\"file_name\\\";s:15:\\\"export-4-orders\\\";}s:11:\\\"\\u0000*\\u0000original\\\";a:8:{s:7:\\\"user_id\\\";i:1;s:8:\\\"exporter\\\";s:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\";s:10:\\\"total_rows\\\";i:5;s:9:\\\"file_disk\\\";s:5:\\\"local\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-10-19 17:31:29\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-10-19 17:31:29\\\";s:2:\\\"id\\\";i:4;s:9:\\\"file_name\\\";s:15:\\\"export-4-orders\\\";}s:10:\\\"\\u0000*\\u0000changes\\\";a:1:{s:9:\\\"file_name\\\";s:15:\\\"export-4-orders\\\";}s:11:\\\"\\u0000*\\u0000previous\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:4:{s:12:\\\"completed_at\\\";s:9:\\\"timestamp\\\";s:14:\\\"processed_rows\\\";s:7:\\\"integer\\\";s:10:\\\"total_rows\\\";s:7:\\\"integer\\\";s:15:\\\"successful_rows\\\";s:7:\\\"integer\\\";}s:17:\\\"\\u0000*\\u0000classCastCache\\\";a:0:{}s:21:\\\"\\u0000*\\u0000attributeCastCache\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:27:\\\"\\u0000*\\u0000relationAutoloadCallback\\\";N;s:26:\\\"\\u0000*\\u0000relationAutoloadContext\\\";N;s:10:\\\"timestamps\\\";b:1;s:13:\\\"usesUniqueIds\\\";b:0;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}}s:12:\\\"\\u0000*\\u0000columnMap\\\";a:8:{s:2:\\\"id\\\";s:2:\\\"ID\\\";s:13:\\\"customer.name\\\";s:8:\\\"Customer\\\";s:11:\\\"total_price\\\";s:11:\\\"Total price\\\";s:8:\\\"discount\\\";s:8:\\\"Discount\\\";s:15:\\\"discount_amount\\\";s:15:\\\"Discount amount\\\";s:13:\\\"total_payment\\\";s:13:\\\"Total payment\\\";s:6:\\\"status\\\";s:6:\\\"Status\\\";s:4:\\\"date\\\";s:4:\\\"Date\\\";}s:10:\\\"\\u0000*\\u0000options\\\";a:0:{}}s:9:\\\"\\u0000*\\u0000export\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:38:\\\"Filament\\\\Actions\\\\Exports\\\\Models\\\\Export\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:8:\\\"\\u0000*\\u0000query\\\";s:618:\\\"O:36:\\\"AnourValar\\\\EloquentSerialize\\\\Package\\\":1:{s:42:\\\"\\u0000AnourValar\\\\EloquentSerialize\\\\Package\\u0000data\\\";a:4:{s:5:\\\"model\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:10:\\\"connection\\\";N;s:8:\\\"eloquent\\\";a:3:{s:4:\\\"with\\\";a:0:{}s:14:\\\"removed_scopes\\\";a:0:{}s:5:\\\"casts\\\";a:1:{s:2:\\\"id\\\";s:3:\\\"int\\\";}}s:5:\\\"query\\\";a:5:{s:8:\\\"bindings\\\";a:9:{s:6:\\\"select\\\";a:0:{}s:4:\\\"from\\\";a:0:{}s:4:\\\"join\\\";a:0:{}s:5:\\\"where\\\";a:0:{}s:7:\\\"groupBy\\\";a:0:{}s:6:\\\"having\\\";a:0:{}s:5:\\\"order\\\";a:0:{}s:5:\\\"union\\\";a:0:{}s:10:\\\"unionOrder\\\";a:0:{}}s:8:\\\"distinct\\\";b:0;s:4:\\\"from\\\";s:6:\\\"orders\\\";s:6:\\\"wheres\\\";a:0:{}s:6:\\\"orders\\\";a:1:{i:0;a:2:{s:6:\\\"column\\\";s:9:\\\"orders.id\\\";s:9:\\\"direction\\\";s:3:\\\"asc\\\";}}}}}\\\";s:12:\\\"\\u0000*\\u0000columnMap\\\";a:8:{s:2:\\\"id\\\";s:2:\\\"ID\\\";s:13:\\\"customer.name\\\";s:8:\\\"Customer\\\";s:11:\\\"total_price\\\";s:11:\\\"Total price\\\";s:8:\\\"discount\\\";s:8:\\\"Discount\\\";s:15:\\\"discount_amount\\\";s:15:\\\"Discount amount\\\";s:13:\\\"total_payment\\\";s:13:\\\"Total payment\\\";s:6:\\\"status\\\";s:6:\\\"Status\\\";s:4:\\\"date\\\";s:4:\\\"Date\\\";}s:10:\\\"\\u0000*\\u0000options\\\";a:0:{}s:12:\\\"\\u0000*\\u0000chunkSize\\\";i:100;s:10:\\\"\\u0000*\\u0000records\\\";N;}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:4:\\\"name\\\";s:0:\\\"\\\";s:7:\\\"options\\\";a:1:{s:13:\\\"allowFailures\\\";b:1;}s:7:\\\"batchId\\\";N;s:38:\\\"\\u0000Illuminate\\\\Bus\\\\ChainedBatch\\u0000fakeBatch\\\";N;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:2:{i:0;s:2764:\\\"O:46:\\\"Filament\\\\Actions\\\\Exports\\\\Jobs\\\\ExportCompletion\\\":5:{s:11:\\\"\\u0000*\\u0000exporter\\\";O:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\":3:{s:9:\\\"\\u0000*\\u0000export\\\";O:38:\\\"Filament\\\\Actions\\\\Exports\\\\Models\\\\Export\\\":33:{s:13:\\\"\\u0000*\\u0000connection\\\";s:5:\\\"mysql\\\";s:8:\\\"\\u0000*\\u0000table\\\";N;s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:19:\\\"preventsLazyLoading\\\";b:0;s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:1;s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:8:{s:7:\\\"user_id\\\";i:1;s:8:\\\"exporter\\\";s:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\";s:10:\\\"total_rows\\\";i:5;s:9:\\\"file_disk\\\";s:5:\\\"local\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-10-19 17:31:29\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-10-19 17:31:29\\\";s:2:\\\"id\\\";i:4;s:9:\\\"file_name\\\";s:15:\\\"export-4-orders\\\";}s:11:\\\"\\u0000*\\u0000original\\\";a:8:{s:7:\\\"user_id\\\";i:1;s:8:\\\"exporter\\\";s:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\";s:10:\\\"total_rows\\\";i:5;s:9:\\\"file_disk\\\";s:5:\\\"local\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-10-19 17:31:29\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-10-19 17:31:29\\\";s:2:\\\"id\\\";i:4;s:9:\\\"file_name\\\";s:15:\\\"export-4-orders\\\";}s:10:\\\"\\u0000*\\u0000changes\\\";a:1:{s:9:\\\"file_name\\\";s:15:\\\"export-4-orders\\\";}s:11:\\\"\\u0000*\\u0000previous\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:4:{s:12:\\\"completed_at\\\";s:9:\\\"timestamp\\\";s:14:\\\"processed_rows\\\";s:7:\\\"integer\\\";s:10:\\\"total_rows\\\";s:7:\\\"integer\\\";s:15:\\\"successful_rows\\\";s:7:\\\"integer\\\";}s:17:\\\"\\u0000*\\u0000classCastCache\\\";a:0:{}s:21:\\\"\\u0000*\\u0000attributeCastCache\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:27:\\\"\\u0000*\\u0000relationAutoloadCallback\\\";N;s:26:\\\"\\u0000*\\u0000relationAutoloadContext\\\";N;s:10:\\\"timestamps\\\";b:1;s:13:\\\"usesUniqueIds\\\";b:0;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}}s:12:\\\"\\u0000*\\u0000columnMap\\\";a:8:{s:2:\\\"id\\\";s:2:\\\"ID\\\";s:13:\\\"customer.name\\\";s:8:\\\"Customer\\\";s:11:\\\"total_price\\\";s:11:\\\"Total price\\\";s:8:\\\"discount\\\";s:8:\\\"Discount\\\";s:15:\\\"discount_amount\\\";s:15:\\\"Discount amount\\\";s:13:\\\"total_payment\\\";s:13:\\\"Total payment\\\";s:6:\\\"status\\\";s:6:\\\"Status\\\";s:4:\\\"date\\\";s:4:\\\"Date\\\";}s:10:\\\"\\u0000*\\u0000options\\\";a:0:{}}s:9:\\\"\\u0000*\\u0000export\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:38:\\\"Filament\\\\Actions\\\\Exports\\\\Models\\\\Export\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"\\u0000*\\u0000columnMap\\\";a:8:{s:2:\\\"id\\\";s:2:\\\"ID\\\";s:13:\\\"customer.name\\\";s:8:\\\"Customer\\\";s:11:\\\"total_price\\\";s:11:\\\"Total price\\\";s:8:\\\"discount\\\";s:8:\\\"Discount\\\";s:15:\\\"discount_amount\\\";s:15:\\\"Discount amount\\\";s:13:\\\"total_payment\\\";s:13:\\\"Total payment\\\";s:6:\\\"status\\\";s:6:\\\"Status\\\";s:4:\\\"date\\\";s:4:\\\"Date\\\";}s:10:\\\"\\u0000*\\u0000formats\\\";a:2:{i:0;E:47:\\\"Filament\\\\Actions\\\\Exports\\\\Enums\\\\ExportFormat:Csv\\\";i:1;E:48:\\\"Filament\\\\Actions\\\\Exports\\\\Enums\\\\ExportFormat:Xlsx\\\";}s:10:\\\"\\u0000*\\u0000options\\\";a:0:{}}\\\";i:1;s:2619:\\\"O:44:\\\"Filament\\\\Actions\\\\Exports\\\\Jobs\\\\CreateXlsxFile\\\":4:{s:11:\\\"\\u0000*\\u0000exporter\\\";O:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\":3:{s:9:\\\"\\u0000*\\u0000export\\\";O:38:\\\"Filament\\\\Actions\\\\Exports\\\\Models\\\\Export\\\":33:{s:13:\\\"\\u0000*\\u0000connection\\\";s:5:\\\"mysql\\\";s:8:\\\"\\u0000*\\u0000table\\\";N;s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:19:\\\"preventsLazyLoading\\\";b:0;s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:1;s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:8:{s:7:\\\"user_id\\\";i:1;s:8:\\\"exporter\\\";s:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\";s:10:\\\"total_rows\\\";i:5;s:9:\\\"file_disk\\\";s:5:\\\"local\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-10-19 17:31:29\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-10-19 17:31:29\\\";s:2:\\\"id\\\";i:4;s:9:\\\"file_name\\\";s:15:\\\"export-4-orders\\\";}s:11:\\\"\\u0000*\\u0000original\\\";a:8:{s:7:\\\"user_id\\\";i:1;s:8:\\\"exporter\\\";s:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\";s:10:\\\"total_rows\\\";i:5;s:9:\\\"file_disk\\\";s:5:\\\"local\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-10-19 17:31:29\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-10-19 17:31:29\\\";s:2:\\\"id\\\";i:4;s:9:\\\"file_name\\\";s:15:\\\"export-4-orders\\\";}s:10:\\\"\\u0000*\\u0000changes\\\";a:1:{s:9:\\\"file_name\\\";s:15:\\\"export-4-orders\\\";}s:11:\\\"\\u0000*\\u0000previous\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:4:{s:12:\\\"completed_at\\\";s:9:\\\"timestamp\\\";s:14:\\\"processed_rows\\\";s:7:\\\"integer\\\";s:10:\\\"total_rows\\\";s:7:\\\"integer\\\";s:15:\\\"successful_rows\\\";s:7:\\\"integer\\\";}s:17:\\\"\\u0000*\\u0000classCastCache\\\";a:0:{}s:21:\\\"\\u0000*\\u0000attributeCastCache\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:27:\\\"\\u0000*\\u0000relationAutoloadCallback\\\";N;s:26:\\\"\\u0000*\\u0000relationAutoloadContext\\\";N;s:10:\\\"timestamps\\\";b:1;s:13:\\\"usesUniqueIds\\\";b:0;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}}s:12:\\\"\\u0000*\\u0000columnMap\\\";a:8:{s:2:\\\"id\\\";s:2:\\\"ID\\\";s:13:\\\"customer.name\\\";s:8:\\\"Customer\\\";s:11:\\\"total_price\\\";s:11:\\\"Total price\\\";s:8:\\\"discount\\\";s:8:\\\"Discount\\\";s:15:\\\"discount_amount\\\";s:15:\\\"Discount amount\\\";s:13:\\\"total_payment\\\";s:13:\\\"Total payment\\\";s:6:\\\"status\\\";s:6:\\\"Status\\\";s:4:\\\"date\\\";s:4:\\\"Date\\\";}s:10:\\\"\\u0000*\\u0000options\\\";a:0:{}}s:9:\\\"\\u0000*\\u0000export\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:38:\\\"Filament\\\\Actions\\\\Exports\\\\Models\\\\Export\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"\\u0000*\\u0000columnMap\\\";a:8:{s:2:\\\"id\\\";s:2:\\\"ID\\\";s:13:\\\"customer.name\\\";s:8:\\\"Customer\\\";s:11:\\\"total_price\\\";s:11:\\\"Total price\\\";s:8:\\\"discount\\\";s:8:\\\"Discount\\\";s:15:\\\"discount_amount\\\";s:15:\\\"Discount amount\\\";s:13:\\\"total_payment\\\";s:13:\\\"Total payment\\\";s:6:\\\"status\\\";s:6:\\\"Status\\\";s:4:\\\"date\\\";s:4:\\\"Date\\\";}s:10:\\\"\\u0000*\\u0000options\\\";a:0:{}}\\\";}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";a:0:{}}\"},\"createdAt\":1760869889,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [Filament\\Actions\\Exports\\Models\\Export]. in C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:780\nStack trace:\n#0 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(110): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): Filament\\Actions\\Exports\\Jobs\\PrepareCsvExport->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(97): Filament\\Actions\\Exports\\Jobs\\PrepareCsvExport->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: Filament\\Actions\\Exports\\Jobs\\PrepareCsvExport->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(95): unserialize(\'O:27:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(62): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(451): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(401): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(344): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(836): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\symfony\\console\\Application.php(1110): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\symfony\\console\\Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\symfony\\console\\Application.php(194): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\seumpamapointv2\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2025-10-26 09:48:30'),
(2, '63a33613-e422-4ca7-82f7-2827dfb974a0', 'database', 'default', '{\"uuid\":\"63a33613-e422-4ca7-82f7-2827dfb974a0\",\"displayName\":\"Illuminate\\\\Bus\\\\ChainedBatch\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Bus\\\\ChainedBatch\",\"command\":\"O:27:\\\"Illuminate\\\\Bus\\\\ChainedBatch\\\":17:{s:4:\\\"jobs\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:0;O:46:\\\"Filament\\\\Actions\\\\Exports\\\\Jobs\\\\PrepareCsvExport\\\":7:{s:11:\\\"\\u0000*\\u0000exporter\\\";O:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\":3:{s:9:\\\"\\u0000*\\u0000export\\\";O:38:\\\"Filament\\\\Actions\\\\Exports\\\\Models\\\\Export\\\":33:{s:13:\\\"\\u0000*\\u0000connection\\\";s:5:\\\"mysql\\\";s:8:\\\"\\u0000*\\u0000table\\\";N;s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:19:\\\"preventsLazyLoading\\\";b:0;s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:1;s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:8:{s:7:\\\"user_id\\\";i:1;s:8:\\\"exporter\\\";s:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\";s:10:\\\"total_rows\\\";i:5;s:9:\\\"file_disk\\\";s:5:\\\"local\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-10-19 17:33:03\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-10-19 17:33:03\\\";s:2:\\\"id\\\";i:5;s:9:\\\"file_name\\\";s:15:\\\"export-5-orders\\\";}s:11:\\\"\\u0000*\\u0000original\\\";a:8:{s:7:\\\"user_id\\\";i:1;s:8:\\\"exporter\\\";s:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\";s:10:\\\"total_rows\\\";i:5;s:9:\\\"file_disk\\\";s:5:\\\"local\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-10-19 17:33:03\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-10-19 17:33:03\\\";s:2:\\\"id\\\";i:5;s:9:\\\"file_name\\\";s:15:\\\"export-5-orders\\\";}s:10:\\\"\\u0000*\\u0000changes\\\";a:1:{s:9:\\\"file_name\\\";s:15:\\\"export-5-orders\\\";}s:11:\\\"\\u0000*\\u0000previous\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:4:{s:12:\\\"completed_at\\\";s:9:\\\"timestamp\\\";s:14:\\\"processed_rows\\\";s:7:\\\"integer\\\";s:10:\\\"total_rows\\\";s:7:\\\"integer\\\";s:15:\\\"successful_rows\\\";s:7:\\\"integer\\\";}s:17:\\\"\\u0000*\\u0000classCastCache\\\";a:0:{}s:21:\\\"\\u0000*\\u0000attributeCastCache\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:27:\\\"\\u0000*\\u0000relationAutoloadCallback\\\";N;s:26:\\\"\\u0000*\\u0000relationAutoloadContext\\\";N;s:10:\\\"timestamps\\\";b:1;s:13:\\\"usesUniqueIds\\\";b:0;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}}s:12:\\\"\\u0000*\\u0000columnMap\\\";a:10:{s:2:\\\"id\\\";s:2:\\\"ID\\\";s:13:\\\"customer.name\\\";s:8:\\\"Customer\\\";s:11:\\\"total_price\\\";s:11:\\\"Total price\\\";s:8:\\\"discount\\\";s:8:\\\"Discount\\\";s:15:\\\"discount_amount\\\";s:15:\\\"Discount amount\\\";s:13:\\\"total_payment\\\";s:13:\\\"Total payment\\\";s:14:\\\"payment_status\\\";s:14:\\\"Payment status\\\";s:14:\\\"payment_method\\\";s:14:\\\"Payment method\\\";s:6:\\\"status\\\";s:6:\\\"Status\\\";s:4:\\\"date\\\";s:4:\\\"Date\\\";}s:10:\\\"\\u0000*\\u0000options\\\";a:0:{}}s:9:\\\"\\u0000*\\u0000export\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:38:\\\"Filament\\\\Actions\\\\Exports\\\\Models\\\\Export\\\";s:2:\\\"id\\\";i:5;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:8:\\\"\\u0000*\\u0000query\\\";s:618:\\\"O:36:\\\"AnourValar\\\\EloquentSerialize\\\\Package\\\":1:{s:42:\\\"\\u0000AnourValar\\\\EloquentSerialize\\\\Package\\u0000data\\\";a:4:{s:5:\\\"model\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:10:\\\"connection\\\";N;s:8:\\\"eloquent\\\";a:3:{s:4:\\\"with\\\";a:0:{}s:14:\\\"removed_scopes\\\";a:0:{}s:5:\\\"casts\\\";a:1:{s:2:\\\"id\\\";s:3:\\\"int\\\";}}s:5:\\\"query\\\";a:5:{s:8:\\\"bindings\\\";a:9:{s:6:\\\"select\\\";a:0:{}s:4:\\\"from\\\";a:0:{}s:4:\\\"join\\\";a:0:{}s:5:\\\"where\\\";a:0:{}s:7:\\\"groupBy\\\";a:0:{}s:6:\\\"having\\\";a:0:{}s:5:\\\"order\\\";a:0:{}s:5:\\\"union\\\";a:0:{}s:10:\\\"unionOrder\\\";a:0:{}}s:8:\\\"distinct\\\";b:0;s:4:\\\"from\\\";s:6:\\\"orders\\\";s:6:\\\"wheres\\\";a:0:{}s:6:\\\"orders\\\";a:1:{i:0;a:2:{s:6:\\\"column\\\";s:9:\\\"orders.id\\\";s:9:\\\"direction\\\";s:3:\\\"asc\\\";}}}}}\\\";s:12:\\\"\\u0000*\\u0000columnMap\\\";a:10:{s:2:\\\"id\\\";s:2:\\\"ID\\\";s:13:\\\"customer.name\\\";s:8:\\\"Customer\\\";s:11:\\\"total_price\\\";s:11:\\\"Total price\\\";s:8:\\\"discount\\\";s:8:\\\"Discount\\\";s:15:\\\"discount_amount\\\";s:15:\\\"Discount amount\\\";s:13:\\\"total_payment\\\";s:13:\\\"Total payment\\\";s:14:\\\"payment_status\\\";s:14:\\\"Payment status\\\";s:14:\\\"payment_method\\\";s:14:\\\"Payment method\\\";s:6:\\\"status\\\";s:6:\\\"Status\\\";s:4:\\\"date\\\";s:4:\\\"Date\\\";}s:10:\\\"\\u0000*\\u0000options\\\";a:0:{}s:12:\\\"\\u0000*\\u0000chunkSize\\\";i:100;s:10:\\\"\\u0000*\\u0000records\\\";N;}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:4:\\\"name\\\";s:0:\\\"\\\";s:7:\\\"options\\\";a:1:{s:13:\\\"allowFailures\\\";b:1;}s:7:\\\"batchId\\\";N;s:38:\\\"\\u0000Illuminate\\\\Bus\\\\ChainedBatch\\u0000fakeBatch\\\";N;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:2:{i:0;s:2942:\\\"O:46:\\\"Filament\\\\Actions\\\\Exports\\\\Jobs\\\\ExportCompletion\\\":5:{s:11:\\\"\\u0000*\\u0000exporter\\\";O:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\":3:{s:9:\\\"\\u0000*\\u0000export\\\";O:38:\\\"Filament\\\\Actions\\\\Exports\\\\Models\\\\Export\\\":33:{s:13:\\\"\\u0000*\\u0000connection\\\";s:5:\\\"mysql\\\";s:8:\\\"\\u0000*\\u0000table\\\";N;s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:19:\\\"preventsLazyLoading\\\";b:0;s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:1;s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:8:{s:7:\\\"user_id\\\";i:1;s:8:\\\"exporter\\\";s:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\";s:10:\\\"total_rows\\\";i:5;s:9:\\\"file_disk\\\";s:5:\\\"local\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-10-19 17:33:03\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-10-19 17:33:03\\\";s:2:\\\"id\\\";i:5;s:9:\\\"file_name\\\";s:15:\\\"export-5-orders\\\";}s:11:\\\"\\u0000*\\u0000original\\\";a:8:{s:7:\\\"user_id\\\";i:1;s:8:\\\"exporter\\\";s:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\";s:10:\\\"total_rows\\\";i:5;s:9:\\\"file_disk\\\";s:5:\\\"local\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-10-19 17:33:03\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-10-19 17:33:03\\\";s:2:\\\"id\\\";i:5;s:9:\\\"file_name\\\";s:15:\\\"export-5-orders\\\";}s:10:\\\"\\u0000*\\u0000changes\\\";a:1:{s:9:\\\"file_name\\\";s:15:\\\"export-5-orders\\\";}s:11:\\\"\\u0000*\\u0000previous\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:4:{s:12:\\\"completed_at\\\";s:9:\\\"timestamp\\\";s:14:\\\"processed_rows\\\";s:7:\\\"integer\\\";s:10:\\\"total_rows\\\";s:7:\\\"integer\\\";s:15:\\\"successful_rows\\\";s:7:\\\"integer\\\";}s:17:\\\"\\u0000*\\u0000classCastCache\\\";a:0:{}s:21:\\\"\\u0000*\\u0000attributeCastCache\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:27:\\\"\\u0000*\\u0000relationAutoloadCallback\\\";N;s:26:\\\"\\u0000*\\u0000relationAutoloadContext\\\";N;s:10:\\\"timestamps\\\";b:1;s:13:\\\"usesUniqueIds\\\";b:0;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}}s:12:\\\"\\u0000*\\u0000columnMap\\\";a:10:{s:2:\\\"id\\\";s:2:\\\"ID\\\";s:13:\\\"customer.name\\\";s:8:\\\"Customer\\\";s:11:\\\"total_price\\\";s:11:\\\"Total price\\\";s:8:\\\"discount\\\";s:8:\\\"Discount\\\";s:15:\\\"discount_amount\\\";s:15:\\\"Discount amount\\\";s:13:\\\"total_payment\\\";s:13:\\\"Total payment\\\";s:14:\\\"payment_status\\\";s:14:\\\"Payment status\\\";s:14:\\\"payment_method\\\";s:14:\\\"Payment method\\\";s:6:\\\"status\\\";s:6:\\\"Status\\\";s:4:\\\"date\\\";s:4:\\\"Date\\\";}s:10:\\\"\\u0000*\\u0000options\\\";a:0:{}}s:9:\\\"\\u0000*\\u0000export\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:38:\\\"Filament\\\\Actions\\\\Exports\\\\Models\\\\Export\\\";s:2:\\\"id\\\";i:5;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"\\u0000*\\u0000columnMap\\\";a:10:{s:2:\\\"id\\\";s:2:\\\"ID\\\";s:13:\\\"customer.name\\\";s:8:\\\"Customer\\\";s:11:\\\"total_price\\\";s:11:\\\"Total price\\\";s:8:\\\"discount\\\";s:8:\\\"Discount\\\";s:15:\\\"discount_amount\\\";s:15:\\\"Discount amount\\\";s:13:\\\"total_payment\\\";s:13:\\\"Total payment\\\";s:14:\\\"payment_status\\\";s:14:\\\"Payment status\\\";s:14:\\\"payment_method\\\";s:14:\\\"Payment method\\\";s:6:\\\"status\\\";s:6:\\\"Status\\\";s:4:\\\"date\\\";s:4:\\\"Date\\\";}s:10:\\\"\\u0000*\\u0000formats\\\";a:2:{i:0;E:47:\\\"Filament\\\\Actions\\\\Exports\\\\Enums\\\\ExportFormat:Csv\\\";i:1;E:48:\\\"Filament\\\\Actions\\\\Exports\\\\Enums\\\\ExportFormat:Xlsx\\\";}s:10:\\\"\\u0000*\\u0000options\\\";a:0:{}}\\\";i:1;s:2797:\\\"O:44:\\\"Filament\\\\Actions\\\\Exports\\\\Jobs\\\\CreateXlsxFile\\\":4:{s:11:\\\"\\u0000*\\u0000exporter\\\";O:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\":3:{s:9:\\\"\\u0000*\\u0000export\\\";O:38:\\\"Filament\\\\Actions\\\\Exports\\\\Models\\\\Export\\\":33:{s:13:\\\"\\u0000*\\u0000connection\\\";s:5:\\\"mysql\\\";s:8:\\\"\\u0000*\\u0000table\\\";N;s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:19:\\\"preventsLazyLoading\\\";b:0;s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:1;s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:8:{s:7:\\\"user_id\\\";i:1;s:8:\\\"exporter\\\";s:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\";s:10:\\\"total_rows\\\";i:5;s:9:\\\"file_disk\\\";s:5:\\\"local\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-10-19 17:33:03\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-10-19 17:33:03\\\";s:2:\\\"id\\\";i:5;s:9:\\\"file_name\\\";s:15:\\\"export-5-orders\\\";}s:11:\\\"\\u0000*\\u0000original\\\";a:8:{s:7:\\\"user_id\\\";i:1;s:8:\\\"exporter\\\";s:34:\\\"App\\\\Filament\\\\Exports\\\\OrderExporter\\\";s:10:\\\"total_rows\\\";i:5;s:9:\\\"file_disk\\\";s:5:\\\"local\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-10-19 17:33:03\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-10-19 17:33:03\\\";s:2:\\\"id\\\";i:5;s:9:\\\"file_name\\\";s:15:\\\"export-5-orders\\\";}s:10:\\\"\\u0000*\\u0000changes\\\";a:1:{s:9:\\\"file_name\\\";s:15:\\\"export-5-orders\\\";}s:11:\\\"\\u0000*\\u0000previous\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:4:{s:12:\\\"completed_at\\\";s:9:\\\"timestamp\\\";s:14:\\\"processed_rows\\\";s:7:\\\"integer\\\";s:10:\\\"total_rows\\\";s:7:\\\"integer\\\";s:15:\\\"successful_rows\\\";s:7:\\\"integer\\\";}s:17:\\\"\\u0000*\\u0000classCastCache\\\";a:0:{}s:21:\\\"\\u0000*\\u0000attributeCastCache\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:27:\\\"\\u0000*\\u0000relationAutoloadCallback\\\";N;s:26:\\\"\\u0000*\\u0000relationAutoloadContext\\\";N;s:10:\\\"timestamps\\\";b:1;s:13:\\\"usesUniqueIds\\\";b:0;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}}s:12:\\\"\\u0000*\\u0000columnMap\\\";a:10:{s:2:\\\"id\\\";s:2:\\\"ID\\\";s:13:\\\"customer.name\\\";s:8:\\\"Customer\\\";s:11:\\\"total_price\\\";s:11:\\\"Total price\\\";s:8:\\\"discount\\\";s:8:\\\"Discount\\\";s:15:\\\"discount_amount\\\";s:15:\\\"Discount amount\\\";s:13:\\\"total_payment\\\";s:13:\\\"Total payment\\\";s:14:\\\"payment_status\\\";s:14:\\\"Payment status\\\";s:14:\\\"payment_method\\\";s:14:\\\"Payment method\\\";s:6:\\\"status\\\";s:6:\\\"Status\\\";s:4:\\\"date\\\";s:4:\\\"Date\\\";}s:10:\\\"\\u0000*\\u0000options\\\";a:0:{}}s:9:\\\"\\u0000*\\u0000export\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:38:\\\"Filament\\\\Actions\\\\Exports\\\\Models\\\\Export\\\";s:2:\\\"id\\\";i:5;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"\\u0000*\\u0000columnMap\\\";a:10:{s:2:\\\"id\\\";s:2:\\\"ID\\\";s:13:\\\"customer.name\\\";s:8:\\\"Customer\\\";s:11:\\\"total_price\\\";s:11:\\\"Total price\\\";s:8:\\\"discount\\\";s:8:\\\"Discount\\\";s:15:\\\"discount_amount\\\";s:15:\\\"Discount amount\\\";s:13:\\\"total_payment\\\";s:13:\\\"Total payment\\\";s:14:\\\"payment_status\\\";s:14:\\\"Payment status\\\";s:14:\\\"payment_method\\\";s:14:\\\"Payment method\\\";s:6:\\\"status\\\";s:6:\\\"Status\\\";s:4:\\\"date\\\";s:4:\\\"Date\\\";}s:10:\\\"\\u0000*\\u0000options\\\";a:0:{}}\\\";}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";a:0:{}}\"},\"createdAt\":1760869983,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [Filament\\Actions\\Exports\\Models\\Export]. in C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:780\nStack trace:\n#0 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(110): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): Filament\\Actions\\Exports\\Jobs\\PrepareCsvExport->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(97): Filament\\Actions\\Exports\\Jobs\\PrepareCsvExport->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: Filament\\Actions\\Exports\\Jobs\\PrepareCsvExport->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(95): unserialize(\'O:27:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(62): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(451): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(401): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(187): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(836): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\symfony\\console\\Application.php(1110): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\symfony\\console\\Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\symfony\\console\\Application.php(194): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\seumpamapointv2\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\seumpamapointv2\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2025-10-26 09:48:40');

-- --------------------------------------------------------

--
-- Table structure for table `imports`
--

CREATE TABLE `imports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `importer` varchar(255) NOT NULL,
  `processed_rows` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_rows` int(10) UNSIGNED NOT NULL,
  `successful_rows` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `stock` decimal(10,2) NOT NULL DEFAULT 0.00,
  `min_stock` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cost_per_unit` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`, `unit`, `stock`, `min_stock`, `cost_per_unit`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 'Coffee Beans', 'gram', 0.00, 0.00, 0.00, 1, NULL, NULL),
(5, 'Espresso Shot', 'ml', 0.00, 0.00, 0.00, 1, NULL, NULL),
(6, 'Air', 'ml', 0.00, 0.00, 0.00, 1, NULL, NULL),
(7, 'Es Batu', 'gram', 0.00, 0.00, 0.00, 1, NULL, NULL),
(8, 'UHT Milk', 'ml', 0.00, 0.00, 0.00, 1, NULL, NULL),
(9, 'Oat Milk', 'ml', 0.00, 0.00, 0.00, 1, NULL, NULL),
(10, 'Krimer', 'gram', 0.00, 0.00, 0.00, 1, NULL, NULL),
(11, 'SKM', 'ml', 0.00, 0.00, 0.00, 1, NULL, NULL),
(12, 'Gula Cair', 'ml', 0.00, 0.00, 0.00, 1, NULL, NULL),
(13, 'Gula Aren', 'ml', 0.00, 0.00, 0.00, 1, NULL, NULL),
(14, 'Coklat Powder', 'gram', 0.00, 0.00, 0.00, 1, NULL, NULL),
(15, 'Matcha Powder', 'gram', 0.00, 0.00, 0.00, 1, NULL, NULL),
(16, 'Thai Tea Powder', 'gram', 0.00, 0.00, 0.00, 1, NULL, NULL),
(17, 'Green Tea Powder', 'gram', 0.00, 0.00, 0.00, 1, NULL, NULL),
(18, 'Black Series Powder', 'gram', 0.00, 0.00, 0.00, 1, NULL, NULL),
(19, 'Vanilla Liquid', 'ml', 0.00, 0.00, 0.00, 1, NULL, NULL),
(20, 'Brown Sugar Liquid', 'ml', 0.00, 0.00, 0.00, 1, NULL, NULL),
(21, 'Caramel Liquid', 'ml', 0.00, 0.00, 0.00, 1, NULL, NULL),
(22, 'Pandan Liquid', 'ml', 0.00, 0.00, 0.00, 1, NULL, NULL),
(23, 'Rum Liquid', 'ml', 0.00, 0.00, 0.00, 1, NULL, NULL),
(24, 'Osmanthus Liquid', 'ml', 0.00, 0.00, 0.00, 1, NULL, NULL),
(25, 'Soda Water', 'ml', 0.00, 0.00, 0.00, 1, NULL, NULL),
(26, 'Tea Bag', 'pcs', 0.00, 0.00, 0.00, 1, NULL, NULL),
(27, 'Lemon Tea Powder', 'gram', 0.00, 0.00, 0.00, 1, NULL, NULL),
(28, 'Lemon Kering', 'pcs', 0.00, 0.00, 0.00, 1, NULL, NULL),
(29, 'Coklat Powder Garnish', 'gram', 0.00, 0.00, 0.00, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_settings`
--

CREATE TABLE `invoice_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL DEFAULT 'Seumpama Coffee',
  `company_address` text DEFAULT NULL,
  `company_phone` varchar(255) DEFAULT NULL,
  `company_email` varchar(255) DEFAULT NULL,
  `invoice_title` varchar(255) NOT NULL DEFAULT 'INVOICE',
  `footer_text` text NOT NULL DEFAULT 'Thank you for your order!',
  `terms_conditions` text DEFAULT NULL,
  `font_size` varchar(255) NOT NULL DEFAULT 'normal',
  `show_preview` tinyint(1) NOT NULL DEFAULT 1,
  `font_family` varchar(255) NOT NULL DEFAULT 'Courier New',
  `paper_size` varchar(255) NOT NULL DEFAULT '80mm',
  `show_logo` tinyint(1) NOT NULL DEFAULT 1,
  `logo_path` varchar(255) DEFAULT NULL,
  `show_cash_details` tinyint(1) NOT NULL DEFAULT 1,
  `show_payment_summary` tinyint(1) NOT NULL DEFAULT 1,
  `auto_calculate_change` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_settings`
--

INSERT INTO `invoice_settings` (`id`, `company_name`, `company_address`, `company_phone`, `company_email`, `invoice_title`, `footer_text`, `terms_conditions`, `font_size`, `show_preview`, `font_family`, `paper_size`, `show_logo`, `logo_path`, `show_cash_details`, `show_payment_summary`, `auto_calculate_change`, `created_at`, `updated_at`) VALUES
(1, 'Seumpama Bunga', 'Malangnengah Blok No.12A, Kadu Agung, Tigaraksa, Tangerang', NULL, 'seumpamabunga25@gmail.com', 'INVOICE', 'Thank you for your order!', NULL, 'normal', 1, 'Courier New', '80mm', 1, 'invoice-settings/01K8FQRX8FAEP5B4F2PD5FGA50.png', 1, 1, 1, '2025-10-26 07:37:25', '2025-11-02 04:32:11');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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

--
-- Dumping data for table `job_batches`
--

INSERT INTO `job_batches` (`id`, `name`, `total_jobs`, `pending_jobs`, `failed_jobs`, `failed_job_ids`, `options`, `cancelled_at`, `created_at`, `finished_at`) VALUES
('a026710b-e5e3-4e97-a682-f0d86db21c22', '', 2, 0, 0, '[]', 'a:2:{s:13:\"allowFailures\";b:1;s:7:\"finally\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:6292:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:4:\"next\";O:46:\"Filament\\Actions\\Exports\\Jobs\\ExportCompletion\":7:{s:11:\"\0*\0exporter\";O:34:\"App\\Filament\\Exports\\OrderExporter\":3:{s:9:\"\0*\0export\";O:38:\"Filament\\Actions\\Exports\\Models\\Export\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";N;s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:1;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:34:\"App\\Filament\\Exports\\OrderExporter\";s:10:\"total_rows\";i:5;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2025-10-19 17:17:15\";s:10:\"created_at\";s:19:\"2025-10-19 17:17:15\";s:2:\"id\";i:1;s:9:\"file_name\";s:15:\"export-1-orders\";}s:11:\"\0*\0original\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:34:\"App\\Filament\\Exports\\OrderExporter\";s:10:\"total_rows\";i:5;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2025-10-19 17:17:15\";s:10:\"created_at\";s:19:\"2025-10-19 17:17:15\";s:2:\"id\";i:1;s:9:\"file_name\";s:15:\"export-1-orders\";}s:10:\"\0*\0changes\";a:1:{s:9:\"file_name\";s:15:\"export-1-orders\";}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:12:\"completed_at\";s:9:\"timestamp\";s:14:\"processed_rows\";s:7:\"integer\";s:10:\"total_rows\";s:7:\"integer\";s:15:\"successful_rows\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}}s:12:\"\0*\0columnMap\";a:10:{s:2:\"id\";s:2:\"ID\";s:13:\"customer.name\";s:8:\"Customer\";s:11:\"total_price\";s:11:\"Total price\";s:8:\"discount\";s:8:\"Discount\";s:15:\"discount_amount\";s:15:\"Discount amount\";s:13:\"total_payment\";s:13:\"Total payment\";s:14:\"payment_status\";s:14:\"Payment status\";s:14:\"payment_method\";s:14:\"Payment method\";s:6:\"status\";s:6:\"Status\";s:4:\"date\";s:4:\"Date\";}s:10:\"\0*\0options\";a:0:{}}s:9:\"\0*\0export\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:38:\"Filament\\Actions\\Exports\\Models\\Export\";s:2:\"id\";i:1;s:9:\"relations\";a:0:{}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}s:12:\"\0*\0columnMap\";a:10:{s:2:\"id\";s:2:\"ID\";s:13:\"customer.name\";s:8:\"Customer\";s:11:\"total_price\";s:11:\"Total price\";s:8:\"discount\";s:8:\"Discount\";s:15:\"discount_amount\";s:15:\"Discount amount\";s:13:\"total_payment\";s:13:\"Total payment\";s:14:\"payment_status\";s:14:\"Payment status\";s:14:\"payment_method\";s:14:\"Payment method\";s:6:\"status\";s:6:\"Status\";s:4:\"date\";s:4:\"Date\";}s:10:\"\0*\0formats\";a:2:{i:0;E:47:\"Filament\\Actions\\Exports\\Enums\\ExportFormat:Csv\";i:1;E:48:\"Filament\\Actions\\Exports\\Enums\\ExportFormat:Xlsx\";}s:10:\"\0*\0options\";a:0:{}s:7:\"chained\";a:1:{i:0;s:2797:\"O:44:\"Filament\\Actions\\Exports\\Jobs\\CreateXlsxFile\":4:{s:11:\"\0*\0exporter\";O:34:\"App\\Filament\\Exports\\OrderExporter\":3:{s:9:\"\0*\0export\";O:38:\"Filament\\Actions\\Exports\\Models\\Export\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";N;s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:1;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:34:\"App\\Filament\\Exports\\OrderExporter\";s:10:\"total_rows\";i:5;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2025-10-19 17:17:15\";s:10:\"created_at\";s:19:\"2025-10-19 17:17:15\";s:2:\"id\";i:1;s:9:\"file_name\";s:15:\"export-1-orders\";}s:11:\"\0*\0original\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:34:\"App\\Filament\\Exports\\OrderExporter\";s:10:\"total_rows\";i:5;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2025-10-19 17:17:15\";s:10:\"created_at\";s:19:\"2025-10-19 17:17:15\";s:2:\"id\";i:1;s:9:\"file_name\";s:15:\"export-1-orders\";}s:10:\"\0*\0changes\";a:1:{s:9:\"file_name\";s:15:\"export-1-orders\";}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:12:\"completed_at\";s:9:\"timestamp\";s:14:\"processed_rows\";s:7:\"integer\";s:10:\"total_rows\";s:7:\"integer\";s:15:\"successful_rows\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}}s:12:\"\0*\0columnMap\";a:10:{s:2:\"id\";s:2:\"ID\";s:13:\"customer.name\";s:8:\"Customer\";s:11:\"total_price\";s:11:\"Total price\";s:8:\"discount\";s:8:\"Discount\";s:15:\"discount_amount\";s:15:\"Discount amount\";s:13:\"total_payment\";s:13:\"Total payment\";s:14:\"payment_status\";s:14:\"Payment status\";s:14:\"payment_method\";s:14:\"Payment method\";s:6:\"status\";s:6:\"Status\";s:4:\"date\";s:4:\"Date\";}s:10:\"\0*\0options\";a:0:{}}s:9:\"\0*\0export\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:38:\"Filament\\Actions\\Exports\\Models\\Export\";s:2:\"id\";i:1;s:9:\"relations\";a:0:{}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}s:12:\"\0*\0columnMap\";a:10:{s:2:\"id\";s:2:\"ID\";s:13:\"customer.name\";s:8:\"Customer\";s:11:\"total_price\";s:11:\"Total price\";s:8:\"discount\";s:8:\"Discount\";s:15:\"discount_amount\";s:15:\"Discount amount\";s:13:\"total_payment\";s:13:\"Total payment\";s:14:\"payment_status\";s:14:\"Payment status\";s:14:\"payment_method\";s:14:\"Payment method\";s:6:\"status\";s:6:\"Status\";s:4:\"date\";s:4:\"Date\";}s:10:\"\0*\0options\";a:0:{}}\";}s:19:\"chainCatchCallbacks\";a:0:{}}}s:8:\"function\";s:266:\"function (\\Illuminate\\Bus\\Batch $batch) use ($next) {\n                if (! $batch->cancelled()) {\n                    \\Illuminate\\Container\\Container::getInstance()->make(\\Illuminate\\Contracts\\Bus\\Dispatcher::class)->dispatch($next);\n                }\n            }\";s:5:\"scope\";s:27:\"Illuminate\\Bus\\ChainedBatch\";s:4:\"this\";N;s:4:\"self\";s:32:\"000000000000092d0000000000000000\";}\";s:4:\"hash\";s:44:\"tf9sgntor9e8T/woQczXqpYWyy4ryipxO4DiV1fdL3s=\";}}}}', NULL, 1760869658, 1760869658),
('a026710b-fe7e-40e1-a2d2-54a68c90119f', '', 2, 0, 0, '[]', 'a:2:{s:13:\"allowFailures\";b:1;s:7:\"finally\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:6292:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:4:\"next\";O:46:\"Filament\\Actions\\Exports\\Jobs\\ExportCompletion\":7:{s:11:\"\0*\0exporter\";O:34:\"App\\Filament\\Exports\\OrderExporter\":3:{s:9:\"\0*\0export\";O:38:\"Filament\\Actions\\Exports\\Models\\Export\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";N;s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:1;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:34:\"App\\Filament\\Exports\\OrderExporter\";s:10:\"total_rows\";i:5;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2025-10-19 17:21:25\";s:10:\"created_at\";s:19:\"2025-10-19 17:21:25\";s:2:\"id\";i:2;s:9:\"file_name\";s:15:\"export-2-orders\";}s:11:\"\0*\0original\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:34:\"App\\Filament\\Exports\\OrderExporter\";s:10:\"total_rows\";i:5;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2025-10-19 17:21:25\";s:10:\"created_at\";s:19:\"2025-10-19 17:21:25\";s:2:\"id\";i:2;s:9:\"file_name\";s:15:\"export-2-orders\";}s:10:\"\0*\0changes\";a:1:{s:9:\"file_name\";s:15:\"export-2-orders\";}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:12:\"completed_at\";s:9:\"timestamp\";s:14:\"processed_rows\";s:7:\"integer\";s:10:\"total_rows\";s:7:\"integer\";s:15:\"successful_rows\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}}s:12:\"\0*\0columnMap\";a:10:{s:2:\"id\";s:2:\"ID\";s:13:\"customer.name\";s:8:\"Customer\";s:11:\"total_price\";s:11:\"Total price\";s:8:\"discount\";s:8:\"Discount\";s:15:\"discount_amount\";s:15:\"Discount amount\";s:13:\"total_payment\";s:13:\"Total payment\";s:14:\"payment_status\";s:14:\"Payment status\";s:14:\"payment_method\";s:14:\"Payment method\";s:6:\"status\";s:6:\"Status\";s:4:\"date\";s:4:\"Date\";}s:10:\"\0*\0options\";a:0:{}}s:9:\"\0*\0export\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:38:\"Filament\\Actions\\Exports\\Models\\Export\";s:2:\"id\";i:2;s:9:\"relations\";a:0:{}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}s:12:\"\0*\0columnMap\";a:10:{s:2:\"id\";s:2:\"ID\";s:13:\"customer.name\";s:8:\"Customer\";s:11:\"total_price\";s:11:\"Total price\";s:8:\"discount\";s:8:\"Discount\";s:15:\"discount_amount\";s:15:\"Discount amount\";s:13:\"total_payment\";s:13:\"Total payment\";s:14:\"payment_status\";s:14:\"Payment status\";s:14:\"payment_method\";s:14:\"Payment method\";s:6:\"status\";s:6:\"Status\";s:4:\"date\";s:4:\"Date\";}s:10:\"\0*\0formats\";a:2:{i:0;E:47:\"Filament\\Actions\\Exports\\Enums\\ExportFormat:Csv\";i:1;E:48:\"Filament\\Actions\\Exports\\Enums\\ExportFormat:Xlsx\";}s:10:\"\0*\0options\";a:0:{}s:7:\"chained\";a:1:{i:0;s:2797:\"O:44:\"Filament\\Actions\\Exports\\Jobs\\CreateXlsxFile\":4:{s:11:\"\0*\0exporter\";O:34:\"App\\Filament\\Exports\\OrderExporter\":3:{s:9:\"\0*\0export\";O:38:\"Filament\\Actions\\Exports\\Models\\Export\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";N;s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:1;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:34:\"App\\Filament\\Exports\\OrderExporter\";s:10:\"total_rows\";i:5;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2025-10-19 17:21:25\";s:10:\"created_at\";s:19:\"2025-10-19 17:21:25\";s:2:\"id\";i:2;s:9:\"file_name\";s:15:\"export-2-orders\";}s:11:\"\0*\0original\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:34:\"App\\Filament\\Exports\\OrderExporter\";s:10:\"total_rows\";i:5;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2025-10-19 17:21:25\";s:10:\"created_at\";s:19:\"2025-10-19 17:21:25\";s:2:\"id\";i:2;s:9:\"file_name\";s:15:\"export-2-orders\";}s:10:\"\0*\0changes\";a:1:{s:9:\"file_name\";s:15:\"export-2-orders\";}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:12:\"completed_at\";s:9:\"timestamp\";s:14:\"processed_rows\";s:7:\"integer\";s:10:\"total_rows\";s:7:\"integer\";s:15:\"successful_rows\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}}s:12:\"\0*\0columnMap\";a:10:{s:2:\"id\";s:2:\"ID\";s:13:\"customer.name\";s:8:\"Customer\";s:11:\"total_price\";s:11:\"Total price\";s:8:\"discount\";s:8:\"Discount\";s:15:\"discount_amount\";s:15:\"Discount amount\";s:13:\"total_payment\";s:13:\"Total payment\";s:14:\"payment_status\";s:14:\"Payment status\";s:14:\"payment_method\";s:14:\"Payment method\";s:6:\"status\";s:6:\"Status\";s:4:\"date\";s:4:\"Date\";}s:10:\"\0*\0options\";a:0:{}}s:9:\"\0*\0export\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:38:\"Filament\\Actions\\Exports\\Models\\Export\";s:2:\"id\";i:2;s:9:\"relations\";a:0:{}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}s:12:\"\0*\0columnMap\";a:10:{s:2:\"id\";s:2:\"ID\";s:13:\"customer.name\";s:8:\"Customer\";s:11:\"total_price\";s:11:\"Total price\";s:8:\"discount\";s:8:\"Discount\";s:15:\"discount_amount\";s:15:\"Discount amount\";s:13:\"total_payment\";s:13:\"Total payment\";s:14:\"payment_status\";s:14:\"Payment status\";s:14:\"payment_method\";s:14:\"Payment method\";s:6:\"status\";s:6:\"Status\";s:4:\"date\";s:4:\"Date\";}s:10:\"\0*\0options\";a:0:{}}\";}s:19:\"chainCatchCallbacks\";a:0:{}}}s:8:\"function\";s:266:\"function (\\Illuminate\\Bus\\Batch $batch) use ($next) {\n                if (! $batch->cancelled()) {\n                    \\Illuminate\\Container\\Container::getInstance()->make(\\Illuminate\\Contracts\\Bus\\Dispatcher::class)->dispatch($next);\n                }\n            }\";s:5:\"scope\";s:27:\"Illuminate\\Bus\\ChainedBatch\";s:4:\"this\";N;s:4:\"self\";s:32:\"00000000000009180000000000000000\";}\";s:4:\"hash\";s:44:\"As11yJyyrhaiEna2NPbsnJ3sz/1pgriWS8k4QQ0nWe8=\";}}}}', NULL, 1760869658, 1760869658),
('a02671b2-c144-40b5-8231-ed25c357ad30', '', 2, 0, 0, '[]', 'a:2:{s:13:\"allowFailures\";b:1;s:7:\"finally\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:6292:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:4:\"next\";O:46:\"Filament\\Actions\\Exports\\Jobs\\ExportCompletion\":7:{s:11:\"\0*\0exporter\";O:34:\"App\\Filament\\Exports\\OrderExporter\":3:{s:9:\"\0*\0export\";O:38:\"Filament\\Actions\\Exports\\Models\\Export\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";N;s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:1;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:34:\"App\\Filament\\Exports\\OrderExporter\";s:10:\"total_rows\";i:5;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2025-10-19 17:29:24\";s:10:\"created_at\";s:19:\"2025-10-19 17:29:24\";s:2:\"id\";i:3;s:9:\"file_name\";s:15:\"export-3-orders\";}s:11:\"\0*\0original\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:34:\"App\\Filament\\Exports\\OrderExporter\";s:10:\"total_rows\";i:5;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2025-10-19 17:29:24\";s:10:\"created_at\";s:19:\"2025-10-19 17:29:24\";s:2:\"id\";i:3;s:9:\"file_name\";s:15:\"export-3-orders\";}s:10:\"\0*\0changes\";a:1:{s:9:\"file_name\";s:15:\"export-3-orders\";}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:12:\"completed_at\";s:9:\"timestamp\";s:14:\"processed_rows\";s:7:\"integer\";s:10:\"total_rows\";s:7:\"integer\";s:15:\"successful_rows\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}}s:12:\"\0*\0columnMap\";a:10:{s:2:\"id\";s:2:\"ID\";s:13:\"customer.name\";s:8:\"Customer\";s:11:\"total_price\";s:11:\"Total price\";s:8:\"discount\";s:8:\"Discount\";s:15:\"discount_amount\";s:15:\"Discount amount\";s:13:\"total_payment\";s:13:\"Total payment\";s:14:\"payment_status\";s:14:\"Payment status\";s:14:\"payment_method\";s:14:\"Payment method\";s:6:\"status\";s:6:\"Status\";s:4:\"date\";s:4:\"Date\";}s:10:\"\0*\0options\";a:0:{}}s:9:\"\0*\0export\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:38:\"Filament\\Actions\\Exports\\Models\\Export\";s:2:\"id\";i:3;s:9:\"relations\";a:0:{}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}s:12:\"\0*\0columnMap\";a:10:{s:2:\"id\";s:2:\"ID\";s:13:\"customer.name\";s:8:\"Customer\";s:11:\"total_price\";s:11:\"Total price\";s:8:\"discount\";s:8:\"Discount\";s:15:\"discount_amount\";s:15:\"Discount amount\";s:13:\"total_payment\";s:13:\"Total payment\";s:14:\"payment_status\";s:14:\"Payment status\";s:14:\"payment_method\";s:14:\"Payment method\";s:6:\"status\";s:6:\"Status\";s:4:\"date\";s:4:\"Date\";}s:10:\"\0*\0formats\";a:2:{i:0;E:47:\"Filament\\Actions\\Exports\\Enums\\ExportFormat:Csv\";i:1;E:48:\"Filament\\Actions\\Exports\\Enums\\ExportFormat:Xlsx\";}s:10:\"\0*\0options\";a:0:{}s:7:\"chained\";a:1:{i:0;s:2797:\"O:44:\"Filament\\Actions\\Exports\\Jobs\\CreateXlsxFile\":4:{s:11:\"\0*\0exporter\";O:34:\"App\\Filament\\Exports\\OrderExporter\":3:{s:9:\"\0*\0export\";O:38:\"Filament\\Actions\\Exports\\Models\\Export\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";N;s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:1;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:34:\"App\\Filament\\Exports\\OrderExporter\";s:10:\"total_rows\";i:5;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2025-10-19 17:29:24\";s:10:\"created_at\";s:19:\"2025-10-19 17:29:24\";s:2:\"id\";i:3;s:9:\"file_name\";s:15:\"export-3-orders\";}s:11:\"\0*\0original\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:34:\"App\\Filament\\Exports\\OrderExporter\";s:10:\"total_rows\";i:5;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2025-10-19 17:29:24\";s:10:\"created_at\";s:19:\"2025-10-19 17:29:24\";s:2:\"id\";i:3;s:9:\"file_name\";s:15:\"export-3-orders\";}s:10:\"\0*\0changes\";a:1:{s:9:\"file_name\";s:15:\"export-3-orders\";}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:12:\"completed_at\";s:9:\"timestamp\";s:14:\"processed_rows\";s:7:\"integer\";s:10:\"total_rows\";s:7:\"integer\";s:15:\"successful_rows\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}}s:12:\"\0*\0columnMap\";a:10:{s:2:\"id\";s:2:\"ID\";s:13:\"customer.name\";s:8:\"Customer\";s:11:\"total_price\";s:11:\"Total price\";s:8:\"discount\";s:8:\"Discount\";s:15:\"discount_amount\";s:15:\"Discount amount\";s:13:\"total_payment\";s:13:\"Total payment\";s:14:\"payment_status\";s:14:\"Payment status\";s:14:\"payment_method\";s:14:\"Payment method\";s:6:\"status\";s:6:\"Status\";s:4:\"date\";s:4:\"Date\";}s:10:\"\0*\0options\";a:0:{}}s:9:\"\0*\0export\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:38:\"Filament\\Actions\\Exports\\Models\\Export\";s:2:\"id\";i:3;s:9:\"relations\";a:0:{}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}s:12:\"\0*\0columnMap\";a:10:{s:2:\"id\";s:2:\"ID\";s:13:\"customer.name\";s:8:\"Customer\";s:11:\"total_price\";s:11:\"Total price\";s:8:\"discount\";s:8:\"Discount\";s:15:\"discount_amount\";s:15:\"Discount amount\";s:13:\"total_payment\";s:13:\"Total payment\";s:14:\"payment_status\";s:14:\"Payment status\";s:14:\"payment_method\";s:14:\"Payment method\";s:6:\"status\";s:6:\"Status\";s:4:\"date\";s:4:\"Date\";}s:10:\"\0*\0options\";a:0:{}}\";}s:19:\"chainCatchCallbacks\";a:0:{}}}s:8:\"function\";s:266:\"function (\\Illuminate\\Bus\\Batch $batch) use ($next) {\n                if (! $batch->cancelled()) {\n                    \\Illuminate\\Container\\Container::getInstance()->make(\\Illuminate\\Contracts\\Bus\\Dispatcher::class)->dispatch($next);\n                }\n            }\";s:5:\"scope\";s:27:\"Illuminate\\Bus\\ChainedBatch\";s:4:\"this\";N;s:4:\"self\";s:32:\"00000000000008de0000000000000000\";}\";s:4:\"hash\";s:44:\"phwvc3ZJiKlCVR68fXqMpwXIYN6xyhtrCUEsX1x87Bs=\";}}}}', NULL, 1760869767, 1760869767);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_18_075704_create_customers_table', 2),
(5, '2025_10_18_081942_create_products_table', 3),
(6, '2025_10_18_133702_create_orders_table', 4),
(7, '2025_10_19_084055_create_attendances_table', 5),
(8, '2025_10_19_090235_create_order_details_table', 6),
(9, '2025_10_19_112008_add_column_to_products_table', 7),
(10, '2025_10_19_131847_add_column_to_orders_table', 8),
(11, '2025_10_19_163356_add_column_to_orders_table', 9),
(12, '2025_10_19_171243_create_imports_table', 10),
(13, '2025_10_19_171244_create_exports_table', 10),
(14, '2025_10_19_171245_create_failed_import_rows_table', 10),
(15, '2025_10_19_171902_create_notifications_table', 11),
(16, '2025_10_20_100330_create_permission_tables', 12),
(17, '2025_10_20_143520_update_attendances_table', 13),
(18, '2025_10_20_145641_attendances_table', 14),
(19, '2025_10_20_151720_add_location_to_attendances_table', 15),
(20, '2025_10_21_125759_create_categories_table', 16),
(21, '2025_10_21_125808_create_sub_categories_table', 16),
(22, '2025_10_21_125911_create_brands_table', 16),
(23, '2025_10_21_134850_create_sub_categories_table', 17),
(24, '2025_10_21_135602_create_navigation_settings_table', 18),
(25, '2025_10_21_141741_create_sub_categories_table', 19),
(26, '2025_10_21_142300_add_category_id_to_sub_categories_table', 20),
(27, '2025_10_21_152713_add_column_to_products_table', 21),
(28, '2025_10_22_211406_add_extra_fields_to_categories_table', 22),
(29, '2025_10_22_211643_remove_extra_fields_from_categories_table', 23),
(31, '2025_10_22_211923_simplify_orders_table', 24),
(32, '2025_10_23_010809_update_orders_table_for_new_structure', 24),
(33, '2025_10_23_010958_add_price_to_order_details_table', 24),
(34, '2025_10_23_011223_fix_orders_table_structure', 24),
(35, '2025_10_23_082525_add_index_to_products_name', 25),
(36, '2025_10_26_120714_add_cost_price_to_products_table', 26),
(37, '2025_10_26_143207_create_invoice_settings_table', 27),
(38, '2025_10_26_145904_add_paper_size_to_invoice_settings_table', 28),
(39, '2025_10_26_152441_add_auto_print_to_invoice_settings_table', 29),
(40, '2025_10_27_080434_create_printer_settings_table', 30),
(43, '2025_10_27_081806_remove_printer_fields_from_invoice_settings', 31),
(44, '2025_11_02_113433_add_cash_received_to_orders_table', 31),
(45, '2025_11_02_114310_fix_invoice_settings_font_columns', 31),
(46, '2025_11_02_124706_add_payment_settings_to_invoice_settings_table', 32),
(47, '2025_11_02_124721_remove_unused_fields_from_invoice_settings_table', 32),
(52, '2025_11_02_124734_add_cash_change_to_orders_table', 33),
(53, '2025_11_02_133134_add_customer_name_to_orders_table', 33),
(54, '2025_11_02_143013_modify_customer_name_in_orders_table', 34),
(55, '2025_11_02_170633_make_discount_nullable_in_orders_table', 34),
(56, '2025_11_02_170737_fix_orders_table_nullable_fields', 34),
(57, '2025_11_02_170756_remove_unused_columns_from_products', 35),
(58, '2025_11_03_133500_create_ingredients_table', 35),
(59, '2025_11_03_133504_create_product_ingredient_table', 35);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 6),
(1, 'App\\Models\\User', 7),
(2, 'App\\Models\\User', 4),
(2, 'App\\Models\\User', 8),
(2, 'App\\Models\\User', 9);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('31ef0be9-4300-4ef9-970e-465240d697ff', 'Filament\\Notifications\\DatabaseNotification', 'App\\Models\\User', 6, '{\"actions\":[],\"body\":\"Ingredient Milk is running low. Current: 0.00ml\",\"color\":null,\"duration\":\"persistent\",\"icon\":\"heroicon-o-exclamation-circle\",\"iconColor\":\"warning\",\"status\":\"warning\",\"title\":\"Low Stock Alert\",\"view\":\"filament-notifications::notification\",\"viewData\":[],\"format\":\"filament\"}', NULL, '2025-11-09 06:23:51', '2025-11-09 06:23:51'),
('8e730aa7-1d9e-49d6-99fd-c65a55210aaf', 'Filament\\Notifications\\DatabaseNotification', 'App\\Models\\User', 6, '{\"actions\":[],\"body\":\"Ingredient Milk is out of stock!\",\"color\":null,\"duration\":\"persistent\",\"icon\":\"heroicon-o-x-circle\",\"iconColor\":\"danger\",\"status\":\"danger\",\"title\":\"Out of Stock Alert\",\"view\":\"filament-notifications::notification\",\"viewData\":[],\"format\":\"filament\"}', NULL, '2025-11-09 06:23:51', '2025-11-09 06:23:51');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `total_price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'new',
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total_payment` decimal(10,2) NOT NULL,
  `payment_status` enum('paid','unpaid','failed') NOT NULL DEFAULT 'unpaid',
  `cash_received` decimal(12,2) DEFAULT NULL,
  `cash_change` decimal(12,2) DEFAULT NULL,
  `payment_method` enum('cash','qris','debit') NOT NULL DEFAULT 'qris'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `total_price`, `created_at`, `updated_at`, `status`, `discount`, `discount_amount`, `total_payment`, `payment_status`, `cash_received`, `cash_change`, `payment_method`) VALUES
(41, NULL, 18000.00, '2025-11-02 03:48:13', '2025-11-02 04:44:28', 'completed', 0.00, 0.00, 18000.00, 'paid', NULL, NULL, 'cash'),
(42, NULL, 18000.00, '2025-11-02 05:57:15', '2025-11-02 05:57:15', 'new', 0.00, 0.00, 18000.00, 'paid', NULL, NULL, 'cash'),
(43, NULL, 18000.00, '2025-11-02 05:58:38', '2025-11-02 06:22:20', 'completed', 0.00, 0.00, 18000.00, 'paid', 50000.00, NULL, 'cash'),
(44, NULL, 30000.00, '2025-11-02 06:51:25', '2025-11-02 06:51:25', 'completed', 50.00, 15000.00, 15000.00, 'paid', 50000.00, NULL, 'cash'),
(45, NULL, 18000.00, '2025-11-02 07:32:27', '2025-11-02 07:32:27', 'new', 0.00, 0.00, 18000.00, 'paid', 50000.00, NULL, 'cash'),
(46, NULL, 18000.00, '2025-11-02 09:59:48', '2025-11-02 09:59:48', 'completed', 0.00, 0.00, 18000.00, 'paid', NULL, NULL, 'cash'),
(47, 'Sheva', 90000.00, '2025-11-02 10:17:07', '2025-11-02 10:17:07', 'completed', 0.00, 0.00, 90000.00, 'paid', NULL, NULL, 'cash'),
(48, 'Sheva', 360000.00, '2025-11-02 10:22:23', '2025-11-02 10:22:23', 'completed', 0.00, 0.00, 360000.00, 'paid', NULL, 0.00, 'qris'),
(49, 'Fariz', 360000.00, '2025-11-02 10:27:45', '2025-11-02 10:27:45', 'new', 0.00, 0.00, 360000.00, 'paid', NULL, 0.00, 'qris'),
(50, 'Febry', 360000.00, '2025-11-02 10:34:15', '2025-11-02 10:34:15', 'completed', 0.00, 0.00, 360000.00, 'paid', NULL, 0.00, 'qris'),
(51, NULL, 18000.00, '2025-11-08 03:10:46', '2025-11-08 03:10:46', 'new', 0.00, 0.00, 18000.00, 'paid', NULL, 0.00, 'qris'),
(52, 'Febry', 18000.00, '2025-11-08 03:48:30', '2025-11-08 03:48:30', 'new', 2.00, 360.00, 17640.00, 'paid', NULL, 0.00, 'qris'),
(53, 'Fariz', 360000.00, '2025-11-08 04:01:48', '2025-11-08 04:02:22', 'completed', 0.00, 0.00, 360000.00, 'paid', NULL, NULL, 'cash'),
(55, 'Fariz', 90000.00, '2025-11-09 06:17:18', '2025-11-09 06:18:13', 'cancelled', 0.00, 0.00, 90000.00, 'paid', 90000.00, 0.00, 'cash'),
(56, 'Sheva', 90000.00, '2025-11-09 06:23:50', '2025-11-09 06:24:56', 'cancelled', 0.00, 0.00, 90000.00, 'paid', NULL, 0.00, 'qris'),
(57, 'Sheva', 198000.00, '2025-11-09 06:34:49', '2025-11-09 06:36:41', 'cancelled', 0.00, 0.00, 198000.00, 'paid', 200000.00, 2000.00, 'cash'),
(58, 'Fariz', 18000.00, '2025-11-29 03:52:41', '2025-11-29 03:52:41', 'new', 0.00, 0.00, 18000.00, 'paid', NULL, NULL, 'cash');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `printer_settings`
--

CREATE TABLE `printer_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `auto_print` tinyint(1) NOT NULL DEFAULT 0,
  `printer_name` varchar(255) DEFAULT NULL,
  `printer_connection` varchar(255) NOT NULL DEFAULT 'usb',
  `paper_size` varchar(255) NOT NULL DEFAULT '80mm',
  `copies` int(11) NOT NULL DEFAULT 1,
  `test_mode` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `printer_settings`
--

INSERT INTO `printer_settings` (`id`, `auto_print`, `printer_name`, `printer_connection`, `paper_size`, `copies`, `test_mode`, `created_at`, `updated_at`) VALUES
(1, 1, 'Brother HL-T4000DW Printer', 'usb', '58mm', 1, 1, '2025-10-27 01:39:01', '2025-10-31 07:12:14');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `in_stock` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `cost_price`, `stock`, `created_at`, `updated_at`, `image`, `category_id`, `is_active`, `in_stock`) VALUES
(13, 'Black Flower', 25000.00, 10100.00, 0, '2025-12-13 12:09:30', '2025-12-13 12:09:30', NULL, 1, 1, 1),
(14, 'Koffie Bunga', 25000.00, 10100.00, 0, '2025-12-13 12:14:13', '2025-12-13 12:14:13', NULL, 1, 1, 1),
(15, 'Seumpama Kopi', 22000.00, 8700.00, 0, '2025-12-13 12:15:32', '2025-12-13 12:15:32', NULL, 1, 1, 1),
(16, 'Air Mineral', 6000.00, 3000.00, 0, '2025-12-13 12:18:29', '2025-12-13 12:18:29', NULL, 2, 1, 1),
(17, 'Americano', 20000.00, 5100.00, 0, '2025-12-13 12:19:39', '2025-12-13 12:19:39', NULL, 1, 1, 1),
(18, 'Black Lemonade', 25000.00, 10100.00, 0, '2025-12-13 12:21:39', '2025-12-13 12:21:39', NULL, 1, 1, 1),
(19, 'Black Rum', 25000.00, 10100.00, 0, '2025-12-13 12:22:47', '2025-12-13 12:22:47', NULL, 1, 1, 1),
(20, 'Cafe Latte', 23000.00, 8700.00, 0, '2025-12-13 12:24:21', '2025-12-13 12:24:21', NULL, 1, 1, 1),
(21, 'Cappucino', 23000.00, 8700.00, 0, '2025-12-13 12:26:04', '2025-12-13 12:26:04', NULL, 1, 1, 1),
(22, 'Cireng', 10000.00, 3000.00, 0, '2025-12-13 12:26:35', '2025-12-13 12:26:35', NULL, 4, 1, 1),
(23, 'Dimsum', 20000.00, 12000.00, 0, '2025-12-13 12:26:58', '2025-12-13 12:26:58', NULL, 4, 1, 1),
(25, 'Dimsum Mentai', 22000.00, 16000.00, 0, '2025-12-13 12:28:39', '2025-12-13 12:28:39', NULL, 4, 1, 1),
(26, 'Double Shot', 4000.00, 2000.00, 0, '2025-12-13 12:29:39', '2025-12-13 12:29:39', NULL, 1, 1, 1),
(27, 'French Fries', 15000.00, 9100.00, 0, '2025-12-13 12:30:09', '2025-12-13 12:30:09', NULL, 4, 1, 1),
(28, 'Green Tea', 22000.00, 6700.00, 0, '2025-12-13 12:31:00', '2025-12-13 12:31:00', NULL, 2, 1, 1),
(29, 'Iced Tea', 15000.00, 6000.00, 0, '2025-12-13 12:34:55', '2025-12-13 12:34:55', NULL, 2, 1, 1),
(30, 'Indomie Double', 18000.00, 11000.00, 0, '2025-12-13 12:35:31', '2025-12-13 12:35:31', NULL, 3, 1, 1),
(31, 'Indomie Single', 13000.00, 5500.00, 0, '2025-12-13 12:35:55', '2025-12-13 12:35:55', NULL, 3, 1, 1),
(32, 'V60', 30000.00, 10500.00, 0, '2025-12-13 12:37:22', '2025-12-13 12:37:22', NULL, 1, 1, 1),
(33, 'Japanese', 30000.00, 10500.00, 0, '2025-12-13 12:38:28', '2025-12-13 12:38:28', NULL, 1, 1, 1),
(34, 'Koffie Butterscotch', 27000.00, NULL, 0, '2025-12-13 12:39:20', '2025-12-13 12:39:20', NULL, 1, 1, 1),
(35, 'Koffie Caramel', 27000.00, 10900.00, 0, '2025-12-13 12:40:58', '2025-12-13 12:40:58', NULL, 1, 1, 1),
(36, 'Koffie Kayu Manis', 27000.00, 10100.00, 0, '2025-12-13 12:42:03', '2025-12-13 12:42:03', NULL, 1, 1, 1),
(37, 'Koffie Klasik', 20000.00, 8700.00, 0, '2025-12-13 12:43:26', '2025-12-13 12:43:26', NULL, 1, 1, 1),
(38, 'Koffie Pandan', 27000.00, 10900.00, 0, '2025-12-13 12:44:51', '2025-12-13 12:44:51', NULL, 1, 1, 1),
(39, 'Korek Api', 10000.00, 6800.00, 0, '2025-12-13 12:45:42', '2025-12-13 12:45:42', NULL, 5, 1, 1),
(40, 'Lemon Tea', 18000.00, 8000.00, 0, '2025-12-13 12:47:04', '2025-12-13 12:47:04', NULL, 2, 1, 1),
(41, 'Matcha', 27000.00, NULL, 0, '2025-12-13 12:48:37', '2025-12-13 12:48:37', NULL, 2, 1, 1),
(42, 'Mie Nyemek', 20000.00, 12500.00, 0, '2025-12-13 12:49:13', '2025-12-13 12:49:13', NULL, 3, 1, 1),
(43, 'Mix Platter', 20000.00, 13300.00, 0, '2025-12-13 12:49:47', '2025-12-13 12:49:47', NULL, 4, 1, 1),
(44, 'Otak-otak', 15000.00, 7000.00, 0, '2025-12-13 12:50:05', '2025-12-13 12:50:05', NULL, 4, 1, 1),
(45, 'Rice Bowl - Chiken Katsu', 25000.00, 15000.00, 0, '2025-12-13 12:50:55', '2025-12-13 12:50:55', NULL, 3, 1, 1),
(46, 'Rice Bowl - Chiken Karage', 25000.00, 15000.00, 0, '2025-12-13 12:51:26', '2025-12-13 12:51:26', NULL, 3, 1, 1),
(47, 'Roti Bakar', 18000.00, NULL, 0, '2025-12-13 12:51:48', '2025-12-13 12:51:48', NULL, 4, 1, 1),
(48, 'Telur', 3000.00, NULL, 0, '2025-12-13 12:52:07', '2025-12-13 12:52:07', NULL, 3, 1, 1),
(49, 'Thai Tea', 20000.00, 5300.00, 0, '2025-12-13 12:53:36', '2025-12-13 12:53:36', NULL, 2, 1, 1),
(50, 'Koffee Tubruk', 15000.00, 5000.00, 0, '2025-12-13 12:54:35', '2025-12-13 12:54:35', NULL, 1, 1, 1),
(51, 'Vanilla Latte', 27000.00, 10900.00, 0, '2025-12-13 12:55:44', '2025-12-13 12:55:44', NULL, 1, 1, 1),
(52, 'Vietnam Drip', 18000.00, 6500.00, 0, '2025-12-13 12:56:51', '2025-12-13 12:56:51', NULL, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_ingredient`
--

CREATE TABLE `product_ingredient` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `ingredient_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_ingredient`
--

INSERT INTO `product_ingredient` (`id`, `product_id`, `ingredient_id`, `quantity`, `created_at`, `updated_at`) VALUES
(7, 13, 24, 25.00, '2025-12-13 12:09:30', '2025-12-13 12:09:30'),
(8, 13, 5, 30.00, '2025-12-13 12:09:30', '2025-12-13 12:09:30'),
(9, 13, 6, 100.00, '2025-12-13 12:09:30', '2025-12-13 12:09:30'),
(10, 15, 5, 30.00, '2025-12-13 12:15:32', '2025-12-13 12:15:32'),
(11, 15, 8, 100.00, '2025-12-13 12:15:32', '2025-12-13 12:15:32'),
(12, 15, 13, 15.00, '2025-12-13 12:15:32', '2025-12-13 12:15:32'),
(13, 18, 18, 10.00, '2025-12-13 12:21:39', '2025-12-13 12:21:39'),
(14, 18, 5, 30.00, '2025-12-13 12:21:39', '2025-12-13 12:21:39'),
(15, 18, 25, 100.00, '2025-12-13 12:21:39', '2025-12-13 12:21:39'),
(16, 18, 28, 1.00, '2025-12-13 12:21:39', '2025-12-13 12:21:39'),
(17, 19, 23, 20.00, '2025-12-13 12:22:47', '2025-12-13 12:22:47'),
(18, 19, 25, 100.00, '2025-12-13 12:22:47', '2025-12-13 12:22:47'),
(19, 19, 5, 30.00, '2025-12-13 12:22:47', '2025-12-13 12:22:47'),
(20, 20, 5, 30.00, '2025-12-13 12:24:21', '2025-12-13 12:24:21'),
(21, 20, 8, 100.00, '2025-12-13 12:24:21', '2025-12-13 12:24:21'),
(22, 20, 7, 110.00, '2025-12-13 12:24:21', '2025-12-13 12:24:21'),
(23, 21, 5, 30.00, '2025-12-13 12:26:04', '2025-12-13 12:26:04'),
(24, 21, 8, 100.00, '2025-12-13 12:26:04', '2025-12-13 12:26:04'),
(25, 21, 14, 2.00, '2025-12-13 12:26:04', '2025-12-13 12:26:04'),
(26, 21, 7, 110.00, '2025-12-13 12:26:04', '2025-12-13 12:26:04'),
(27, 26, 5, 60.00, '2025-12-13 12:29:39', '2025-12-13 12:29:39'),
(28, 28, 17, 10.00, '2025-12-13 12:31:00', '2025-12-13 12:32:44'),
(29, 28, 6, 200.00, '2025-12-13 12:31:00', '2025-12-13 12:32:44'),
(30, 28, 10, 10.00, '2025-12-13 12:32:44', '2025-12-13 12:32:44'),
(31, 28, 11, 38.00, '2025-12-13 12:32:44', '2025-12-13 12:32:44'),
(32, 29, 26, 1.00, '2025-12-13 12:34:55', '2025-12-13 12:34:55'),
(33, 29, 6, 150.00, '2025-12-13 12:34:55', '2025-12-13 12:34:55'),
(34, 29, 12, 60.00, '2025-12-13 12:34:55', '2025-12-13 12:34:55'),
(35, 29, 7, 130.00, '2025-12-13 12:34:55', '2025-12-13 12:34:55'),
(36, 32, 4, 15.00, '2025-12-13 12:37:22', '2025-12-13 12:37:22'),
(37, 32, 6, 225.00, '2025-12-13 12:37:22', '2025-12-13 12:37:22'),
(38, 33, 4, 15.00, '2025-12-13 12:38:28', '2025-12-13 12:38:28'),
(39, 33, 6, 150.00, '2025-12-13 12:38:28', '2025-12-13 12:38:28'),
(40, 33, 7, 105.00, '2025-12-13 12:38:28', '2025-12-13 12:38:28'),
(41, 35, 5, 30.00, '2025-12-13 12:40:58', '2025-12-13 12:40:58'),
(42, 35, 8, 100.00, '2025-12-13 12:40:58', '2025-12-13 12:40:58'),
(43, 35, 21, 25.00, '2025-12-13 12:40:58', '2025-12-13 12:40:58'),
(44, 37, 9, 200.00, '2025-12-13 12:43:26', '2025-12-13 12:43:26'),
(45, 37, 12, 20.00, '2025-12-13 12:43:26', '2025-12-13 12:43:26'),
(46, 38, 5, 30.00, '2025-12-13 12:44:51', '2025-12-13 12:44:51'),
(47, 38, 8, 100.00, '2025-12-13 12:44:51', '2025-12-13 12:44:51'),
(48, 38, 22, 25.00, '2025-12-13 12:44:51', '2025-12-13 12:44:51'),
(49, 40, 27, 25.00, '2025-12-13 12:47:04', '2025-12-13 12:47:04'),
(50, 40, 12, 20.00, '2025-12-13 12:47:04', '2025-12-13 12:47:04'),
(51, 40, 6, 150.00, '2025-12-13 12:47:04', '2025-12-13 12:47:04'),
(52, 40, 7, 130.00, '2025-12-13 12:47:04', '2025-12-13 12:47:04'),
(53, 41, 15, 8.00, '2025-12-13 12:48:37', '2025-12-13 12:48:37'),
(54, 41, 6, 30.00, '2025-12-13 12:48:37', '2025-12-13 12:48:37'),
(55, 41, 8, 100.00, '2025-12-13 12:48:37', '2025-12-13 12:48:37'),
(56, 41, 12, 40.00, '2025-12-13 12:48:37', '2025-12-13 12:48:37'),
(57, 49, 16, 10.00, '2025-12-13 12:53:36', '2025-12-13 12:53:36'),
(58, 49, 6, 200.00, '2025-12-13 12:53:36', '2025-12-13 12:53:36'),
(59, 49, 10, 10.00, '2025-12-13 12:53:36', '2025-12-13 12:53:36'),
(60, 49, 11, 38.00, '2025-12-13 12:53:36', '2025-12-13 12:53:36'),
(61, 50, 4, 10.00, '2025-12-13 12:54:35', '2025-12-13 12:54:35'),
(62, 50, 6, 190.00, '2025-12-13 12:54:35', '2025-12-13 12:54:35'),
(63, 51, 5, 30.00, '2025-12-13 12:55:44', '2025-12-13 12:55:44'),
(64, 51, 8, 100.00, '2025-12-13 12:55:44', '2025-12-13 12:55:44'),
(65, 51, 19, 25.00, '2025-12-13 12:55:44', '2025-12-13 12:55:44'),
(66, 52, 4, 15.00, '2025-12-13 12:56:51', '2025-12-13 12:56:51'),
(67, 52, 11, 50.00, '2025-12-13 12:56:51', '2025-12-13 12:56:51'),
(68, 52, 6, 220.00, '2025-12-13 12:56:51', '2025-12-13 12:56:51');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-10-20 03:07:42', '2025-10-20 03:07:42'),
(2, 'cashier', 'web', '2025-10-20 03:07:42', '2025-10-20 03:07:42');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
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
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('pxaSbJOaBSpEbOCKYFMhNs0PDcWQRjsHY1ndNr0L', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiSVRlZ2NXM0Fodlo1VVg4Q2xpTWhPY0phWklzVGRDRWZ2a29GTm1UZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zZXR0aW5ncyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo3O3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkQVkwb2o2b3pZaHp3TmJaVGh3YjhST3lrY0MzcWdhQVB0Y0xNdHZMejQveGpyUjU0dGsxdGUiO3M6ODoiZmlsYW1lbnQiO2E6MDp7fX0=', 1761907371),
('ymiqLgxV0i19RrMcqe5xYji3vS2SQAhtf1hN76Zc', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid09XcTV1N0V1bFRYVnRLUGdJMjlkaVpyb1hmeEZLZmJ3SnJ1MUcwMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9kZWJ1Zy1vcmRlci1ldmVudHMvMTkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1761900895);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(4, 'Fariz Afdilah Muhamad', 'fariz@seumpama.com', NULL, '$2y$12$oqsP684ozVLKkf6f1gLxV.FFGHxzbOuFWEhgh5tFmJOg.1YqURHR6', NULL, '2025-10-18 00:41:15', '2025-10-20 07:02:35'),
(6, 'Febry', 'febry@seumpama.com', NULL, '$2y$12$lBSPwdutH6qRQMxVYDddXOOIJmfAuR3rghd6Fv/eFtanaVXhs2d7y', NULL, '2025-10-19 05:01:50', '2025-10-20 07:05:06'),
(7, 'Super Admin', 'admin@seumpama.com', '2025-12-12 12:13:48', '$2y$12$7QPDfmHp1M.CCY5df7dq8uJeVYBRt4Ml/XQchqJZjgRqCQDz30Pwu', '7SUQhPoeiCxJ9UXRGnN2cBw1QySeffJCyuxt3hjT2wn1k4cHoTFbbHBfyr9O', '2025-10-20 06:39:59', '2025-12-12 12:14:05'),
(8, 'Sheva Ardiansyah', 'sheva@seumpama.com', NULL, '$2y$12$b4qLHw1WRMe9Xw068x.oWuexVOqvDGX519y2ahC5AjL1Hzswu4XdS', NULL, '2025-10-20 07:07:23', '2025-10-20 07:07:23'),
(9, 'Arinda', 'arinda@seumpama.com', '2025-10-20 07:21:04', '$2y$12$lnfAjM48TL0pozD/ghE7DO1jFCtf9qCjHcrpIYtrrjvp21BGhzh/6', NULL, '2025-10-20 07:21:11', '2025-10-20 07:21:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exports`
--
ALTER TABLE `exports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exports_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_import_rows`
--
ALTER TABLE `failed_import_rows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `failed_import_rows_import_id_foreign` (`import_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `imports`
--
ALTER TABLE `imports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `imports_user_id_foreign` (`user_id`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_settings`
--
ALTER TABLE `invoice_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_product_id_foreign` (`product_id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `printer_settings`
--
ALTER TABLE `printer_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_name_index` (`name`),
  ADD KEY `products_stock_index` (`stock`);

--
-- Indexes for table `product_ingredient`
--
ALTER TABLE `product_ingredient`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_ingredient_product_id_ingredient_id_unique` (`product_id`,`ingredient_id`),
  ADD KEY `product_ingredient_ingredient_id_foreign` (`ingredient_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `exports`
--
ALTER TABLE `exports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_import_rows`
--
ALTER TABLE `failed_import_rows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `imports`
--
ALTER TABLE `imports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `invoice_settings`
--
ALTER TABLE `invoice_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `printer_settings`
--
ALTER TABLE `printer_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `product_ingredient`
--
ALTER TABLE `product_ingredient`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exports`
--
ALTER TABLE `exports`
  ADD CONSTRAINT `exports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `failed_import_rows`
--
ALTER TABLE `failed_import_rows`
  ADD CONSTRAINT `failed_import_rows_import_id_foreign` FOREIGN KEY (`import_id`) REFERENCES `imports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `imports`
--
ALTER TABLE `imports`
  ADD CONSTRAINT `imports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_ingredient`
--
ALTER TABLE `product_ingredient`
  ADD CONSTRAINT `product_ingredient_ingredient_id_foreign` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ingredient_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
