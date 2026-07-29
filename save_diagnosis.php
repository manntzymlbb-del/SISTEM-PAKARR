<?php
require 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
    exit;
}

$rawBody = file_get_contents('php://input');
$input = json_decode($rawBody, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Payload JSON tidak valid']);
    error_log('save_diagnosis.php: invalid JSON payload: ' . json_last_error_msg() . " | body=" . $rawBody . "\n", 3, __DIR__ . '/save_diagnosis.log');
    exit;
}

$patientName = trim($input['patientName'] ?? '');
$patientAge = trim($input['patientAge'] ?? '');
$patientGender = trim($input['patientGender'] ?? '');
$disease = trim($input['disease'] ?? '');
$score = (int) ($input['score'] ?? 0);
$level = trim($input['level'] ?? '');
$answers = $input['answers'] ?? [];
$recommendations = $input['recommendations'] ?? [];
$evidence = $input['evidence'] ?? [];
$time = trim($input['time'] ?? '');

if ($patientName === '' || $disease === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Data pasien atau hasil diagnosa tidak lengkap']);
    error_log('save_diagnosis.php: missing required fields | body=' . $rawBody . "\n", 3, __DIR__ . '/save_diagnosis.log');
    exit;
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO diagnoses (patient_name, patient_age, patient_gender, disease, score, level_name, answers, recommendations, evidence, created_at) VALUES (:patient_name, :patient_age, :patient_gender, :disease, :score, :level_name, :answers, :recommendations, :evidence, :created_at)"
    );

    $stmt->execute([
        ':patient_name' => $patientName,
        ':patient_age' => $patientAge,
        ':patient_gender' => $patientGender,
        ':disease' => $disease,
        ':score' => $score,
        ':level_name' => $level,
        ':answers' => json_encode($answers),
        ':recommendations' => json_encode($recommendations),
        ':evidence' => json_encode($evidence),
        ':created_at' => $time ?: date('Y-m-d H:i:s')
    ]);

    echo json_encode(['success' => true, 'message' => 'Data berhasil disimpan']);
} catch (PDOException $e) {
    http_response_code(500);
    $message = 'Gagal menyimpan data: ' . $e->getMessage();
    echo json_encode(['success' => false, 'message' => $message]);
    error_log('save_diagnosis.php: ' . $message . " | body=" . $rawBody . "\n", 3, __DIR__ . '/save_diagnosis.log');
}
