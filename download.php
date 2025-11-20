<?php
include 'config.php';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=riwayat_kedai_yomijo.csv');


$output = fopen('php://output', 'w');
fputcsv($output, ['ID','Items','Qty','Subtotal','Diskon(%)','TotalAkhir','Tanggal']);


$res = mysqli_query($koneksi, 'SELECT * FROM riwayat_transaksi ORDER BY id');
while ($r = mysqli_fetch_assoc($res)) {
fputcsv($output, [$r['id'], $r['menu'], $r['jumlah'], $r['total'], $r['diskon'], $r['total_akhir'], $r['tanggal']]);
}


fclose($output);
exit;
?>


<?php
session_start(); session_destroy(); header('Location: index.php'); exit;
?>