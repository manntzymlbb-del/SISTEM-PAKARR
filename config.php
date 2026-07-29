<?php
$host = 'localhost';
$db   = 'firman'; // Pastikan nama database di sini
$user = 'phpmyadmin'; // Pastikan username database di sini
$pass = 'Server2026';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Koneksi database gagal: " . $e->getMessage()]);
    exit;
}