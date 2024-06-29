<?php
session_start();
require_once '../db/koneksi.php'; // Sesuaikan path ini dengan struktur proyek Anda

$errors = array(); // Array untuk menyimpan pesan kesalahan
$success = false; // Inisialisasi variabel $success

// Fungsi untuk menghindari XSS (Cross-site Scripting)
function sanitizeInput($input) {
    global $conn;
    return htmlspecialchars(stripslashes(trim($conn->real_escape_string($input))));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $IDKaryawan = sanitizeInput($_POST['IDKaryawan']);
    $Password = sanitizeInput($_POST['Password']);

    // Validasi input
    if (empty($IDKaryawan)) {
        $errors[] = "ID Karyawan harus diisi.";
    }
    if (empty($Password)) {
        $errors[] = "Password harus diisi.";
    }

    if (empty($errors)) {
        // Hash the password using md5 (Ini hanya untuk contoh, sebaiknya gunakan metode hashing yang lebih aman)
        $hashedPassword = md5($Password);

        // Query to fetch the user
        $sql = "SELECT * FROM karyawan WHERE IDKaryawan = '$IDKaryawan'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify password
            if ($hashedPassword == $row['Password']) {
                $_SESSION['IDKaryawan'] = $row['IDKaryawan'];
                $_SESSION['jabatan'] = $row['Jabatan'];
                $success = true; // Set success to true on successful login
                header("Location: ../view/dashboardView.php"); // Redirect to homepage or dashboard
                exit();
            } else {
                $errors[] = "ID Karyawan & Password salah.";
            }
        } else {
            $errors[] = "ID Karyawan & Password salah.";
        }
    }
}

// Logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_start();
    session_unset();
    session_destroy();
    header("Location: ../view/loginView.php");
    exit();
}

$conn->close();

// Sertakan view setelah pemrosesan form
include '../view/loginView.php';
?>
