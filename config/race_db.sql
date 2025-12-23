-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2025 at 05:53 AM
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
-- Database: `race_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$GFJdtmJy9A6OIxkTHkMxn.46sRZXq0aeaIiJ6mHFpmTaskGgDs08u', 'admin', '2025-12-23 11:48:46'),
(2, 'user', '$2y$10$86ElaVk/gbdUE8ceXAwfjuyRiZ02SCC8PQDKWZde.vmi8nmlX4oXq', 'user', '2025-12-23 11:49:12');

-- --------------------------------------------------------

--
-- Table structure for table `การลงทะเบียน`
--

CREATE TABLE `การลงทะเบียน` (
  `รหัสใบสมัคร` int(11) NOT NULL,
  `รหัสนักวิ่ง` int(11) NOT NULL,
  `รหัสประเภท` int(11) NOT NULL,
  `รหัสราคา` int(11) NOT NULL,
  `รหัสขนส่ง` int(11) NOT NULL,
  `วันที่สมัคร` datetime DEFAULT current_timestamp(),
  `ไซส์เสื้อ` varchar(10) DEFAULT NULL,
  `หมายเลข_bib` varchar(20) DEFAULT NULL,
  `สถานะ` enum('รอชำระเงิน','ชำระแล้ว','ยกเลิก') DEFAULT 'รอชำระเงิน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ตัวเลือกการจัดส่ง`
--

CREATE TABLE `ตัวเลือกการจัดส่ง` (
  `รหัสขนส่ง` int(11) NOT NULL,
  `รูปแบบ` varchar(50) NOT NULL,
  `ราคาค่าส่ง` decimal(10,2) DEFAULT 0.00,
  `รายละเอียด` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ตัวเลือกการจัดส่ง`
--

INSERT INTO `ตัวเลือกการจัดส่ง` (`รหัสขนส่ง`, `รูปแบบ`, `ราคาค่าส่ง`, `รายละเอียด`) VALUES
(1, 'รับเองหน้างาน', 0.00, NULL),
(2, 'EMS', 60.00, NULL),
(3, 'Kerry', 100.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `นักวิ่ง`
--

CREATE TABLE `นักวิ่ง` (
  `รหัสนักวิ่ง` int(11) NOT NULL,
  `ชื่อจริง` varchar(100) NOT NULL,
  `นามสกุล` varchar(100) NOT NULL,
  `วันเกิด` date NOT NULL,
  `เพศ` enum('ชาย','หญิง','อื่นๆ') NOT NULL,
  `เลขบัตรประชาชน` varchar(20) DEFAULT NULL,
  `เบอร์โทรศัพท์` varchar(20) DEFAULT NULL,
  `อีเมล` varchar(100) NOT NULL,
  `ที่อยู่` text DEFAULT NULL,
  `โรคประจำตัว` text DEFAULT NULL COMMENT 'ระบุโรคประจำตัว',
  `สถานะผู้พิการ` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ประเภทการแข่งขัน`
--

CREATE TABLE `ประเภทการแข่งขัน` (
  `รหัสประเภท` int(11) NOT NULL,
  `ชื่อรายการ` varchar(100) NOT NULL,
  `ระยะทาง_กม` float NOT NULL,
  `เวลาปล่อยตัว` time DEFAULT NULL,
  `เวลาตัดตัว` varchar(50) DEFAULT NULL,
  `ของที่ระลึก` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ประเภทการแข่งขัน`
--

INSERT INTO `ประเภทการแข่งขัน` (`รหัสประเภท`, `ชื่อรายการ`, `ระยะทาง_กม`, `เวลาปล่อยตัว`, `เวลาตัดตัว`, `ของที่ระลึก`) VALUES
(1, 'Full Marathon', 42.195, '03:00:00', NULL, NULL),
(2, 'Half Marathon', 21.1, '04:00:00', NULL, NULL),
(3, 'Mini Marathon', 10.5, '05:00:00', NULL, NULL),
(4, 'Fun Run', 5, '05:30:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `เรทราคา`
--

CREATE TABLE `เรทราคา` (
  `รหัสราคา` int(11) NOT NULL,
  `รหัสประเภท` int(11) NOT NULL,
  `ประเภทนักวิ่ง` varchar(50) DEFAULT NULL,
  `ราคา` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `เรทราคา`
--

INSERT INTO `เรทราคา` (`รหัสราคา`, `รหัสประเภท`, `ประเภทนักวิ่ง`, `ราคา`) VALUES
(1, 1, 'บุคคลทั่วไป', 1200.00),
(2, 1, 'ผู้สูงอายุ', 1000.00),
(3, 2, 'บุคคลทั่วไป', 900.00),
(4, 2, 'ผู้สูงอายุ', 700.00),
(5, 3, 'บุคคลทั่วไป', 600.00),
(6, 3, 'ผู้สูงอายุ', 400.00),
(7, 4, 'บุคคลทั่วไป', 400.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `การลงทะเบียน`
--
ALTER TABLE `การลงทะเบียน`
  ADD PRIMARY KEY (`รหัสใบสมัคร`),
  ADD KEY `รหัสนักวิ่ง` (`รหัสนักวิ่ง`),
  ADD KEY `รหัสประเภท` (`รหัสประเภท`),
  ADD KEY `รหัสราคา` (`รหัสราคา`),
  ADD KEY `รหัสขนส่ง` (`รหัสขนส่ง`);

--
-- Indexes for table `ตัวเลือกการจัดส่ง`
--
ALTER TABLE `ตัวเลือกการจัดส่ง`
  ADD PRIMARY KEY (`รหัสขนส่ง`);

--
-- Indexes for table `นักวิ่ง`
--
ALTER TABLE `นักวิ่ง`
  ADD PRIMARY KEY (`รหัสนักวิ่ง`),
  ADD UNIQUE KEY `เลขบัตรประชาชน` (`เลขบัตรประชาชน`);

--
-- Indexes for table `ประเภทการแข่งขัน`
--
ALTER TABLE `ประเภทการแข่งขัน`
  ADD PRIMARY KEY (`รหัสประเภท`);

--
-- Indexes for table `เรทราคา`
--
ALTER TABLE `เรทราคา`
  ADD PRIMARY KEY (`รหัสราคา`),
  ADD KEY `รหัสประเภท` (`รหัสประเภท`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `การลงทะเบียน`
--
ALTER TABLE `การลงทะเบียน`
  MODIFY `รหัสใบสมัคร` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ตัวเลือกการจัดส่ง`
--
ALTER TABLE `ตัวเลือกการจัดส่ง`
  MODIFY `รหัสขนส่ง` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `นักวิ่ง`
--
ALTER TABLE `นักวิ่ง`
  MODIFY `รหัสนักวิ่ง` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ประเภทการแข่งขัน`
--
ALTER TABLE `ประเภทการแข่งขัน`
  MODIFY `รหัสประเภท` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `เรทราคา`
--
ALTER TABLE `เรทราคา`
  MODIFY `รหัสราคา` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `การลงทะเบียน`
--
ALTER TABLE `การลงทะเบียน`
  ADD CONSTRAINT `การลงทะเบียน_ibfk_1` FOREIGN KEY (`รหัสนักวิ่ง`) REFERENCES `นักวิ่ง` (`รหัสนักวิ่ง`),
  ADD CONSTRAINT `การลงทะเบียน_ibfk_2` FOREIGN KEY (`รหัสประเภท`) REFERENCES `ประเภทการแข่งขัน` (`รหัสประเภท`),
  ADD CONSTRAINT `การลงทะเบียน_ibfk_3` FOREIGN KEY (`รหัสราคา`) REFERENCES `เรทราคา` (`รหัสราคา`),
  ADD CONSTRAINT `การลงทะเบียน_ibfk_4` FOREIGN KEY (`รหัสขนส่ง`) REFERENCES `ตัวเลือกการจัดส่ง` (`รหัสขนส่ง`);

--
-- Constraints for table `เรทราคา`
--
ALTER TABLE `เรทราคา`
  ADD CONSTRAINT `เรทราคา_ibfk_1` FOREIGN KEY (`รหัสประเภท`) REFERENCES `ประเภทการแข่งขัน` (`รหัสประเภท`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
