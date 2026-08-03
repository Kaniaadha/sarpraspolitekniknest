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

$id_admin = (int) $_GET['id'];

// Cek apakah data ada
$cek = mysqli_query($conn, "
    SELECT *
    FROM admin
    WHERE id_admin = '$id_admin'
");

if (mysqli_num_rows($cek) == 0) {

    $_SESSION['error'] = "Data admin tidak ditemukan.";

    header("Location: index.php");
    exit;
}

// Hapus data
$query = mysqli_query($conn, "
    DELETE FROM admin
    WHERE id_admin = '$id_admin'
");

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
            'Menghapus Admin',
            'admin',
            '$id_admin'
        )
    ");

    $_SESSION['success'] = "Data admin berhasil dihapus.";

} else {

    $_SESSION['error'] = "Data admin gagal dihapus.";

}

header("Location: index.php");
exit;