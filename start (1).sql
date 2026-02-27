-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2026 at 11:04 AM
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
-- Database: `start`
--

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
  `date_created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `username`, `email`, `image`, `password`, `role_id`, `supervisor_id`, `is_active`, `date_created`) VALUES
(2, 'Hengky Darmawan', 'superadmin', 'hengkydarmawan66@gmail.com', 'pro1772185682.webp', '$2y$10$ZFVAfmBb8Bgqo5oFGwaox.XQ2GxrgYOBTgL2eDR6akFWe8sfoplFS', 1, NULL, 1, 1772098277),
(3, 'Chris', 'chrisjayapc', 'chrisjayapc@gmail.com', 'pro1772185840.webp', '$2y$10$nSWbn9Z8Hv6r1tnnxc9KGuCVR9YB0MxsIAfLWHVlnEZka0SxubRJK', 3, NULL, 1, 1772177834);

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
  `can_export` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_access_control`
--

INSERT INTO `user_access_control` (`id`, `role_id`, `submenu_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `can_import`, `can_export`) VALUES
(2, 3, 3, 0, 0, 0, 0, 0, 0),
(3, 3, 4, 0, 0, 0, 0, 0, 0),
(4, 3, 1, 1, 0, 0, 0, 0, 0),
(5, 3, 10, 1, 0, 1, 0, 0, 0),
(6, 3, 6, 1, 0, 0, 0, 0, 0);

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
(12, 3, 'UPDATE', 'user', 3, '{\"password\":\"[HIDDEN]\"}', '{\"password\":\"[CHANGED]\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-27 10:56:43');

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
(2, 'User', 1, 4),
(3, 'Task', 1, 3),
(4, 'Menu', 1, 2),
(6, 'System Security', 1, 5),
(8, 'tes', 1, 12);

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
(4, 'Direktur');

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
(12, 1, 'Activity Logs', 'admin/activity_logs', 'fas fa-fw fa-clipboard-list', 1, 9);

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_access_control`
--
ALTER TABLE `user_access_control`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_log`
--
ALTER TABLE `user_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_menu`
--
ALTER TABLE `user_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
