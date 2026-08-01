<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once("../config.php");

// Hitung data dari database `firman`
$totalUser      = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalGejala    = $pdo->query("SELECT COUNT(*) FROM gejala")->fetchColumn();
$totalPenyakit  = $pdo->query("SELECT COUNT(*) FROM penyakit")->fetchColumn();
$totalRule      = $pdo->query("SELECT COUNT(*) FROM rule_base")->fetchColumn();
$totalDiagnosis = $pdo->query("SELECT COUNT(*) FROM diagnoses")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
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
        .stat-card { border: none; border-radius: 10px; color: #fff; box-shadow: 0 4px 10px rgba(0,0,0,.12); text-decoration: none; display: block; }
        .stat-card h5 { font-size: .95rem; opacity: .9; }
        .stat-card h2 { font-weight: 700; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 14px rgba(0,0,0,.18); }
    </style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fa fa-shield-halved"></i> ADMIN</h2>
    <a href="dashboard.php" class="active"><i class="fa fa-home"></i> Dashboard</a>
    <a href="crud_gejala.php"><i class="fa fa-list"></i> Data Gejala</a>
    <a href="crud_penyakit.php"><i class="fa fa-brain"></i> Data Penyakit</a>
    <a href="crud_pasien.php"><i class="fa fa-clipboard-list"></i> Daftar Pasien</a>
    <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
</div>

<div class="content">
    <h2 class="mb-4">Dashboard Admin</h2>
    <p>Selamat datang, <b><?= htmlspecialchars($_SESSION['nama']) ?></b> (<?= htmlspecialchars($_SESSION['username']) ?>)</p>

    <div class="row g-3">
        <div class="col-md-4">
            <a href="crud_pasien.php" class="card stat-card bg-primary">
                <div class="card-body">
                    <h5><i class="fa fa-clipboard-list"></i> Total Pasien / Diagnosa</h5>
                    <h2><?= $totalDiagnosis ?></h2>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="crud_gejala.php" class="card stat-card bg-success">
                <div class="card-body">
                    <h5><i class="fa fa-list"></i> Total Gejala</h5>
                    <h2><?= $totalGejala ?></h2>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="crud_penyakit.php" class="card stat-card bg-warning text-dark">
                <div class="card-body">
                    <h5><i class="fa fa-brain"></i> Total Penyakit</h5>
                    <h2><?= $totalPenyakit ?></h2>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-info">
                <div class="card-body">
                    <h5><i class="fa fa-link"></i> Total Rule Base</h5>
                    <h2><?= $totalRule ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-danger">
                <div class="card-body">
                    <h5><i class="fa fa-users"></i> Total User</h5>
                    <h2><?= $totalUser ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
