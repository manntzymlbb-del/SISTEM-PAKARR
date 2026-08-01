<?php
// ======================================================
// RESET PASSWORD ADMIN — database `firman` (tabel users)
// Gunakan sekali, lalu HAPUS file ini.
// ======================================================
session_start();
require_once("../config.php");

$message = "";
$error   = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username    = trim($_POST['username'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $confirm     = $_POST['confirm_password'] ?? '';

    if ($username === '' || $newPassword === '' || $confirm === '') {
        $error = "Semua field wajib diisi.";
    } elseif (strlen($newPassword) < 6) {
        $error = "Password minimal 6 karakter.";
    } elseif ($newPassword !== $confirm) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        $stmt = $pdo->prepare("SELECT id_user FROM users WHERE username = ? AND role = 'admin'");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin) {
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $upd  = $pdo->prepare("UPDATE users SET password = ? WHERE id_user = ?");
            $upd->execute([$hash, $admin['id_user']]);
            $message = "Password untuk user '$username' berhasil direset.";
        } else {
            $error = "User admin dengan username '$username' tidak ditemukan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .card { margin-top: 80px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,.15); }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-center mb-4">Reset Password Admin</h4>

                    <?php if ($message): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Username Admin</label>
                            <input type="text" name="username" class="form-control" value="admin" required>
                        </div>
                        <div class="mb-3">
                            <label>Password Baru</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Konfirmasi Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button class="btn btn-warning w-100">RESET PASSWORD</button>
                    </form>

                    <hr>
                    <a href="login.php" class="btn btn-primary w-100">Kembali ke Login</a>
                    <p class="text-muted text-center mt-3 small">Setelah selesai, hapus file reset_password.php demi keamanan.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
