CREATE DATABASE IF NOT EXISTS anxiety_expert_system;
USE anxiety_expert_system;

CREATE TABLE IF NOT EXISTS diagnoses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(100) NOT NULL,
    patient_age VARCHAR(20) NOT NULL,
    patient_gender VARCHAR(20) DEFAULT '',
    disease VARCHAR(100) NOT NULL,
    score INT NOT NULL,
    level_name VARCHAR(50) NOT NULL,
    answers JSON NOT NULL,
    recommendations JSON NOT NULL,
    evidence JSON NOT NULL,
    created_at VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;