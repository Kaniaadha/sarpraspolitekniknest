<?php

session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";
require_once "../../../helpers/activity_log.php";


// =====================================================
// CEK ID
// =====================================================

if (!isset($_GET['id'])) {

    $_SESSION['error'] = "ID admin tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$id_admin = (int) $_GET['id'];


// =====================================================
// CEK ADMIN YANG SEDANG LOGIN
// =====================================================

if ($id_admin === (int) $_SESSION['id_admin']) {

    $_SESSION['error'] =
        "Admin yang sedang login tidak dapat dihapus.";

    header("Location: index.php");
    exit;
}


// =====================================================
// CEK DATA ADA ATAU TIDAK
// =====================================================

$cek = mysqli_query($conn, "
    SELECT *
    FROM admin
    WHERE id_admin = '$id_admin'
");

if (!$cek) {

    $_SESSION['error'] =
        "Terjadi kesalahan saat memeriksa data admin.";

    header("Location: index.php");
    exit;
}


if (mysqli_num_rows($cek) == 0) {

    $_SESSION['error'] =
        "Data admin tidak ditemukan.";

    header("Location: index.php");
    exit;
}


// =====================================================
// HAPUS DATA ADMIN
// =====================================================

$query = mysqli_query($conn, "
    DELETE FROM admin
    WHERE id_admin = '$id_admin'
");


if ($query) {

    // =================================================
    // SIMPAN ACTIVITY LOG
    // =================================================

    simpanActivityLog(
        $conn,
        $_SESSION['id_admin'],
        "Menghapus Admin",
        "admin",
        $id_admin
    );


    $_SESSION['success'] =
        "Data admin berhasil dihapus.";

} else {

    $_SESSION['error'] =
        "Data admin gagal dihapus.";

}


// =====================================================
// KEMBALI KE INDEX
// =====================================================

header("Location: index.php");
exit;