<?php
header('Content-Type: text/html; charset=utf-8');

$servername = "localhost";
$username = "root";
$password = "";
$database = "vijesti"; 

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Greška u spajanju s bazom: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
?>
