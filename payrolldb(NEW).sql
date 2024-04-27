-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2024 at 04:35 AM
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
-- Database: `payrolldb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `fname`, `username`, `email`, `password`, `gender`) VALUES
(1, 'admin', 'admintest', 'admintest@gmail.com', '$2y$10$atvoAPlQGAleWzxv6gkcUOZw/CczB4H7owdT60od7F46YvgJi62US', 'Male');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `morning_time_in` time DEFAULT NULL,
  `morning_time_out` time DEFAULT NULL,
  `afternoon_time_in` time DEFAULT NULL,
  `afternoon_time_out` time DEFAULT NULL,
  `morning_total_hours` decimal(5,2) DEFAULT NULL,
  `afternoon_total_hours` decimal(5,2) DEFAULT NULL,
  `overall_total_hours` decimal(5,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `overtime_time_in` time DEFAULT NULL,
  `overtime_time_out` time DEFAULT NULL,
  `overtime_total_hours` decimal(5,2) DEFAULT NULL,
  `total_paid` decimal(10,0) DEFAULT NULL,
  `overtime_cost` decimal(10,0) DEFAULT NULL,
  `deduction` decimal(10,0) DEFAULT NULL,
  `applied_OT` enum('','Overtime','Undertime','') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `employee_id`, `name`, `morning_time_in`, `morning_time_out`, `afternoon_time_in`, `afternoon_time_out`, `morning_total_hours`, `afternoon_total_hours`, `overall_total_hours`, `status`, `overtime_time_in`, `overtime_time_out`, `overtime_total_hours`, `total_paid`, `overtime_cost`, `deduction`, `applied_OT`, `created_at`) VALUES
(37, 12, 'Abrajano, John Paulo  O', '07:50:14', '12:00:00', '01:30:00', '06:00:00', 4.50, 6.30, 10.80, '<span style=\"color: green;\">Time In</span>', NULL, NULL, NULL, 473, 123, 0, 'Overtime', '2024-04-01 07:50:14'),
(38, 13, 'Acabo, Paul John Z', '07:50:44', '12:30:00', '01:00:00', '05:30:00', 5.30, 4.30, 9.60, '<span style=\"color: green;\">Time In</span>', NULL, NULL, NULL, 666, 111, 0, 'Overtime', '2024-04-14 07:50:44'),
(39, 14, 'Aquino, Leonard A.', '07:50:55', '11:30:00', '01:15:00', '05:30:00', 3.20, 4.50, 7.70, '<span style=\"color: green;\">Time In</span>', NULL, NULL, NULL, 385, 0, -15, 'Undertime', '2024-04-18 07:50:55'),
(40, 12, 'Abrajano, John Paulo  O', '07:51:02', '10:30:00', '12:50:00', '05:01:00', 2.21, 5.50, 7.71, '<span style=\"color: green;\">Time In</span>', NULL, NULL, NULL, 337, 0, -13, 'Undertime', '2024-04-14 07:51:02'),
(41, 12, 'Abrajano, John Paulo  O', '07:51:09', '09:00:00', '01:00:00', '06:00:00', 1.51, 5.00, 6.51, '<span style=\"color: green;\">Time In</span>', NULL, NULL, NULL, 285, 0, -65, 'Undertime', '2024-04-16 07:51:09');

-- --------------------------------------------------------

--
-- Table structure for table `dtr`
--

CREATE TABLE `dtr` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `morning` datetime DEFAULT NULL,
  `afternoon` datetime DEFAULT NULL,
  `total_hours` time DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `middle_initial` varchar(10) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `employee_id` varchar(10) DEFAULT NULL,
  `mobile_number` bigint(20) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `salary` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `lname`, `fname`, `middle_initial`, `name`, `address`, `employee_id`, `mobile_number`, `barangay`, `street`, `city`, `postal_code`, `gender`, `department`, `salary`, `start_date`) VALUES
(12, 'Abrajano', 'John Paulo ', 'O', 'Abrajano, John Paulo  O', 'ph3', '2021', 9682079765, NULL, NULL, NULL, NULL, 'male', 'Cashier', 350.00, '2024-03-23'),
(13, 'Acabo', 'Paul John', 'Z', 'Acabo, Paul John Z', NULL, '22123', 1231231231231321, NULL, NULL, NULL, NULL, 'male', 'Barista', 555.00, '2024-03-23'),
(14, 'Aquino', 'Leonard', 'A.', 'Aquino, Leonard A.', NULL, '2021-20202', 9218844712, 'Barangay Masagana', 'Purok Mabuhay', 'Zamboanga City', '7000', 'male', 'Barista', 400.00, '2024-04-11'),
(15, 'Abukar', 'Malik', 'O.', 'Abukar, Malik O.', ', , , ', '2021-0023', 92281234112, 'Barangay Maasin', 'Purok Manila', 'Zamboanga City', '7000', 'male', 'Barista', 400.00, '2024-04-11');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `employee_id`, `image_name`, `image_path`, `created_at`) VALUES
(690, 12, '6620606d9ffc3.png', 'images/6620606d9ffc3.png', '2024-04-17 23:51:09'),
(691, 13, '6620605458cc9.png', 'images/6620605458cc9.png', '2024-04-17 23:50:44'),
(692, 14, '6620606002a69.png', 'images/6620606002a69.png', '2024-04-17 23:50:56');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `day` text NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `date_schedule` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `day`, `date`, `date_schedule`) VALUES
(1, '1', '2024-04-01', '8:00 AM');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dtr`
--
ALTER TABLE `dtr`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `dtr`
--
ALTER TABLE `dtr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=693;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dtr`
--
ALTER TABLE `dtr`
  ADD CONSTRAINT `dtr_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
