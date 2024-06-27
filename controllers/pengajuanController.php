<?php
session_start(); // Pastikan session dimulai jika akan digunakan

require_once '../db/koneksi.php'; // Sesuaikan dengan path yang benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $inputNama = $_POST['inputNama'];
    $inputJabatan = $_POST['inputJabatan'];
    $inputNIK = $_POST['inputNIK'];
    $inputJenisCuti = $_POST['inputJenisCuti'];
    $inputTanggalAwal = $_POST['inputTanggalAwal'];
    $inputTanggalAkhir = $_POST['inputTanggalAkhir'];
    $inputAlasanCuti = $_POST['inputAlasanCuti'];

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
        // Redirect to login page or handle error
        exit("User session not found. Please login.");
    }

     // Proses validasi file lampiran
    if (isset($_FILES['inputLampiran'])) {
        $uploadOk = 1;
        $uploadDir = "../assets/lampiran/"; // Direktori tempat menyimpan file
        $targetFile = $uploadDir . basename($_FILES["inputLampiran"]["name"]);
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if file already exists
        if (file_exists($targetFile)) {
            echo "File sudah ada.";
            $uploadOk = 0;
        }

        // Check file size (max 5MB)
        if ($_FILES["inputLampiran"]["size"] > 5 * 1024 * 1024) {
            echo "File terlalu besar.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        $allowedTypes = array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png');
        if (!in_array($fileType, $allowedTypes)) {
            echo "Format file tidak diizinkan.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Pengajuan cuti gagal.";
        } else {
            // Jika semua validasi berhasil, upload file
            if (move_uploaded_file($_FILES["inputLampiran"]["tmp_name"], $targetFile)) {
                // Jika upload berhasil, simpan data ke database
                $lampiranFilename = basename($_FILES["inputLampiran"]["name"]);

                // Query untuk menyimpan data pengajuan cuti ke database
                $insertSql = "INSERT INTO pengajuancuti (IDKaryawan, NamaKaryawan, Jabatan, NIK, JenisCuti, TanggalAwal, TanggalAkhir, Alasan, Lampiran, Status) 
                VALUES ('$userID', '$inputNama', '$inputJabatan', '$inputNIK', '$inputJenisCuti', '$inputTanggalAwal', '$inputTanggalAkhir', '$inputAlasanCuti', '$lampiranFilename', 'Pending')";
                if ($conn->query($insertSql) === TRUE) {
                    echo "Pengajuan cuti berhasil disimpan.";
                } else {
                    echo "Error: " . $insertSql . "<br>" . $conn->error;
                }
            } else {
                echo "Error uploading file.";
            }
        }
    } else {
        echo "File lampiran tidak ditemukan.";
    }

    $conn->close();
} else {
    // Redirect to appropriate error page or handle accordingly
    exit("Invalid request method.");
}
?>