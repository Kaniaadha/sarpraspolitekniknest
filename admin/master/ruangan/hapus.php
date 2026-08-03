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

$id_ruangan = (int) $_GET['id'];

// ==============================
// Cek Data
// ==============================

$cek = mysqli_query($conn, "
    SELECT *
    FROM ruangan
    WHERE id_ruangan = '$id_ruangan'
");

if (mysqli_num_rows($cek) == 0) {

    $_SESSION['error'] = "Data ruangan tidak ditemukan.";

    header("Location: index.php");
    exit;
}
// ==============================
// Cek Relasi Inventaris
// ==============================

$cekRelasi = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM inventaris
    WHERE id_ruangan = '$id_ruangan'
");

$dataRelasi = mysqli_fetch_assoc($cekRelasi);

if ($dataRelasi['total'] > 0) {

    $_SESSION['error'] = "Data ruangan tidak dapat dihapus karena masih digunakan oleh inventaris.";

    header("Location: index.php");
    exit;
}
// ==============================
// Hapus Data
// ==============================

$query = mysqli_query($conn, "
    DELETE FROM ruangan
    WHERE id_ruangan = '$id_ruangan'
");

// ==============================
// Hasil
// ==============================

if ($query) {

    mysqli_query($conn, "
        INSERT INTO activity_log
        (
            id_admin,
            aktivitas,
            tabel_terkait,
            id_data
        )
        VALUES
        (
            '{$_SESSION['id_admin']}',
            'Menghapus Ruangan',
            'ruangan',
            '$id_ruangan'
        )
    ");

    $_SESSION['success'] = "Data ruangan berhasil dihapus.";

} else {

    $_SESSION['error'] = "Data ruangan gagal dihapus.";

}

header("Location: index.php");
exit;