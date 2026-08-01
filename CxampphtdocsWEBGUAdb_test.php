<?php

$host = "localhost";
$dbname = "anxiety_expert_system";
$user = "root";
$pass = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass
    );

    echo "✅ Database Connected";
} catch (PDOException $e) {
    echo "❌ Gagal konek: " . $e->getMessage();
}