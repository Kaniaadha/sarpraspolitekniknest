<?php

session_start();

if (!isset($_SESSION['id_admin'])) {

    header("Location: ../../../login.php");
    exit;

}

require_once "../../../config/database.php";

if (!isset($_GET['id'])) {

    $_SESSION['error'] = "ID peminjaman tidak ditemukan.";

    header("Location: index.php");
    exit;

}

$id_peminjaman = (int) $_GET['id'];

$query = mysqli_query($conn, "
    SELECT
        id_peminjaman,
        kode_peminjaman,
        status
    FROM peminjaman
    WHERE id_peminjaman = '$id_peminjaman'
    LIMIT 1
");

if (!$query || mysqli_num_rows($query) == 0) {

    $_SESSION['error'] = "Data peminjaman tidak ditemukan.";

    header("Location: index.php");
    exit;

}

$peminjaman = mysqli_fetch_assoc($query);

if ($peminjaman['status'] != 'Menunggu') {

    $_SESSION['error'] = "Transaksi hanya dapat dihapus saat status masih Menunggu.";

    header("Location: index.php");
    exit;

}

mysqli_begin_transaction($conn);

try {

    $deleteDetail = mysqli_query($conn, "
        DELETE FROM detail_peminjaman
        WHERE id_peminjaman = '$id_peminjaman'
    ");

    if (!$deleteDetail) {

        throw new Exception("Gagal menghapus detail peminjaman.");

    }

    $deletePeminjaman = mysqli_query($conn, "
        DELETE FROM peminjaman
        WHERE id_peminjaman = '$id_peminjaman'
    ");

    if (!$deletePeminjaman) {

        throw new Exception("Gagal menghapus data peminjaman.");

    }

    mysqli_commit($conn);

    $_SESSION['success'] = "Data peminjaman berhasil dihapus.";

    header("Location: index.php");
    exit;

} catch (Exception $e) {

    mysqli_rollback($conn);

    $_SESSION['error'] = $e->getMessage();

    header("Location: index.php");
    exit;

}