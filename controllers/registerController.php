<?php
require_once '../db/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai dari $_POST
    $IDKaryawan = isset($_POST['IDKaryawan']) ? intval($_POST['IDKaryawan']) : 0; // intval untuk memastikan nilai adalah integer
    $Fullname = isset($_POST['Fullname']) ? $_POST['Fullname'] : '';
    $Username = isset($_POST['Username']) ? $_POST['Username'] : '';
    $Password = isset($_POST['password']) ? $_POST['password'] : '';

    // Protect against SQL injection
    $IDKaryawan = $conn->real_escape_string($IDKaryawan);
    $Fullname = $conn->real_escape_string($Fullname);
    $Username = $conn->real_escape_string($Username);
    $Password = $conn->real_escape_string($Password);

    // Hash the password using password_hash
    $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

    // Check if username already exists
    $checkUsernameQuery = "SELECT * FROM karyawan WHERE Username = '$Username'";
    $checkResult = $conn->query($checkUsernameQuery);

    if ($checkResult->num_rows > 0) {
        echo "Username already exists. Please choose a different username.";
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO karyawan (IDKaryawan, NamaKaryawan, Username, Password) VALUES ('$IDKaryawan', '$Fullname', '$Username', '$hashedPassword')";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../view/loginView.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>
