<?php
session_start();

require_once '../db/koneksi.php'; // Sesuaikan dengan path yang benar

// Inisialisasi variabel
$laporanData = array();
$rekapData = array();

// Retrieve user ID from session
if (isset($_SESSION['IDKaryawan'])) {
    $userID = $_SESSION['IDKaryawan'];

     // Query untuk laporan cuti dari view LaporanCuti
     $laporanQuery = "SELECT * FROM LaporanCuti WHERE IDKaryawan = '$userID'";
    
     // Logging query
     error_log("Laporan Query: " . $laporanQuery);
 
     $laporanResult = $conn->query($laporanQuery);
 

     if ($laporanResult) {
        if ($laporanResult->num_rows > 0) {
            while ($row = $laporanResult->fetch_assoc()) {
                $laporanData[] = $row;
            }
        } else {
            $laporanData = array(); // Jika tidak ada data
        }
    } else {
        // Logging error jika query gagal
        error_log("Error: " . $laporanQuery . " - " . $conn->error);
        echo "Error: " . $laporanQuery . "<br>" . $conn->error;
    }

    // Query untuk rekapitulasi laporan cuti per bulan
    $rekapQuery = "SELECT MONTH(TanggalAwal) AS Bulan, COUNT(*) AS Jumlah FROM pengajuancuti WHERE IDKaryawan = '$userID' GROUP BY MONTH(TanggalAwal)";
    
    // Logging query
    error_log("Rekap Query: " . $rekapQuery);

    $rekapResult = $conn->query($rekapQuery);
    
    if ($rekapResult) {
        if ($rekapResult->num_rows > 0) {
            while ($row = $rekapResult->fetch_assoc()) {
                $bulan = $row["Bulan"];
                $jumlah = $row["Jumlah"];

                // Convert numeric month to month name
                $namaBulan = date("F", mktime(0, 0, 0, $bulan, 1));

                // Tambahkan ke array rekapData
                $rekapData[] = array(
                    'Bulan' => $namaBulan,
                    'Jumlah' => $jumlah
                );
            }
        } else {
            $rekapData = array(); // Jika tidak ada data
        }
    } else {
        echo "Error: " . $rekapQuery . "<br>" . $conn->error;
    }
} else {
    
    exit("User session not found. Please login.");
}

// Close the database connection after use
$conn->close();

// Include view file
include('../view/laporanView.php');
?>
