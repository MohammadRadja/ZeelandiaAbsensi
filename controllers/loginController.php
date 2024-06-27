<?php
session_start();
require_once '../db/koneksi.php'; // Pastikan path ini benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $IDKaryawan = $_POST['IDKaryawan'];
    $Password = $_POST['Password'];

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
        
        // Debug statements
        echo "Entered hashed password: $hashedPassword<br>";
        echo "Stored hashed password: " . $row['Password'] . "<br>";

        // Verify password
        if ($hashedPassword == $row['Password']) {
            $_SESSION['IDKaryawan'] = $row['IDKaryawan'];
            $_SESSION['Username'] = $row['Username'];
            header("Location: ../view/dashboardview.php"); // Redirect to homepage or dashboard
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Invalid ID Karyawan.";
    }
}

$conn->close();
?>
