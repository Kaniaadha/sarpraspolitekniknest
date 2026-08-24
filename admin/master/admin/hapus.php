<?php

session_start();


// =====================================================
// CEK LOGIN
// =====================================================

if (!isset($_SESSION['id_admin'])) {

    header("Location: ../../../login.php");
    exit;

}


// =====================================================
// DATABASE
// =====================================================

require_once "../../../config/database.php";
require_once "../../../helpers/activity_log.php";


// =====================================================
// CEK ID ADMIN
// =====================================================

if (!isset($_GET['id']) || empty($_GET['id'])) {

    $_SESSION['error'] =
        "ID admin tidak ditemukan.";

    header("Location: index.php");
    exit;

}


$id_admin = (int) $_GET['id'];


// =====================================================
// CEK ADMIN YANG SEDANG LOGIN
// =====================================================

if ($id_admin === (int) $_SESSION['id_admin']) {

    $_SESSION['error'] =
        "Admin yang sedang login tidak dapat dihapus. "
        . "Silakan gunakan akun admin lain.";

    header("Location: index.php");
    exit;

}


// =====================================================
// CEK APAKAH ADMIN ADA
// =====================================================

$cekAdmin = mysqli_query($conn, "
    SELECT
        id_admin,
        nama_admin
    FROM admin
    WHERE id_admin = '$id_admin'
");


if (!$cekAdmin) {

    $_SESSION['error'] =
        "Terjadi kesalahan saat memeriksa data admin.";

    header("Location: index.php");
    exit;

}


if (mysqli_num_rows($cekAdmin) === 0) {

    $_SESSION['error'] =
        "Data admin tidak ditemukan.";

    header("Location: index.php");
    exit;

}


$admin = mysqli_fetch_assoc($cekAdmin);

$nama_admin =
    $admin['nama_admin'];


// =====================================================
// CEK ACTIVITY LOG
// =====================================================

$cekActivity = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM activity_log
    WHERE id_admin = '$id_admin'
");


if (!$cekActivity) {

    $_SESSION['error'] =
        "Gagal memeriksa riwayat aktivitas admin.";

    header("Location: index.php");
    exit;

}


$dataActivity =
    mysqli_fetch_assoc($cekActivity);

$totalActivity =
    (int) $dataActivity['total'];


// =====================================================
// CEK RIWAYAT STOCK OPNAME
// =====================================================

$cekStockOpname = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM stock_opname
    WHERE id_admin = '$id_admin'
");


if (!$cekStockOpname) {

    $_SESSION['error'] =
        "Gagal memeriksa riwayat stock opname admin.";

    header("Location: index.php");
    exit;

}


$dataStockOpname =
    mysqli_fetch_assoc($cekStockOpname);

$totalStockOpname =
    (int) $dataStockOpname['total'];


// =====================================================
// ADMIN PUNYA RIWAYAT
// =====================================================

if (
    $totalActivity > 0 ||
    $totalStockOpname > 0
) {

    $alasan = [];


    if ($totalActivity > 0) {

        $alasan[] =
            "activity log";

    }


    if ($totalStockOpname > 0) {

        $alasan[] =
            "riwayat stock opname";

    }


    $alasanText =
        implode(" dan ", $alasan);


    $_SESSION['error'] =
        "Admin \""
        . $nama_admin
        . "\" tidak dapat dihapus karena memiliki "
        . $alasanText
        . ". Silakan nonaktifkan admin tersebut jika sudah tidak digunakan.";


    header("Location: index.php");
    exit;

}


// =====================================================
// HAPUS ADMIN
// =====================================================

$queryDelete = mysqli_query($conn, "
    DELETE FROM admin
    WHERE id_admin = '$id_admin'
");


if (!$queryDelete) {

    $_SESSION['error'] =
        "Data admin gagal dihapus.";

    header("Location: index.php");
    exit;

}


// =====================================================
// ACTIVITY LOG
// =====================================================

simpanActivityLog(
    $conn,
    $_SESSION['id_admin'],
    "Menghapus Admin",
    "admin",
    $id_admin
);


// =====================================================
// PESAN BERHASIL
// =====================================================

$_SESSION['success'] =
    "Admin \""
    . $nama_admin
    . "\" berhasil dihapus.";


// =====================================================
// KEMBALI
// =====================================================

header("Location: index.php");
exit;

?>