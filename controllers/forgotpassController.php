<?php
session_start();
require_once '../db/koneksi.php'; // Sesuaikan dengan path yang benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $IDKaryawan = $_POST['ForgotPass'];
    $newPassword = $_POST['newPassword'];

    // Validasi apakah ID Karyawan ada di database
    $query = "SELECT * FROM karyawan WHERE IDKaryawan = '$IDKaryawan'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // ID Karyawan valid, hash password baru
        $hashedPassword = md5($newPassword);

        // Update password di database
        $updatePasswordQuery = "UPDATE karyawan SET Password = '$hashedPassword' WHERE IDKaryawan = '$IDKaryawan'";
        if ($conn->query($updatePasswordQuery) === TRUE) {
            header("Location: loginView.php");
            exit();
            echo "Password berhasil direset. Silakan login dengan password baru Anda.";
        } else {
            echo "Error: " . $updatePasswordQuery . "<br>" . $conn->error;
        }
    } else {
        echo "ID Karyawan tidak ditemukan.";
    }
}

$conn->close();
?>
