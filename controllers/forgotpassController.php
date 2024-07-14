<?php
require_once '../db/koneksi.php'; // Sesuaikan dengan path yang benar

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $KataKunci = $_POST['ForgotPass'];
    $newPassword = $_POST['newPassword'];

    // Prepare the SELECT statement
    $stmt = $conn->prepare("SELECT * FROM karyawan WHERE IDKaryawan = ? OR NIK = ? OR NoTelp = ?");
    
    // Bind parameters
    $stmt->bind_param("sss", $KataKunci, $KataKunci, $KataKunci);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows > 0) {
        // ID Karyawan valid, hash password baru
        $hashedPassword = md5($newPassword);

        // Prepare and bind for UPDATE
       // Prepare the UPDATE statement
       $updateStmt = $conn->prepare("UPDATE karyawan SET Password = ? WHERE IDKaryawan = ? OR NIK = ? OR NoTelp = ?");
        
       // Bind parameters for update
       $updateStmt->bind_param("ssss", $hashedPassword, $KataKunci, $KataKunci, $KataKunci);
       if ($updateStmt->execute()) {
            $_SESSION['success_message'] = "Password berhasil direset. Silakan login dengan password baru Anda.";
            header("Location: ../view/forgotpassView.php");
            exit();
        } else {
            echo "Error: " . $updateStmt->error;
        }
    } else {
        $_SESSION['error_message'] = "$KataKunci tidak ditemukan";
        header("Location: ../view/forgotpassView.php");
        exit();
    }

    $stmt->close();
    $updateStmt->close();
}
$conn->close();
?>  