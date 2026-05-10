<?php

$host = "localhost";
$user = "root";
$pass = "kumahaweh123";
$db = "genheals_db";

$conn = new mysqli($host, $user, $pass, $db);

// Cek Koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>