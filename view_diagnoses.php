<?php
require 'config.php';

$stmt = $pdo->query('SELECT * FROM diagnoses ORDER BY id DESC');
$rows = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Diagnosa</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 24px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background: #f2f2f2; }
    .empty { color: #666; }
  </style>
</head>
<body>
  <h1>Riwayat Diagnosa</h1>
  <?php if (empty($rows)) : ?>
    <p class="empty">Belum ada data diagnosa yang tersimpan.</p>
  <?php else : ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama</th>
          <th>Usia</th>
          <th>Gender</th>
          <th>Hasil</th>
          <th>Skor</th>
          <th>Level</th>
          <th>Waktu</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row) : ?>
          <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['patient_name']) ?></td>
            <td><?= htmlspecialchars($row['patient_age']) ?></td>
            <td><?= htmlspecialchars($row['patient_gender']) ?></td>
            <td><?= htmlspecialchars($row['disease']) ?></td>
            <td><?= htmlspecialchars($row['score']) ?></td>
            <td><?= htmlspecialchars($row['level_name']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</body>
</html>
