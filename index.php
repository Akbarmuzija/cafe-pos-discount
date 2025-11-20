<?php
session_start();
include 'config.php';


if (isset($_SESSION['kasir'])) {
header('Location: dashboard.php');
exit;
}


$error = '';
if (isset($_POST['login'])) {
$user = mysqli_real_escape_string($koneksi, $_POST['username']);
$pass = mysqli_real_escape_string($koneksi, $_POST['password']);


$q = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$user' AND password='$pass'");
if (mysqli_num_rows($q) > 0) {
$_SESSION['kasir'] = $user;
header('Location: dashboard.php');
exit;
} else {
$error = 'Username atau password salah.';
}
}
?>


<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login - Kedai Yomijo</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar navbar-dark">
<div class="container">
<a class="navbar-brand" href="#">Kedai Yomijo</a>
</div>
</nav>


<div class="container mt-5">
<div class="card mx-auto" style="max-width:420px">
<div class="card-body">
<h4 class="card-title text-center title">Masuk - Kasir</h4>


<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>


<form method="POST">
<div class="mb-3">
<label class="form-label">Username</label>
<input name="username" class="form-control" required>
</div>
<div class="mb-3">
<label class="form-label">Password</label>
<input name="password" type="password" class="form-control" required>
</div>
<button class="btn btn-dark w-100" name="login">Masuk</button>
</form>


<hr>
<small class="text-muted">Login default: <b>kasir</b> / <b>12345</b></small>
</div>
</div>
</div>


</body>
</html>