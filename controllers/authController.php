<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../db/koneksi.php'; // Sesuaikan path ini dengan struktur proyek Anda

$errors = array(); // Array untuk menyimpan pesan kesalahan
$success = false; // Inisialisasi variabel $success

/*
Function Secure untuk menghindari XSS (Cross-site Scripting)
*/
function sanitizeInput($input) {
    global $conn;
    return htmlspecialchars(stripslashes(trim($conn->real_escape_string($input))));
}

/*
  Function Login
 */
function processLogin() {
    global $conn, $errors, $success;

    // Ambil data dari form
    $IDKaryawan = sanitizeInput($_POST['IDKaryawan']);
    $Password = sanitizeInput($_POST['Password']);

    // Validasi input
    if (empty($IDKaryawan)) {
        $errors['IDKaryawan'] = "ID Karyawan harus diisi.";
    }
    if (empty($Password)) {
        $errors['Password'] = "Password harus diisi.";
    }

    if (empty($errors)) {
        // Hash the password using md5 (Ini hanya untuk contoh, sebaiknya gunakan metode hashing yang lebih aman)
        $hashedPassword = md5($Password);

        // Query to fetch the user
        $sql = "SELECT * FROM Karyawan WHERE IDKaryawan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $IDKaryawan);
        $stmt->execute();
        $result = $stmt->get_result();

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
                $errors['general'] = "ID Karyawan & Password salah.";
            }
        } else {
            $errors['general'] = "ID Karyawan & Password salah.";
        }
    }

    // Simpan pesan kesalahan ke session
    $_SESSION['login_errors'] = $errors;
    header("Location: ../view/loginView.php");
    exit();
}

/*
 Function Validasi Input Registrasi
 */
function validateRegistrationInput() {
    global $errors, $conn;

    // Ambil nilai dari $_POST
    $IDKaryawan = isset($_POST['IDKaryawan']) ? intval($_POST['IDKaryawan']) : 0; // intval untuk memastikan nilai adalah integer
    $Fullname = sanitizeInput($_POST['Fullname']);
    $Username = sanitizeInput($_POST['Username']);
    $Password = sanitizeInput($_POST['Password']);

    // Validasi input
    if (empty($IDKaryawan)) {
        $errors['IDKaryawan'] = "ID Karyawan harus diisi.";
    }
    if (empty($Fullname)) {
        $errors['Fullname'] = "Nama lengkap harus diisi.";
    }
    if (empty($Username)) {
        $errors['Username'] = "Username harus diisi.";
    }
    if (empty($Password)) {
        $errors['Password'] = "Password harus diisi.";
    }

    // Cek apakah username sudah digunakan
    $checkUsernameQuery = "SELECT * FROM Karyawan WHERE Username = ?";
    $stmt = $conn->prepare($checkUsernameQuery);
    $stmt->bind_param("s", $Username);
    $stmt->execute();
    $checkResult = $stmt->get_result();

    if ($checkResult && $checkResult->num_rows > 0) {
        $errors['Username'] = "Username sudah digunakan. Silakan pilih username lain.";
    }

    return empty($errors); // Return true jika tidak ada error
}

/*
 Function Registrasi Process
 */
function processRegistration() {
    global $conn, $errors;

    // Validasi input sebelum memproses
    if (!validateRegistrationInput()) {
        $_SESSION['register_errors'] = $errors; // Simpan pesan kesalahan ke session
        header("Location: ../view/registerView.php"); // Redirect kembali ke halaman registrasi
        exit();
    }

    // Ambil nilai dari $_POST setelah divalidasi
    $IDKaryawan = intval($_POST['IDKaryawan']);
    $Fullname = sanitizeInput($_POST['Fullname']);
    $Username = sanitizeInput($_POST['Username']);
    $Password = sanitizeInput($_POST['Password']);

    // Hash the password using password_hash
    $hashedPassword = md5($Password);

    // Insert the new user into the database
    $sql = "INSERT INTO Karyawan (IDKaryawan, NamaKaryawan, Jabatan, Username, Password) VALUES (?, ?, 'karyawan', ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $IDKaryawan, $Fullname, $Username, $hashedPassword);

    if ($stmt->execute()) {
        header("Location: ../view/loginView.php");
        exit();
    } else {
        $errors['general'] = "Error: " . $sql . "<br>" . $conn->error;
        $_SESSION['register_errors'] = $errors; // Simpan pesan kesalahan ke session
        header("Location: ../view/registerView.php"); // Redirect kembali ke halaman registrasi
        exit();
    }
}

/*
 Function Logout
*/
function processLogout() {
    session_unset();
    session_destroy();
    header("Location: ../view/loginView.php");
    exit();
}
//POST Process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        processLogin();
    } elseif (isset($_POST['register'])) {
        processRegistration();
    }
}
//Logout Process
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    processLogout();
}

$conn->close();
?>
