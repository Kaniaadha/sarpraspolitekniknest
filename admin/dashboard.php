<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>

<h2>Login Berhasil 🎉</h2>

<p>Selamat datang,
    <b><?= $_SESSION['nama_admin']; ?></b>
</p>

<a href="../logout.php">Logout</a>

</body>
</html>