-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2025 at 11:19 AM
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
-- Database: `tiket_wisata`
--

-- --------------------------------------------------------

--
-- Table structure for table `tiket_wisata_events`
--

CREATE TABLE `tiket_wisata_events` (
  `event_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(250) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tiket_wisata_events`
--

INSERT INTO `tiket_wisata_events` (`event_id`, `title`, `slug`, `venue_id`, `description`, `date_start`, `date_end`, `price`, `capacity`, `image`, `created_at`) VALUES
(3, 'TES', NULL, 1, 'tes', '2025-11-05 08:03:00', '2025-11-06 06:03:00', 2.00, 2, '1762314276_images (3).jfif', '2025-11-04 23:03:45'),
(4, 'qwerty', NULL, 1, '123', '2025-11-05 08:17:00', '2025-11-05 09:17:00', 8.00, 8, '1762314691_images.jfif', '2025-11-04 23:17:42');

-- --------------------------------------------------------

--
-- Table structure for table `tiket_wisata_orders`
--

CREATE TABLE `tiket_wisata_orders` (
  `order_id` int(11) NOT NULL,
  `order_code` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `status` enum('pending','paid','cancelled','used') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tiket_wisata_orders`
--

INSERT INTO `tiket_wisata_orders` (`order_id`, `order_code`, `user_id`, `event_id`, `qty`, `total`, `status`, `created_at`) VALUES
(12, 'ORD1762297451412', 1, 3, 1, 2.00, 'paid', '2025-11-04 23:04:11'),
(13, 'ORD1762297623294', 1, 3, 1, 2.00, 'used', '2025-11-04 23:07:03'),
(14, 'ORD1762298279235', 1, 4, 1, 8.00, 'used', '2025-11-04 23:17:59'),
(15, 'ORD1762312937516', 3, 4, 1, 8.00, 'used', '2025-11-05 03:22:17'),
(16, 'ORD1762313303124', 3, 4, 1, 8.00, 'paid', '2025-11-05 03:28:23');

-- --------------------------------------------------------

--
-- Table structure for table `tiket_wisata_tickets`
--

CREATE TABLE `tiket_wisata_tickets` (
  `ticket_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `ticket_code` varchar(100) DEFAULT NULL,
  `ticket_owner` varchar(100) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `checked_in` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tiket_wisata_tickets`
--

INSERT INTO `tiket_wisata_tickets` (`ticket_id`, `order_id`, `ticket_code`, `ticket_owner`, `qr_code`, `checked_in`, `created_at`) VALUES
(1, 14, 'TKT315f4409', '1', 'uploads/qr/TKT315f4409.png', 1, '2025-11-04 23:17:59'),
(2, 15, 'TKT110df627', '', 'uploads/qr/TKT110df627.png', 1, '2025-11-05 03:22:17'),
(3, 16, 'TKT7c862581', 'alwan', 'uploads/qr/TKT7c862581.png', 0, '2025-11-05 03:28:23');

-- --------------------------------------------------------

--
-- Table structure for table `tiket_wisata_users`
--

CREATE TABLE `tiket_wisata_users` (
  `user_id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `phone` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tiket_wisata_users`
--

INSERT INTO `tiket_wisata_users` (`user_id`, `nama`, `email`, `password`, `role`, `phone`, `created_at`) VALUES
(1, 'tes', 'ryukivan4@gmail.com', '$2y$10$kyXuOMfOJ2oGiSKn3uu5weryCYHBXk9J.A6/ISs7KzlZzpUdXKT2m', 'user', NULL, '2025-11-04 20:32:01'),
(2, 'admin', 'admin@gmail.com', '$2y$10$EBJ4Y7qa1GN7peYna2LzuusgDQDyIxg36eEs6U9Pd3go3ONtps38G', 'admin', NULL, '2025-11-04 20:36:35'),
(3, 'abi', 'abi@gmail.com', '$2y$10$nUg3tmsOsVkmV9pdg5EYOewbZ//cf.rl3WleRUxg44Hi8plmLk/CS', 'user', NULL, '2025-11-05 02:58:13');

-- --------------------------------------------------------

--
-- Table structure for table `tiket_wisata_venues`
--

CREATE TABLE `tiket_wisata_venues` (
  `venue_id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `alamat` text DEFAULT NULL,
  `kapasitas` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tiket_wisata_venues`
--

INSERT INTO `tiket_wisata_venues` (`venue_id`, `nama`, `alamat`, `kapasitas`, `created_at`) VALUES
(1, 'tes', 'wew', NULL, '2025-11-04 20:38:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tiket_wisata_events`
--
ALTER TABLE `tiket_wisata_events`
  ADD PRIMARY KEY (`event_id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_events_venue` (`venue_id`);

--
-- Indexes for table `tiket_wisata_orders`
--
ALTER TABLE `tiket_wisata_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `fk_orders_user` (`user_id`),
  ADD KEY `fk_orders_event` (`event_id`);

--
-- Indexes for table `tiket_wisata_tickets`
--
ALTER TABLE `tiket_wisata_tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD UNIQUE KEY `ticket_code` (`ticket_code`),
  ADD KEY `fk_tickets_order` (`order_id`);

--
-- Indexes for table `tiket_wisata_users`
--
ALTER TABLE `tiket_wisata_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tiket_wisata_venues`
--
ALTER TABLE `tiket_wisata_venues`
  ADD PRIMARY KEY (`venue_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tiket_wisata_events`
--
ALTER TABLE `tiket_wisata_events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tiket_wisata_orders`
--
ALTER TABLE `tiket_wisata_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tiket_wisata_tickets`
--
ALTER TABLE `tiket_wisata_tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tiket_wisata_users`
--
ALTER TABLE `tiket_wisata_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tiket_wisata_venues`
--
ALTER TABLE `tiket_wisata_venues`
  MODIFY `venue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tiket_wisata_events`
--
ALTER TABLE `tiket_wisata_events`
  ADD CONSTRAINT `fk_events_venue` FOREIGN KEY (`venue_id`) REFERENCES `tiket_wisata_venues` (`venue_id`);

--
-- Constraints for table `tiket_wisata_orders`
--
ALTER TABLE `tiket_wisata_orders`
  ADD CONSTRAINT `fk_orders_event` FOREIGN KEY (`event_id`) REFERENCES `tiket_wisata_events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `tiket_wisata_users` (`user_id`);

--
-- Constraints for table `tiket_wisata_tickets`
--
ALTER TABLE `tiket_wisata_tickets`
  ADD CONSTRAINT `fk_tickets_order` FOREIGN KEY (`order_id`) REFERENCES `tiket_wisata_orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
