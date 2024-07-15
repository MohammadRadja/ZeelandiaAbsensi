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

// Proses POST untuk acc/block status cuti
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $IDPengajuan = $_POST['IDPengajuan'];
    if (isset($_POST['approveStatus'])) {
        updateStatus($IDPengajuan, 'Disetujui');
        $_SESSION['message'] = "Pengajuan cuti berhasil disetujui oleh ". $_SESSION['jabatan'];
    } elseif (isset($_POST['rejectStatus'])) {
        updateStatus($IDPengajuan, 'Ditolak');
        $_SESSION['message'] = "Cuti berhasil ditolak oleh ". $_SESSION['jabatan'];
    }
    echo '<script>window.location.href="../view/laporanView.php";</script>';
}


// Update leave application status 
function updateStatus($IDPengajuan, $newStatus) {
    global $conn;

    if (!isset($_SESSION['NamaKaryawan'], $_SESSION['jabatan'])) {
        $_SESSION['message'] = "Gagal: Informasi pengguna tidak ditemukan.";
        return;
    }
    
    $approver = $_SESSION['NamaKaryawan'] . ' ' . $_SESSION['jabatan'];

    if ($newStatus === 'Disetujui') {
        $query = "UPDATE PengajuanCuti 
          SET Status = ?, 
              ApprovedBy = IF(ApprovedBy IS NULL OR ApprovedBy = '', ?, CONCAT(ApprovedBy, ',', ?))
          WHERE IDPengajuan = ? AND Status = 'Pending'";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            $_SESSION['message'] = "Error: Gagal menyiapkan pernyataan SQL: " . $conn->error;
            return;
        }

        $stmt->bind_param("sssi", $newStatus, $approver, $approver, $IDPengajuan);
        
        if (!$stmt->execute()) {
            $_SESSION['message'] = "Error: Gagal mengeksekusi pernyataan SQL: " . $stmt->error;
            return;
        } else {
            $_SESSION['message'] = "Pengajuan cuti berhasil disetujui.";
        }
        var_dump($newStatus, $approver, $IDPengajuan);
        $stmt->close();

        // Mengurangi sisa cuti jika jabatan adalah HRD
        if ($_SESSION['jabatan'] === 'HRD') {
            $queryReduceLeave = "UPDATE PengajuanCuti 
                                 SET JumlahSisaCuti = JumlahSisaCuti - 1
                                 WHERE IDPengajuan = ?";
            $stmtReduce = $conn->prepare($queryReduceLeave);
            if (!$stmtReduce) {
                $_SESSION['message'] = "Error: Gagal menyiapkan pernyataan SQL untuk mengurangi cuti: " . $conn->error;
                return;
            }
            $stmtReduce->bind_param("i", $IDPengajuan);
            if (!$stmtReduce->execute()) {
                $_SESSION['message'] = "Error: Gagal mengeksekusi pernyataan SQL untuk mengurangi cuti: " . $stmtReduce->error;
                return;
            } else {
                $_SESSION['message'] .= " Sisa cuti berhasil dikurangi.";
            }
            $stmtReduce->close();
        }
        
    } elseif ($newStatus === 'Ditolak') {
        $query = "UPDATE PengajuanCuti 
              SET Status = ?, 
                  RejectedBy = IF(RejectedBy IS NULL OR RejectedBy = '', ?, CONCAT(RejectedBy, ',', ?))
              WHERE IDPengajuan = ? AND Status = 'Pending'";
        
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            $_SESSION['message'] = "Error: Gagal menyiapkan pernyataan SQL untuk penolakan: " . $conn->error;
            return;
        }
        
        $stmt->bind_param("sssi", $newStatus, $approver, $approver, $IDPengajuan);
            
        if (!$stmt->execute()) {
            $_SESSION['message'] = "Error: Gagal mengeksekusi pernyataan SQL untuk penolakan: " . $stmt->error;
            return;
        }
        $stmt->close();
        $_SESSION['message'] .= " Pengajuan cuti berhasil ditolak.";
    }

    $_SESSION['message'] .= " Fungsi updateStatus selesai untuk IDPengajuan=$IDPengajuan, newStatus=$newStatus";
}


// Pastikan koneksi tetap terbuka selama proses eksekusi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

