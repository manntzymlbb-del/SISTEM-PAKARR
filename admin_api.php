<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = 'localhost';
$user = 'root';
$pass = ''; 
$db   = 'anxiety_expert_system'; // Disesuaikan dengan nama database Anda

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal: ' . $conn->connect_error]);
    exit();
}

$action = $_GET['action'] ?? '';

// -----------------------------------------------------------
// A. FITUR LOGIN ADMIN
// -----------------------------------------------------------

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $username = trim($data['username'] ?? '');
    $password = trim($data['password'] ?? '');

    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username dan password wajib diisi']);
        exit();
    }

    // Mengambil data dari tabel 'admin' (tanpa s)
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Verifikasi password yang sudah di-hash
        if (password_verify($password, $user['password'])) {
            echo json_encode([
                'success' => true, 
                'message' => 'Login berhasil',
                'user' => [
                    'id' => $user['id_admin'],
                    'username' => $user['username']
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Password salah!']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Username tidak ditemukan!']);
    }

    $stmt->close();
    exit();
}

// -----------------------------------------------------------
// B. KELOLA GEJALA (CRUD SYMPTOMS)
// -----------------------------------------------------------

// 1. GET GEJALA
if ($action === 'get_symptoms') {
    $result = $conn->query("SELECT * FROM symptoms ORDER BY id ASC");
    $symptoms = [];
    while ($row = $result->fetch_assoc()) {
        $symptoms[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $symptoms]);
    exit();
}

// 2. TAMBAH GEJALA
if ($action === 'add_symptom' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $code = $conn->real_escape_string($data['code']);
    $description = $conn->real_escape_string($data['description']);
    $weight = floatval($data['weight']);

    $sql = "INSERT INTO symptoms (code, description, weight) VALUES ('$code', '$description', $weight)";
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Gejala berhasil ditambahkan']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menambahkan: ' . $conn->error]);
    }
    exit();
}

// 3. UPDATE GEJALA
if ($action === 'update_symptom' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = intval($data['id']);
    $code = $conn->real_escape_string($data['code']);
    $description = $conn->real_escape_string($data['description']);
    $weight = floatval($data['weight']);

    $sql = "UPDATE symptoms SET code='$code', description='$description', weight=$weight WHERE id=$id";
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Gejala berhasil diperbarui']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal memperbarui: ' . $conn->error]);
    }
    exit();
}

// 4. HAPUS GEJALA
if ($action === 'delete_symptom' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = intval($data['id']);

    $sql = "DELETE FROM symptoms WHERE id=$id";
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Gejala berhasil dihapus']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus: ' . $conn->error]);
    }
    exit();
}

// -----------------------------------------------------------
// C. KELOLA RIWAYAT DIAGNOSA
// -----------------------------------------------------------

// 1. GET ALL RIWAYAT
if ($action === 'get_history') {
    $result = $conn->query("SELECT * FROM diagnoses ORDER BY id DESC");
    $history = [];
    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $history]);
    exit();
}

// 2. HAPUS RIWAYAT
if ($action === 'delete_history' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = intval($data['id']);

    $sql = "DELETE FROM diagnoses WHERE id=$id";
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Riwayat berhasil dihapus']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus riwayat: ' . $conn->error]);
    }
    exit();
}

$conn->close();
?>