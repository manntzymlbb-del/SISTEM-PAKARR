<?php
// ======================================
// KONEKSI DATABASE
// ======================================

$host = "localhost";
$user = "root";
$pass = "";
$db   = "firman";

// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal : " . mysqli_connect_error());
}

// Charset UTF-8
mysqli_set_charset($conn, "utf8");
?>