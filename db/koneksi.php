<?php
$host = "localhost";
$username = "u265582689_zeelandia";
$password = "Zeelandia123";
$database = "u265582689_zeelandiadb";

$conn = new mysqli($host, $username, $password, $database);
if($conn -> connect_error){
    echo "Koneksi gagal".mysqli_connect_error();
    die;
}
?>
