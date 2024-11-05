-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2024 at 11:25 AM
-- Server version: 8.0.34
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `timeoff`
--

-- --------------------------------------------------------

--
-- Table structure for table `leaverequests`
--

CREATE TABLE `leaverequests` (
  `employee_id` int NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `leave_type` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `approval_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `leaverequests`
--

INSERT INTO `leaverequests` (`employee_id`, `employee_name`, `leave_type`, `start_date`, `end_date`, `approval_status`) VALUES
(1, 'Sharon Wambui Kanyi', 'Medical', '2024-10-01', '2024-10-16', 1),
(2, 'John Doe', 'Medical', '2024-09-03', '2024-09-10', 1),
(3, 'Louis Karanja', 'Paternity', '2024-01-01', '2024-01-08', 1),
(4, 'Max Verstappen', 'Study', '2024-02-01', '2024-02-08', 1),
(5, 'Lily Grace', 'Personal', '2024-03-01', '2024-03-08', 1),
(6, 'Nora Jane', 'Maternity', '2024-04-01', '2024-04-09', 1),
(7, 'Skyler Jordan', 'Study', '2024-05-09', '2024-05-15', 1),
(9, 'Stacy Ann', 'Study', '2024-06-28', '2024-06-30', 1),
(10, 'Nora Jane', 'Study', '2024-07-01', '2024-07-10', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `leaverequests`
--
ALTER TABLE `leaverequests`
  ADD PRIMARY KEY (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leaverequests`
--
ALTER TABLE `leaverequests`
  MODIFY `employee_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
