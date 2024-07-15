-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 15 Jul 2024 pada 13.28
-- Versi server: 8.0.30
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zeelandiadb`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `IDKaryawan` int NOT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `NamaKaryawan` varchar(255) DEFAULT NULL,
  `Jabatan` enum('Karyawan','HRD','Manager','Admin','SPV') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `NIK` varchar(50) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `TanggalLahir` date DEFAULT NULL,
  `TanggalBergabung` date DEFAULT NULL,
  `Alamat` text,
  `NoTelp` varchar(15) DEFAULT NULL,
  `MasaKerja` int DEFAULT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `laporancuti`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `laporancuti` (
`No` bigint unsigned
,`IDPengajuan` int
,`IDKaryawan` int
,`NamaKaryawan` varchar(255)
,`TanggalAwal` date
,`JenisCuti` enum('cuti sakit','cuti meninggal (keluarga)','cuti sidang sarjana','cuti wisuda','cuti melahirkan','cuti khitan','cuti 1 hari area luar kota')
,`JumlahHari` bigint
,`Status` enum('Pending','Disetujui','Ditolak')
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuancuti`
--

CREATE TABLE `pengajuancuti` (
  `IDPengajuan` int NOT NULL,
  `IDKaryawan` int DEFAULT NULL,
  `NamaKaryawan` varchar(255) DEFAULT NULL,
  `Jabatan` varchar(100) DEFAULT NULL,
  `NIK` varchar(50) DEFAULT NULL,
  `JenisCuti` enum('cuti sakit','cuti meninggal (keluarga)','cuti sidang sarjana','cuti wisuda','cuti melahirkan','cuti khitan','cuti 1 hari area luar kota') DEFAULT NULL,
  `TanggalAwal` date DEFAULT NULL,
  `TanggalAkhir` date DEFAULT NULL,
  `Alasan` text,
  `Lampiran` varchar(255) DEFAULT NULL,
  `Status` enum('Pending','Disetujui','Ditolak') DEFAULT 'Pending',
  `JumlahSisaCuti` int DEFAULT '12',
  `ApprovedBy` varchar(255) DEFAULT NULL,
  `RejectedBy` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `statuscuti`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `statuscuti` (
`IDKaryawan` int
,`TanggalAwal` date
,`JenisCuti` enum('cuti sakit','cuti meninggal (keluarga)','cuti sidang sarjana','cuti wisuda','cuti melahirkan','cuti khitan','cuti 1 hari area luar kota')
,`Status` enum('Pending','Disetujui','Ditolak')
,`JumlahSisaCuti` int
,`ApprovedBy` varchar(255)
,`RejectedBy` varchar(255)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `laporancuti`
--
DROP TABLE IF EXISTS `laporancuti`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `laporancuti`  AS SELECT row_number() OVER (ORDER BY `pengajuancuti`.`TanggalAwal` ) AS `No`, `pengajuancuti`.`IDPengajuan` AS `IDPengajuan`, `pengajuancuti`.`IDKaryawan` AS `IDKaryawan`, `pengajuancuti`.`NamaKaryawan` AS `NamaKaryawan`, `pengajuancuti`.`TanggalAwal` AS `TanggalAwal`, `pengajuancuti`.`JenisCuti` AS `JenisCuti`, ((to_days(`pengajuancuti`.`TanggalAkhir`) - to_days(`pengajuancuti`.`TanggalAwal`)) + 1) AS `JumlahHari`, `pengajuancuti`.`Status` AS `Status` FROM `pengajuancuti` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `statuscuti`
--
DROP TABLE IF EXISTS `statuscuti`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `statuscuti`  AS SELECT `pengajuancuti`.`IDKaryawan` AS `IDKaryawan`, `pengajuancuti`.`TanggalAwal` AS `TanggalAwal`, `pengajuancuti`.`JenisCuti` AS `JenisCuti`, `pengajuancuti`.`Status` AS `Status`, `pengajuancuti`.`JumlahSisaCuti` AS `JumlahSisaCuti`, `pengajuancuti`.`ApprovedBy` AS `ApprovedBy`, `pengajuancuti`.`RejectedBy` AS `RejectedBy` FROM `pengajuancuti` ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`IDKaryawan`),
  ADD UNIQUE KEY `NIK` (`NIK`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indeks untuk tabel `pengajuancuti`
--
ALTER TABLE `pengajuancuti`
  ADD PRIMARY KEY (`IDPengajuan`),
  ADD KEY `IDKaryawan` (`IDKaryawan`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `IDKaryawan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11212;

--
-- AUTO_INCREMENT untuk tabel `pengajuancuti`
--
ALTER TABLE `pengajuancuti`
  MODIFY `IDPengajuan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pengajuancuti`
--
ALTER TABLE `pengajuancuti`
  ADD CONSTRAINT `pengajuancuti_ibfk_1` FOREIGN KEY (`IDKaryawan`) REFERENCES `karyawan` (`IDKaryawan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
