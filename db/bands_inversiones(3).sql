-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2025 at 11:54 AM
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
-- Database: `bands_inversiones`
--

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `investment_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `location_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `owner_name` varchar(100) DEFAULT NULL,
  `lat` decimal(10,7) NOT NULL,
  `lng` decimal(10,7) NOT NULL,
  `status` enum('planning','construction','finished') DEFAULT 'planning',
  `progress_percent` tinyint(3) UNSIGNED DEFAULT 0,
  `min_ticket` decimal(12,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `operation_type` varchar(32) NOT NULL DEFAULT 'inversion',
  `asset_type` varchar(32) DEFAULT NULL,
  `price_total` decimal(15,2) DEFAULT NULL,
  `surface_m2` decimal(10,2) DEFAULT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `developer_name` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `investments`
--

INSERT INTO `investments` (`id`, `title`, `location_name`, `description`, `image_url`, `owner_name`, `lat`, `lng`, `status`, `progress_percent`, `min_ticket`, `created_at`, `operation_type`, `asset_type`, `price_total`, `surface_m2`, `bedrooms`, `bathrooms`, `developer_name`, `video_url`) VALUES
(1, 'Torre Capital I', 'Centro – Plaza 25 de Mayo', 'Edificio residencial premium en pleno microcentro. Departamentos 1 y 2 dormitorios.', 'https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4138900, -66.8553100, 'construction', 45, 25000.00, '2025-11-25 05:53:00', 'inversion', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Bulevar Norte Residencias', 'Zona Norte – Av. Ortiz de Ocampo', 'Conjunto de 3 torres con amenities. Vista panorámica de la ciudad.', 'https://images.pexels.com/photos/259588/pexels-photo-259588.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4045200, -66.8462000, 'planning', 10, 30000.00, '2025-11-25 05:53:00', 'inversion', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Parque Industrial Logistic Hub', 'Parque Industrial – Acceso Sur', 'Nave logística con infraestructura para PyMEs y depósitos.', 'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.3827200, -66.8418900, 'construction', 55, 40000.00, '2025-11-25 05:53:00', 'inversion', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Residencias UNLaR', 'Zona Universitaria – UNLaR', 'Viviendas modernas para estudiantes universitarios. 48 unidades.', 'https://images.pexels.com/photos/1643383/pexels-photo-1643383.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4420100, -66.8561200, 'finished', 100, 20000.00, '2025-11-25 05:53:00', 'inversion', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Condominio Parque Sur', 'Parque Sur', 'Barrio cerrado con áreas verdes, salón y pileta.', 'https://images.pexels.com/photos/534164/pexels-photo-534164.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4648800, -66.8333100, 'construction', 40, 18000.00, '2025-11-25 05:53:00', 'inversion', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Torre Libertad II', 'Microcentro – Libertad', 'Edificio de 14 pisos con locales comerciales en planta baja.', 'https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4122200, -66.8525000, 'planning', 5, 35000.00, '2025-11-25 05:53:00', 'inversion', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'Complejo Antártida VIP', 'Barrio Antártida', 'Conjunto habitacional premium con cocheras subterráneas.', 'https://images.pexels.com/photos/259588/pexels-photo-259588.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4079000, -66.8654200, 'finished', 100, 45000.00, '2025-11-25 05:53:00', 'inversion', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'Paseo del Sol Housing', 'Barrio Vargas – Paseo del Sol', 'Viviendas familiares modernas en zona de alto crecimiento urbano.', 'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4311400, -66.8369000, 'construction', 65, 22000.00, '2025-11-25 05:53:00', 'inversion', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'Portal del Este', 'Zona Este – Ruta 5', 'Barrio semi-privado con lotes disponibles y viviendas llave en mano.', 'https://images.pexels.com/photos/1643383/pexels-photo-1643383.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4252200, -66.8253300, 'planning', 20, 15000.00, '2025-11-25 05:53:00', 'inversion', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'EcoResidencias Terminal', 'Zona Terminal de Ómnibus', 'Edificio sostenible con paneles solares y jardines verticales.', 'https://images.pexels.com/photos/534164/pexels-photo-534164.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4186700, -66.8435700, 'construction', 33, 27000.00, '2025-11-25 05:53:00', 'inversion', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'Torre Capital I', 'Centro – Plaza 25 de Mayo', 'Edificio residencial premium en pleno microcentro. Departamentos de 1 y 2 dormitorios con amenities en terraza.', 'https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4138900, -66.8553100, '', 45, 25000.00, '2025-11-30 00:20:42', 'inversion', 'departamento', NULL, 5500.00, 2, 2, 'Desarrolladora Andina', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'),
(12, 'Bulevar Norte Residencias', 'Zona Norte – Av. Ortiz de Ocampo', 'Conjunto de 3 torres con amenities completos, coworking y vistas panorámicas de la ciudad.', 'https://images.pexels.com/photos/439391/pexels-photo-439391.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4045200, -66.8462000, '', 10, 30000.00, '2025-11-30 00:20:42', 'inversion', 'departamento', NULL, 7200.00, 2, 2, 'Grupo Horizonte', NULL),
(13, 'Parque Industrial Logistic Hub', 'Parque Industrial – Acceso Sur', 'Nave logística con infraestructura para PyMEs y depósitos, con accesos para transporte pesado.', 'https://images.pexels.com/photos/209251/pexels-photo-209251.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.3827200, -66.8418900, '', 55, 40000.00, '2025-11-30 00:20:42', 'inversion', 'comercial', NULL, 8500.00, NULL, NULL, 'Parque Industrial La Rioja', NULL),
(14, 'Residencias UNLaR', 'Zona Universitaria – UNLaR', 'Viviendas modernas para estudiantes universitarios. 48 unidades con espacios comunes y laundry.', 'https://images.pexels.com/photos/259600/pexels-photo-259600.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4420100, -66.8561200, 'finished', 100, 20000.00, '2025-11-30 00:20:42', 'inversion', 'departamento', NULL, 3200.00, 1, 1, 'Campus Desarrollos', NULL),
(15, 'Condominio Parque Sur', 'Parque Sur', 'Barrio cerrado con áreas verdes, salón de usos múltiples y pileta, ideal para familias.', 'https://images.pexels.com/photos/32870/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4648800, -66.8333100, '', 0, NULL, '2025-11-30 00:20:42', 'venta', 'casa', 120000.00, 180.00, 3, 2, 'Parque Sur Desarrollos', 'https://www.youtube.com/watch?v=oHg5SJYRHA0'),
(16, 'Torre Libertad II', 'Microcentro – Libertad', 'Edificio de 14 pisos con locales comerciales en planta baja y cocheras cubiertas.', 'https://images.pexels.com/photos/323705/pexels-photo-323705.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4122200, -66.8525000, '', 5, NULL, '2025-11-30 00:20:42', 'venta', 'departamento', 98000.00, 65.00, 2, 1, 'Libertad Desarrollos', NULL),
(17, 'Complejo Antártida VIP', 'Barrio Antártida', 'Conjunto habitacional premium con cocheras subterráneas y seguridad 24hs.', 'https://images.pexels.com/photos/259588/pexels-photo-259588.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4079000, -66.8654200, '', 100, NULL, '2025-11-30 00:20:42', 'venta', 'casa', 145000.00, 210.00, 3, 3, 'Antártida Desarrollos', NULL),
(18, 'Paseo del Sol Housing', 'Barrio Vargas – Paseo del Sol', 'Viviendas familiares modernas en zona de alto crecimiento urbano, con espacios verdes comunes.', 'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4311400, -66.8369000, '', 65, NULL, '2025-11-30 00:20:42', 'alquiler', 'casa', 75000.00, 140.00, 3, 2, 'Paseo del Sol SA', NULL),
(19, 'Portal del Este', 'Zona Este – Ruta 5', 'Barrio semi-privado con lotes disponibles y viviendas llave en mano. Perfecto para estadías temporales.', 'https://images.pexels.com/photos/534151/pexels-photo-534151.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4252200, -66.8253300, '', 20, NULL, '2025-11-30 00:20:42', 'alquiler_temporal', 'casa', 65000.00, 120.00, 2, 2, 'Portal del Este Desarrollos', 'https://www.youtube.com/watch?v=9bZkp7q19f0'),
(20, 'EcoResidencias Terminal', 'Zona Terminal de Ómnibus', 'Edificio sostenible con paneles solares, jardines verticales y cocheras cubiertas.', 'https://images.pexels.com/photos/439391/pexels-photo-439391.jpeg?auto=compress&cs=tinysrgb&w=800', NULL, -29.4186700, -66.8435700, '', 33, NULL, '2025-11-30 00:20:42', 'venta', 'departamento', 110000.00, 90.00, 2, 2, 'EcoDesarrollos', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `investment_user`
--

CREATE TABLE `investment_user` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `investment_id` int(11) NOT NULL,
  `amount_invested` decimal(15,2) NOT NULL,
  `roi_percent` decimal(10,2) DEFAULT 0.00,
  `status` varchar(50) DEFAULT 'activo',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `investment_user`
--

INSERT INTO `investment_user` (`id`, `user_id`, `investment_id`, `amount_invested`, `roi_percent`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 11, 15000.00, 8.50, 'activo', '2025-11-29 23:05:56', NULL),
(2, 3, 12, 20000.00, 4.20, 'activo', '2025-11-29 23:05:56', NULL),
(3, 3, 13, 30000.00, 6.10, 'activo', '2025-11-29 23:05:56', NULL),
(4, 3, 14, 10000.00, 12.30, 'cerrado', '2025-11-29 23:05:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'admin_propiedades', 'Administración completa'),
(2, 'inversor', 'Usuario inversor con acceso a ROI'),
(3, 'visitante', 'Acceso básico de lectura');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `role` enum('admin','viewer') NOT NULL DEFAULT 'viewer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password_hash`, `role_id`, `is_active`, `role`, `created_at`, `updated_at`) VALUES
(2, 'Admin BandS', 'admin@bands.test', 'bands', '$2y$10$lyGUdqs1isHpPOYbmCr6BevkdmfwzQtHUIfJa10WaY26PSnGWX.W6', 1, 1, 'admin', '2025-11-25 01:57:55', '2025-11-29 23:04:42'),
(3, 'Inversor Demo', 'inversor@bands.test', 'inversor_demo', '$2b$12$jcZMbtx1pCb2i4CTmRqSOuWxAdDJLETRx6w.DRMNyxVzLlUR2mbJ6', 2, 1, 'viewer', '2025-11-30 02:04:56', NULL),
(4, 'Visitante Demo', 'visitante@bands.test', 'visitante_demo', '$2b$12$JDy8Kpsx/p5J3NjoVlwz6eEIvGb16OMetd0pKWLPv5tKn9bZkXPMm', 3, 1, 'viewer', '2025-11-30 02:04:56', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`investment_id`),
  ADD KEY `fk_fav_inv` (`investment_id`);

--
-- Indexes for table `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investment_user`
--
ALTER TABLE `investment_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_invuser_user` (`user_id`),
  ADD KEY `idx_invuser_investment` (`investment_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD KEY `idx_users_role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `investment_user`
--
ALTER TABLE `investment_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_fav_inv` FOREIGN KEY (`investment_id`) REFERENCES `investments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_fav_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
