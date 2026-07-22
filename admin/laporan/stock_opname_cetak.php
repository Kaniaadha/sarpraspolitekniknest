<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../login.php");
    exit;
}

require_once "../../config/database.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: stock_opname.php");
    exit;
}

$id_stock_opname = mysqli_real_escape_string($conn, $_GET['id']);

$queryStockOpname = mysqli_query($conn, "
    SELECT
        so.*,
        a.nama_admin
    FROM stock_opname so
    JOIN admin a
        ON so.id_admin = a.id_admin
    WHERE so.id_stock_opname = '$id_stock_opname'
");

$stockOpname = mysqli_fetch_assoc($queryStockOpname);

if (!$stockOpname) {
    die("Data Stock Opname tidak ditemukan.");
}

$queryDetail = mysqli_query($conn, "
    SELECT
        dso.*,
        i.kode_inventaris,
        i.nama_barang
    FROM detail_stock_opname dso
    JOIN inventaris i
        ON dso.id_inventaris = i.id_inventaris
    WHERE dso.id_stock_opname = '$id_stock_opname'
    ORDER BY i.nama_barang ASC
");
?>

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<title>Cetak Laporan Stock Opname</title>

<style>

body{
    font-family:Arial,Helvetica,sans-serif;
    font-size:12px;
}

.header{
    text-align:center;
    margin-bottom:20px;
}

.header h2{
    margin:0;
}

.header p{
    margin:5px 0;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th,
table td{
    border:1px solid #000;
    padding:8px;
}

th{
    background:#efefef;
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

<p><strong>LAPORAN STOCK OPNAME</strong></p>

<hr>

<table style="width:60%; margin-bottom:20px; border:none;">
    <tr>
        <td style="border:none;"><b>Kode Stock Opname</b></td>
        <td style="border:none;">:</td>
        <td style="border:none;"><?= $stockOpname['kode_stock_opname']; ?></td>
    </tr>

    <tr>
        <td style="border:none;"><b>Tanggal</b></td>
        <td style="border:none;">:</td>
        <td style="border:none;">
            <?= date('d F Y', strtotime($stockOpname['tanggal'])); ?>
        </td>
    </tr>

    <tr>
        <td style="border:none;"><b>Petugas</b></td>
        <td style="border:none;">:</td>
        <td style="border:none;"><?= $stockOpname['nama_admin']; ?></td>
    </tr>

    <tr>
        <td style="border:none;"><b>Status</b></td>
        <td style="border:none;">:</td>
        <td style="border:none;"><?= $stockOpname['status']; ?></td>
    </tr>
</table>

</div>

<table>

<thead>

<tr>

<th width="5%">No</th>
<th>Kode Barang</th>
<th>Nama Barang</th>
<th>Stok Sistem</th>
<th>Stok Fisik</th>
<th>Selisih</th>
<th>Kondisi</th>
<th>Catatan</th>

</tr>

</thead>

<tbody>

<?php

$no = 1;

$no = 1;

while ($row = mysqli_fetch_assoc($queryDetail)) {
    echo "<tr>";

    echo "<td align='center'>{$no}</td>";
    echo "<td>{$row['kode_inventaris']}</td>";
    echo "<td>{$row['nama_barang']}</td>";
    echo "<td align='center'>{$row['stok_sistem']}</td>";
    echo "<td align='center'>{$row['stok_fisik']}</td>";
    echo "<td align='center'>{$row['selisih']}</td>";
    echo "<td>{$row['kondisi']}</td>";
    echo "<td>" . (!empty($row['catatan']) ? $row['catatan'] : '-') . "</td>";

    echo "</tr>";

    $no++;
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