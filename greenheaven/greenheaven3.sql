CREATE DATABASE IF NOT EXISTS `greenheaven`;
USE `greenheaven`;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 12, 2025 at 11:22 AM
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
-- Database: `greenheaven`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `AdminID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AdminID`, `Username`, `Password`) VALUES
(1, 'Kamrul', 'ovi32'),
(2, 'ovi', 'ovi32');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`CustomerID`, `Name`, `Email`, `Phone`, `Address`) VALUES
(1, 'Sharmin Juthy', 'sharmin@example.com', '01712345678', 'Dhaka, Bangladesh');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `OrderDetailID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `PlantID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `UnitPrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`OrderDetailID`, `OrderID`, `PlantID`, `Quantity`, `UnitPrice`) VALUES
(1, 1, 1, 2, 50.00),
(2, 2, 1, 2, 50.00),
(3, 3, 2, 10, 35.50),
(4, 4, 2, 10, 35.50),
(5, 5, 3, 10, 25.00),
(6, 9, 2, 10, 35.50),
(7, 10, 1, 2, 50.00),
(8, 11, 2, 10, 35.50),
(9, 12, 3, 50, 25.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `OrderDate` datetime DEFAULT current_timestamp(),
  `TotalAmount` decimal(10,2) NOT NULL,
  `PaymentMethod` varchar(50) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `CustomerID`, `OrderDate`, `TotalAmount`, `PaymentMethod`, `Address`, `Phone`) VALUES
(1, 1, '2025-09-11 16:24:20', 100.00, 'COD', 'Gulisthan', '017654654654'),
(2, 1, '2025-09-11 16:40:38', 100.00, 'COD', 'Gulisthan', '017654654654'),
(3, 1, '2025-09-11 16:41:15', 355.00, 'COD', 'Gulisthan', '017654654654'),
(4, 1, '2025-09-11 16:41:44', 355.00, 'COD', 'Gulisthan', '017654654654'),
(5, 1, '2025-09-11 17:22:33', 250.00, 'COD', 'Gulisthan', '321654987'),
(9, 1, '2025-09-11 17:36:46', 355.00, 'COD', 'Gulisthan', '000000000'),
(10, 4, '2025-09-12 15:01:11', 100.00, 'COD', 'Gulisthan', '017654654654'),
(11, 5, '2025-09-12 15:01:50', 355.00, 'COD', 'Gulisthan', '000000000'),
(12, 6, '2025-09-12 15:10:36', 1250.00, 'COD', 'sdsdfg;ljk', '6545614');

-- --------------------------------------------------------

--
-- Table structure for table `plants`
--

CREATE TABLE `plants` (
  `PlantID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Type` varchar(50) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `QuantityInStock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plants`
--

INSERT INTO `plants` (`PlantID`, `Name`, `Type`, `Price`, `QuantityInStock`) VALUES
(1, 'Rose', 'Flowering', 50.00, 94),
(2, 'Tulip', 'Flowering', 35.50, 110),
(3, 'Fern', 'Foliage', 25.00, 140),
(4, 'Belly', 'Flower', 10.00, 500);

-- --------------------------------------------------------

--
-- Table structure for table `plantsuppliers`
--

CREATE TABLE `plantsuppliers` (
  `PlantID` int(11) NOT NULL,
  `SupplierID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plantsuppliers`
--

INSERT INTO `plantsuppliers` (`PlantID`, `SupplierID`) VALUES
(1, 1),
(2, 1),
(3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `SupplierID` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`SupplierID`, `Name`, `Phone`, `Email`, `Address`) VALUES
(1, 'Green Supplier Ltd', '01912345678', 'contact@greensupplier.com', 'Dhaka, Bangladesh'),
(2, 'Flora Wholesalers', '01823456789', 'info@florawholesalers.com', 'Chittagong, Bangladesh');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Address` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `Name`, `Email`, `Password`, `Phone`, `Address`) VALUES
(1, 'ovi', 'ovi@gmail.com', 'ovi32', NULL, NULL),
(2, 'Cat', 'cat@cat', '123', NULL, NULL),
(3, 'shanto', 's@s', '123', NULL, NULL),
(4, 'a', 'a@a', '1234', NULL, NULL); 

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AdminID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`CustomerID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`OrderDetailID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `PlantID` (`PlantID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `fk_customer_user` (`CustomerID`);

--
-- Indexes for table `plants`
--
ALTER TABLE `plants`
  ADD PRIMARY KEY (`PlantID`);

--
-- Indexes for table `plantsuppliers`
--
ALTER TABLE `plantsuppliers`
  ADD PRIMARY KEY (`PlantID`,`SupplierID`),
  ADD KEY `SupplierID` (`SupplierID`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`SupplierID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `OrderDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `plants`
--
ALTER TABLE `plants`
  MODIFY `PlantID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `SupplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`PlantID`) REFERENCES `plants` (`PlantID`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_customer_user` FOREIGN KEY (`CustomerID`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plantsuppliers`
--
ALTER TABLE `plantsuppliers`
  ADD CONSTRAINT `plantsuppliers_ibfk_1` FOREIGN KEY (`PlantID`) REFERENCES `plants` (`PlantID`) ON DELETE CASCADE,
  ADD CONSTRAINT `plantsuppliers_ibfk_2` FOREIGN KEY (`SupplierID`) REFERENCES `suppliers` (`SupplierID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
