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

$id_public_space = (int) $_GET['id'];

// ==============================
// Cek Data
// ==============================

$cek = mysqli_query($conn, "
    SELECT *
    FROM public_space
    WHERE id_public_space = '$id_public_space'
");

if (mysqli_num_rows($cek) == 0) {

    $_SESSION['error'] = "Data Public Space tidak ditemukan.";

    header("Location: index.php");
    exit;
}
// ==============================
// Cek Relasi Inventaris
// ==============================

$cekRelasi = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM inventaris
    WHERE id_public_space = '$id_public_space'
");

$dataRelasi = mysqli_fetch_assoc($cekRelasi);

if ($dataRelasi['total'] > 0) {

    $_SESSION['error'] = "Data Public Space tidak dapat dihapus karena masih digunakan oleh inventaris.";

    header("Location: index.php");
    exit;
}
// ==============================
// Hapus Data
// ==============================

$query = mysqli_query($conn, "
    DELETE FROM public_space
    WHERE id_public_space = '$id_public_space'
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
            'Menghapus Public Space',
            'public_space',
            '$id_public_space'
        )
    ");

    $_SESSION['success'] = "Data Public Space berhasil dihapus.";

} else {

    $_SESSION['error'] = "Data Public Space gagal dihapus.";

}

header("Location: index.php");
exit;