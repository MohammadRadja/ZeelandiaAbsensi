<?php
$host = "localhost";
$username =  "root";
$password = "";
$database = "zeelandiadb";

$conn = new mysqli($host, $username, $password, $database);
if($conn -> connect_error){
    echo "Koneksi gagal".mysqli_connect_error();
    die;
}

?>