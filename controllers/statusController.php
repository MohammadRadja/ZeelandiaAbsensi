<?php
// Pastikan session_start hanya dipanggil jika belum ada sesi yang aktif
require_once '../db/koneksi.php'; // Sesuaikan dengan path yang benar

// Fungsi untuk menambah notifikasi ke sesi
function addNotification($message) {
    if (!isset($_SESSION['notifications'])) {
        $_SESSION['notifications'] = [];
    }
    $_SESSION['notifications'][] = ['message' => $message];
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
// Proses POST untuk menyetujui status cuti
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approveStatus'])) {
    $IDPengajuan = $_POST['IDPengajuan'];
    updateStatus($IDPengajuan, 'Disetujui');
    $_SESSION['message'] = "Cuti berhasil disetujui.";
    addNotification("Pengajuan cuti Anda telah disetujui.");
    header("Location: ../view/StatusView.php");
    exit();
}

// Proses POST untuk menolak status cuti
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rejectStatus'])) {
    $IDPengajuan = $_POST['IDPengajuan'];
    updateStatus($IDPengajuan, 'Ditolak');
    $_SESSION['message'] = "Cuti berhasil ditolak.";
    addNotification("Pengajuan cuti Anda telah ditolak.");
    header("Location: ../view/StatusView.php");
    exit();
}

function getPengajuanCutiByRole($userID, $jabatan) {
    global $conn;

    if (in_array($jabatan, ['hrd', 'admin', 'manager'])) {
        // Query for HRD or Manager to get all pengajuancuti data
        $query = "SELECT pc.*, k.NamaKaryawan 
                FROM pengajuancuti pc
                JOIN karyawan k ON pc.IDKaryawan = k.IDKaryawan
                WHERE pc.Status = 'Pending';";
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
        $query = "SELECT TanggalAwal, JenisCuti, Status 
                  FROM pengajuancuti 
                  WHERE IDKaryawan = ?";
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

function updateStatus($IDPengajuan, $newStatus) {
    global $conn;
    $query = "UPDATE pengajuancuti SET Status = ? WHERE IDPengajuan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $newStatus, $IDPengajuan);
    $stmt->execute();
    $stmt->close();
}

// Pastikan koneksi tetap terbuka selama proses eksekusi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

