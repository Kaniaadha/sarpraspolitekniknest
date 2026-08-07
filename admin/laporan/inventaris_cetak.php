<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../login.php");
    exit;
}

require_once "../../config/database.php";

$kategori = $_GET['kategori'] ?? '';
$kondisi  = $_GET['kondisi'] ?? '';

$where = [];

if (!empty($kategori)) {
    $kategori = mysqli_real_escape_string($conn, $kategori);
    $where[] = "k.id_kategori = '$kategori'";
}

if (!empty($kondisi)) {
    $kondisi = mysqli_real_escape_string($conn, $kondisi);
    $where[] = "i.kondisi = '$kondisi'";
}

$whereSQL = "";

if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

$query = mysqli_query($conn, "
SELECT
    i.kode_inventaris,
    i.nama_barang,
    i.jumlah,
    i.kondisi,
    k.nama_kategori,
    r.nama_ruangan,
    ps.nama_public_space
FROM inventaris i
LEFT JOIN kategori k
ON i.id_kategori = k.id_kategori
LEFT JOIN ruangan r
ON i.id_ruangan = r.id_ruangan
LEFT JOIN public_space ps
ON i.id_public_space = ps.id_public_space
$whereSQL
ORDER BY i.kode_inventaris ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak Laporan Inventaris</title>

<style>

body{
    font-family: Arial, Helvetica, sans-serif;
    font-size:12px;
    color:#000;
}

.header{
    text-align:center;
    margin-bottom:20px;
}

.header h2{
    margin:0;
}

.header p{
    margin:4px 0;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th,
table td{
    border:1px solid #000;
    padding:7px;
    font-size:11px;
}

th{
    background:#f0f0f0;
}

.text-center{
    text-align:center;
}

.footer{
    margin-top:40px;
    width:100%;
}

.ttd{
    float:right;
    text-align:center;
    width:250px;
}

</style>

</head>

<body>

<div class="header">

    <h2>POLITEKNIK NEST</h2>

    <p>
        Laporan Data Inventaris
    </p>

    <hr>

</div>

<table>

<thead>

<tr>

<th width="5%">No</th>
<th>Kode Inventaris</th>
<th>Nama Barang</th>
<th>Kategori</th>
<th>Lokasi</th>
<th width="8%">Jumlah</th>
<th width="12%">Kondisi</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

while($row=mysqli_fetch_assoc($query)):

$lokasi="-";

if(!empty($row['nama_ruangan'])){
    $lokasi=$row['nama_ruangan'];
}elseif(!empty($row['nama_public_space'])){
    $lokasi=$row['nama_public_space'];
}

?>

<tr>

<td class="text-center"><?= $no++; ?></td>

<td><?= htmlspecialchars($row['kode_inventaris']); ?></td>

<td><?= htmlspecialchars($row['nama_barang']); ?></td>

<td><?= htmlspecialchars($row['nama_kategori']); ?></td>

<td><?= htmlspecialchars($lokasi); ?></td>

<td class="text-center"><?= $row['jumlah']; ?></td>

<td class="text-center"><?= htmlspecialchars($row['kondisi']); ?></td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

<div class="footer">

<div class="ttd">

<p>Sukoharjo, <?= date('d F Y'); ?></p>

<br><br><br>

<p><b>Administrator</b></p>

</div>

</div>

<script>
window.print();
</script>

</body>
</html>