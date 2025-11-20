<?php
session_start();
include 'config.php';

if (!isset($_SESSION['kasir'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$menus     = $_POST['menu_nama'] ?? [];
$hargas    = $_POST['harga'] ?? [];
$qtys      = $_POST['qty'] ?? [];
$subtotals = $_POST['subtotal'] ?? [];
$total     = (int) ($_POST['total'] ?? 0);
$diskon    = (int) ($_POST['diskon'] ?? 0);
$item_lines = [];
for ($i = 0; $i < count($menus); $i++) {

    $parts = explode('|', $menus[$i]);
    $nama = $parts[0];

    $harga_satuan = (int) $hargas[$i];
    $jumlah       = (int) $qtys[$i];
    $subtotal     = (int) $subtotals[$i];

    $item_lines[] = $nama . ' x' . $jumlah . ' @' . number_format($harga_satuan, 0, ',', '.');
}

$items_text = implode("; ", $item_lines);

$potongan     = round($total * ($diskon / 100));
$total_akhir  = $total - $potongan;

$total_qty = array_sum(array_map('intval', $qtys));

$stmt = mysqli_prepare($koneksi, 
    "INSERT INTO riwayat_transaksi 
    (menu, jumlah, harga_satuan, total, diskon, total_akhir, tanggal) 
    VALUES (?, ?, ?, ?, ?, ?, NOW())"
);

$harga_satuan_store = $total;

mysqli_stmt_bind_param(
    $stmt,
    'siiiii',
    $items_text,
    $total_qty,
    $harga_satuan_store,
    $total,
    $diskon,
    $total_akhir
);

$ok = mysqli_stmt_execute($stmt);

if (!$ok) {
    die('Gagal menyimpan: ' . mysqli_error($koneksi));
}

header('Location: riwayat.php');
exit;
?>
