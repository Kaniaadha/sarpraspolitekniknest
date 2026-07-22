<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../login.php");
    exit;
}

require_once "../../config/database.php";

$tanggalAwal  = $_GET['tanggal_awal'] ?? '';
$tanggalAkhir = $_GET['tanggal_akhir'] ?? '';
$status       = $_GET['status'] ?? '';

$where = [];

if (!empty($tanggalAwal)) {
    $where[] = "DATE(p.tanggal_pinjam) >= '$tanggalAwal'";
}

if (!empty($tanggalAkhir)) {
    $where[] = "DATE(p.tanggal_pinjam) <= '$tanggalAkhir'";
}

if (!empty($status)) {
    $status = mysqli_real_escape_string($conn, $status);
    $where[] = "p.status = '$status'";
}

$whereSQL = "";

if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

$query = mysqli_query($conn,"
SELECT
    p.kode_peminjaman,
    p.nama_peminjam,
    p.tanggal_pinjam,
    p.tanggal_kembali,
    p.status
FROM peminjaman p
$whereSQL
ORDER BY p.tanggal_pinjam DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<title>Cetak Laporan Peminjaman</title>

<style>

body{
    font-family:Arial,Helvetica,sans-serif;
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
}

th{
    background:#efefef;
}

.text-center{
    text-align:center;
}

.footer{
    margin-top:40px;
}

.ttd{
    width:250px;
    float:right;
    text-align:center;
}

</style>

</head>

<body>

<div class="header">

<h2>POLITEKNIK NEST</h2>

<p><strong>LAPORAN PEMINJAMAN INVENTARIS</strong></p>

<hr>

</div>

<table>

<thead>

<tr>

<th width="5%">No</th>
<th>Kode Peminjaman</th>
<th>Nama Peminjam</th>
<th>Tanggal Pinjam</th>
<th>Tanggal Kembali</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

while($row=mysqli_fetch_assoc($query)){

echo "<tr>";

echo "<td align='center'>".$no++."</td>";

echo "<td>".$row['kode_peminjaman']."</td>";

echo "<td>".$row['nama_peminjam']."</td>";

echo "<td align='center'>".date('d-m-Y',strtotime($row['tanggal_pinjam']))."</td>";

echo "<td align='center'>".date('d-m-Y',strtotime($row['tanggal_kembali']))."</td>";

echo "<td align='center'>".$row['status']."</td>";

echo "</tr>";

}

?>

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