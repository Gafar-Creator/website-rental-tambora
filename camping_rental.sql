-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 12:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `camping_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama_lengkap`, `email`, `created_at`) VALUES
(1, 'admin1', '790f6f9d54a9608cb5b4c776375d76d3', 'abdul gafar', 'afangafar@gmail.com', '2025-12-16 08:06:01'),
(3, 'admin2', '$2y$10$kd9MN6C18WpBLuqStbqrqOtrXd8RSRIZ.ZIiO.77CUsMASi/Vx1EK', '', NULL, '2025-12-16 09:45:34'),
(5, 'admin123', '$2y$10$WCxnYEGW0FY5w1DB6Q.1fujzQly7EhEkSyd2KoQ60bRiUxlbDgJxG', '', NULL, '2025-12-16 11:01:55');

-- --------------------------------------------------------

--
-- Table structure for table `alat`
--

CREATE TABLE `alat` (
  `id` int(11) NOT NULL,
  `nama_alat` varchar(100) NOT NULL,
  `harga_sewa` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alat`
--

INSERT INTO `alat` (`id`, `nama_alat`, `harga_sewa`, `stok`, `foto`) VALUES
(1, 'Tenda Dome', 50000, 10, '1765880459_OIP (2).jpeg'),
(2, 'Sleeping Bag', 20000, 15, '1765880378_OIP (1).jpeg'),
(3, 'Carrier Ransel', 30000, 8, '1765880199_OIP.jpeg'),
(4, 'Tenda 2 Orang', 50, 10, '1765880284_S3452a13fc88e469aa23ccb2fc30679deF.jpg_720x720q80.jpg'),
(5, 'Kursi Lipat', 20000, 15, '1765880610_OIP (3).jpeg'),
(6, 'Meja Lipat', 50000, 30, '1765880804_OIP (4).jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `sewa`
--

CREATE TABLE `sewa` (
  `id` int(11) NOT NULL,
  `nama_penyewa` varchar(100) NOT NULL,
  `alat_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tgl_sewa` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sewa`
--

INSERT INTO `sewa` (`id`, `nama_penyewa`, `alat_id`, `jumlah`, `tgl_sewa`, `tgl_kembali`, `total`) VALUES
(1, 'adrian', 2, 2, '2025-12-17', '2025-12-18', 40000),
(2, 'sahril', 3, 5, '2025-12-17', '2025-12-18', 150000),
(3, 'abdul gafar', 3, 1, '2025-12-17', '2025-12-18', 30000),
(4, 'given', 1, 2, '2025-12-17', '2025-12-19', 100000),
(5, 'adit', 6, 2, '2025-12-19', '2025-12-20', 100000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `alat`
--
ALTER TABLE `alat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sewa`
--
ALTER TABLE `sewa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alat_id` (`alat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `alat`
--
ALTER TABLE `alat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sewa`
--
ALTER TABLE `sewa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sewa`
--
ALTER TABLE `sewa`
  ADD CONSTRAINT `sewa_ibfk_1` FOREIGN KEY (`alat_id`) REFERENCES `alat` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
