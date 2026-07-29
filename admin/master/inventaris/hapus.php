<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";
require_once "../foto/helper.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_inventaris = (int) $_GET['id'];
$uploadFolder = "../../../assets/uploads/inventaris/";
// ==============================
// Cek Data
// ==============================

$cek = mysqli_query($conn, "
    SELECT *
    FROM inventaris
    WHERE id_inventaris = '$id_inventaris'
");

if (mysqli_num_rows($cek) == 0) {

    $_SESSION['error'] = "Data Inventaris tidak ditemukan.";

    header("Location: index.php");
    exit;
}
$data = mysqli_fetch_assoc($cek);

// ==============================
// Hapus Data
// ==============================

$query = mysqli_query($conn, "
    DELETE FROM inventaris
    WHERE id_inventaris = '$id_inventaris'
");

// ==============================
// Hasil
// ==============================

if ($query) {
if (!empty($data['foto'])) {

    deletePhysicalFile(
        $uploadFolder . $data['foto']
    );
}
    $_SESSION['success'] = "Data Inventaris berhasil dihapus.";

} else {

    $_SESSION['error'] = "Data Inventaris gagal dihapus.";

}

header("Location: index.php");
exit;