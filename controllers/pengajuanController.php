<?php
require_once '../db/koneksi.php'; // Sesuaikan dengan path yang benar

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



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form dan lakukan pengecekan apakah ada
    $inputNama = isset($_POST['inputNama']) ? $_POST['inputNama'] : '';
    $inputJabatan = isset($_POST['inputJabatan']) ? $_POST['inputJabatan'] : '';
    $inputNIK = isset($_POST['inputNIK']) ? $_POST['inputNIK'] : '';
    $inputJenisCuti = isset($_POST['inputJenisCuti']) ? $_POST['inputJenisCuti'] : '';
    $inputTanggalAwal = isset($_POST['inputTanggalAwal']) ? $_POST['inputTanggalAwal'] : '';
    $inputTanggalAkhir = isset($_POST['inputTanggalAkhir']) ? $_POST['inputTanggalAkhir'] : '';
    $inputAlasanCuti = isset($_POST['inputAlasanCuti']) ? $_POST['inputAlasanCuti'] : '';
    $inputAlasanCuti = isset($_POST['inputAlasanCuti']) ? $_POST['inputAlasanCuti'] : '';

    // Protect against SQL injection
    $inputNama = $conn->real_escape_string($inputNama);
    $inputJabatan = $conn->real_escape_string($inputJabatan);
    $inputNIK = $conn->real_escape_string($inputNIK);
    $inputJenisCuti = $conn->real_escape_string($inputJenisCuti);
    $inputTanggalAwal = $conn->real_escape_string($inputTanggalAwal);
    $inputTanggalAkhir = $conn->real_escape_string($inputTanggalAkhir);
    $inputAlasanCuti = $conn->real_escape_string($inputAlasanCuti);

    // Ambil IDKaryawan dari session
    if (isset($_SESSION['IDKaryawan'])) {
        $userID = $_SESSION['IDKaryawan'];
    } else {
        // Handle case when session IDKaryawan is not set
        $_SESSION['error_message'] = "User session not found. Please login.";
        header("Location: ../view/loginView.php");
        exit();
    }

    // Validasi form input
    $errors = [];
    if (empty($inputJenisCuti)) {
        $errors[] = "Jenis cuti harus diisi.";
    }
    if (empty($inputTanggalAwal)) {
        $errors[] = "Tanggal awal harus diisi.";
    }
    if (empty($inputTanggalAkhir)) {
        $errors[] = "Tanggal akhir harus diisi.";
    }
    if (empty($inputAlasanCuti)) {
        $errors[] = "Alasan cuti harus diisi.";
    }

    // Jika ada kesalahan, simpan dalam session dan kembali ke form
    if (!empty($errors)) {
        $_SESSION['error_message'] = implode("<br>", $errors);
        header("Location: ../view/pengajuanView.php");
        exit();
    }

     // Proses validasi file lampiran
     $lampiranFilename = null; // Default value
     if (isset($_FILES['inputLampiran']) && $_FILES['inputLampiran']['error'] == 0) {
        $uploadOk = 1;
        $uploadDir = "../assets/lampiran/"; // Direktori tempat menyimpan file
        $targetFile = $uploadDir . basename($_FILES["inputLampiran"]["name"]);
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if file already exists
        if (file_exists($targetFile)) {
            $errors[] = "File sudah ada.";
            $uploadOk = 0;
        }

        // Check file size (max 5MB)
        if ($_FILES["inputLampiran"]["size"] > 5 * 1024 * 1024) {
            $errors[] = "File terlalu besar.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        $allowedTypes = array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png');
        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = "Format file tidak diizinkan.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $_SESSION['error_message'] = implode("<br>", $errors);
            header("Location: ../view/pengajuanView.php");
            exit();
        } else {
            // Jika semua validasi berhasil, upload file
            if (move_uploaded_file($_FILES["inputLampiran"]["tmp_name"], $targetFile)) {
                // Jika upload berhasil, simpan nama file ke dalam variabel
                $lampiranFilename = basename($_FILES["inputLampiran"]["name"]);
            } else {
                $_SESSION['error_message'] = "Error uploading file.";
                header("Location: ../view/pengajuanView.php");
                exit();
            }
        }
    }

    // Jika ada kesalahan, simpan dalam session dan kembali ke form
    if (!empty($errors)) {
        $_SESSION['error_message'] = implode("<br>", $errors);
        header("Location: ../view/pengajuanView.php");
        exit();
    }

    // Query untuk menyimpan data pengajuan cuti ke database
    $insertSql = "INSERT INTO pengajuancuti (IDKaryawan, NamaKaryawan, Jabatan, NIK, JenisCuti, TanggalAwal, TanggalAkhir, Alasan, Lampiran, Status) 
                  VALUES ('$userID', '$inputNama', '$inputJabatan', '$inputNIK', '$inputJenisCuti', '$inputTanggalAwal', '$inputTanggalAkhir', '$inputAlasanCuti', '$lampiranFilename', 'Pending')";
    
    if ($conn->query($insertSql) === TRUE) {
        $_SESSION['success_message'] = "Pengajuan cuti berhasil disimpan.";
        addNotification("Pengajuan cuti Anda telah disimpan.");
    } else {
        $_SESSION['error_message'] = "Error: " . $insertSql . "<br>" . $conn->error;
        addNotification("Gagal menyimpan pengajuan cuti.");
    }

    $conn->close();
    echo '<script>window.location.href="../view/StatusView.php";</script>';
} else {
    // Redirect to appropriate error page or handle accordingly
    header("Location: ../view/pengajuanView.php");
    exit("Invalid request method.");
}
?>