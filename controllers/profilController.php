<?php
require_once '../db/koneksi.php'; // Pastikan path ini benar
session_start();
$userID = $_SESSION['IDKaryawan'];

$currentNama = '';
$currentJabatan = '';
$currentNIK = '';
$currentAlamat = '';
$currentEmail = '';
$currentNoTelp = '';
$currentTanggalBergabung = '';
$currentMasaKerja = '';

var_dump($currentNama); // atau
echo $currentNama;
// Fungsi untuk mengambil data karyawan dari database berdasarkan IDKaryawan
function getEmployeeData($conn, $userID) {
    $sql = "SELECT * FROM karyawan WHERE IDKaryawan = '$userID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}


// Ambil IDKaryawan dari session
if (isset($_SESSION['IDKaryawan'])) {
    $userID = $_SESSION['IDKaryawan'];

    // Ambil data karyawan dari database berdasarkan IDKaryawan menggunakan fungsi getEmployeeData
    $employeeData = getEmployeeData($conn, $userID);

    if (!$employeeData) {
        $_SESSION['error_message'] = "Data karyawan tidak ditemukan.";
        header("Location: ../view/login.php");
        exit();
    }

    // Assign data karyawan ke variabel untuk ditampilkan di form
    $currentNama = isset($employeeData['NamaKaryawan']) ? htmlspecialchars($employeeData['NamaKaryawan']) : '';
    $currentJabatan = isset($employeeData['Jabatan']) ? htmlspecialchars($employeeData['Jabatan']) : '';
    $currentNIK = isset($employeeData['NIK']) ? htmlspecialchars($employeeData['NIK']) : '';
    $currentAlamat = isset($employeeData['Alamat']) ? htmlspecialchars($employeeData['Alamat']) : '';
    $currentEmail = isset($employeeData['Email']) ? htmlspecialchars($employeeData['Email']) : '';
    $currentNoTelp = isset($employeeData['NoTelp']) ? htmlspecialchars($employeeData['NoTelp']) : '';
    $currentTanggalBergabung = isset($employeeData['TanggalBergabung']) ? htmlspecialchars($employeeData['TanggalBergabung']) : '';
    $currentMasaKerja = isset($employeeData['MasaKerja']) ? htmlspecialchars($employeeData['MasaKerja']) : '';    
} else {
    // Handle case when session IDKaryawan is not set
    $_SESSION['error_message'] = "User session not found. Please login.";
    header("Location: ../view/loginView.php");
    exit();
}



// Handle POST request untuk update profil
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
        $_SESSION['error_message'] = "User session not found. Please login.";
        header("Location: ../view/login.php");
        exit();
    }

    // Ambil data karyawan dari database berdasarkan IDKaryawan
    $employeeData = getEmployeeData($conn, $userID);

    if (!$employeeData) {
        $_SESSION['error_message'] = "Data karyawan tidak ditemukan.";
        header("Location: ../view/profilView.php");
        exit();
    }

    // Validasi input (contoh validasi, sesuaikan dengan kebutuhan Anda)
    $errors = [];

    if (empty($NamaKaryawan)) {
        $errors[] = "Nama Karyawan harus diisi.";
    }

    if (empty($Email)) {
        $errors[] = "Email harus diisi.";
    } elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }

    // Anda bisa tambahkan validasi lain sesuai kebutuhan seperti panjang password, dll.

    if (!empty($errors)) {
        $_SESSION['error_message'] = implode("<br>", $errors);
        header("Location: ../view/profilView.php");
        exit();
    }

    // Query untuk update profil
    $updateSql = "UPDATE karyawan SET 
            NamaKaryawan = '$NamaKaryawan',
            Jabatan = '$Jabatan',
            NIK = '$NIK',
            Alamat = '$Alamat',
            Email = '$Email',
            NoTelp = '$NoTelp',
            TanggalBergabung = '$TanggalBergabung',
            MasaKerja = '$MasaKerja'
            WHERE IDKaryawan = '$userID'"; // Sesuaikan dengan ID Karyawan yang sedang login

    if ($conn->query($updateSql) === TRUE) {
        $_SESSION['success_message'] = "Profil berhasil diperbarui.";
    } else {
        $_SESSION['error_message'] = "Error: " . $updateSql . "<br>" . $conn->error;
    }

    // Jika ada perubahan password
    if (!empty($PasswordBaru)) {
        $hashedPassword = password_hash($PasswordBaru, PASSWORD_DEFAULT);
        $updatePasswordSql = "UPDATE karyawan SET Password = '$hashedPassword' WHERE IDKaryawan = '$userID'";

        if ($conn->query($updatePasswordSql) === TRUE) {
            $_SESSION['success_message'] .= " Password berhasil diubah.";
        } else {
            $_SESSION['error_message'] .= " Error updating password: " . $conn->error;
        }
    }

    $conn->close();
}

// Redirect ke profilView.php setelah selesai operasi

?>
