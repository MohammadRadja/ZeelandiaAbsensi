<?php
require_once '../db/koneksi.php'; // Pastikan path ini benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ForgotPass = $_POST['ForgotPass'];

    // Protect against SQL injection
    $ForgotPass = $conn->real_escape_string($ForgotPass);

    // Query to check if email exists
    $sql = "SELECT * FROM karyawan WHERE Email = '$ForgotPass' OR NoTelp = '$ForgotPass' OR Username = '$ForgotPass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userID = $row['IDKaryawan']; 

        // Display user information
        echo "User Found:<br>";
        echo "ID Karyawan: " . $row['IDKaryawan'] . "<br>";
        echo "Nama Karyawan: " . $row['NamaKaryawan'] . "<br>";
        echo "Email: " . $row['Email'] . "<br>";
        echo "No. Telepon: " . $row['NoTelp'] . "<br>";
        echo "Username: " . $row['Username'] . "<br><br>";

        // Generate a temporary password (example: random 8-character string)
        $temporaryPassword = generateRandomPassword(8); // Function to generate random password
        
        // Hash the temporary password (using bcrypt for example)
        $hashedPassword = password_hash($temporaryPassword, PASSWORD_DEFAULT);

        // Update user's password in the database
        $updatePasswordSql = "UPDATE karyawan SET Password = '$hashedPassword' WHERE IDKaryawan = '$userID'";

        if ($conn->query($updatePasswordSql) === TRUE) {
            // Send an email with the temporary password
            $to = $row['Email']; // Replace with the user's email
            $subject = 'Temporary Password for Password Reset';
            $message = "Hello " . $row['NamaKaryawan'] . ",\n\nYour temporary password is: " . $temporaryPassword . "\n\nPlease use this password to login and reset your password.\n\nRegards,\nYour Company Name";
            $headers = 'From: zeelandia@gmail.com'; // Replace with your email address or use a dedicated service for sending emails

            if (mail($to, $subject, $message, $headers)) {
                echo "Temporary password sent to your email.";
            } else {
                echo "Failed to send email. Please contact support.";
            }
        } else {
            echo "Error updating password: " . $conn->error;
        }
    } else {
        echo "Email, phone, or username not found.";
    }

    $conn->close();
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