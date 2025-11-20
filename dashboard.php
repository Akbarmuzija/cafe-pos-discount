<?php
session_start();
include 'config.php';
if (!isset($_SESSION['kasir'])) {
header('Location: index.php');
exit;
}
$menu_list = [
['nama'=>'Americano','harga'=>20000],
['nama'=>'Cappuccino','harga'=>25000],
['nama'=>'Matcha Latte','harga'=>28000],
['nama'=>'Roti Bakar','harga'=>15000],
['nama'=>'Es Teh Manis','harga'=>10000]
];
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard - Kedai Yomijo</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar navbar-dark">
<div class="container">
<a class="navbar-brand" href="#">Kedai Yomijo</a>
<div class="d-flex">
<a href="riwayat.php" class="btn btn-outline-light me-2">Riwayat</a>
<a href="logout.php" class="btn btn-outline-light">Logout</a>
</div>
</div>
</nav>
<div class="container py-4">
<div class="card p-4">
<h3 class="title mb-3">Buat Transaksi Baru</h3>
<form id="formTransaksi" action="proses.php" method="POST">
<div id="produkWrapper">
<div class="row g-3 mb-3 produk-item">
<div class="col-md-4">
<label class="form-label">Menu</label>
<select name="menu_nama[]" class="form-select nama-select">
<?php foreach($menu_list as $m): ?>
<option value="<?= $m['nama'] ?>|<?= $m['harga'] ?>"><?= $m['nama'] ?> - Rp <?= number_format($m['harga'],0,',','.') ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="col-md-2">
<label class="form-label">Harga (Rp)</label>
<input type="number" name="harga[]" class="form-control harga" readonly>
</div>
<div class="col-md-2">
<label class="form-label">Qty</label>
<input type="number" name="qty[]" class="form-control qty" value="1" min="1">
</div>
<div class="col-md-3">
<label class="form-label">Subtotal</label>
<input type="number" name="subtotal[]" class="form-control subtotal" readonly>
</div>
<div class="col-md-1 d-flex align-items-end">
<button type="button" class="btn btn-danger btn-sm remove">✕</button>
</div>
</div>
</div>
<div class="mb-3">
<button type="button" id="addProduk" class="btn btn-dark">+ Tambah Produk</button>
</div>
<div class="row g-3 align-items-center">
<div class="col-md-6">
<label class="form-label">Subtotal (sebelum diskon)</label>
<input type="text" id="displayTotal" class="form-control" readonly value="Rp 0">
</div>
<div class="col-md-6">
<label class="form-label">Diskon (otomatis)</label>
<input type="text" id="displayDiskon" class="form-control" readonly value="0%">
</div>
</div>
<div class="mt-3">
<label class="form-label">Total Bayar</label>
<input type="text" id="displayTotalAkhir" name="total_akhir_display" class="form-control" readonly value="Rp 0">
</div>
<input type="hidden" name="total" id="totalRaw" value="0">
<input type="hidden" name="diskon" id="diskonRaw" value="0">
<div class="mt-3 text-end">
<button type="submit" class="btn btn-dark">Simpan Transaksi</button>
</div>
</form>
</div>
</div>
<script>
function formatRp(num) {
return 'Rp ' + Number(num).toLocaleString('id-ID');
}
function attachHandlers() {
document.querySelectorAll('.nama-select').forEach(select => {
select.onchange = () => {
const row = select.closest('.produk-item');
const [nama, harga] = select.value.split('|');
row.querySelector('.harga').value = parseInt(harga);
row.querySelector('.qty').value = 1;
updateAll();
};
});
document.querySelectorAll('.qty').forEach(q => q.oninput = updateAll);
document.querySelectorAll('.remove').forEach(btn => {
btn.onclick = () => {
if (document.querySelectorAll('.produk-item').length > 1) {
btn.closest('.produk-item').remove();
updateAll();
}
};
});
}
function updateAll() {
let total = 0;
document.querySelectorAll('.produk-item').forEach(row => {
const harga = parseInt(row.querySelector('.harga').value || 0);
const qty = parseInt(row.querySelector('.qty').value || 0);
const subtotal = harga * qty;
row.querySelector('.subtotal').value = subtotal;
total += subtotal;
});
// Kondisional diskon
let diskon = 0;
if (total >= 100000) diskon = 15;
else if (total >= 50000) diskon = 10;
const potongan = Math.round(total * (diskon/100));
const akhir = total - potongan;
document.getElementById('displayTotal').value = formatRp(total);
document.getElementById('displayDiskon').value = diskon + '%';
document.getElementById('displayTotalAkhir').value = formatRp(akhir);
document.getElementById('totalRaw').value = total;
document.getElementById('diskonRaw').value = diskon;
}
// tambah produk
document.getElementById('addProduk').onclick = () => {
const wrapper = document.getElementById('produkWrapper');
const clone = wrapper.children[0].cloneNode(true);
// reset inputs
clone.querySelectorAll('input').forEach(i => i.value = '');
// set select to first
clone.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
wrapper.appendChild(clone);
attachHandlers();
updateAll();
};
// inisialisasi: set harga input sesuai select pertama
document.addEventListener('DOMContentLoaded', () => {
document.querySelectorAll('.nama-select').forEach(s => {
const row = s.closest('.produk-item');
const val = s.value.split('|');
row.querySelector('.harga').value = val[1];
});
attachHandlers();
updateAll();
});
</script>
</body>
</html>