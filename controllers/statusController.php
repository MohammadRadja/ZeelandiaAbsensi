<?php
require_once '../db/koneksi.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fungsi untuk menambah notifikasi ke sesi
function addNotification($message) {
    if (!isset($_SESSION['notifications'])) {
        $_SESSION['notifications'] = [];
    }
    $currentDateTime = date('Y-m-d H:i:s');
    $_SESSION['notifications'][] = [
        'message' => $message,
        'timestamp' => $currentDateTime
    ];
}

// Ambil IDKaryawan dan jabatan dari session
if (isset($_SESSION['IDKaryawan'], $_SESSION['jabatan'])) {
    $userID = $_SESSION['IDKaryawan'];
    $jabatan = $_SESSION['jabatan'];

    // Mendapatkan data pengajuan cuti berdasarkan role pengguna
    $data = getPengajuanCutiByRole($userID, $jabatan);

    // Menampilkan pesan jika ada yang tersimpan di session
    if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }
}
// Proses POST untuk acc/block status cuti
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $IDPengajuan = $_POST['IDPengajuan'];
    if (isset($_POST['approveStatus'])) {
        updateStatus($IDPengajuan, 'Disetujui');
        $_SESSION['message'] = "Pengajuan cuti berhasil disetujui oleh $jabatan";
    } elseif (isset($_POST['rejectStatus'])) {
        updateStatus($IDPengajuan, 'Ditolak');
        $_SESSION['message'] = "Cuti berhasil ditolak oleh $jabatan";
    }
    echo '<script>window.location.href="../view/laporanView.php";</script>';
}

function getPengajuanCutiByRole($userID, $jabatan) {
    global $conn;
    if (in_array($jabatan, ['HRD', 'Manager', 'SPV','Admin'])) {
        // Hanya ambil pengajuan yang belum ditolak
        $query = "SELECT pc.*, k.NamaKaryawan 
                  FROM PengajuanCuti pc
                  JOIN Karyawan k ON pc.IDKaryawan = k.IDKaryawan
                  WHERE pc.Status != 'Ditolak' OR (pc.Status = 'Ditolak' AND RejectedBy IS NULL);";
    } else {
        // Query untuk karyawan untuk mendapatkan data pengajuan cuti
        $query = "SELECT * FROM StatusCuti WHERE IDKaryawan = ?";
    }
    
    $stmt = $conn->prepare($query);
    if (in_array($jabatan, ['HRD', 'Manager', 'Admin', 'SPV'])) {
        $stmt->execute();
    } else {
        $stmt->bind_param("i", $userID);
        $stmt->execute();
    }
    
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
    return $data;
}

// Update leave application status 
function updateStatus($IDPengajuan, $newStatus) {
    global $conn;
    // Check if session variables are set
    if (!isset($_SESSION['NamaKaryawan'], $_SESSION['jabatan'])) {
        $_SESSION['message'] = "Gagal: Informasi pengguna tidak ditemukan.";
        return;
    }
    $approver = $_SESSION['NamaKaryawan'] . ' ' . $_SESSION['jabatan'];

    if ($newStatus === 'Disetujui') {
        $query = "UPDATE PengajuanCuti 
                  SET Status = 'Pending', 
                  ApprovedBy = CONCAT(IFNULL(ApprovedBy, ''), IF(ApprovedBy IS NOT NULL AND ApprovedBy != '',',',''), ?)
                  WHERE IDPengajuan = ? AND Status = 'Pending'"; // Hanya update jika status 'Pending'

        // Jika status disetujui oleh HRD, kurangi sisa cuti karyawan
        if ($_SESSION['jabatan'] === 'HRD') {
            $queryReduceLeave = "UPDATE Karyawan k
                                 JOIN PengajuanCuti pc ON k.IDKaryawan = pc.IDKaryawan
                                 SET pc.JumlahSisaCuti = pc.JumlahSisaCuti - 1
                                 WHERE pc.IDPengajuan = ?";
            $stmtReduce = $conn->prepare($queryReduceLeave);
            if ($stmtReduce === false) {
                die("Error: Failed to prepare SQL statement for reducing leave: " . $conn->error);
            }
            $stmtReduce->bind_param("i", $IDPengajuan);
            if (!$stmtReduce->execute()) {
                die("Error: Failed to execute SQL statement for reducing leave: " . $stmtReduce->error);
            }
            $stmtReduce->close();
        }
        
    } elseif ($newStatus === 'Ditolak') {
        $query = "UPDATE PengajuanCuti 
                  SET Status = 'Pending', 
                  RejectedBy = CONCAT(IFNULL(RejectedBy, ''), IF(RejectedBy IS NOT NULL AND RejectedBy != '',',',''), ?)
                  WHERE IDPengajuan = ? AND Status = 'Pending'"; // Hanya update jika status 'Pending'
    }

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Error: Failed to prepare SQL statement: " . $conn->error);
    }

    $stmt->bind_param("ssi", $newStatus, $approver, $IDPengajuan);
    
    if (!$stmt->execute()) {
        die("Error: Failed to execute SQL statement: " . $stmt->error);
    }

    $stmt->close();
}


// Pastikan koneksi tetap terbuka selama proses eksekusi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

