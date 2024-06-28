<?php
require_once '../db/koneksi.php'; // Sesuaikan path dengan struktur Anda

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ForgotPass = $_POST['ForgotPass'];

    // Lindungi dari SQL injection
    $ForgotPass = $conn->real_escape_string($ForgotPass);

    // Validasi input
    if (empty($ForgotPass)) {
        $response = [
            'success' => false,
            'error_message' => "ID Karyawan, Email, or Username is required."
        ];
    } else {
        // Query untuk memeriksa apakah pengguna ada berdasarkan ID Karyawan, Username, atau Nomor Telepon
        $sql = "SELECT * FROM karyawan WHERE IDKaryawan = '$ForgotPass' OR Username = '$ForgotPass' OR NoTelp = '$ForgotPass'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userID = $row['IDKaryawan'];

            // Generate password sementara (contoh: string acak 8 karakter)
            $temporaryPassword = generateRandomPassword(8); // Fungsi untuk menghasilkan password acak

            // Hash password sementara menggunakan MD5
            $hashedPassword = md5($temporaryPassword);

            // Update password pengguna di database
            $updatePasswordSql = "UPDATE karyawan SET Password = '$hashedPassword' WHERE IDKaryawan = '$userID'";

            if ($conn->query($updatePasswordSql) === TRUE) {
                // Kirim email atau notifikasi lain dengan password baru ke pengguna
                $response = [
                    'success' => true,
                    'newPasswordInfo' => [
                        'IDKaryawan' => $row['IDKaryawan'],
                        'Username' => $row['Username'],
                        'TemporaryPassword' => $temporaryPassword // Menggunakan temporaryPassword untuk ditampilkan kepada pengguna
                    ]
                ];
            } else {
                $response = [
                    'success' => false,
                    'error_message' => "Error updating password: " . $conn->error
                ];
            }
        } else {
            $response = [
                'success' => false,
                'error_message' => "ID Karyawan, Username, or No Telp not found."
            ];
        }
    }

    // Menutup koneksi database
    $conn->close();

    // Mengirimkan respons dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}

function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
?>
