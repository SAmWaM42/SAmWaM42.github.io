-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2024 at 11:26 AM
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
-- Database: `easyleave`
--

-- --------------------------------------------------------

--
-- Table structure for table `employess`
--

CREATE TABLE `employess` (
  `emp_id` int(10) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `emp_role` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employess`
--

INSERT INTO `employess` (`emp_id`, `emp_name`, `emp_role`) VALUES
(1, 'James Bond', 'Spy'),
(2, 'Vin Wonder', 'Janitor'),
(3, 'John Wick', 'Engineer');

-- --------------------------------------------------------

--
-- Table structure for table `leave_balance`
--

CREATE TABLE `leave_balance` (
  `emp_id` int(11) NOT NULL,
  `annual_leave_balance` int(11) DEFAULT 21,
  `sick_leave_balance` int(11) DEFAULT 14,
  `maternity_leave_balance` int(11) DEFAULT 90,
  `last_accrual_date` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_balance`
--

INSERT INTO `leave_balance` (`emp_id`, `annual_leave_balance`, `sick_leave_balance`, `maternity_leave_balance`, `last_accrual_date`) VALUES
(1, 1, 14, 90, '2024-10-29'),
(2, 21, 14, 90, '2024-10-29'),
(3, 21, 14, 90, '2024-10-29');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `request_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `leave_type` varchar(50) NOT NULL,
  `days_taken` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`request_id`, `emp_id`, `leave_type`, `days_taken`, `start_date`, `end_date`, `status`, `request_date`) VALUES
(1, 1, 'annual', 5, '2024-11-01', '2024-11-05', 'approved', '2024-10-28 21:24:49'),
(2, 2, 'maternity', 7, '2024-12-01', '2024-12-08', 'pending', '2024-10-29 09:18:58'),
(3, 3, 'sick', 2, '2024-12-01', '2024-12-03', 'pending', '2024-10-29 09:19:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employess`
--
ALTER TABLE `employess`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `leave_balance`
--
ALTER TABLE `leave_balance`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `emp_id` (`emp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employess`
--
ALTER TABLE `employess`
  MODIFY `emp_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leave_balance`
--
ALTER TABLE `leave_balance`
  ADD CONSTRAINT `leave_balance_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `employess` (`emp_id`) ON DELETE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `employess` (`emp_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
