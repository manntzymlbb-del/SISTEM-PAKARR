-- ======================================================
-- DATABASE : if0_42469487_anxiety
-- WEBSITE SISTEM PAKAR DIAGNOSA ANXIETY
-- ======================================================

CREATE DATABASE IF NOT EXISTS `if0_42469487_anxiety`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `if0_42469487_anxiety`;

-- ======================================================
-- HAPUS TABEL JIKA SUDAH ADA
-- ======================================================

DROP TABLE IF EXISTS `diagnoses`;

-- ======================================================
-- TABEL HASIL DIAGNOSA
-- ======================================================

CREATE TABLE `diagnoses` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `patient_name` VARCHAR(100) NOT NULL,
    `patient_age` VARCHAR(20) NOT NULL,
    `patient_gender` VARCHAR(20) NOT NULL,
    `disease` VARCHAR(100) NOT NULL,
    `score` INT NOT NULL,
    `level_name` VARCHAR(50) NOT NULL,
    `answers` LONGTEXT NOT NULL,
    `recommendations` LONGTEXT NOT NULL,
    `evidence` LONGTEXT NOT NULL,
    `created_at` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- ======================================================
-- SELESAI
-- ======================================================