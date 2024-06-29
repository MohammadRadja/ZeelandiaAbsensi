<?php
// session_start(); // Pastikan session dimulai jika akan digunakan

require_once '../db/koneksi.php'; // Sesuaikan dengan path yang benar

// Ambil IDKaryawan dari session
if (isset($_SESSION['IDKaryawan'])) {
    $userID = $_SESSION['IDKaryawan'];

    // Query untuk mengambil data pengajuan cuti dari database
    $query = "SELECT TanggalAwal, JenisCuti, Status FROM pengajuancuti WHERE IDKaryawan = '$userID'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["TanggalAwal"] . "</td>";
            echo "<td>" . $row["JenisCuti"] . "</td>";
            echo "<td>" . $row["Status"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>Belum ada data pengajuan cuti.</td></tr>";
    }
} else {
    // Handle case when session IDKaryawan is not set
    // Redirect to login page or handle error
    exit("User session not found. Please login.");
}


/*
 Function Update Status Cuti (Admin,Manager & HRD) 
*/
function updateStatus($IDPengajuan, $newStatus) {
    global $conn;
    $query = "UPDATE pengajuancuti SET Status = '$newStatus' WHERE IDPengajuan = '$IDPengajuan'";
    return $conn->query($query);
}

// POST Status Process
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateStatus'])) {
    $IDPengajuan = $_POST['IDPengajuan'];
    $newStatus = $_POST['newStatus'];
    if (updateStatus($cutiID, $newStatus)) {
        echo "Status cuti berhasil diubah.";
    } else {
        echo "Gagal mengubah status cuti.";
    }
}


$conn->close();
?>
