<?php
require_once '../db/koneksi.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userID = $_SESSION['IDKaryawan'];

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

// Fungsi untuk menambahkan proteksi XSS dan SQL Injection
function sanitizeInput($conn, $input) {
    return htmlspecialchars(stripslashes(trim($conn->real_escape_string($input))));
}

// Fungsi untuk mengambil data karyawan dari database berdasarkan IDKaryawan
function getEmployeeData($conn, $userID) {
    $sql = "SELECT * FROM Karyawan WHERE IDKaryawan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

// Ambil IDKaryawan dari session
if (isset($_SESSION['IDKaryawan'])) {
    $userID = $_SESSION['IDKaryawan'];

    // Ambil data karyawan dari database berdasarkan IDKaryawan menggunakan fungsi getEmployeeData
    $employeeData = getEmployeeData($conn, $userID);

    if (!$employeeData) {
        $_SESSION['error_message'] = "Data karyawan tidak ditemukan.";
        echo '<script>window.location.href="../view/loginView.php";</script>';
        exit();
    }

    // Assign data karyawan ke variabel untuk ditampilkan di form
    $currentFoto = '../assets/img/profiles/profile.png'; // Default profile picture
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
    echo '<script>window.location.href="../view/loginView.php";</script>';
    exit();
}

// Handle POST request untuk update profil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $NamaKaryawan = sanitizeInput($conn, $_POST['inputNama']);
    $NIK = sanitizeInput($conn, $_POST['inputNIK']);
    $Alamat = sanitizeInput($conn, $_POST['inputAlamat']);
    $Email = sanitizeInput($conn, $_POST['inputEmail']);
    $NoTelp = sanitizeInput($conn, $_POST['inputNoTelp']);
    $TanggalBergabung = sanitizeInput($conn, $_POST['inputTanggalBergabung']);
    $MasaKerja = sanitizeInput($conn, $_POST['inputMasaKerja']);
    $PasswordBaru = sanitizeInput($conn, $_POST['inputPassword']);

    // Ambil IDKaryawan dari session
    if (isset($_SESSION['IDKaryawan'])) {
        $userID = $_SESSION['IDKaryawan'];
    } else {
        // Handle case when session IDKaryawan is not set
        // Redirect to login page or handle error
        $_SESSION['error_message'] = "User session not found. Please login.";
        echo '<script>window.location.href="../view/loginView.php";</script>';
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

    // Validasi file upload
    // if ($FotoProfil['error'] == UPLOAD_ERR_OK) {
    //     $targetDir = "../assets/img/profiles/";
    //     $targetFile = $targetDir . basename($FotoProfil["name"]);
    //     $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    //     // Pastikan path file tidak kosong sebelum memanggil getimagesize
    //     if (!empty($FotoProfil["tmp_name"])) {
    //         $check = getimagesize($FotoProfil["tmp_name"]);
    //         if ($check === false) {
    //             $errors[] = "File bukan gambar.";
    //         }
    //     } else {
    //         $errors[] = "File tidak ditemukan.";
    //     }

    //     // Periksa ukuran file
    //     if ($FotoProfil["size"] > 500000) {
    //         $errors[] = "Ukuran file terlalu besar. Maksimal 500KB.";
    //     }

    //     // Periksa format file
    //     if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    //         $errors[] = "Hanya format JPG, JPEG, PNG & GIF yang diperbolehkan.";
    //     }

    //     // Jika tidak ada error, pindahkan file ke folder target
    //     if (empty($errors)) {
    //         if (!move_uploaded_file($FotoProfil["tmp_name"], $targetFile)) {
    //             $errors[] = "Terjadi kesalahan saat mengunggah file.";
    //         } else {
    //             $currentFoto = $targetFile;
    //         }
    //     }
    // }

    // Anda bisa tambahkan validasi lain sesuai kebutuhan seperti panjang password, dll.
    if (!empty($errors)) {
        $_SESSION['error_message'] = implode("<br>", $errors);
        header("Location: ../view/profilView.php");
        exit();
    }

    // Query untuk update profil menggunakan prepared statements
    $updateSql = "UPDATE Karyawan SET 
        NamaKaryawan = ?, 
        NIK = ?, 
        Alamat = ?, 
        Email = ?, 
        NoTelp = ?, 
        TanggalBergabung = ?, 
        MasaKerja = ? 
        WHERE IDKaryawan = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sssssssi", $NamaKaryawan, $NIK, $Alamat, $Email, $NoTelp, $TanggalBergabung, $MasaKerja, $userID);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Profil berhasil diperbarui.";
        addNotification("Profil berhasil diperbarui.");
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error;
        addNotification("Gagal memperbarui profil.");
    }

    // Jika ada perubahan password
    if (!empty($PasswordBaru)) {
        $hashedPassword = password_hash($PasswordBaru, PASSWORD_DEFAULT);
        $updatePasswordSql = "UPDATE Karyawan SET Password = ? WHERE IDKaryawan = ?";
        $stmt = $conn->prepare($updatePasswordSql);
        $stmt->bind_param("si", $hashedPassword, $userID);

        if ($stmt->execute()) {
            $_SESSION['success_message'] .= " Password berhasil diubah.";
            addNotification("Password berhasil diubah.");
        } else {
            $_SESSION['error_message'] .= " Error updating password: " . $stmt->error;
            addNotification("Password gagal diubah.");
        }
    }

    header("Location: ../view/profilView.php");
    exit();
}

$conn->close();
// Redirect ke profilView.php setelah selesai operasi

?>
