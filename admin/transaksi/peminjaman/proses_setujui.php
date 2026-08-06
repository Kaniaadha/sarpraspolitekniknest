<?php
session_start();

// Cek login admin
if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";
require_once "../../../helpers/activity_log.php";

// Validasi ID peminjaman
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID peminjaman tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$id_peminjaman = (int) $_GET['id'];

// Mengambil data peminjaman
$queryPeminjaman = mysqli_query($conn, "
    SELECT
        id_peminjaman,
        kode_peminjaman,
        status
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

// Validasi status peminjaman
if ($peminjaman['status'] != 'Menunggu') {
    $_SESSION['error'] = "Hanya transaksi dengan status Menunggu yang dapat disetujui.";

    header("Location: detail.php?id=$id_peminjaman");
    exit;
}

mysqli_begin_transaction($conn);

try {

    // Mengambil detail barang yang dipinjam
    $queryDetail = mysqli_query($conn, "
        SELECT
            dp.id_inventaris,
            dp.jumlah,
            i.nama_barang,
            i.jumlah AS stok
        FROM detail_peminjaman dp
        INNER JOIN inventaris i
            ON dp.id_inventaris = i.id_inventaris
        WHERE dp.id_peminjaman = '$id_peminjaman'
    ");

    if (!$queryDetail) {
        throw new Exception("Gagal mengambil detail peminjaman.");
    }

    if (mysqli_num_rows($queryDetail) == 0) {
        throw new Exception("Data barang peminjaman tidak ditemukan.");
    }

    $detailBarang = [];

    // Validasi stok barang
    while ($detail = mysqli_fetch_assoc($queryDetail)) {

        $detailBarang[] = $detail;

        if ($detail['stok'] < $detail['jumlah']) {
            throw new Exception(
                "Stok barang <strong>" .
                htmlspecialchars($detail['nama_barang']) .
                "</strong> tidak mencukupi."
            );
        }

    }

    // Memperbarui status peminjaman
    $updatePeminjaman = mysqli_query($conn, "
        UPDATE peminjaman
        SET
            status = 'Dipinjam',
            updated_at = NOW()
        WHERE id_peminjaman = '$id_peminjaman'
    ");

    if (!$updatePeminjaman) {
        throw new Exception("Gagal memperbarui status peminjaman.");
    }

    mysqli_commit($conn);

    // Menyimpan activity log
    simpanActivityLog(
        $conn,
        $_SESSION['id_admin'],
        "Menyetujui Peminjaman",
        "peminjaman",
        $id_peminjaman
    );

    $_SESSION['success'] = "Peminjaman berhasil disetujui.";

    header("Location: detail.php?id=$id_peminjaman");
    exit;

} catch (Exception $e) {

    mysqli_rollback($conn);

    $_SESSION['error'] = $e->getMessage();

    header("Location: detail.php?id=$id_peminjaman");
    exit;
}