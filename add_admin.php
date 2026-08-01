<?php
// 1. Koneksi langsung ke database tanpa file config lain
$host     = "localhost";
$user     = "root";
$pass     = ""; 
$database = "anxiety_expert_system";

$conn = new mysqli($host, $user, $pass, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error . "\n");
}

// 2. Data Admin Baru
$id_admin = rand(10, 9999); // Generate ID otomatis jika tidak AUTO_INCREMENT
$username = "admin_baru";
$password = "password123";

// Hash password dengan BCRYPT
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 3. Query khusus ke tabel 'admin' (tanpa s)
$stmt = $conn->prepare("INSERT INTO admin (id_admin, username, password) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $id_admin, $username, $hashed_password);

if ($stmt->execute()) {
    echo "\n========================================\n";
    echo " BERHASIL: Admin baru terbuat!\n";
    echo "========================================\n";
    echo " ID Admin : $id_admin\n";
    echo " Username : $username\n";
    echo " Password : $password\n";
    echo "========================================\n\n";
} else {
    echo "\nGAGAL: " . $stmt->error . "\n\n";
}

$stmt->close();
$conn->close();
?>