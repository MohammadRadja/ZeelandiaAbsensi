<?php
session_start();
require_once '../db/koneksi.php'; // Pastikan path ini benar

$errors = array(); // Array untuk menyimpan pesan kesalahan
$success = false; // Inisialisasi variabel $success

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $IDKaryawan = trim($_POST['IDKaryawan']);
    $Password = trim($_POST['Password']);

    // Validasi input
    if (empty($IDKaryawan)) {
        $errors[] = "ID Karyawan harus diisi.";
    }
    if (empty($Password)) {
        $errors[] = "Password harus diisi.";
    }

    if (empty($errors)) {
        // Protect against SQL injection
        $IDKaryawan = $conn->real_escape_string($IDKaryawan);
        $Password = $conn->real_escape_string($Password);

        // Hash the password using md5
        $hashedPassword = md5($Password);

        // Query to fetch the user
        $sql = "SELECT * FROM karyawan WHERE IDKaryawan = '$IDKaryawan'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify password
            if ($hashedPassword == $row['Password']) {
                $_SESSION['IDKaryawan'] = $row['IDKaryawan'];
                $_SESSION['Username'] = $row['Username'];
                $success = true; // Set success to true on successful login
                header("Location: ../view/dashboardview.php"); // Redirect to homepage or dashboard
                exit();
            } else {
                $errors[] = "ID Karyawan & Password salah.";
            }
        } else {
            $errors[] = "ID Karyawan & Password salah";
        }
    }
}

$conn->close();

// Sertakan view setelah pemrosesan form
include '../view/loginView.php';
?>
