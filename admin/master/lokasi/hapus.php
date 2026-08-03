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

$id_lokasi = (int) $_GET['id'];

// ==============================
// Cek Data
// ==============================

$cek = mysqli_query($conn, "
    SELECT *
    FROM lokasi
    WHERE id_lokasi = '$id_lokasi'
");

if (mysqli_num_rows($cek) == 0) {

    $_SESSION['error'] = "Data lokasi tidak ditemukan.";

    header("Location: index.php");
    exit;
}
// ==============================
// Cek Relasi Lantai
// ==============================

$cekRelasi = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM lantai
    WHERE id_lokasi = '$id_lokasi'
");

$dataRelasi = mysqli_fetch_assoc($cekRelasi);

if ($dataRelasi['total'] > 0) {

    $_SESSION['error'] = "Data lokasi tidak dapat dihapus karena masih memiliki data lantai.";

    header("Location: index.php");
    exit;
}
// ==============================
// Hapus Data
// ==============================

$query = mysqli_query($conn, "
    DELETE FROM lokasi
    WHERE id_lokasi = '$id_lokasi'
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
            'Menghapus Lokasi',
            'lokasi',
            '$id_lokasi'
        )
    ");

    $_SESSION['success'] = "Data lokasi berhasil dihapus.";

} else {

    $_SESSION['error'] = "Data lokasi gagal dihapus.";

}

header("Location: index.php");
exit;