-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2026 at 08:53 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `asset_id` int(11) NOT NULL,
  `asset_code` varchar(50) NOT NULL COMMENT 'รหัสครุภัณฑ์',
  `asset_name` varchar(255) NOT NULL COMMENT 'ชื่อครุภัณฑ์',
  `purchase_date` date DEFAULT NULL COMMENT 'วันที่ซื้อ',
  `price` decimal(10,2) DEFAULT NULL COMMENT 'ราคา',
  `image` varchar(100) DEFAULT NULL COMMENT 'ชื่อไฟล์หรือ Path รูปภาพ',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`asset_id`, `asset_code`, `asset_name`, `purchase_date`, `price`, `image`, `created_at`) VALUES
(1, 'IT001', 'คอมพิวเตอร์ตั้งโต๊ะ', '2026-01-15', '25000.00', '1793346133_computer.jpg', '2026-10-30 06:27:28'),
(2, 'IT002', 'โน้ตบุ๊ก', '2026-02-10', '32000.00', '1793346105_notebook.jpg', '2026-10-30 06:27:28'),
(3, 'IT003', 'เครื่องพิมพ์', '2026-03-05', '8500.00', '1793346078_printer.jpg', '2026-10-30 06:27:28'),
(4, 'IT004', 'โปรเจกเตอร์', '2026-04-20', '18000.00', '1793346048_projector.jpg', '2026-10-30 06:27:28'),
(5, 'IT005', 'เครื่องสำรองไฟ UPS', '2026-05-12', '3500.00', '1793346011_ups.jpg', '2026-10-30 06:27:28'),
(9, 'พวงกุญแจนางเงือก', 'นางเงือกกินเผ็ด', '2026-10-30', '650.00', '1793344774_2026-07-24191359.webp', '2026-10-30 07:19:34');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `detail` text NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `subject`, `detail`, `fullname`, `email`, `created_at`) VALUES
(1, 'งงงง', 'งงงงง', 'หใสบฟ', 'k36889017@gmail.com', '2026-10-02 06:04:03');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(8) UNSIGNED ZEROFILL NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `firstName`, `lastName`, `phone`, `username`, `password`) VALUES
(00000001, 'มานะ', 'เด็กดี', '038756921', 'mana', '1234'),
(00000002, 'มานี', 'ใจดี', '038756901', 'manee', '1234'),
(00000005, 'มีใจ', 'ปลา', '038256921', 'meejai', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_id`, `firstName`, `lastName`, `phone`, `username`, `password`) VALUES
(000001, 'มานา', 'นีนี', '038756923', 'mana', '1234'),
(000002, 'มาดี', 'ปรีดา', '038756911', 'madee', '1234'),
(000003, 'สมชาย', 'ดี', '038756951', 'somchai', '1234'),
(000004, 'สมหญิง', 'ใจ', '038756909', 'somying', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` text DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `image`, `stock`, `created_at`) VALUES
(1, 'เสื้อยืดคอกลม', 'เสื้อยืดผ้าฝ้าย 100% สวมใส่สบาย', '199.00', '1793340335_tshirt.jpg', 50, '2026-09-04 02:52:06'),
(2, 'กางเกงยีนส์', 'กางเกงยีนส์ทรงกระบอก สีฟ้าอ่อน', '799.00', '1793340323_jeans.jpg', 30, '2026-09-04 02:52:06'),
(3, 'รองเท้าผ้าใบ', 'รองเท้าผ้าใบสีขาว ใส่ได้ทุกโอกาส', '1299.00', '1793340314_shoes.jpg', 20, '2026-09-04 02:52:06'),
(4, 'พวงกุญแจกระรอกบิน', 'ใช้สำหรับห้อยกระเป๋า', '350.00', '1793340681_momonga.jpg', 10, '2026-10-30 06:11:21'),
(5, 'แว่นตาเรืองแสง', 'เพิ่มความตระหนักรู้ในตัวคุณ', '999.00', '1793340798_glassess.jpg', 20, '2026-10-30 06:13:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`asset_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `emp_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
