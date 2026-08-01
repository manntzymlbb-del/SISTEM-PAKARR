<?php
// ======================================================
// CRUD DATA PENYAKIT — database firman (tabel penyakit)
// ======================================================
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once("../config.php");

$message = "";
$error   = "";

// ---------- PROSES FORM ----------
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id       = (int)($_POST['id_penyakit'] ?? 0);
    $nama     = trim($_POST['nama_penyakit'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $solusi   = trim($_POST['solusi'] ?? '');

    if ($nama === '') {
        $error = "Nama penyakit tidak boleh kosong.";
    } else {
        try {
            if ($id > 0) {
                $stmt = $pdo->prepare("UPDATE penyakit SET nama_penyakit = ?, deskripsi = ?, solusi = ? WHERE id_penyakit = ?");
                $stmt->execute([$nama, $deskripsi, $solusi, $id]);
                $message = "Penyakit berhasil diperbarui.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO penyakit (nama_penyakit, deskripsi, solusi) VALUES (?, ?, ?)");
                $stmt->execute([$nama, $deskripsi, $solusi]);
                $message = "Penyakit berhasil ditambahkan.";
            }
        } catch (PDOException $e) {
            $error = "Gagal menyimpan: " . $e->getMessage();
        }
    }
}

// ---------- HAPUS ----------
if (isset($_GET['hapus'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM penyakit WHERE id_penyakit = ?");
        $stmt->execute([(int)$_GET['hapus']]);
        $message = "Penyakit berhasil dihapus.";
    } catch (PDOException $e) {
        $error = "Gagal menghapus (mungkin dipakai rule base): " . $e->getMessage();
    }
}

// ---------- AMBIL DATA ----------
$penyakit = $pdo->query("SELECT * FROM penyakit ORDER BY id_penyakit ASC")->fetchAll();

$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM penyakit WHERE id_penyakit = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editData = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Penyakit - Admin</title>
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
    </style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fa fa-shield-halved"></i> ADMIN</h2>
    <a href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
    <a href="crud_gejala.php"><i class="fa fa-list"></i> Data Gejala</a>
    <a href="crud_penyakit.php" class="active"><i class="fa fa-brain"></i> Data Penyakit</a>
    <a href="crud_pasien.php"><i class="fa fa-clipboard-list"></i> Daftar Pasien</a>
    <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
</div>

<div class="content">
    <h2 class="mb-4"><i class="fa fa-brain"></i> CRUD Data Penyakit</h2>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <b><?= $editData ? 'Edit Penyakit' : 'Tambah Penyakit Baru' ?></b>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id_penyakit" value="<?= $editData ? (int)$editData['id_penyakit'] : 0 ?>">
                <div class="mb-3">
                    <label class="form-label">Nama Penyakit</label>
                    <input type="text" name="nama_penyakit" class="form-control" required
                           value="<?= $editData ? htmlspecialchars($editData['nama_penyakit']) : '' ?>"
                           placeholder="Contoh: Generalized Anxiety Disorder">
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3"
                              placeholder="Penjelasan singkat penyakit..."><?= $editData ? htmlspecialchars($editData['deskripsi']) : '' ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Solusi / Saran</label>
                    <textarea name="solusi" class="form-control" rows="3"
                              placeholder="Rekomendasi penanganan..."><?= $editData ? htmlspecialchars($editData['solusi']) : '' ?></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary"><i class="fa fa-save"></i> <?= $editData ? 'Update' : 'Simpan' ?></button>
                    <?php if ($editData): ?>
                        <a href="crud_penyakit.php" class="btn btn-secondary"><i class="fa fa-times"></i> Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><b>Daftar Penyakit (<?= count($penyakit) ?>)</b></div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th style="width:60px">ID</th>
                        <th style="width:220px">Nama Penyakit</th>
                        <th>Deskripsi</th>
                        <th style="width:160px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($penyakit)): ?>
                        <tr><td colspan="4" class="text-center text-muted">Belum ada data penyakit.</td></tr>
                    <?php else: ?>
                        <?php foreach ($penyakit as $p): ?>
                        <tr>
                            <td><?= (int)$p['id_penyakit'] ?></td>
                            <td><b><?= htmlspecialchars($p['nama_penyakit']) ?></b></td>
                            <td><?= htmlspecialchars(mb_strimwidth($p['deskripsi'] ?? '', 0, 100, '...')) ?></td>
                            <td>
                                <a href="crud_penyakit.php?edit=<?= (int)$p['id_penyakit'] ?>" class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <a href="crud_penyakit.php?hapus=<?= (int)$p['id_penyakit'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Hapus penyakit ini?')">
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
</div>

</body>
</html>
