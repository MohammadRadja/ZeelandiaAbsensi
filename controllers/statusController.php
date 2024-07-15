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
        $_SESSION['message'] = "Pengajuan cuti berhasil disetujui $jabatan";
    } elseif (isset($_POST['rejectStatus'])) {
        updateStatus($IDPengajuan, 'Ditolak');
        $_SESSION['message'] = "Cuti berhasil ditolak $jabatan";
    }
    echo '<script>window.location.href="../view/laporanView.php";</script>';
}

function getPengajuanCutiByRole($userID, $jabatan) {
    global $conn;
    if (in_array($jabatan, ['HRD', 'Manager', 'SPV','Admin'])) {
        $query = "SELECT pc.*, k.NamaKaryawan 
                FROM PengajuanCuti pc
                JOIN Karyawan k ON pc.IDKaryawan = k.IDKaryawan;";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    } else {
        // Query for other roles to get pengajuancuti data for the logged-in user
        $query = "SELECT * FROM StatusCuti WHERE IDKaryawan = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }
}

// Update leave application status 
function updateStatus($IDPengajuan, $newStatus) {
    global $conn;

    // Check if session variables are set
    if (!isset($_SESSION['NamaKaryawan'], $_SESSION['jabatan'])) {
        $_SESSION['message'] = "Gagal: Informasi pengguna tidak ditemukan.";
    }
    $approver = $_SESSION['NamaKaryawan'] . ' ' . $_SESSION['jabatan'];

    if ($newStatus === 'Disetujui') {
        $query = "UPDATE PengajuanCuti 
                  SET Status = ?, 
                  ApprovedBy = CONCAT(IFNULL(ApprovedBy, ''), IF(ApprovedBy IS NOT NULL AND ApprovedBy != '', ', ', ''), ?)
                  WHERE IDPengajuan = ?";
        
    } elseif ($newStatus === 'Ditolak') {
        $query = "UPDATE PengajuanCuti 
                  SET Status = ?, 
                  RejectedBy = CONCAT(IFNULL(RejectedBy, ''), IF(RejectedBy IS NOT NULL AND RejectedBy != '', ', ', ''), ?)
                  WHERE IDPengajuan = ?";
    }

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Error: Failed to prepare SQL statement: " . $conn->error);
    }

    $stmt->bind_param("ssi", $newStatus, $approver, $IDPengajuan);
    
    if (!$stmt->execute()) {
        die("Error: Failed to execute SQL statement: " . $stmt->error);
    }

    //HRD Approved
    if ($newStatus === 'Disetujui' && strpos($_SESSION['jabatan'], 'HRD') !== false) {
        $query = "SELECT JumlahSisaCuti, DATEDIFF           (TanggalAkhir, TanggalAwal) + 1 AS JumlahHari
                  FROM PengajuanCuti 
                  WHERE IDPengajuan = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Error: Failed to prepare SQL statement: " . $conn->error);
        }

        $stmt->bind_param("i", $IDPengajuan);
        if (!$stmt->execute()) {
            die("Error: Failed to execute SQL statement: " . $stmt->error);
        }

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $sisaCuti = $row['JumlahSisaCuti'];
            $hariCuti = $row['JumlahHari'];

            // Calculate new remaining leave days
            $sisaCutiBaru = $sisaCuti - $hariCuti;
            if ($sisaCutiBaru < 0) {
                $sisaCutiBaru = 0;
            }

            // Update the remaining leave days in the database
            $query = "UPDATE PengajuanCuti 
                      SET JumlahSisaCuti = ? 
                      WHERE IDPengajuan = ?";
            $stmt = $conn->prepare($query);
            if ($stmt === false) {
                die("Error: Failed to prepare SQL statement: " . $conn->error);
            }

            $stmt->bind_param("ii", $sisaCutiBaru, $IDPengajuan);
            if (!$stmt->execute()) {
                die("Error: Failed to execute SQL statement: " . $stmt->error);
            }
        }
    }

    $stmt->close();
}


