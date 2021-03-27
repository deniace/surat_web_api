-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2021 at 01:59 AM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `surat_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_jabatan`
--

CREATE TABLE `tb_jabatan` (
  `id_jabatan` int(11) NOT NULL,
  `nama_jabatan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_jabatan`
--

INSERT INTO `tb_jabatan` (`id_jabatan`, `nama_jabatan`) VALUES
(1, 'admin'),
(2, 'Ketua RT'),
(3, 'Ketua RW'),
(4, 'Warga');

-- --------------------------------------------------------

--
-- Table structure for table `tb_login`
--

CREATE TABLE `tb_login` (
  `id_login` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_login`
--

INSERT INTO `tb_login` (`id_login`, `email`, `password`) VALUES
(3, 'admin@mail.com', '$2y$10$UZWOwv1vlqDN84p0k5421eyKy/PXFRh9uXU9rTMcFffkHMV5uvefS'),
(5, 'deni@mail.com', '$2y$10$Bckovnj5XRbWQ7CFmJorWuoKJEat9L5o8bcfGfdW/wyLnbp1fi5eC'),
(6, 'warga1@mail.com', '$2y$10$KLaQQblg3PpD67ACWX2ale5vE/ajhsP8l2oLRL8bdqDuDEsVLZ8za'),
(7, 'susi@mail.com', '$2y$10$A.azy57lsXn5f7BH4j2LT.nUGgrtaG.2ZgXcmDLYUqxoUNKnorsfS'),
(8, 'budi@mail.com', '$2y$10$B0/8pTo2/XG9q7dJbZaLFevi8NQJNb1u36EZfwFSdM2wE88Cspgse'),
(9, 'untung@mail.com', '$2y$10$9AfnNQR5zMb7Dk8R2/X4FOTkQJO3j/X1Msj9//yHIVHP9Xdvb7Qgi'),
(10, 'slamet@mail.com', '$2y$10$ouEl7jc1P5E4841owzKddeMuOPf14dPoszjzn.WYmQ8xaZqL/Fuoy'),
(11, 'muklis@mail.com', '$2y$10$Y/lY2cDlmkm5GrRafmtizeqa9/5/U524WbFpDuKPf/x2O2qh4K3gy'),
(12, 'bobi@mail.com', '$2y$10$VikL1U27Mc8U0MqsBxX7NeWYuV8J0rfHwpSpI3j1zysGFBPtMcI8G'),
(13, 'ali@mail.com', '$2y$10$xOUzuLhpLV9ItPfFzhCc8OmfOH7MxHnbSkOvrbOvRud2kgvdK1n1i');

-- --------------------------------------------------------

--
-- Table structure for table `tb_login_user`
--

CREATE TABLE `tb_login_user` (
  `id_login_user` int(11) NOT NULL,
  `id_login` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_login_user`
--

INSERT INTO `tb_login_user` (`id_login_user`, `id_login`, `id_user`) VALUES
(1, 3, 1),
(2, 5, 2),
(3, 6, 3),
(4, 7, 4),
(5, 8, 5),
(6, 9, 6),
(7, 10, 7),
(8, 11, 8),
(9, 12, 9),
(10, 13, 10);

-- --------------------------------------------------------

--
-- Table structure for table `tb_status`
--

CREATE TABLE `tb_status` (
  `id_status` int(11) NOT NULL,
  `nama_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_status`
--

INSERT INTO `tb_status` (`id_status`, `nama_status`) VALUES
(0, 'Pending'),
(1, 'Diterima'),
(2, 'Ditolak');

-- --------------------------------------------------------

--
-- Table structure for table `tb_surat_pengantar`
--

CREATE TABLE `tb_surat_pengantar` (
  `id_surat` int(11) NOT NULL,
  `no_surat` varchar(50) NOT NULL,
  `id_user` int(11) NOT NULL,
  `keperluan` varchar(255) NOT NULL,
  `id_rt` int(11) NOT NULL DEFAULT 0,
  `id_rw` int(11) NOT NULL DEFAULT 0,
  `id_status_rt` int(11) NOT NULL DEFAULT 0,
  `id_status_rw` int(11) NOT NULL DEFAULT 0,
  `tgl_rt` date NOT NULL,
  `nama_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_surat_pengantar`
--

INSERT INTO `tb_surat_pengantar` (`id_surat`, `no_surat`, `id_user`, `keperluan`, `id_rt`, `id_rw`, `id_status_rt`, `id_status_rw`, `tgl_rt`, `nama_file`) VALUES
(1, '', 3, 'Membuaat KTP', 8, 9, 1, 2, '2020-06-12', ''),
(2, '', 3, 'Membuat KTP', 8, 0, 1, 0, '2020-06-12', ''),
(3, '', 3, 'Membuat SIM', 8, 9, 1, 1, '2020-06-12', ''),
(4, '', 4, 'Membuat SKCK', 0, 0, 0, 0, '0000-00-00', ''),
(5, '', 3, 'Membuat Surat Izin Menikah', 0, 0, 0, 0, '0000-00-00', ''),
(6, '', 5, 'Membuat KTP', 6, 9, 1, 2, '2020-06-21', ''),
(7, '', 3, 'Pembuatan Kartu Keluarga (KK)', 8, 0, 1, 0, '2021-03-24', ''),
(8, '', 5, 'Membuat KTP', 0, 0, 0, 0, '0000-00-00', '');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(11) NOT NULL,
  `id_jabatan` int(11) NOT NULL,
  `nama_user` varchar(255) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `rt` varchar(5) NOT NULL,
  `rw` varchar(5) NOT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan','','') NOT NULL,
  `agama` enum('Islam','Kristen Protestan','Katolik','Hindu','Budha') NOT NULL,
  `tempat_lahir` varchar(40) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `nik` varchar(30) NOT NULL,
  `status_perkawinan` enum('Belum Menikah','Sudah Menikah','Duda','Janda') NOT NULL,
  `pekerjaan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `id_jabatan`, `nama_user`, `no_hp`, `alamat`, `rt`, `rw`, `jenis_kelamin`, `agama`, `tempat_lahir`, `tanggal_lahir`, `nik`, `status_perkawinan`, `pekerjaan`) VALUES
(1, 1, 'admin', '123123123', 'admin', '01', '01', 'Laki-Laki', 'Islam', 'Bekasi', '2020-06-09', '', 'Belum Menikah', 'Kerja'),
(2, 4, 'deni', '098', 'jakan', '01', '01', 'Laki-Laki', 'Islam', 'Jakarta', '2020-12-31', '', 'Belum Menikah', 'Kerja'),
(3, 4, 'warga1', '1234', 'warga alamata', '01', '01', 'Perempuan', 'Islam', 'Bekasi', '2020-06-30', '', 'Sudah Menikah', 'Kerja'),
(4, 4, 'Susi Susanti', '09876', 'jalan kaki', '02', '01', 'Perempuan', 'Islam', 'Bekasi', '2000-06-11', '', 'Belum Menikah', 'Kerja'),
(5, 4, 'Budi anduk', '08121212', 'jalan lubang', '03', '01', 'Laki-Laki', 'Islam', 'Bekasi', '2004-06-11', '', 'Belum Menikah', 'Kerja'),
(6, 2, 'Untung', '0812345', 'jalan lubang', '03', '01', 'Laki-Laki', 'Islam', 'Bekasi', '2004-06-11', '', 'Belum Menikah', 'Kerja'),
(7, 2, 'Slamet', '0812345678', 'jalan kaki', '02', '01', 'Laki-Laki', 'Islam', 'Bekasi', '2004-06-11', '', 'Sudah Menikah', 'Kerja'),
(8, 2, 'Muklis', '0856345678', 'jalan jalan', '01', '01', 'Laki-Laki', 'Islam', 'Bekasi', '1998-06-30', '', 'Sudah Menikah', 'Kerja'),
(9, 3, 'Bobi', '0857345678', 'jalan jalan', '01', '01', 'Laki-Laki', 'Islam', 'Bekasi', '1982-08-31', '', 'Sudah Menikah', 'Kerja'),
(10, 4, 'Ali', '123123', 'jalanan', '04', '01', 'Laki-Laki', 'Islam', 'Babelan', '2020-06-12', '', 'Belum Menikah', 'Karyawan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_jabatan`
--
ALTER TABLE `tb_jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indexes for table `tb_login`
--
ALTER TABLE `tb_login`
  ADD PRIMARY KEY (`id_login`);

--
-- Indexes for table `tb_login_user`
--
ALTER TABLE `tb_login_user`
  ADD PRIMARY KEY (`id_login_user`),
  ADD KEY `login_login` (`id_login`),
  ADD KEY `user_user` (`id_user`);

--
-- Indexes for table `tb_status`
--
ALTER TABLE `tb_status`
  ADD PRIMARY KEY (`id_status`);

--
-- Indexes for table `tb_surat_pengantar`
--
ALTER TABLE `tb_surat_pengantar`
  ADD PRIMARY KEY (`id_surat`),
  ADD KEY `surat_user` (`id_user`),
  ADD KEY `surat_status_rt` (`id_status_rt`),
  ADD KEY `surat_status_rw` (`id_status_rw`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_jabatan`
--
ALTER TABLE `tb_jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_login`
--
ALTER TABLE `tb_login`
  MODIFY `id_login` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tb_login_user`
--
ALTER TABLE `tb_login_user`
  MODIFY `id_login_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tb_surat_pengantar`
--
ALTER TABLE `tb_surat_pengantar`
  MODIFY `id_surat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_login_user`
--
ALTER TABLE `tb_login_user`
  ADD CONSTRAINT `login_login` FOREIGN KEY (`id_login`) REFERENCES `tb_login` (`id_login`),
  ADD CONSTRAINT `user_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_surat_pengantar`
--
ALTER TABLE `tb_surat_pengantar`
  ADD CONSTRAINT `surat_status_rt` FOREIGN KEY (`id_status_rt`) REFERENCES `tb_status` (`id_status`),
  ADD CONSTRAINT `surat_status_rw` FOREIGN KEY (`id_status_rw`) REFERENCES `tb_status` (`id_status`),
  ADD CONSTRAINT `surat_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
