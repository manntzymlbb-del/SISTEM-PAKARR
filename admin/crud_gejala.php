<?php
// ======================================================
// CRUD DATA GEJALA — database firman (tabel gejala)
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
    $id    = (int)($_POST['id_gejala'] ?? 0);
    $nama  = trim($_POST['nama_gejala'] ?? '');

    if ($nama === '') {
        $error = "Nama gejala tidak boleh kosong.";
    } else {
        try {
            if ($id > 0) {
                // UPDATE
                $stmt = $pdo->prepare("UPDATE gejala SET nama_gejala = ? WHERE id_gejala = ?");
                $stmt->execute([$nama, $id]);
                $message = "Gejala berhasil diperbarui.";
            } else {
                // TAMBAH
                $stmt = $pdo->prepare("INSERT INTO gejala (nama_gejala) VALUES (?)");
                $stmt->execute([$nama]);
                $message = "Gejala berhasil ditambahkan.";
            }
        } catch (PDOException $e) {
            $error = "Gagal menyimpan: " . $e->getMessage();
        }
    }
}

// ---------- HAPUS ----------
if (isset($_GET['hapus'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM gejala WHERE id_gejala = ?");
        $stmt->execute([(int)$_GET['hapus']]);
        $message = "Gejala berhasil dihapus.";
    } catch (PDOException $e) {
        $error = "Gagal menghapus (mungkin dipakai rule base): " . $e->getMessage();
    }
}

// ---------- AMBIL DATA ----------
$gejala = $pdo->query("SELECT * FROM gejala ORDER BY id_gejala ASC")->fetchAll();

// Data untuk form edit
$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM gejala WHERE id_gejala = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editData = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Gejala - Admin</title>
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
    <a href="crud_gejala.php" class="active"><i class="fa fa-list"></i> Data Gejala</a>
    <a href="crud_penyakit.php"><i class="fa fa-brain"></i> Data Penyakit</a>
    <a href="crud_pasien.php"><i class="fa fa-clipboard-list"></i> Daftar Pasien</a>
    <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
</div>

<div class="content">
    <h2 class="mb-4"><i class="fa fa-list"></i> CRUD Data Gejala</h2>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <b><?= $editData ? 'Edit Gejala' : 'Tambah Gejala Baru' ?></b>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-2 align-items-end">
                <input type="hidden" name="id_gejala" value="<?= $editData ? (int)$editData['id_gejala'] : 0 ?>">
                <div class="col-md-9">
                    <label class="form-label">Nama Gejala</label>
                    <input type="text" name="nama_gejala" class="form-control" required
                           value="<?= $editData ? htmlspecialchars($editData['nama_gejala']) : '' ?>"
                           placeholder="Contoh: G01 - Jantung berdetak cepat">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary flex-fill">
                        <i class="fa fa-save"></i> <?= $editData ? 'Update' : 'Simpan' ?>
                    </button>
                    <?php if ($editData): ?>
                        <a href="crud_gejala.php" class="btn btn-secondary"><i class="fa fa-times"></i></a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><b>Daftar Gejala (<?= count($gejala) ?>)</b></div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th style="width:80px">ID</th>
                        <th>Nama Gejala</th>
                        <th style="width:160px">Dibuat</th>
                        <th style="width:180px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($gejala)): ?>
                        <tr><td colspan="4" class="text-center text-muted">Belum ada data gejala.</td></tr>
                    <?php else: ?>
                        <?php foreach ($gejala as $g): ?>
                        <tr>
                            <td><?= (int)$g['id_gejala'] ?></td>
                            <td><?= htmlspecialchars($g['nama_gejala']) ?></td>
                            <td><?= htmlspecialchars($g['created_at']) ?></td>
                            <td>
                                <a href="crud_gejala.php?edit=<?= (int)$g['id_gejala'] ?>" class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <a href="crud_gejala.php?hapus=<?= (int)$g['id_gejala'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Hapus gejala ini?')">
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
</content>
