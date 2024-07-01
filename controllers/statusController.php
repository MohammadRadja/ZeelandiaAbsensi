<?php
// session_start(); // Pastikan session dimulai jika akan digunakan

require_once '../db/koneksi.php'; // Sesuaikan dengan path yang benar

// Ambil IDKaryawan dari session
if (isset($_SESSION['IDKaryawan'])) {
    $userID = $_SESSION['IDKaryawan'];
    $userRole = $_SESSION['jabatan'];

    // Check role to determine query
    if ($userRole === 'hrd' || $userRole === 'manager') {
        // Query for HRD or Manager to get all pengajuancuti data
        $query = "SELECT pc.*, k.NamaKaryawan 
                  FROM pengajuancuti pc
                  JOIN karyawan k ON pc.IDKaryawan = k.IDKaryawan";
    } else {
        // Query for other roles to get pengajuancuti data for the logged-in user
        $query = "SELECT TanggalAwal, JenisCuti, Status 
                  FROM pengajuancuti 
                  WHERE IDKaryawan = '$userID'";
    }

    // Execute query
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            if ($userRole === 'hrd' || $userRole === 'manager') {
                echo "<td>" . $row["NamaKaryawan"] . "</td>"; // Display NamaKaryawan for HRD and Manager
            }
            echo "<td>" . $row["TanggalAwal"] . "</td>";
            echo "<td>" . $row["JenisCuti"] . "</td>";
            echo "<td>" . $row["Status"] . "</td>";
            // Add form to update status for HRD and Manager
            if ($userRole === 'hrd' || $userRole === 'manager') {

                echo "<form method='post'>";
                echo "<input type='hidden' name='IDPengajuan' value='" . $row["IDPengajuan"] . "'>";
                echo "<td>";
                echo "<select name='newStatus'>";
                echo "<option value='Diajukan'>Diajukan</option>";
                echo "<option value='Disetujui'>Disetujui</option>";
                echo "<option value='Ditolak'>Ditolak</option>";
                echo "</select>";
                echo "</td>";
                echo "<td>";
                echo "<button type='submit' name='updateStatus'>Update Status</button>";
                echo "</td>";
                echo "</form>";
            }
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Belum ada data pengajuan cuti.</td></tr>";
    }
} else {
    // Handle case when session IDKaryawan is not set
    exit("User session not found. Please login.");
}

/*
 Function Update Status Cuti (Admin,Manager & HRD) 
*/
function updateStatus($IDPengajuan, $newStatus)
{
    global $conn;
    $query = "UPDATE pengajuancuti SET Status = '$newStatus' WHERE IDPengajuan = '$IDPengajuan'";
    return $conn->query($query);
}

// POST Status Process
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateStatus'])) {
    $IDPengajuan = $_POST['IDPengajuan'];
    $newStatus = $_POST['newStatus'];
    if (updateStatus($IDPengajuan, $newStatus)) {
        echo "Status cuti berhasil diubah.";
    } else {
        echo "Gagal mengubah status cuti.";
    }
}

$conn->close();
