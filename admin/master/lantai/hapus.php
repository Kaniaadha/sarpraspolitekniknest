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

$id_lantai = (int) $_GET['id'];

// ==============================
// Cek Data
// ==============================

$cek = mysqli_query($conn, "
    SELECT *
    FROM lantai
    WHERE id_lantai = '$id_lantai'
");

if (mysqli_num_rows($cek) == 0) {

    $_SESSION['error'] = "Data lantai tidak ditemukan.";

    header("Location: index.php");
    exit;
}
// ==============================
// Cek Relasi Ruangan
// ==============================

$cekRuangan = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM ruangan
    WHERE id_lantai = '$id_lantai'
");

$dataRuangan = mysqli_fetch_assoc($cekRuangan);

// ==============================
// Cek Relasi Public Space
// ==============================

$cekPublic = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM public_space
    WHERE id_lantai = '$id_lantai'
");

$dataPublic = mysqli_fetch_assoc($cekPublic);

if ($dataRuangan['total'] > 0 || $dataPublic['total'] > 0) {

    $_SESSION['error'] = "Data lantai tidak dapat dihapus karena masih memiliki ruangan atau public space.";

    header("Location: index.php");
    exit;
}
// ==============================
// Hapus Data
// ==============================

$query = mysqli_query($conn, "
    DELETE FROM lantai
    WHERE id_lantai = '$id_lantai'
");

// ==============================
// Hasil
// ==============================

if ($query) {

    $_SESSION['success'] = "Data lantai berhasil dihapus.";

} else {

    $_SESSION['error'] = "Data lantai gagal dihapus.";

}

header("Location: index.php");
exit;