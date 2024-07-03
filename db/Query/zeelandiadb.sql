-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 02 Jul 2024 pada 12.39
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
  `NamaKaryawan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Jabatan` enum('karyawan','hrd','manager') NOT NULL,
  `NIK` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `TanggalLahir` date DEFAULT NULL,
  `TanggalBergabung` date DEFAULT NULL,
  `Alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `NoTelp` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `MasaKerja` int DEFAULT NULL,
  `Username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`IDKaryawan`, `NamaKaryawan`, `Jabatan`, `NIK`, `Email`, `TanggalLahir`, `TanggalBergabung`, `Alamat`, `NoTelp`, `MasaKerja`, `Username`, `Password`, `Foto`) VALUES
(1111, 'test', 'hrd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'test', '098f6bcd4621d373cade4e832627b4f6', NULL),
(11211, 'radja', 'karyawan', '11211', 'radja.amri28@gmail.com', NULL, '2024-06-30', 'Pesona Parahiyangan', '11211', 0, 'radja', '42afcd328885ec205cb656b53194e816', NULL);

--
-- Trigger `karyawan`
--
DELIMITER $$
CREATE TRIGGER `before_insert_karyawan` BEFORE INSERT ON `karyawan` FOR EACH ROW BEGIN
    SET NEW.MasaKerja = TIMESTAMPDIFF(YEAR, NEW.TanggalBergabung, CURDATE());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_karyawan` BEFORE UPDATE ON `karyawan` FOR EACH ROW BEGIN
    SET NEW.MasaKerja = TIMESTAMPDIFF(YEAR, NEW.TanggalBergabung, CURDATE());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `laporancuti`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `laporancuti` (
`No` bigint unsigned
,`IDKaryawan` int
,`NamaKaryawan` varchar(255)
,`TanggalAwal` date
,`JenisCuti` enum('cuti sakit','cuti meninggal (keluarga)','cuti sidang sarjana','cuti wisuda','cuti melahirkan','cuti 1 hari area luar kota')
,`Jumlah hari` bigint
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuancuti`
--

CREATE TABLE `pengajuancuti` (
  `IDPengajuan` int NOT NULL,
  `IDKaryawan` int DEFAULT NULL,
  `NamaKaryawan` varchar(255) NOT NULL,
  `Jabatan` varchar(100) NOT NULL,
  `NIK` varchar(50) NOT NULL,
  `JenisCuti` enum('cuti sakit','cuti meninggal (keluarga)','cuti sidang sarjana','cuti wisuda','cuti melahirkan','cuti 1 hari area luar kota') NOT NULL,
  `TanggalAwal` date NOT NULL,
  `TanggalAkhir` date NOT NULL,
  `Alasan` text NOT NULL,
  `Lampiran` varchar(255) DEFAULT NULL,
  `Status` enum('Pending','Disetujui','Ditolak') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pengajuancuti`
--

INSERT INTO `pengajuancuti` (`IDPengajuan`, `IDKaryawan`, `NamaKaryawan`, `Jabatan`, `NIK`, `JenisCuti`, `TanggalAwal`, `TanggalAkhir`, `Alasan`, `Lampiran`, `Status`) VALUES
(4, 11211, 'Radja', 'Karyawan', '11211', 'cuti sakit', '2024-06-29', '2024-06-30', 'Sakit', NULL, 'Disetujui');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `statuscuti`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `statuscuti` (
`TanggalAwal` date
,`JenisCuti` enum('cuti sakit','cuti meninggal (keluarga)','cuti sidang sarjana','cuti wisuda','cuti melahirkan','cuti 1 hari area luar kota')
,`Status` enum('Pending','Disetujui','Ditolak')
);

-- --------------------------------------------------------

--
-- Struktur untuk view `laporancuti`
--
DROP TABLE IF EXISTS `laporancuti`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `laporancuti`  AS SELECT row_number() OVER (ORDER BY `pengajuancuti`.`TanggalAwal` ) AS `No`, `pengajuancuti`.`IDKaryawan` AS `IDKaryawan`, `pengajuancuti`.`NamaKaryawan` AS `NamaKaryawan`, `pengajuancuti`.`TanggalAwal` AS `TanggalAwal`, `pengajuancuti`.`JenisCuti` AS `JenisCuti`, ((to_days(`pengajuancuti`.`TanggalAkhir`) - to_days(`pengajuancuti`.`TanggalAwal`)) + 1) AS `Jumlah hari` FROM `pengajuancuti` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `statuscuti`
--
DROP TABLE IF EXISTS `statuscuti`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `statuscuti`  AS SELECT `pengajuancuti`.`TanggalAwal` AS `TanggalAwal`, `pengajuancuti`.`JenisCuti` AS `JenisCuti`, `pengajuancuti`.`Status` AS `Status` FROM `pengajuancuti` ;

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
-- AUTO_INCREMENT untuk tabel `pengajuancuti`
--
ALTER TABLE `pengajuancuti`
  MODIFY `IDPengajuan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
