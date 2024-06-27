<?php
session_start();
require_once '../db/koneksi.php'; // Pastikan path ini benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $NamaKaryawan = $_POST['inputNama'];
    $Jabatan = $_POST['inputJabatan'];
    $NIK = $_POST['inputNIK'];
    $Alamat = $_POST['inputAlamat'];
    $Email = $_POST['inputEmail'];
    $NoTelp = $_POST['inputNoTelp'];
    $TanggalBergabung = $_POST['inputTanggalBergabung'];
    $MasaKerja = $_POST['inputMasaKerja'];
    $PasswordBaru = $_POST['inputPassword'];

    // Proteksi dari SQL Injection
    $NamaKaryawan = $conn->real_escape_string($NamaKaryawan);
    $Jabatan = $conn->real_escape_string($Jabatan);
    $NIK = $conn->real_escape_string($NIK);
    $Alamat = $conn->real_escape_string($Alamat);
    $Email = $conn->real_escape_string($Email);
    $NoTelp = $conn->real_escape_string($NoTelp);
    $TanggalBergabung = $conn->real_escape_string($TanggalBergabung);
    $MasaKerja = $conn->real_escape_string($MasaKerja);
    $PasswordBaru = $conn->real_escape_string($PasswordBaru);

     // Ambil IDKaryawan dari session
     if (isset($_SESSION['IDKaryawan'])) {
        $userID = $_SESSION['IDKaryawan'];
    } else {
        // Handle case when session IDKaryawan is not set
        // Redirect to login page or handle error
        exit("User session not found. Please login.");
    }

    // Query untuk update profil
    $sql = "UPDATE karyawan SET 
            NamaKaryawan = '$NamaKaryawan',
            Jabatan = '$Jabatan',
            NIK = '$NIK',
            Alamat = '$Alamat',
            Email = '$Email',
            NoTelp = '$NoTelp',
            TanggalBergabung = '$TanggalBergabung',
            MasaKerja = '$MasaKerja'
            WHERE IDKaryawan = '$userID'"; // Sesuaikan dengan ID Karyawan yang sedang login

    if ($conn->query($sql) === TRUE) {
        echo "Profil berhasil diperbarui.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Jika ada perubahan password
    if (!empty($password_baru)) {
        $hashedPassword = password_hash($password_baru, PASSWORD_DEFAULT);
        $updatePasswordSql = "UPDATE karyawan SET Password = '$hashedPassword' WHERE IDKaryawan = '$userID'";

        if ($conn->query($updatePasswordSql) === TRUE) {
            echo "Password berhasil diubah.";
        } else {
            echo "Error updating password: " . $conn->error;
        }
    }

    $conn->close();
}
?>
