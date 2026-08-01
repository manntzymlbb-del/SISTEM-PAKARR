<?php
// ======================================================
// DAFTAR PASIEN / DIAGNOSA — database firman (tabel diagnoses)
// Fitur: Lihat, Edit, Hapus, Cari
// ======================================================
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once("../config.php");

$message = "";
$error   = "";

// ---------- PROSES EDIT (POST) ----------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_diagnosis'])) {
    $id           = (int)$_POST['id_diagnosis'];
    $name         = trim($_POST['name'] ?? '');
    $umur         = (int)($_POST['umur'] ?? 0);
    $jenisKelamin = trim($_POST['jenis_kelamin'] ?? '');
    $nilaiCf      = str_replace(',', '.', trim($_POST['nilai_cf'] ?? ''));
    $tingkat      = trim($_POST['tingkat'] ?? '');
    $saran        = trim($_POST['saran'] ?? '');

    if ($name === '' || $umur <= 0) {
        $error = "Nama dan umur wajib diisi dengan benar.";
    } else {
        try {
            // Simpan ulang saran sebagai JSON array agar konsisten dengan data lama
            $saranDecoded = json_decode($saran, true);
            $saranJson = is_array($saranDecoded) ? $saran : json_encode([$saran]);

            $stmt = $pdo->prepare(
                "UPDATE diagnoses
                 SET name = ?, umur = ?, jenis_kelamin = ?, nilai_cf = ?, tingkat = ?, saran = ?
                 WHERE id_diagnosis = ?"
            );
            $stmt->execute([$name, $umur, $jenisKelamin, $nilaiCf, $tingkat, $saranJson, $id]);
            $message = "Data pasien berhasil diperbarui.";
        } catch (PDOException $e) {
            $error = "Gagal memperbarui: " . $e->getMessage();
        }
    }
}

// ---------- HAPUS ----------
if (isset($_GET['hapus'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM diagnoses WHERE id_diagnosis = ?");
        $stmt->execute([(int)$_GET['hapus']]);
        $message = "Data pasien berhasil dihapus.";
    } catch (PDOException $e) {
        $error = "Gagal menghapus: " . $e->getMessage();
    }
}

// ---------- CARI ----------
$keyword = trim($_GET['q'] ?? '');
if ($keyword !== '') {
    $stmt = $pdo->prepare(
        "SELECT * FROM diagnoses
         WHERE name LIKE :q OR tingkat LIKE :q
         ORDER BY id_diagnosis DESC"
    );
    $stmt->execute([':q' => '%' . $keyword . '%']);
    $pasien = $stmt->fetchAll();
} else {
    $pasien = $pdo->query("SELECT * FROM diagnoses ORDER BY id_diagnosis DESC")->fetchAll();
}

// ---------- LIHAT DETAIL ----------
$detail = null;
if (isset($_GET['lihat'])) {
    $stmt = $pdo->prepare("SELECT * FROM diagnoses WHERE id_diagnosis = ?");
    $stmt->execute([(int)$_GET['lihat']]);
    $detail = $stmt->fetch();
    if ($detail) {
        $detail['jawaban_arr'] = json_decode($detail['jawaban'], true) ?: [];
        $saranDecoded = json_decode($detail['saran'], true);
        $detail['saran_arr'] = is_array($saranDecoded) ? $saranDecoded : [$detail['saran']];
    }
}

// ---------- EDIT ----------
$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM diagnoses WHERE id_diagnosis = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editData = $stmt->fetch();
    if ($editData) {
        // Tampilkan saran sebagai teks biasa di form edit
        $saranDecoded = json_decode($editData['saran'], true);
        $editData['saran_text'] = is_array($saranDecoded) ? implode("\n", $saranDecoded) : $editData['saran'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Pasien - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body { background: #f4f6f9; }
        .sidebar {
            height: 100vh; width: 240px; position: fixed; top: 0; left: 0;
            background: #1f2937; color: #fff; padding-top: 20px;
        }
        .sidebar h2 { text-align: center; font-size: 1.2rem; margin-bottom: 25px; }
        .sidebar a {
            display: block; color: #cbd5e1; padding: 12px 20px; text-decoration: none;
            border-left: 3px solid transparent;
        }
        .sidebar a:hover, .sidebar a.active { background: #374151; color: #fff; border-left-color: #3b82f6; }
        .content { margin-left: 240px; padding: 30px; }
        .table th { background: #f1f5f9; }
        .badge-tingkat { font-size: .8rem; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fa fa-shield-halved"></i> ADMIN</h2>
    <a href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
    <a href="crud_gejala.php"><i class="fa fa-list"></i> Data Gejala</a>
    <a href="crud_penyakit.php"><i class="fa fa-brain"></i> Data Penyakit</a>
    <a href="crud_pasien.php" class="active"><i class="fa fa-clipboard-list"></i> Daftar Pasien</a>
    <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
</div>

<div class="content">
    <h2 class="mb-4"><i class="fa fa-clipboard-list"></i> Daftar Pasien / Diagnosa</h2>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($editData): ?>
    <!-- ===== FORM EDIT DATA PASIEN ===== -->
    <div class="card mb-4">
        <div class="card-header">
            <b><i class="fa fa-edit"></i> Edit Data Pasien — <?= htmlspecialchars($editData['name']) ?></b>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="id_diagnosis" value="<?= (int)$editData['id_diagnosis'] ?>">
                <div class="col-md-6">
                    <label class="form-label">Nama Pasien</label>
                    <input type="text" name="name" class="form-control" required
                           value="<?= htmlspecialchars($editData['name']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Umur</label>
                    <input type="number" name="umur" class="form-control" min="1" required
                           value="<?= (int)$editData['umur'] ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <?php
                            $jk = $editData['jenis_kelamin'];
                            $options = ['Laki-laki', 'Perempuan', 'L', 'P'];
                            foreach ($options as $opt) {
                                $selected = ($jk === $opt) ? 'selected' : '';
                                echo "<option value=\"" . htmlspecialchars($opt) . "\" $selected>" . htmlspecialchars($opt) . "</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nilai CF (%)</label>
                    <input type="number" step="0.01" name="nilai_cf" class="form-control" required
                           value="<?= htmlspecialchars($editData['nilai_cf']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tingkat</label>
                    <select name="tingkat" class="form-select">
                        <?php
                            $tingkat = $editData['tingkat'];
                            foreach (['Rendah', 'Sedang', 'Tinggi'] as $opt) {
                                $selected = ($tingkat === $opt) ? 'selected' : '';
                                echo "<option $selected>" . $opt . "</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Saran / Rekomendasi</label>
                    <textarea name="saran" class="form-control" rows="3"><?= htmlspecialchars($editData['saran_text']) ?></textarea>
                    <small class="text-muted">Saran di simpan sebagai JSON otomatis.</small>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary"><i class="fa fa-save"></i> Simpan Perubahan</button>
                    <a href="crud_pasien.php" class="btn btn-secondary"><i class="fa fa-times"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- PENCARIAN -->
    <form method="GET" class="d-flex gap-2 mb-4">
        <input type="text" name="q" class="form-control" placeholder="Cari nama pasien atau tingkat..." value="<?= htmlspecialchars($keyword) ?>">
        <button class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
        <?php if ($keyword !== ''): ?>
            <a href="crud_pasien.php" class="btn btn-secondary"><i class="fa fa-times"></i> Reset</a>
        <?php endif; ?>
    </form>

    <div class="card">
        <div class="card-header"><b>Total Data: <?= count($pasien) ?></b></div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th style="width:60px">ID</th>
                        <th>Nama</th>
                        <th style="width:70px">Umur</th>
                        <th style="width:110px">Gender</th>
                        <th style="width:100px">Nilai CF</th>
                        <th style="width:100px">Tingkat</th>
                        <th style="width:170px">Waktu</th>
                        <th style="width:220px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pasien)): ?>
                        <tr><td colspan="8" class="text-center text-muted">Tidak ada data pasien.</td></tr>
                    <?php else: ?>
                        <?php foreach ($pasien as $p): ?>
                        <?php
                            $badgeClass = 'bg-secondary';
                            if (($p['tingkat'] ?? '') === 'Tinggi') $badgeClass = 'bg-danger';
                            elseif (($p['tingkat'] ?? '') === 'Sedang') $badgeClass = 'bg-warning text-dark';
                            elseif (($p['tingkat'] ?? '') === 'Rendah') $badgeClass = 'bg-success';
                        ?>
                        <tr>
                            <td><?= (int)$p['id_diagnosis'] ?></td>
                            <td><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= (int)$p['umur'] ?></td>
                            <td><?= htmlspecialchars($p['jenis_kelamin']) ?></td>
                            <td><?= htmlspecialchars($p['nilai_cf']) ?>%</td>
                            <td><span class="badge <?= $badgeClass ?> badge-tingkat"><?= htmlspecialchars($p['tingkat']) ?></span></td>
                            <td><small><?= htmlspecialchars($p['created_at']) ?></small></td>
                            <td>
                                <a href="crud_pasien.php?lihat=<?= (int)$p['id_diagnosis'] ?>" class="btn btn-sm btn-info">
                                    <i class="fa fa-eye"></i> Lihat
                                </a>
                                <a href="crud_pasien.php?edit=<?= (int)$p['id_diagnosis'] ?>" class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <a href="crud_pasien.php?hapus=<?= (int)$p['id_diagnosis'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Hapus data pasien ini?')">
                                    <i class="fa fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($detail): ?>
    <!-- MODAL DETAIL -->
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Diagnosa: <?= htmlspecialchars($detail['name']) ?></h5>
                    <a href="crud_pasien.php<?= $keyword !== '' ? '?q=' . urlencode($keyword) : '' ?>" class="btn-close"></a>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr><th style="width:160px">Nama</th><td><?= htmlspecialchars($detail['name']) ?></td></tr>
                        <tr><th>Umur</th><td><?= (int)$detail['umur'] ?> tahun</td></tr>
                        <tr><th>Jenis Kelamin</th><td><?= htmlspecialchars($detail['jenis_kelamin']) ?></td></tr>
                        <tr><th>Nilai CF</th><td><?= htmlspecialchars($detail['nilai_cf']) ?>%</td></tr>
                        <tr><th>Tingkat</th><td><span class="badge bg-primary"><?= htmlspecialchars($detail['tingkat']) ?></span></td></tr>
                        <tr><th>Waktu</th><td><?= htmlspecialchars($detail['created_at']) ?></td></tr>
                        <tr>
                            <th>Jawaban Gejala</th>
                            <td>
                                <?php if (empty($detail['jawaban_arr'])): ?>
                                    <span class="text-muted">-</span>
                                <?php else: ?>
                                    <ul class="mb-0">
                                    <?php foreach ($detail['jawaban_arr'] as $kode => $nilai): ?>
                                        <li><b><?= htmlspecialchars($kode) ?></b>: <?= htmlspecialchars($nilai) ?></li>
                                    <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Saran</th>
                            <td>
                                <?php if (empty($detail['saran_arr'])): ?>
                                    <span class="text-muted">-</span>
                                <?php else: ?>
                                    <ul class="mb-0">
                                    <?php foreach ($detail['saran_arr'] as $s): ?>
                                        <li><?= htmlspecialchars($s) ?></li>
                                    <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer d-flex gap-2">
                    <a href="crud_pasien.php?edit=<?= (int)$detail['id_diagnosis'] ?>" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Edit Data Ini
                    </a>
                    <a href="crud_pasien.php" class="btn btn-secondary">Tutup</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

</body>
</html>
