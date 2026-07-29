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
// Ambil Data Inventaris
// ==============================

$query = mysqli_query($conn, "
    SELECT foto
    FROM inventaris
    WHERE id_inventaris = '$id_inventaris'
");

if (mysqli_num_rows($query) == 0) {

    $_SESSION['error'] = "Data Inventaris tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// ==============================
// Tidak ada foto
// ==============================

if (empty($data['foto'])) {

    $_SESSION['error'] = "Foto Inventaris tidak ditemukan.";

    header("Location: edit.php?id=$id_inventaris");
    exit;
}

// ==============================
// Hapus File Fisik
// ==============================

deletePhysicalFile(
    $uploadFolder . $data['foto']
);

// ==============================
// Update Database
// ==============================

$update = mysqli_query($conn, "
    UPDATE inventaris
    SET
        foto = NULL,
        updated_at = NOW()
    WHERE id_inventaris = '$id_inventaris'
");

if ($update) {

    $_SESSION['success'] = "Foto Inventaris berhasil dihapus.";

} else {

    $_SESSION['error'] = "Foto Inventaris gagal dihapus.";

}

header("Location: edit.php?id=$id_inventaris");
exit;