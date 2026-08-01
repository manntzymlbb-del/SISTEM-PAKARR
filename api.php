<?php
// Header CORS untuk akses dari Frontend / Terminal
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Start Session untuk autentikasi Admin
session_start();

// Pastikan config.php ada
if (!file_exists('config.php')) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "File config.php tidak ditemukan"]);
    exit;
}

require 'config.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

switch ($method) {

    // ==========================================
    // 1. READ / AMBIL DATA (GET)
    // ==========================================
    case 'GET':
        $action = $_GET['action'] ?? '';

        // A. Cek Status Login Admin
        if ($action === 'check_auth') {
            echo json_encode([
                'success' => true,
                'isLoggedIn' => isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true,
                'username' => $_SESSION['admin_username'] ?? null
            ]);
            exit;
        }

        // B. Logout Admin
        if ($action === 'logout') {
            session_destroy();
            echo json_encode(['success' => true, 'message' => 'Berhasil logout']);
            exit;
        }

        // C. Ambil daftar gejala (symptoms)
        if ($action === 'get_symptoms') {
            try {
                $stmt = $pdo->query("SELECT * FROM symptoms ORDER BY id ASC");
                $symptoms = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode([
                    'success' => true,
                    'data' => $symptoms
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(["success" => false, "message" => $e->getMessage()]);
            }
            exit;
        }

        // D. Request default GET: Ambil semua riwayat diagnosa
        try {
            $stmt = $pdo->query("SELECT * FROM diagnoses ORDER BY id DESC");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Decode kolom JSON
            foreach ($data as &$row) {
                if (isset($row['answers']) && is_string($row['answers'])) {
                    $row['answers'] = json_decode($row['answers']);
                }
                if (isset($row['recommendations']) && is_string($row['recommendations'])) {
                    $row['recommendations'] = json_decode($row['recommendations']);
                }
                if (isset($row['evidence']) && is_string($row['evidence'])) {
                    $row['evidence'] = json_decode($row['evidence']);
                }
            }

            echo json_encode([
                "success" => true,
                "data" => $data
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
        }
        break;

    // ==========================================
    // 2. CREATE / PROSES DATA (POST)
    // ==========================================
    case 'POST':
        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true);

        if (!is_array($input)) {
            $input = [];
        }

        $action = $_GET['action'] ?? '';

        // A. Proses Login Admin
        if ($action === 'login') {
            $username = trim($input['username'] ?? '');
            $password = trim($input['password'] ?? '');

            if (empty($username) || empty($password)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Username dan password wajib diisi']);
                exit;
            }

            try {
                $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username LIMIT 1");
                $stmt->execute([':username' => $username]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($admin && password_verify($password, $admin['password'])) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_username'] = $admin['username'];

                    echo json_encode([
                        'success' => true,
                        'message' => 'Login berhasil!',
                        'user' => ['username' => $admin['username']]
                    ]);
                } else {
                    http_response_code(401);
                    echo json_encode(['success' => false, 'message' => 'Username atau password salah']);
                }
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit;
        }

        // B. Simpan Data Diagnosa Pasien
        $patientName    = trim($input['patientName'] ?? '');
        $patientAge     = (string)($input['patientAge'] ?? '');
        $patientGender  = $input['patientGender'] ?? null;
        $disease        = $input['disease'] ?? '';
        $score          = (int)($input['score'] ?? 0);
        $levelName      = $input['levelName'] ?? ($input['level'] ?? '');
        
        // Konversi Array/Object ke String JSON untuk disimpan di database
        $answers         = isset($input['answers']) ? json_encode($input['answers']) : '[]';
        $recommendations = isset($input['recommendations']) ? json_encode($input['recommendations']) : '[]';
        $evidence        = isset($input['evidence']) ? json_encode($input['evidence']) : '[]';

        $createdAt       = date('Y-m-d H:i:s');

        // Validasi input wajib
        if (empty($patientName) || empty($disease)) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Data nama pasien dan diagnosa wajib diisi"]);
            exit;
        }

        try {
            $sql = "INSERT INTO diagnoses (
                        name, 
                        umur, 
                        jenis_kelamin, 
                        -- id_penyakit, 
                        nilai_cf, 
                        tingkat, 
                        jawaban, 
                        saran, 
                        -- evidence,
                        created_at
                    ) VALUES (
                        :patient_name, 
                        :patient_age, 
                        :patient_gender, 
                        -- :disease, 
                        :score, 
                        :level_name, 
                        :answers, 
                        :recommendations, 
                        -- :evidence,
                        :created_at
                    )";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':patient_name'    => $patientName,
                ':patient_age'     => $patientAge,
                ':patient_gender'  => $patientGender,
                // ':disease'         => $disease,
                ':score'           => $score,
                ':level_name'      => $levelName,
                ':answers'         => $answers,
                ':recommendations' => $recommendations,
                // ':evidence'        => $evidence,
                ':created_at'      => $createdAt
            ]);

            echo json_encode([
                "success" => true,
                "message" => "Data diagnosa berhasil disimpan!"
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Gagal menyimpan: " . $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["success" => false, "message" => "Method tidak diizinkan"]);
        break;
}