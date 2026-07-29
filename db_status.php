<?php
require 'config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query('SELECT 1');
    $ok = $stmt->fetchColumn() === '1';
    echo json_encode([
        'success' => true,
        'message' => 'Koneksi database berhasil',
        'database' => $db
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Koneksi database gagal: ' . $e->getMessage()
    ]);
}
