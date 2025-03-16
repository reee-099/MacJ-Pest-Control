-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2025 at 01:36 PM
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
-- Database: `macj_pest_control`
--

-- --------------------------------------------------------

--
-- Table structure for table `archived_clients`
--

CREATE TABLE `archived_clients` (
  `client_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archived_technicians`
--

CREATE TABLE `archived_technicians` (
  `technician_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chemical_archive`
--

CREATE TABLE `chemical_archive` (
  `chemical_name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `quantity` decimal(5,3) UNSIGNED NOT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chemical_inventory`
--

CREATE TABLE `chemical_inventory` (
  `id` int(11) NOT NULL,
  `chemical_name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `quantity` decimal(5,3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chemical_inventory`
--

INSERT INTO `chemical_inventory` (`id`, `chemical_name`, `type`, `quantity`) VALUES
(16, 'asadad', 'dasdad', 0.005);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `first_name`, `last_name`, `email`, `contact_number`, `password`, `registered_at`) VALUES
(1, 'adasd', 'asdasasas', 're@gmail.com', '123123123', '$2y$10$xSQ.3X88fRUB3g/KIrXhK.CWMcIZAS/oxqky3egag/udTgVxpwHdS', '2025-03-07 18:55:46'),
(4, 'Rean', 's', 'das1@gmail.com', '0920', '$2y$10$CllrA.ntuaN2akFwJ5N.X.8anFPtWpJI5dbWJcqdcfpRrKoq14nL.', '2025-03-10 18:37:57');

-- --------------------------------------------------------

--
-- Table structure for table `office_staff`
--

CREATE TABLE `office_staff` (
  `staff_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `office_staff`
--

INSERT INTO `office_staff` (`staff_id`, `username`, `password`) VALUES
(1, 'admin_mike', '4a169480fb6c63f85a2bdb42192bb7c6'),
(2, 'staff_jane', 'de9bf5643eabf80f4a56fda3bbb84483');

-- --------------------------------------------------------

--
-- Table structure for table `technicians`
--

CREATE TABLE `technicians` (
  `technician_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `technicians`
--

INSERT INTO `technicians` (`technician_id`, `username`, `password`) VALUES
(1, 'tech_one', '482c811da5d5b4bc6d497ffa98491e38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archived_clients`
--
ALTER TABLE `archived_clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `archived_technicians`
--
ALTER TABLE `archived_technicians`
  ADD PRIMARY KEY (`technician_id`);

--
-- Indexes for table `chemical_inventory`
--
ALTER TABLE `chemical_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `office_staff`
--
ALTER TABLE `office_staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `technicians`
--
ALTER TABLE `technicians`
  ADD PRIMARY KEY (`technician_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chemical_inventory`
--
ALTER TABLE `chemical_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `office_staff`
--
ALTER TABLE `office_staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `technicians`
--
ALTER TABLE `technicians`
  MODIFY `technician_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `purge_old_chemicals` ON SCHEDULE EVERY 1 DAY STARTS '2025-02-24 00:09:30' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    DELETE FROM chemical_archive 
    WHERE deleted_at < NOW() - INTERVAL 30 Day;
END$$

CREATE DEFINER=`root`@`localhost` EVENT `purge_old_technicians` ON SCHEDULE EVERY 1 SECOND STARTS '2025-02-25 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    DELETE FROM archived_technicians 
    WHERE deleted_at < NOW() - INTERVAL 30 SECOND;
END$$

CREATE DEFINER=`root`@`localhost` EVENT `purge_old_clients` ON SCHEDULE EVERY 1 SECOND STARTS '2025-03-10 23:24:18' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    DELETE FROM archived_clients 
    WHERE deleted_at < NOW() - INTERVAL 30 SECOND;
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
