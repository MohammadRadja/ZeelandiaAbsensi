<?php
session_start();
require_once '../db/koneksi.php';
require_once '../utils/emailUtil.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ForgotPass = $_POST['ForgotPass'];

    // Protect against SQL injection
    $ForgotPass = $conn->real_escape_string($ForgotPass);

    // Validate input
    if (empty($ForgotPass)) {
        $response = [
            'success' => false,
            'error_message' => "ID Karyawan, Email, or Username is required."
        ];
    } else {
        // Query to check if the user exists based on ID Karyawan, Username, or No Telp
        $sql = "SELECT * FROM karyawan WHERE IDKaryawan = '$ForgotPass' OR Username = '$ForgotPass' OR NoTelp = '$ForgotPass'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userID = $row['IDKaryawan'];

            // Generate a new temporary password
            $temporaryPassword = generateRandomPassword(8); // Function to generate a random password

            // Hash the new temporary password using MD5
            $hashedPassword = md5($temporaryPassword);

            // Update the user's password in the database
            $updatePasswordSql = "UPDATE karyawan SET Password = '$hashedPassword' WHERE IDKaryawan = '$userID'";

            if ($conn->query($updatePasswordSql) === TRUE) {
                // Send the new password via email
                $to = $row['Email']; // Assume there is an email field in the karyawan table
                $subject = "Your New Password";
                $message = "Your new password is: $temporaryPassword";
                $headers = "From: no-reply@zeelandia.com";

                if (sendPasswordResetEmail($to, $subject, $message, $headers)) {
                    $response = [
                        'success' => true,
                        'message' => "A new password has been sent to your email."
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'error_message' => "Failed to send email."
                    ];
                }
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

    // Close the database connection
    $conn->close();

    // Send the response in JSON format
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
