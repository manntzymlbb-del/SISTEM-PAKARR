<?php
header('Content-Type: application/json');

require_once 'config.php';

try {

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        throw new Exception("Data tidak diterima.");
    }

    $stmt = $pdo->prepare("
        INSERT INTO diagnoses
        (
            patient_name,
            patient_age,
            patient_gender,
            disease,
            score,
            level_name,
            answers,
            recommendations,
            evidence,
            created_at
        )
        VALUES
        (
            :patient_name,
            :patient_age,
            :patient_gender,
            :disease,
            :score,
            :level_name,
            :answers,
            :recommendations,
            :evidence,
            NOW()
        )
    ");

    $stmt->execute([

        ':patient_name'     => $data['patient_name'],
        ':patient_age'      => $data['patient_age'],
        ':patient_gender'   => $data['patient_gender'],
        ':disease'          => $data['disease'],
        ':score'            => $data['score'],
        ':level_name'       => $data['level_name'],
        ':answers'          => json_encode($data['answers']),
        ':recommendations'  => json_encode($data['recommendations']),
        ':evidence'         => json_encode($data['evidence'])

    ]);

    echo json_encode([
        "success" => true,
        "message" => "Diagnosis berhasil disimpan."
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);

}
?>