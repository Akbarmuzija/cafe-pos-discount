<?php
session_start();
include 'config.php';
if (!isset($_SESSION['kasir'])) {
header('Location: index.php'); exit;
}
$data = mysqli_query($koneksi, "SELECT * FROM riwayat_transaksi ORDER BY id DESC");
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Riwayat - Kedai Yomijo</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar navbar-dark">
<div class="container">
<a class="navbar-brand" href="dashboard.php">Kedai Yomijo</a>
<div class="d-flex">
<a href="dashboard.php" class="btn btn-outline-light me-2">Dashboard</a>
<a href="logout.php" class="btn btn-outline-light">Logout</a>
</div>
</div>
</nav>
<div class="container py-4">
<div class="card p-3">
<h3 class="title mb-3">Riwayat Transaksi</h3>
<div class="table-responsive">
<table class="table table-striped">
<thead>
<tr>
<th>#</th>
<th>Items</th>
<th>Qty</th>
<th>Subtotal</th>
<th>Diskon</th>
<th>Total Akhir</th>
<th>Tanggal</th>
</tr>
</thead>
<tbody>
<?php while($row = mysqli_fetch_assoc($data)): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['menu']) ?></td>
<td><?= $row['jumlah'] ?></td>
<td>Rp <?= number_format($row['total'],0,',','.') ?></td>
<td><?= $row['diskon'] ?>%</td>
<td>Rp <?= number_format($row['total_akhir'],0,',','.') ?></td>
<td><?= $row['tanggal'] ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
<div class="mt-3">
<a href="download.php" class="btn btn-dark">Download CSV</a>
</div>
</div>
</div>
</body>
</html>