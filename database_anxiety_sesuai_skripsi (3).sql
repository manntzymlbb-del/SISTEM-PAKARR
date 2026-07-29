-- ======================================================
-- DATABASE SISTEM PAKAR DIAGNOSA ANXIETY
-- Metode: Forward Chaining + Certainty Factor (MB/MD)
-- Disusun sesuai Spesifikasi Basis Data Bab 3 Skripsi
-- ======================================================

USE `if0_42469487_anxiety`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `diagnoses`;
DROP TABLE IF EXISTS `rule_base`;
DROP TABLE IF EXISTS `cf_nilai`;
DROP TABLE IF EXISTS `gejala`;
DROP TABLE IF EXISTS `penyakit`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- ======================================================
-- 1. TABEL USERS
-- ======================================================
CREATE TABLE `users` (
    `id_user` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `nama_lengkap` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `role` ENUM('admin','user') NOT NULL DEFAULT 'user',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_user`),
    UNIQUE KEY `uq_username` (`username`),
    UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================================
-- 2. TABEL GEJALA
-- ======================================================
CREATE TABLE `gejala` (
    `id_gejala` INT(11) NOT NULL AUTO_INCREMENT,
    `nama_gejala` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_gejala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================================
-- 3. TABEL PENYAKIT
-- ======================================================
CREATE TABLE `penyakit` (
    `id_penyakit` INT(11) NOT NULL AUTO_INCREMENT,
    `nama_penyakit` VARCHAR(100) NOT NULL,
    `deskripsi` TEXT NOT NULL,
    `solusi` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_penyakit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================================
-- 4. TABEL RULE_BASE (Forward Chaining + nilai MB/MD pakar)
-- ======================================================
CREATE TABLE `rule_base` (
    `id_rule` INT(11) NOT NULL AUTO_INCREMENT,
    `id_gejala` INT(11) NOT NULL,
    `id_penyakit` INT(11) NOT NULL,
    `mb` DECIMAL(3,2) NOT NULL,
    `md` DECIMAL(3,2) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_rule`),
    KEY `fk_rb_gejala` (`id_gejala`),
    KEY `fk_rb_penyakit` (`id_penyakit`),
    CONSTRAINT `fk_rb_gejala` FOREIGN KEY (`id_gejala`) REFERENCES `gejala` (`id_gejala`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_rb_penyakit` FOREIGN KEY (`id_penyakit`) REFERENCES `penyakit` (`id_penyakit`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================================
-- 5. TABEL CF_NILAI (skala jawaban pengguna)
-- ======================================================
CREATE TABLE `cf_nilai` (
    `id_cf` INT(11) NOT NULL AUTO_INCREMENT,
    `nilai` DECIMAL(3,2) NOT NULL,
    `keterangan` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id_cf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================================
-- 6. TABEL DIAGNOSES (hasil akhir tes CF pengguna)
-- ======================================================
CREATE TABLE `diagnoses` (
    `id_diagnosis` INT(11) NOT NULL AUTO_INCREMENT,
    `id_user` INT(11) NOT NULL,
    `nama` VARCHAR(100) NOT NULL,
    `umur` INT(3) NOT NULL,
    `jenis_kelamin` ENUM('L','P') NOT NULL,
    `id_penyakit` INT(11) NOT NULL,
    `nilai_cf` DECIMAL(5,2) NOT NULL,
    `tingkat` VARCHAR(50) NOT NULL,
    `jawaban` TEXT NOT NULL,
    `saran` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_diagnosis`),
    KEY `fk_dg_user` (`id_user`),
    KEY `fk_dg_penyakit` (`id_penyakit`),
    CONSTRAINT `fk_dg_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_dg_penyakit` FOREIGN KEY (`id_penyakit`) REFERENCES `penyakit` (`id_penyakit`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================================
-- DATA: USERS (contoh akun admin default)
-- Password contoh di-hash dengan bcrypt untuk 'admin123'
-- Ganti/hash ulang sesuai kebutuhan produksi Anda
-- ======================================================
INSERT INTO `users` (`username`, `password`, `nama_lengkap`, `email`, `role`) VALUES
('admin', '$2y$10$7EqJtq98hPqEX7fNZaFWoOhi5S.zP.WEhV3O0m3aXbCXqm7XmxD0y', 'Administrator', 'admin@anxiety-system.local', 'admin');

-- ======================================================
-- DATA: GEJALA (25 gejala)
-- ======================================================
INSERT INTO `gejala` (`id_gejala`, `nama_gejala`) VALUES
(1, 'Jantung berdetak cepat atau berdebar.'),
(2, 'Keringat berlebih.'),
(3, 'Gemetar atau tremor.'),
(4, 'Sesak napas.'),
(5, 'Pusing atau sakit kepala.'),
(6, 'Mual atau gangguan perut.'),
(7, 'Otot tegang atau kaku.'),
(8, 'Kelelahan berlebih.'),
(9, 'Sulit tidur (insomnia).'),
(10, 'Khawatir berlebihan tentang berbagai hal.'),
(11, 'Sulit berkonsentrasi atau pikiran kosong.'),
(12, 'Pikiran negatif terus-menerus.'),
(13, 'Takut dinilai negatif oleh orang lain.'),
(14, 'Takut memalukan diri sendiri di depan umum.'),
(15, 'Pikiran tentang bencana atau malapetaka.'),
(16, 'Perasaan tidak nyata atau terpisah dari lingkungan.'),
(17, 'Mudah marah atau sangat sensitif.'),
(18, 'Perasaan cemas yang intens secara tiba-tiba.'),
(19, 'Takut kehilangan kendali.'),
(20, 'Takut mati mendadak.'),
(21, 'Menghindari situasi sosial.'),
(22, 'Menghindari objek atau situasi tertentu.'),
(23, 'Sulit berpisah dengan orang tua atau orang terdekat.'),
(24, 'Mencari jaminan atau kepastian berulang-ulang.'),
(25, 'Penurunan prestasi akademik.');

-- ======================================================
-- DATA: PENYAKIT (4 jenis gangguan kecemasan)
-- ======================================================
INSERT INTO `penyakit` (`id_penyakit`, `nama_penyakit`, `deskripsi`, `solusi`) VALUES
(1, 'Generalized Anxiety Disorder', 'Kecemasan berlebihan, kesulitan mengendalikan khawatir, dan gangguan fokus memperkuat indikasi GAD.', 'Luangkan waktu untuk relaksasi dan latihan pernapasan. Catat kekhawatiran Anda agar tidak berputar terus menerus.'),
(2, 'Social Anxiety', 'Takut dinilai negatif dan menghindari situasi sosial sangat menonjol.', 'Mulai latihan interaksi sosial secara bertahap. Jangan terlalu keras pada diri sendiri saat berada di lingkungan baru.'),
(3, 'Panic Disorder', 'Jantung berdebar, tidak nyaman, dan serangan panik memperkuat indikasi panic disorder.', 'Cari dukungan profesional jika panik sering terjadi. Praktikkan teknik grounding ketika gejala muncul.'),
(4, 'Adjustment Anxiety', 'Kesulitan beradaptasi terhadap perubahan dan stres lingkungan mendukung adjustment anxiety.', 'Identifikasi sumber stres dan buat prioritas aktivitas. Jaga rutinitas tidur, makan, dan istirahat yang teratur.');

-- ======================================================
-- DATA: RULE_BASE (nilai MB/MD per gejala-penyakit)
-- CATATAN ASUMSI KONVERSI:
-- Data asli (script.js) hanya berupa satu nilai bobot per gejala-penyakit
-- (0.03 - 0.09), belum berupa MB/MD terpisah untuk metode Certainty Factor.
-- Konversi yang digunakan di sini:
--   MB (Measure of Belief)    = bobot_asli x 10  (dibulatkan 2 desimal, min 0.05, maks 0.95)
--   MD (Measure of Disbelief) = MB x 0.2         (asumsi keraguan pakar kecil)
-- SILAKAN KONSULTASIKAN nilai MB/MD final ini dengan dosen pembimbing / pakar,
-- karena ini adalah estimasi otomatis, bukan hasil wawancara pakar asli.
-- ======================================================
INSERT INTO `rule_base` (`id_gejala`, `id_penyakit`, `mb`, `md`) VALUES
(1, 1, 0.50, 0.10),
(1, 2, 0.30, 0.06),
(1, 3, 0.90, 0.18),
(1, 4, 0.40, 0.08),
(2, 1, 0.40, 0.08),
(2, 2, 0.30, 0.06),
(2, 3, 0.80, 0.16),
(2, 4, 0.40, 0.08),
(3, 1, 0.40, 0.08),
(3, 2, 0.30, 0.06),
(3, 3, 0.80, 0.16),
(3, 4, 0.30, 0.06),
(4, 1, 0.40, 0.08),
(4, 2, 0.30, 0.06),
(4, 3, 0.80, 0.16),
(4, 4, 0.30, 0.06),
(5, 1, 0.40, 0.08),
(5, 2, 0.30, 0.06),
(5, 3, 0.60, 0.12),
(5, 4, 0.40, 0.08),
(6, 1, 0.40, 0.08),
(6, 2, 0.30, 0.06),
(6, 3, 0.60, 0.12),
(6, 4, 0.40, 0.08),
(7, 1, 0.50, 0.10),
(7, 2, 0.30, 0.06),
(7, 3, 0.50, 0.10),
(7, 4, 0.50, 0.10),
(8, 1, 0.50, 0.10),
(8, 2, 0.30, 0.06),
(8, 3, 0.40, 0.08),
(8, 4, 0.60, 0.12),
(9, 1, 0.60, 0.12),
(9, 2, 0.30, 0.06),
(9, 3, 0.50, 0.10),
(9, 4, 0.50, 0.10),
(10, 1, 0.80, 0.16),
(10, 2, 0.40, 0.08),
(10, 3, 0.30, 0.06),
(10, 4, 0.50, 0.10),
(11, 1, 0.80, 0.16),
(11, 2, 0.40, 0.08),
(11, 3, 0.30, 0.06),
(11, 4, 0.40, 0.08),
(12, 1, 0.80, 0.16),
(12, 2, 0.40, 0.08),
(12, 3, 0.30, 0.06),
(12, 4, 0.50, 0.10),
(13, 1, 0.40, 0.08),
(13, 2, 0.80, 0.16),
(13, 3, 0.30, 0.06),
(13, 4, 0.40, 0.08),
(14, 1, 0.40, 0.08),
(14, 2, 0.80, 0.16),
(14, 3, 0.30, 0.06),
(14, 4, 0.30, 0.06),
(15, 1, 0.50, 0.10),
(15, 2, 0.30, 0.06),
(15, 3, 0.40, 0.08),
(15, 4, 0.30, 0.06),
(16, 1, 0.40, 0.08),
(16, 2, 0.30, 0.06),
(16, 3, 0.40, 0.08),
(16, 4, 0.30, 0.06),
(17, 1, 0.50, 0.10),
(17, 2, 0.40, 0.08),
(17, 3, 0.40, 0.08),
(17, 4, 0.50, 0.10),
(18, 1, 0.40, 0.08),
(18, 2, 0.40, 0.08),
(18, 3, 0.90, 0.18),
(18, 4, 0.40, 0.08),
(19, 1, 0.40, 0.08),
(19, 2, 0.40, 0.08),
(19, 3, 0.80, 0.16),
(19, 4, 0.30, 0.06),
(20, 1, 0.40, 0.08),
(20, 2, 0.30, 0.06),
(20, 3, 0.90, 0.18),
(20, 4, 0.30, 0.06),
(21, 1, 0.40, 0.08),
(21, 2, 0.90, 0.18),
(21, 3, 0.30, 0.06),
(21, 4, 0.40, 0.08),
(22, 1, 0.40, 0.08),
(22, 2, 0.70, 0.14),
(22, 3, 0.40, 0.08),
(22, 4, 0.40, 0.08),
(23, 1, 0.40, 0.08),
(23, 2, 0.50, 0.10),
(23, 3, 0.30, 0.06),
(23, 4, 0.80, 0.16),
(24, 1, 0.50, 0.10),
(24, 2, 0.40, 0.08),
(24, 3, 0.30, 0.06),
(24, 4, 0.70, 0.14),
(25, 1, 0.50, 0.10),
(25, 2, 0.30, 0.06),
(25, 3, 0.30, 0.06),
(25, 4, 0.70, 0.14);

-- ======================================================
-- DATA: CF_NILAI (skala kepastian jawaban pengguna)
-- ======================================================
INSERT INTO `cf_nilai` (`nilai`, `keterangan`) VALUES
(0.00, 'Tidak'),
(0.20, 'Sedikit Yakin'),
(0.40, 'Cukup Yakin'),
(0.80, 'Yakin'),
(1.00, 'Sangat Yakin');

-- ======================================================
-- CONTOH DATA DIAGNOSES (opsional, boleh dihapus)
-- ======================================================
INSERT INTO `diagnoses` (`id_user`, `nama`, `umur`, `jenis_kelamin`, `id_penyakit`, `nilai_cf`, `tingkat`, `jawaban`, `saran`) VALUES
(1, 'Contoh Pasien', 20, 'P', 1, 87.50, 'Tinggi', '{\"q1\":1,\"q10\":1,\"q11\":1,\"q12\":1}', 'Luangkan waktu untuk relaksasi dan latihan pernapasan.');

-- ======================================================
-- SELESAI
-- ======================================================