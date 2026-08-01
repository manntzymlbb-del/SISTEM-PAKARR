<?php

require 'config.php';

$password = password_hash("admin123", PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
INSERT INTO users
(username,password,nama_lengkap,email,role)
VALUES(?,?,?,?,?)
");

$stmt->execute([
"admin",
$password,
"Administrator",
"admin@gmail.com",
"admin"
]);

echo "Admin berhasil dibuat";