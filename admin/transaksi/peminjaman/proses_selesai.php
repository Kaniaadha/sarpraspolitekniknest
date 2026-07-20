<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Data peminjaman tidak ditemukan.";
    header("Location: index.php");
    exit;
}

$id_peminjaman = (int) $_GET['id'];

$queryPeminjaman = mysqli_query($conn, "
    SELECT *
    FROM peminjaman
    WHERE id_peminjaman = '$id_peminjaman'
    LIMIT 1
");

if (!$queryPeminjaman || mysqli_num_rows($queryPeminjaman) == 0) {
    $_SESSION['error'] = "Data peminjaman tidak ditemukan.";
    header("Location: index.php");
    exit;
}

$peminjaman = mysqli_fetch_assoc($queryPeminjaman);

if ($peminjaman['status'] != 'Menunggu Pengembalian') {
    $_SESSION['error'] = "Pengembalian belum diajukan oleh peminjam.";
    header("Location: detail.php?id=" . $id_peminjaman);
    exit;
}

mysqli_begin_transaction($conn);

try {

    $queryDetail = mysqli_query($conn, "
        SELECT *
        FROM detail_peminjaman
        WHERE id_peminjaman = '$id_peminjaman'
    ");

    while ($detail = mysqli_fetch_assoc($queryDetail)) {

        $id_inventaris = $detail['id_inventaris'];
        $jumlah = $detail['jumlah'];

        $updateStok = mysqli_query($conn, "
            UPDATE inventaris
            SET jumlah = jumlah + '$jumlah',
                updated_at = NOW()
            WHERE id_inventaris = '$id_inventaris'
        ");

        if (!$updateStok) {
            throw new Exception("Gagal mengembalikan stok inventaris.");
        }
    }

    $updatePeminjaman = mysqli_query($conn, "
        UPDATE peminjaman
        SET
            status = 'Selesai',
            updated_at = NOW()
        WHERE id_peminjaman = '$id_peminjaman'
    ");

    if (!$updatePeminjaman) {
        throw new Exception("Gagal memperbarui status peminjaman.");
    }

    mysqli_commit($conn);

    $_SESSION['success'] = "Pengembalian berhasil dikonfirmasi.";

} catch (Exception $e) {

    mysqli_rollback($conn);

    $_SESSION['error'] = $e->getMessage();
}

header("Location: detail.php?id=" . $id_peminjaman);
exit;