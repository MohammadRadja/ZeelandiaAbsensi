-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 20 Jun 2024 pada 04.25
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
-- Struktur dari tabel `hrd`
--

CREATE TABLE `hrd` (
  `IDHRD` int NOT NULL,
  `NamaHRD` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `hrd`
--

INSERT INTO `hrd` (`IDHRD`, `NamaHRD`, `Email`, `Username`, `Password`) VALUES
(1, 'Jessica Tan', 'jessica.tan@example.com', 'jessicatan', 'hrdpwd1'),
(2, 'Kevin Chan', 'kevin.chan@example.com', 'kevinchan', 'hrdpwd2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `IDKaryawan` int NOT NULL,
  `NamaKaryawan` varchar(255) NOT NULL,
  `Jabatan` varchar(100) NOT NULL,
  `NIK` varchar(50) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `TanggalLahir` date NOT NULL,
  `TanggalBergabung` date NOT NULL,
  `Alamat` text NOT NULL,
  `NoTelp` varchar(15) NOT NULL,
  `MasaKerja` int DEFAULT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`IDKaryawan`, `NamaKaryawan`, `Jabatan`, `NIK`, `Email`, `TanggalLahir`, `TanggalBergabung`, `Alamat`, `NoTelp`, `MasaKerja`, `Username`, `Password`) VALUES
(1, 'John Doe', 'Staff', '1234567890', 'john.doe@example.com', '1990-05-15', '2010-07-01', 'Jl. Raya No. 123, Jakarta', '081234567890', 13, 'johndoe', 'password123'),
(2, 'Jane Smith', 'Manager', '0987654321', 'jane.smith@example.com', '1985-09-20', '2005-03-10', 'Jl. Indah No. 456, Surabaya', '087654321098', 19, 'janesmith', 'password456'),
(3, 'Michael Johnson', 'Analyst', '5678901234', 'michael.johnson@example.com', '1995-12-03', '2018-02-15', 'Jl. Mawar No. 789, Bandung', '089012345678', 6, 'michaelj', 'securepwd');

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
,`TanggalAwal` date
,`JenisCuti` enum('cuti sakit','cuti meninggal (keluarga)','cuti sidang sarjana','cuti wisuda','cuti melahirkan','cuti 1 hari area luar kota')
,`Jumlah hari` bigint
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `manager`
--

CREATE TABLE `manager` (
  `IDManager` int NOT NULL,
  `NamaManager` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `manager`
--

INSERT INTO `manager` (`IDManager`, `NamaManager`, `Email`, `Username`, `Password`) VALUES
(1, 'Sarah Lee', 'sarah.lee@example.com', 'sarahlee', 'managerpwd1'),
(2, 'David Wong', 'david.wong@example.com', 'davidwong', 'managerpwd2');

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
(1, 1, 'John Doe', 'Staff', '1234567890', 'cuti sakit', '2024-06-01', '2024-06-03', 'Sakit perut', 'cuti_sakit_johndoe.pdf', 'Disetujui'),
(2, 2, 'Jane Smith', 'Manager', '0987654321', 'cuti melahirkan', '2024-07-10', '2024-08-10', 'Melahirkan anak kedua', 'cuti_melahirkan_janesmith.pdf', 'Pending'),
(3, 3, 'Michael Johnson', 'Analyst', '5678901234', 'cuti 1 hari area luar kota', '2024-07-15', '2024-07-15', 'Meeting di luar kota', NULL, 'Ditolak');

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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `laporancuti`  AS SELECT row_number() OVER (ORDER BY `pengajuancuti`.`TanggalAwal` ) AS `No`, `pengajuancuti`.`TanggalAwal` AS `TanggalAwal`, `pengajuancuti`.`JenisCuti` AS `JenisCuti`, ((to_days(`pengajuancuti`.`TanggalAkhir`) - to_days(`pengajuancuti`.`TanggalAwal`)) + 1) AS `Jumlah hari` FROM `pengajuancuti` ;

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
-- Indeks untuk tabel `hrd`
--
ALTER TABLE `hrd`
  ADD PRIMARY KEY (`IDHRD`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`IDKaryawan`),
  ADD UNIQUE KEY `NIK` (`NIK`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indeks untuk tabel `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`IDManager`),
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
-- AUTO_INCREMENT untuk tabel `hrd`
--
ALTER TABLE `hrd`
  MODIFY `IDHRD` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `IDKaryawan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `manager`
--
ALTER TABLE `manager`
  MODIFY `IDManager` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pengajuancuti`
--
ALTER TABLE `pengajuancuti`
  MODIFY `IDPengajuan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
