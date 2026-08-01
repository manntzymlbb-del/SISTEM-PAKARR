<?php
// ======================================================
// LOGIN ADMIN — tabel `users` (database firman)
// Mendukung 2 mode:
//   1. Form HTML biasa (POST username & password)
//   2. AJAX/JSON (fetch) dari login.html / admin/login.html
// ======================================================

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

session_start();
require_once("../config.php");

// CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ---------- Deteksi request JSON (fetch) ----------
$rawBody = file_get_contents('php://input');
$isJson  = false;
$input   = $_POST;

if (empty($input) && $rawBody !== '') {
    $decoded = json_decode($rawBody, true);
    if (is_array($decoded)) {
        $input  = $decoded;
        $isJson = true;
    }
}

// Jika sudah login, jangan redirect untuk request JSON —
// langsung balas JSON sukses agar fetch tidak error.
if (isset($_SESSION['admin'])) {
    if ($isJson || $_SERVER["REQUEST_METHOD"] === "POST") {
        header('Content-Type: application/json');
        echo json_encode([
            'success'  => true,
            'message'  => 'Sesi masih aktif. Mengarahkan ke dashboard...',
            'redirect' => 'dashboard.php',
            'user'     => [
                'username'     => $_SESSION['username'] ?? '',
                'nama_lengkap' => $_SESSION['nama'] ?? ''
            ]
        ]);
        exit;
    }
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($input['username'] ?? '');
    $password = $input['password'] ?? '';

    if ($username === '' || $password === '') {

        if ($isJson) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Username dan password wajib diisi']);
            exit;
        }
        $error = "Username dan password wajib diisi.";

    } else {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin'");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {

            session_regenerate_id(true);
            $_SESSION['admin']    = $admin['id_user'];
            $_SESSION['nama']     = $admin['nama_lengkap'];
            $_SESSION['username'] = $admin['username'];

            if ($isJson) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success'  => true,
                    'message'  => 'Login berhasil!',
                    'redirect' => 'dashboard.php',
                    'user'     => [
                        'username'     => $admin['username'],
                        'nama_lengkap' => $admin['nama_lengkap']
                    ]
                ]);
                exit;
            }

            header("Location: dashboard.php");
            exit;

        } else {

            if ($isJson) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Username atau password salah.']);
                exit;
            }
            $error = "Username atau Password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f4f6f9;}
.card{margin-top:120px;border:none;box-shadow:0 5px 20px rgba(0,0,0,.15);}
</style>
</head>
<body>
<div class="container">
<div class="row justify-content-center">
<div class="col-md-4">
<div class="card">
<div class="card-body">
<h3 class="text-center mb-4">Login Admin</h3>

<?php if($error!=""){ ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php } ?>

<form method="POST">
<div class="mb-3">
<label>Username</label>
<input type="text" name="username" class="form-control" required>
</div>
<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>
<button class="btn btn-primary w-100">LOGIN</button>
</form>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
