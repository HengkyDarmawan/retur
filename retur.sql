-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2026 at 11:33 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `retur`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_expeditions`
--

CREATE TABLE `m_expeditions` (
  `id` int(11) NOT NULL,
  `expedition_name` varchar(100) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_expeditions`
--

INSERT INTO `m_expeditions` (`id`, `expedition_name`, `is_active`, `created_at`, `created_by`) VALUES
(1, 'JNE', 1, '2026-03-03 11:40:55', NULL),
(2, 'J&T Express', 1, '2026-03-03 11:40:55', NULL),
(3, 'SiCepat', 1, '2026-03-03 11:40:55', NULL),
(4, 'Anteraja', 1, '2026-03-03 11:40:55', NULL),
(5, 'GoSend/Grab', 1, '2026-03-03 11:40:55', NULL),
(6, 'Wahana', 1, '2026-03-03 11:40:55', NULL),
(7, 'TIKI', 1, '2026-03-03 11:40:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_holidays`
--

CREATE TABLE `m_holidays` (
  `id` int(11) NOT NULL,
  `holiday_date` date NOT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_holidays`
--

INSERT INTO `m_holidays` (`id`, `holiday_date`, `description`) VALUES
(1, '2026-12-25', 'Libur Natal'),
(2, '2026-03-20', 'Libur Lebaran'),
(3, '2026-03-21', 'Libur Lebaran');

-- --------------------------------------------------------

--
-- Table structure for table `m_platforms`
--

CREATE TABLE `m_platforms` (
  `id` int(11) NOT NULL,
  `platform_name` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_platforms`
--

INSERT INTO `m_platforms` (`id`, `platform_name`, `created_at`, `created_by`) VALUES
(1, 'Shopee', '2026-03-02 16:11:51', NULL),
(2, 'Tokopedia', '2026-03-02 16:11:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_return_types`
--

CREATE TABLE `m_return_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_return_types`
--

INSERT INTO `m_return_types` (`id`, `type_name`, `created_at`) VALUES
(1, 'Klaim Garansi', '2026-03-02 16:13:25'),
(2, 'Salah Kirim', '2026-03-02 16:13:25'),
(3, 'Rusak', '2026-03-02 16:13:36');

-- --------------------------------------------------------

--
-- Table structure for table `m_stores`
--

CREATE TABLE `m_stores` (
  `id` int(11) NOT NULL,
  `store_name` varchar(100) NOT NULL,
  `store_logo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_stores`
--

INSERT INTO `m_stores` (`id`, `store_name`, `store_logo`, `created_at`, `created_by`) VALUES
(1, 'Jaya PC', NULL, '2026-03-02 16:14:19', NULL),
(2, 'Jaya PRO', NULL, '2026-03-03 12:27:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_vendors`
--

CREATE TABLE `m_vendors` (
  `id` int(11) NOT NULL,
  `vendor_name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_vendors`
--

INSERT INTO `m_vendors` (`id`, `vendor_name`, `created_at`, `created_by`) VALUES
(1, 'Lexar', '2026-03-02 16:15:02', NULL),
(2, 'Toshiba', '2026-03-02 16:15:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_returns`
--

CREATE TABLE `tr_returns` (
  `id` int(11) NOT NULL,
  `return_number` varchar(30) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `store_id` int(11) NOT NULL,
  `platform_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_wa` varchar(20) NOT NULL,
  `purchase_date` date DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `courier_id` int(11) DEFAULT NULL,
  `receipt_number` varchar(100) DEFAULT NULL,
  `shipping_date` date DEFAULT NULL,
  `status` enum('received','checking','processing','to_vendor','from_vendor','ready','shipped','completed','rejected') DEFAULT 'received',
  `shipping_address` text DEFAULT NULL,
  `receiver_info` varchar(255) DEFAULT NULL,
  `current_keterangan` text DEFAULT NULL,
  `evidence_photo` varchar(255) DEFAULT NULL,
  `evidence_video` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_return_history`
--

CREATE TABLE `tr_return_history` (
  `id` int(11) NOT NULL,
  `return_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `evidence_files` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_return_items`
--

CREATE TABLE `tr_return_items` (
  `id` int(11) NOT NULL,
  `return_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `warranty_expiry` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `username` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `image` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `role_id` int(11) NOT NULL,
  `supervisor_id` int(11) DEFAULT NULL,
  `is_active` int(1) NOT NULL,
  `date_created` int(11) NOT NULL,
  `login_attempts` int(11) NOT NULL DEFAULT 0,
  `last_login_attempt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `username`, `email`, `image`, `password`, `role_id`, `supervisor_id`, `is_active`, `date_created`, `login_attempts`, `last_login_attempt`) VALUES
(2, 'Hengky Darmawan', 'superadmin', 'hengkydarmawan66@gmail.com', 'pro1772185682.webp', '$2y$10$ZFVAfmBb8Bgqo5oFGwaox.XQ2GxrgYOBTgL2eDR6akFWe8sfoplFS', 1, NULL, 1, 1772098277, 0, NULL),
(3, 'Chris', 'chrisjayapc', 'chrisjayapc@gmail.com', 'pro1772247231.webp', '$2y$10$bPfoTSN6p97OjemZOxO9rOi6TxR2EQ2XBmArEJSDQWvB739Qrw4We', 3, NULL, 1, 1772177834, 0, NULL),
(4, 'jansen', 'jansen', 'jansen@gmail.com', 'default.jpg', '$2y$10$IwdFlmgosP8u4n5IEM2ZpuwpY9vJc4QjkbJYWYsTJpFzqIyCbatiu', 9, NULL, 1, 1772438135, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_access_control`
--

CREATE TABLE `user_access_control` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `submenu_id` int(11) NOT NULL,
  `can_view` tinyint(1) DEFAULT 0,
  `can_add` tinyint(1) DEFAULT 0,
  `can_edit` tinyint(1) DEFAULT 0,
  `can_delete` tinyint(1) DEFAULT 0,
  `can_import` tinyint(1) DEFAULT 0,
  `can_export` tinyint(1) DEFAULT 0,
  `can_password` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_access_control`
--

INSERT INTO `user_access_control` (`id`, `role_id`, `submenu_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `can_import`, `can_export`, `can_password`) VALUES
(2, 3, 3, 1, 1, 1, 0, 0, 0, 0),
(3, 3, 4, 1, 0, 0, 0, 0, 0, 0),
(4, 3, 1, 1, 0, 0, 0, 0, 0, 0),
(5, 3, 10, 1, 0, 0, 0, 0, 0, 0),
(6, 3, 6, 1, 0, 0, 0, 0, 0, 0),
(7, 3, 2, 1, 0, 0, 0, 0, 0, 0),
(8, 3, 11, 1, 0, 0, 0, 0, 0, 0),
(9, 9, 14, 1, 1, 1, 1, 0, 0, 0),
(10, 9, 5, 0, 0, 0, 0, 0, 0, 0),
(11, 9, 15, 1, 1, 1, 1, 0, 0, 0),
(12, 9, 10, 1, 1, 1, 0, 0, 0, 0),
(13, 9, 16, 1, 1, 1, 0, 0, 0, 0),
(14, 9, 17, 1, 1, 1, 0, 0, 0, 0),
(15, 9, 18, 1, 1, 1, 1, 0, 0, 0),
(16, 9, 19, 1, 1, 1, 1, 0, 0, 0),
(17, 9, 20, 1, 1, 1, 1, 0, 0, 0),
(18, 9, 21, 1, 1, 1, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE `user_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `data_id` int(11) DEFAULT NULL,
  `data_before` text DEFAULT NULL,
  `data_after` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_log`
--

INSERT INTO `user_log` (`id`, `user_id`, `action`, `table_name`, `data_id`, `data_before`, `data_after`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 2, 'UPDATE', 'user_sub_menu', 12, '{\"id\":\"12\",\"menu_id\":\"1\",\"title\":\"Activity Logs\",\"url\":\"admin\\/activity\",\"icon\":\"fas fa-fw fa-clipboard-list\",\"is_active\":\"1\",\"sort_order\":\"9\"}', '{\"title\":\"Activity Logs\",\"menu_id\":\"1\",\"url\":\"admin\\/activity_logs\",\"icon\":\"fas fa-fw fa-clipboard-list\",\"sort_order\":\"9\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-27 09:55:34'),
(2, 3, 'CREATE', 'user_menu', 8, NULL, '{\"menu\":\"tes\",\"sort_order\":\"12\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-27 10:23:52'),
(3, 2, 'UPDATE', 'user', 2, '{\"id\":\"2\",\"name\":\"Hengky Darmawan\",\"username\":\"superadmin\",\"email\":\"hengkydarmawan66@gmail.com\",\"image\":\"default.jpg\",\"password\":\"$2y$10$ZFVAfmBb8Bgqo5oFGwaox.XQ2GxrgYOBTgL2eDR6akFWe8sfoplFS\",\"role_id\":\"1\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772098277\"}', '{\"id\":\"2\",\"name\":\"Hengky Darmawan\",\"username\":\"superadmin\",\"email\":\"hengkydarmawan66@gmail.com\",\"image\":\"pro1772185076.jpg\",\"password\":\"$2y$10$ZFVAfmBb8Bgqo5oFGwaox.XQ2GxrgYOBTgL2eDR6akFWe8sfoplFS\",\"role_id\":\"1\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772098277\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-27 10:37:57'),
(4, 2, 'UPDATE', 'user', 2, '{\"id\":\"2\",\"name\":\"Hengky Darmawan\",\"username\":\"superadmin\",\"email\":\"hengkydarmawan66@gmail.com\",\"image\":\"pro1772185076.jpg\",\"password\":\"$2y$10$ZFVAfmBb8Bgqo5oFGwaox.XQ2GxrgYOBTgL2eDR6akFWe8sfoplFS\",\"role_id\":\"1\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772098277\"}', '{\"id\":\"2\",\"name\":\"Hengky Darmawan\",\"username\":\"superadmin\",\"email\":\"hengkydarmawan66@gmail.com\",\"image\":\"pro1772185394.webp\",\"password\":\"$2y$10$ZFVAfmBb8Bgqo5oFGwaox.XQ2GxrgYOBTgL2eDR6akFWe8sfoplFS\",\"role_id\":\"1\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772098277\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-27 10:43:14'),
(5, 2, 'UPDATE', 'user', 2, '{\"id\":\"2\",\"name\":\"Hengky Darmawan\",\"username\":\"superadmin\",\"email\":\"hengkydarmawan66@gmail.com\",\"image\":\"pro1772185394.webp\",\"password\":\"$2y$10$ZFVAfmBb8Bgqo5oFGwaox.XQ2GxrgYOBTgL2eDR6akFWe8sfoplFS\",\"role_id\":\"1\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772098277\"}', '{\"id\":\"2\",\"name\":\"Hengky Darmawan\",\"username\":\"superadmin\",\"email\":\"hengkydarmawan66@gmail.com\",\"image\":\"pro1772185411.webp\",\"password\":\"$2y$10$ZFVAfmBb8Bgqo5oFGwaox.XQ2GxrgYOBTgL2eDR6akFWe8sfoplFS\",\"role_id\":\"1\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772098277\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-27 10:43:31'),
(6, 2, 'UPDATE', 'user', 2, '{\"id\":\"2\",\"name\":\"Hengky Darmawan\",\"username\":\"superadmin\",\"email\":\"hengkydarmawan66@gmail.com\",\"image\":\"pro1772185411.webp\",\"password\":\"$2y$10$ZFVAfmBb8Bgqo5oFGwaox.XQ2GxrgYOBTgL2eDR6akFWe8sfoplFS\",\"role_id\":\"1\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772098277\"}', '{\"id\":\"2\",\"name\":\"Hengky Darmawan\",\"username\":\"superadmin\",\"email\":\"hengkydarmawan66@gmail.com\",\"image\":\"pro1772185513.webp\",\"password\":\"$2y$10$ZFVAfmBb8Bgqo5oFGwaox.XQ2GxrgYOBTgL2eDR6akFWe8sfoplFS\",\"role_id\":\"1\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772098277\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-27 10:45:13'),
(7, 2, 'UPDATE', 'user', 2, '{\"id\":\"2\",\"name\":\"Hengky Darmawan\",\"username\":\"superadmin\",\"email\":\"hengkydarmawan66@gmail.com\",\"image\":\"pro1772185513.webp\",\"password\":\"$2y$10$ZFVAfmBb8Bgqo5oFGwaox.XQ2GxrgYOBTgL2eDR6akFWe8sfoplFS\",\"role_id\":\"1\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772098277\"}', '{\"id\":\"2\",\"name\":\"Hengky Darmawan1\",\"username\":\"superadmin\",\"email\":\"hengkydarmawan66@gmail.com\",\"image\":\"pro1772185513.webp\",\"password\":\"$2y$10$ZFVAfmBb8Bgqo5oFGwaox.XQ2GxrgYOBTgL2eDR6akFWe8sfoplFS\",\"role_id\":\"1\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772098277\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-27 10:46:23'),
(8, 2, 'UPDATE', 'user', 2, '{\"id\":\"2\",\"name\":\"Hengky Darmawan1\",\"username\":\"superadmin\",\"email\":\"hengkydarmawan66@gmail.com\",\"image\":\"pro1772185513.webp\",\"password\":\"$2y$10$ZFVAfmBb8Bgqo5oFGwaox.XQ2GxrgYOBTgL2eDR6akFWe8sfoplFS\",\"role_id\":\"1\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772098277\"}', '{\"id\":\"2\",\"name\":\"Hengky Darmawan\",\"username\":\"superadmin\",\"email\":\"hengkydarmawan66@gmail.com\",\"image\":\"pro1772185682.webp\",\"password\":\"$2y$10$ZFVAfmBb8Bgqo5oFGwaox.XQ2GxrgYOBTgL2eDR6akFWe8sfoplFS\",\"role_id\":\"1\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772098277\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-27 10:48:02'),
(9, 2, 'DELETE', 'user_sub_menu', 6, '{\"id\":\"6\",\"menu_id\":\"2\",\"title\":\"Edit Profile\",\"url\":\"user\\/edit\",\"icon\":\"fas fa-fw fa-user-edit\",\"is_active\":\"1\",\"sort_order\":\"7\"}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-27 10:49:35'),
(10, 3, 'UPDATE', 'user', 3, '{\"id\":\"3\",\"name\":\"Chris\",\"username\":\"chrisjayapc\",\"email\":\"chrisjayapc@gmail.com\",\"image\":\"pro1772185513.webp\",\"password\":\"$2y$10$aMmF22iTUhA6Q2lprXMAOOOYD\\/7XhVOmlDz6CmYfrY3qko0KSETNu\",\"role_id\":\"3\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772177834\"}', '{\"id\":\"3\",\"name\":\"Chris1\",\"username\":\"chrisjayapc\",\"email\":\"chrisjayapc@gmail.com\",\"image\":\"pro1772185840.webp\",\"password\":\"$2y$10$aMmF22iTUhA6Q2lprXMAOOOYD\\/7XhVOmlDz6CmYfrY3qko0KSETNu\",\"role_id\":\"3\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772177834\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-27 10:50:41'),
(11, 3, 'UPDATE', 'user', 3, '{\"id\":\"3\",\"name\":\"Chris1\",\"username\":\"chrisjayapc\",\"email\":\"chrisjayapc@gmail.com\",\"image\":\"pro1772185840.webp\",\"password\":\"$2y$10$aMmF22iTUhA6Q2lprXMAOOOYD\\/7XhVOmlDz6CmYfrY3qko0KSETNu\",\"role_id\":\"3\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772177834\"}', '{\"id\":\"3\",\"name\":\"Chris\",\"username\":\"chrisjayapc\",\"email\":\"chrisjayapc@gmail.com\",\"image\":\"pro1772185840.webp\",\"password\":\"$2y$10$aMmF22iTUhA6Q2lprXMAOOOYD\\/7XhVOmlDz6CmYfrY3qko0KSETNu\",\"role_id\":\"3\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772177834\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-27 10:50:52'),
(12, 3, 'UPDATE', 'user', 3, '{\"password\":\"[HIDDEN]\"}', '{\"password\":\"[CHANGED]\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-27 10:56:43'),
(13, 3, 'UPDATE', 'user', 3, '{\"password\":\"[HIDDEN]\"}', '{\"password\":\"[CHANGED]\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-28 03:44:06'),
(14, 3, 'UPDATE', 'user', 3, '{\"id\":\"3\",\"name\":\"Chris\",\"username\":\"chrisjayapc\",\"email\":\"chrisjayapc@gmail.com\",\"image\":\"pro1772185840.webp\",\"password\":\"$2y$10$bPfoTSN6p97OjemZOxO9rOi6TxR2EQ2XBmArEJSDQWvB739Qrw4We\",\"role_id\":\"3\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772177834\"}', '{\"id\":\"3\",\"name\":\"Chris1\",\"username\":\"chrisjayapc\",\"email\":\"chrisjayapc@gmail.com\",\"image\":\"pro1772246660.webp\",\"password\":\"$2y$10$bPfoTSN6p97OjemZOxO9rOi6TxR2EQ2XBmArEJSDQWvB739Qrw4We\",\"role_id\":\"3\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772177834\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-28 03:44:20'),
(15, 3, 'UPDATE', 'user', 3, '{\"id\":\"3\",\"name\":\"Chris1\",\"username\":\"chrisjayapc\",\"email\":\"chrisjayapc@gmail.com\",\"image\":\"pro1772246660.webp\",\"password\":\"$2y$10$bPfoTSN6p97OjemZOxO9rOi6TxR2EQ2XBmArEJSDQWvB739Qrw4We\",\"role_id\":\"3\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772177834\"}', '{\"id\":\"3\",\"name\":\"Chris\",\"username\":\"chrisjayapc\",\"email\":\"chrisjayapc@gmail.com\",\"image\":\"pro1772246660.webp\",\"password\":\"$2y$10$bPfoTSN6p97OjemZOxO9rOi6TxR2EQ2XBmArEJSDQWvB739Qrw4We\",\"role_id\":\"3\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772177834\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-28 03:53:45'),
(16, 3, 'UPDATE', 'user', 3, '{\"id\":\"3\",\"name\":\"Chris\",\"username\":\"chrisjayapc\",\"email\":\"chrisjayapc@gmail.com\",\"image\":\"pro1772246660.webp\",\"password\":\"$2y$10$bPfoTSN6p97OjemZOxO9rOi6TxR2EQ2XBmArEJSDQWvB739Qrw4We\",\"role_id\":\"3\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772177834\"}', '{\"id\":\"3\",\"name\":\"Chris\",\"username\":\"chrisjayapc\",\"email\":\"chrisjayapc@gmail.com\",\"image\":\"pro1772247231.webp\",\"password\":\"$2y$10$bPfoTSN6p97OjemZOxO9rOi6TxR2EQ2XBmArEJSDQWvB739Qrw4We\",\"role_id\":\"3\",\"supervisor_id\":null,\"is_active\":\"1\",\"date_created\":\"1772177834\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-28 03:53:51'),
(17, 2, 'UPDATE', 'user_menu', 8, '{\"id\":\"8\",\"menu\":\"tes\",\"is_active\":\"1\",\"sort_order\":\"12\"}', '{\"menu\":\"Retur\",\"sort_order\":\"6\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-02 08:58:57'),
(18, 2, 'UPDATE', 'user_menu', 2, '{\"id\":\"2\",\"menu\":\"User\",\"is_active\":\"1\",\"sort_order\":\"4\"}', '{\"menu\":\"User\",\"sort_order\":\"100\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-02 08:59:02'),
(19, 2, 'CREATE', 'user_menu', 9, NULL, '{\"menu\":\"Dashboard\",\"sort_order\":\"0\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-02 09:08:55'),
(20, 2, 'CREATE', 'user_sub_menu', 14, NULL, '{\"title\":\"Dahboard Admin Retur\",\"menu_id\":\"9\",\"url\":\"dashboard\",\"icon\":\"fas fa-fw fa-tachometer-alt\",\"sort_order\":\"1\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-02 09:12:15'),
(21, 2, 'CREATE', 'user_sub_menu', 15, NULL, '{\"title\":\"retur\",\"menu_id\":\"8\",\"url\":\"returns\",\"icon\":\"fas fa-fw fa-truck-loading\",\"sort_order\":\"1\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-02 09:41:43'),
(22, 2, 'UPDATE', 'user_sub_menu', 15, '{\"id\":\"15\",\"menu_id\":\"8\",\"title\":\"retur\",\"url\":\"returns\",\"icon\":\"fas fa-fw fa-truck-loading\",\"is_active\":\"1\",\"sort_order\":\"1\"}', '{\"title\":\"Retur\",\"menu_id\":\"8\",\"url\":\"returns\",\"icon\":\"fas fa-fw fa-truck-loading\",\"sort_order\":\"1\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-02 09:57:46'),
(23, 2, 'CREATE', 'user_menu', 10, NULL, '{\"menu\":\"Master\",\"sort_order\":\"7\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-02 10:08:06'),
(24, 2, 'CREATE', 'user_sub_menu', 16, NULL, '{\"title\":\"Platform\",\"menu_id\":\"10\",\"url\":\"platform\",\"icon\":\"fas fa-fw fa-store\",\"sort_order\":\"2\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 05:46:26'),
(25, 2, 'UPDATE', 'user_sub_menu', 16, '{\"id\":\"16\",\"menu_id\":\"10\",\"title\":\"Platform\",\"url\":\"platform\",\"icon\":\"fas fa-fw fa-store\",\"is_active\":\"1\",\"sort_order\":\"2\"}', '{\"title\":\"Platform\",\"menu_id\":\"10\",\"url\":\"platform\",\"icon\":\"fas fa-fw fa-store\",\"sort_order\":\"1\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 05:46:34'),
(26, 2, 'UPDATE', 'user_sub_menu', 16, '{\"id\":\"16\",\"menu_id\":\"10\",\"title\":\"Platform\",\"url\":\"platform\",\"icon\":\"fas fa-fw fa-store\",\"is_active\":\"1\",\"sort_order\":\"1\"}', '{\"title\":\"Platform\",\"menu_id\":\"10\",\"url\":\"platform\",\"icon\":\"fas fa-fw fa-shopping-bag\",\"sort_order\":\"1\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 05:47:35'),
(27, 2, 'CREATE', 'user_sub_menu', 17, NULL, '{\"title\":\"Stores\",\"menu_id\":\"10\",\"url\":\"master\\/stores\",\"icon\":\"fas fa-fw fa-store\",\"sort_order\":\"2\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 05:48:25'),
(28, 2, 'UPDATE', 'user_sub_menu', 16, '{\"id\":\"16\",\"menu_id\":\"10\",\"title\":\"Platform\",\"url\":\"platform\",\"icon\":\"fas fa-fw fa-shopping-bag\",\"is_active\":\"1\",\"sort_order\":\"1\"}', '{\"title\":\"Platform\",\"menu_id\":\"10\",\"url\":\"master\\/platform\",\"icon\":\"fas fa-fw fa-shopping-bag\",\"sort_order\":\"1\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 05:48:33'),
(29, 2, 'CREATE', 'user_sub_menu', 18, NULL, '{\"title\":\"Expeditions\",\"menu_id\":\"10\",\"url\":\"master\\/expeditions\",\"icon\":\"fas fa-fw fa-shipping-fast\",\"sort_order\":\"3\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 05:49:24'),
(30, 2, 'CREATE', 'user_sub_menu', 19, NULL, '{\"title\":\"Vendors\",\"menu_id\":\"10\",\"url\":\"master\\/vendors\",\"icon\":\"fas fa-fw fa-people-carry\",\"sort_order\":\"4\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 06:01:48'),
(31, 2, 'UPDATE', 'user_sub_menu', 16, '{\"id\":\"16\",\"menu_id\":\"10\",\"title\":\"Platform\",\"url\":\"master\\/platform\",\"icon\":\"fas fa-fw fa-shopping-bag\",\"is_active\":\"1\",\"sort_order\":\"1\"}', '{\"title\":\"Platforms\",\"menu_id\":\"10\",\"url\":\"master\\/platform\",\"icon\":\"fas fa-fw fa-shopping-bag\",\"sort_order\":\"1\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 06:02:52'),
(32, 2, 'UPDATE', 'user_sub_menu', 16, '{\"id\":\"16\",\"menu_id\":\"10\",\"title\":\"Platforms\",\"url\":\"master\\/platform\",\"icon\":\"fas fa-fw fa-shopping-bag\",\"is_active\":\"1\",\"sort_order\":\"1\"}', '{\"title\":\"Platforms\",\"menu_id\":\"10\",\"url\":\"master\\/index\\/platform\",\"icon\":\"fas fa-fw fa-shopping-bag\",\"sort_order\":\"1\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 06:08:14'),
(33, 2, 'UPDATE', 'user_sub_menu', 16, '{\"id\":\"16\",\"menu_id\":\"10\",\"title\":\"Platforms\",\"url\":\"master\\/index\\/platform\",\"icon\":\"fas fa-fw fa-shopping-bag\",\"is_active\":\"1\",\"sort_order\":\"1\"}', '{\"title\":\"Platforms\",\"menu_id\":\"10\",\"url\":\"master\\/index\\/platforms\",\"icon\":\"fas fa-fw fa-shopping-bag\",\"sort_order\":\"1\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 06:09:19'),
(34, 2, 'UPDATE', 'user_sub_menu', 16, '{\"id\":\"16\",\"menu_id\":\"10\",\"title\":\"Platforms\",\"url\":\"master\\/index\\/platforms\",\"icon\":\"fas fa-fw fa-shopping-bag\",\"is_active\":\"1\",\"sort_order\":\"1\"}', '{\"title\":\"Platforms\",\"menu_id\":\"10\",\"url\":\"master\\/platforms\",\"icon\":\"fas fa-fw fa-shopping-bag\",\"sort_order\":\"1\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 06:16:13'),
(35, 2, 'CREATE', 'm_platforms', 3, NULL, '{\"platform_name\":\"tes\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 06:26:45'),
(36, 2, 'UPDATE', 'm_platforms', 3, '{\"id\":\"3\",\"platform_name\":\"tes\",\"created_at\":\"2026-03-03 12:26:45\",\"created_by\":null}', '{\"platform_name\":\"tes1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 06:26:49'),
(37, 2, 'DELETE', 'm_platforms', 3, '{\"id\":\"3\",\"platform_name\":\"tes1\",\"created_at\":\"2026-03-03 12:26:45\",\"created_by\":null}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 06:26:52'),
(38, 2, 'CREATE', 'm_stores', 2, NULL, '{\"store_name\":\"Jaya PRO\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 06:27:03'),
(39, 2, 'UPDATE', 'm_stores', 2, '{\"id\":\"2\",\"store_name\":\"Jaya PRO\",\"store_logo\":null,\"created_at\":\"2026-03-03 12:27:03\",\"created_by\":null}', '{\"store_name\":\"Jaya PRO1\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 06:27:07'),
(40, 2, 'UPDATE', 'm_stores', 2, '{\"id\":\"2\",\"store_name\":\"Jaya PRO1\",\"store_logo\":null,\"created_at\":\"2026-03-03 12:27:03\",\"created_by\":null}', '{\"store_name\":\"Jaya PRO\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 06:27:11'),
(41, 2, 'CREATE', 'm_stores', 3, NULL, '{\"store_name\":\"s\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 06:27:14'),
(42, 2, 'DELETE', 'm_stores', 3, '{\"id\":\"3\",\"store_name\":\"s\",\"store_logo\":null,\"created_at\":\"2026-03-03 12:27:14\",\"created_by\":null}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 06:27:17'),
(43, 2, 'CREATE', 'user_sub_menu', 20, NULL, '{\"title\":\"Tipe Retur\",\"menu_id\":\"10\",\"url\":\"master\\/return_types\",\"icon\":\"fas fa-fw fa-truck-loading\",\"sort_order\":\"10\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 11:08:01'),
(44, 2, 'UPDATE', 'user_sub_menu', 20, '{\"id\":\"20\",\"menu_id\":\"10\",\"title\":\"Tipe Retur\",\"url\":\"master\\/return_types\",\"icon\":\"fas fa-fw fa-truck-loading\",\"is_active\":\"1\",\"sort_order\":\"10\"}', '{\"title\":\"Tipe Retur\",\"menu_id\":\"10\",\"url\":\"master\\/return_types\",\"icon\":\"fas fa-fw fa-truck-loading\",\"sort_order\":\"5\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 11:08:10'),
(45, 2, 'CREATE', 'user_sub_menu', 21, NULL, '{\"title\":\"Holidays\",\"menu_id\":\"10\",\"url\":\"master\\/holidays\",\"icon\":\"fas fa-fw fa-calendar-check\",\"sort_order\":\"6\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 11:09:46'),
(46, 4, 'DELETE', 'm_holidays', 4, '{\"id\":\"4\",\"holiday_date\":\"2026-03-03\",\"description\":\"libur abal - abal\"}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 12:19:35');

-- --------------------------------------------------------

--
-- Table structure for table `user_menu`
--

CREATE TABLE `user_menu` (
  `id` int(11) NOT NULL,
  `menu` varchar(128) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_menu`
--

INSERT INTO `user_menu` (`id`, `menu`, `is_active`, `sort_order`) VALUES
(1, 'Admin', 1, 1),
(2, 'User', 1, 100),
(3, 'Task', 1, 3),
(4, 'Menu', 1, 2),
(6, 'System Security', 1, 5),
(8, 'Retur', 1, 6),
(9, 'Dashboard', 1, 0),
(10, 'Master', 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `role` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `role`) VALUES
(1, 'Super Admin'),
(2, 'Supervisor'),
(3, 'Karyawan'),
(4, 'Direktur'),
(9, 'Admin Retur');

-- --------------------------------------------------------

--
-- Table structure for table `user_sub_menu`
--

CREATE TABLE `user_sub_menu` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `url` varchar(128) NOT NULL,
  `icon` varchar(128) NOT NULL,
  `is_active` int(1) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_sub_menu`
--

INSERT INTO `user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon`, `is_active`, `sort_order`) VALUES
(1, 1, 'Dashboard', 'admin', 'fas fa-fw fa-tachometer-alt', 1, 1),
(2, 1, 'Role Management', 'admin/role', 'fas fa-fw fa-user-tie', 1, 2),
(3, 4, 'Menu Management', 'menu', 'fas fa-fw fa-folder', 1, 3),
(4, 4, 'Submenu Management', 'menu/submenu', 'fas fa-fw fa-folder-open', 1, 4),
(5, 3, 'To-Do List', 'task', 'fas fa-fw fa-clipboard-list', 1, 5),
(10, 2, 'My Profile', 'user', 'fas fa-fw fa-user', 1, 6),
(11, 1, 'User Management', 'admin/usermanagement', 'fas fa-fw fa-users-cog', 1, 8),
(12, 1, 'Activity Logs', 'admin/activity_logs', 'fas fa-fw fa-clipboard-list', 1, 9),
(14, 9, 'Dahboard Admin Retur', 'dashboard', 'fas fa-fw fa-tachometer-alt', 1, 1),
(15, 8, 'Retur', 'returns', 'fas fa-fw fa-truck-loading', 1, 1),
(16, 10, 'Platforms', 'master/platforms', 'fas fa-fw fa-shopping-bag', 1, 1),
(17, 10, 'Stores', 'master/stores', 'fas fa-fw fa-store', 1, 2),
(18, 10, 'Expeditions', 'master/expeditions', 'fas fa-fw fa-shipping-fast', 1, 3),
(19, 10, 'Vendors', 'master/vendors', 'fas fa-fw fa-people-carry', 1, 4),
(20, 10, 'Tipe Retur', 'master/return_types', 'fas fa-fw fa-truck-loading', 1, 5),
(21, 10, 'Holidays', 'master/holidays', 'fas fa-fw fa-calendar-check', 1, 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `m_expeditions`
--
ALTER TABLE `m_expeditions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_holidays`
--
ALTER TABLE `m_holidays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `holiday_date` (`holiday_date`);

--
-- Indexes for table `m_platforms`
--
ALTER TABLE `m_platforms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_return_types`
--
ALTER TABLE `m_return_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_stores`
--
ALTER TABLE `m_stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_vendors`
--
ALTER TABLE `m_vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tr_returns`
--
ALTER TABLE `tr_returns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `return_number` (`return_number`),
  ADD KEY `fk_expedition_out` (`courier_id`);

--
-- Indexes for table `tr_return_history`
--
ALTER TABLE `tr_return_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `return_id` (`return_id`);

--
-- Indexes for table `tr_return_items`
--
ALTER TABLE `tr_return_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `return_id` (`return_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_access_control`
--
ALTER TABLE `user_access_control`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_log`
--
ALTER TABLE `user_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_menu`
--
ALTER TABLE `user_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m_expeditions`
--
ALTER TABLE `m_expeditions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `m_holidays`
--
ALTER TABLE `m_holidays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `m_platforms`
--
ALTER TABLE `m_platforms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `m_return_types`
--
ALTER TABLE `m_return_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `m_stores`
--
ALTER TABLE `m_stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `m_vendors`
--
ALTER TABLE `m_vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tr_returns`
--
ALTER TABLE `tr_returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tr_return_history`
--
ALTER TABLE `tr_return_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `tr_return_items`
--
ALTER TABLE `tr_return_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_access_control`
--
ALTER TABLE `user_access_control`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user_log`
--
ALTER TABLE `user_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `user_menu`
--
ALTER TABLE `user_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tr_returns`
--
ALTER TABLE `tr_returns`
  ADD CONSTRAINT `fk_expedition_out` FOREIGN KEY (`courier_id`) REFERENCES `m_expeditions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tr_return_history`
--
ALTER TABLE `tr_return_history`
  ADD CONSTRAINT `tr_return_history_ibfk_1` FOREIGN KEY (`return_id`) REFERENCES `tr_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tr_return_items`
--
ALTER TABLE `tr_return_items`
  ADD CONSTRAINT `tr_return_items_ibfk_1` FOREIGN KEY (`return_id`) REFERENCES `tr_returns` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
