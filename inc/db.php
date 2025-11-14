<?php
// db.php - kết nối database
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "vietchill";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if(!$conn){
    die("Kết nối database thất bại: " . mysqli_connect_error());
}
?>
