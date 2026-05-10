-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2026 at 09:54 AM
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
-- Database: `genheals_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `gerakan_latihan`
--

CREATE TABLE `gerakan_latihan` (
  `id` int(11) NOT NULL,
  `modul_id` int(11) NOT NULL,
  `nama_gerakan` varchar(100) NOT NULL,
  `durasi` varchar(20) NOT NULL,
  `fokus` varchar(50) NOT NULL,
  `link_youtube` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gerakan_latihan`
--

INSERT INTO `gerakan_latihan` (`id`, `modul_id`, `nama_gerakan`, `durasi`, `fokus`, `link_youtube`) VALUES
(1, 1, 'Marilyn Kiss', '00:30', 'Fokus Pipi', 'https://www.youtube.com/embed/dQw4w9WgXcQ'),
(2, 1, 'Cheek Firmer', '00:30', 'Fokus Pipi', 'https://www.youtube.com/embed/dQw4w9WgXcQ');

-- --------------------------------------------------------

--
-- Table structure for table `langganan`
--

CREATE TABLE `langganan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `paket` varchar(50) NOT NULL,
  `mulai_berlaku` datetime DEFAULT NULL,
  `berakhir_pada` datetime DEFAULT NULL,
  `status` enum('aktif','pending','nonaktif') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `langganan`
--

INSERT INTO `langganan` (`id`, `user_id`, `paket`, `mulai_berlaku`, `berakhir_pada`, `status`) VALUES
(1, 1, 'Trial Gratis 7 Hari', '2026-05-06 10:24:54', '2026-05-13 10:24:54', 'aktif'),
(2, 1, 'Paket Hemat 6 Bulan', NULL, NULL, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `modul_latihan`
--

CREATE TABLE `modul_latihan` (
  `id` int(11) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `estimasi_waktu` varchar(20) NOT NULL,
  `gambar_cover` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modul_latihan`
--

INSERT INTO `modul_latihan` (`id`, `kategori`, `judul`, `estimasi_waktu`, `gambar_cover`, `deskripsi`) VALUES
(1, 'Perawatan Wajah', 'Face Contour Boost', '3 menit', 'https://images.unsplash.com/photo-1512290923902-8a9f81dc236c?w=500&q=80', 'Ubah struktur wajahmu dengan latihan rutin 3 menit setiap hari secara alami.');

-- --------------------------------------------------------

--
-- Table structure for table `pelacak_air`
--

CREATE TABLE `pelacak_air` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah_gelas` int(11) DEFAULT 0,
  `total_ml` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelacak_air`
--

INSERT INTO `pelacak_air` (`id`, `user_id`, `tanggal`, `jumlah_gelas`, `total_ml`) VALUES
(1, 1, '2026-05-06', 4, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama_pengguna` varchar(100) NOT NULL,
  `nomor_whatsapp` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama_pengguna`, `nomor_whatsapp`, `email`, `password`, `tanggal_lahir`, `role`, `created_at`) VALUES
(1, 'admin', '085722408690', NULL, '$2y$10$dCIy2GU8p6lCgQfE6yxpku5a4WQyWIH5t3IS5fgbIZBIlOITVtw4y', NULL, 'user', '2026-05-06 03:53:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gerakan_latihan`
--
ALTER TABLE `gerakan_latihan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `langganan`
--
ALTER TABLE `langganan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `modul_latihan`
--
ALTER TABLE `modul_latihan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pelacak_air`
--
ALTER TABLE `pelacak_air`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gerakan_latihan`
--
ALTER TABLE `gerakan_latihan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `langganan`
--
ALTER TABLE `langganan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `modul_latihan`
--
ALTER TABLE `modul_latihan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pelacak_air`
--
ALTER TABLE `pelacak_air`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `langganan`
--
ALTER TABLE `langganan`
  ADD CONSTRAINT `langganan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pelacak_air`
--
ALTER TABLE `pelacak_air`
  ADD CONSTRAINT `pelacak_air_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
