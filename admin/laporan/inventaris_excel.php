<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../login.php");
    exit;
}

require_once "../../config/database.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Inventaris_" . date("Ymd_His") . ".xls");

$kategori = $_GET['kategori'] ?? '';
$kondisi  = $_GET['kondisi'] ?? '';

$where = [];

if (!empty($kategori)) {
    $kategori = mysqli_real_escape_string($conn, $kategori);
    $where[] = "k.id_kategori='$kategori'";
}

if (!empty($kondisi)) {
    $kondisi = mysqli_real_escape_string($conn, $kondisi);
    $where[] = "i.kondisi='$kondisi'";
}

$whereSQL = "";

if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

$query = mysqli_query($conn,"
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
ON i.id_kategori=k.id_kategori

LEFT JOIN ruangan r
ON i.id_ruangan=r.id_ruangan

LEFT JOIN public_space ps
ON i.id_public_space=ps.id_public_space

$whereSQL

ORDER BY i.kode_inventaris ASC
");
?>

<h2>Laporan Inventaris</h2>

<table border="1">

<tr>
<th>No</th>
<th>Kode</th>
<th>Nama Barang</th>
<th>Kategori</th>
<th>Lokasi</th>
<th>Jumlah</th>
<th>Kondisi</th>
</tr>

<?php

$no=1;

while($row=mysqli_fetch_assoc($query)){

$lokasi="-";

if($row['nama_ruangan']!=""){
    $lokasi=$row['nama_ruangan'];
}elseif($row['nama_public_space']!=""){
    $lokasi=$row['nama_public_space'];
}

echo "<tr>
<td>".$no++."</td>
<td>".$row['kode_inventaris']."</td>
<td>".$row['nama_barang']."</td>
<td>".$row['nama_kategori']."</td>
<td>".$lokasi."</td>
<td>".$row['jumlah']."</td>
<td>".$row['kondisi']."</td>
</tr>";

}

?>

</table>