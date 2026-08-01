<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once("../config.php");

// Hitung data
$totalUser = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalGejala = $pdo->query("SELECT COUNT(*) FROM gejala")->fetchColumn();
$totalPenyakit = $pdo->query("SELECT COUNT(*) FROM penyakit")->fetchColumn();
$totalRule = $pdo->query("SELECT COUNT(*) FROM rule_base")->fetchColumn();
$totalDiagnosis = $pdo->query("SELECT COUNT(*) FROM diagnoses")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Dashboard Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="sidebar">

<h2>ADMIN</h2>

<a href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a>

<a href="gejala.php"><i class="fa fa-list"></i> Data Gejala</a>

<a href="penyakit.php"><i class="fa fa-brain"></i> Data Penyakit</a>

<a href="rule.php"><i class="fa fa-link"></i> Rule Base</a>

<a href="users.php"><i class="fa fa-users"></i> Data User</a>

<a href="diagnosis.php"><i class="fa fa-file-medical"></i> Diagnosis</a>

<a href="statistik.php"><i class="fa fa-chart-bar"></i> Statistik</a>

<a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>

</div>

<div class="content">

<h2>Dashboard Admin</h2>

<p>Selamat datang,
<b><?= $_SESSION['nama']; ?></b>

</p>

<div class="row">

<div class="col-md-4">

<div class="card bg-primary text-white">

<div class="card-body">

<h5>Total User</h5>

<h2><?= $totalUser ?></h2>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card bg-success text-white">

<div class="card-body">

<h5>Total Gejala</h5>

<h2><?= $totalGejala ?></h2>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card bg-warning text-dark">

<div class="card-body">

<h5>Total Penyakit</h5>

<h2><?= $totalPenyakit ?></h2>

</div>

</div>

</div>

</div>

<br>

<div class="row">

<div class="col-md-6">

<div class="card bg-info text-white">

<div class="card-body">

<h5>Total Rule</h5>

<h2><?= $totalRule ?></h2>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card bg-danger text-white">

<div class="card-body">

<h5>Total Diagnosis</h5>

<h2><?= $totalDiagnosis ?></h2>

</div>

</div>

</div>

</div>

</div>

</body>
</html>