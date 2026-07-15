<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_kategori = (int) $_GET['id'];

// ==============================
// Cek Data
// ==============================

$cek = mysqli_query($conn, "
    SELECT *
    FROM kategori
    WHERE id_kategori = '$id_kategori'
");

if (mysqli_num_rows($cek) == 0) {

    $_SESSION['error'] = "Data Kategori tidak ditemukan.";

    header("Location: index.php");
    exit;
}

// ==============================
// Cek Apakah Masih Digunakan
// ==============================

$cekInventaris = mysqli_query($conn, "
    SELECT id_inventaris
    FROM inventaris
    WHERE id_kategori = '$id_kategori'
");

if (mysqli_num_rows($cekInventaris) > 0) {

    $_SESSION['error'] = "Kategori tidak dapat dihapus karena masih digunakan pada data inventaris.";

    header("Location: index.php");
    exit;
}

// ==============================
// Hapus Data
// ==============================

$query = mysqli_query($conn, "
    DELETE FROM kategori
    WHERE id_kategori = '$id_kategori'
");

// ==============================
// Hasil
// ==============================

if ($query) {

    $_SESSION['success'] = "Data Kategori berhasil dihapus.";

} else {

    $_SESSION['error'] = "Data Kategori gagal dihapus.";

}

header("Location: index.php");
exit;