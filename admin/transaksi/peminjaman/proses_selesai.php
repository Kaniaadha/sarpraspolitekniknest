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
if (!isset($_POST['id_peminjaman']) || empty($_POST['id_peminjaman'])) {
    $_SESSION['error'] = "Data peminjaman tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$id_peminjaman = (int) $_POST['id_peminjaman'];

// Mengambil data peminjaman
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

// Validasi status peminjaman
if ($peminjaman['status'] != "Dipinjam") {
    $_SESSION['error'] = "Status peminjaman tidak valid.";

    header("Location: detail.php?id=$id_peminjaman");
    exit;
}

// Mengambil data pengembalian
$id_detail = $_POST['id_detail'] ?? [];
$kondisi_sesudah = $_POST['kondisi_sesudah'] ?? [];
$catatan = $_POST['catatan'] ?? [];

if (empty($id_detail) || empty($kondisi_sesudah)) {
    $_SESSION['error'] = "Data pengembalian belum lengkap.";

    header("Location: pengembalian.php?id=$id_peminjaman");
    exit;
}

mysqli_begin_transaction($conn);

try {

    // Memperbarui detail pengembalian
    foreach ($id_detail as $index => $detail) {

        $detail = (int) $detail;

        $kondisi = mysqli_real_escape_string(
            $conn,
            trim($kondisi_sesudah[$index])
        );

        $catatanBarang = mysqli_real_escape_string(
            $conn,
            trim($catatan[$index])
        );

        $queryDetail = mysqli_query($conn, "
            SELECT
                dp.*,
                i.id_inventaris
            FROM detail_peminjaman dp
            INNER JOIN inventaris i
                ON dp.id_inventaris = i.id_inventaris
            WHERE dp.id_detail = '$detail'
            LIMIT 1
        ");

        if (!$queryDetail || mysqli_num_rows($queryDetail) == 0) {
            throw new Exception("Data detail barang tidak ditemukan.");
        }

        $updateDetail = mysqli_query($conn, "
            UPDATE detail_peminjaman
            SET
                kondisi_sesudah = '$kondisi',
                catatan = '$catatanBarang'
            WHERE id_detail = '$detail'
        ");

        if (!$updateDetail) {
            throw new Exception("Gagal memperbarui detail peminjaman.");
        }

    }

    // Memperbarui status dan tanggal peminjaman
    $updatePeminjaman = mysqli_query($conn, "
        UPDATE peminjaman
        SET
            status = 'Selesai',
            tanggal_pengembalian = CURDATE(),
            updated_at = NOW()
        WHERE id_peminjaman = '$id_peminjaman'
    ");

    if (!$updatePeminjaman) {
        throw new Exception("Gagal memperbarui status peminjaman.");
    }

    // Menyimpan activity log
    simpanActivityLog(
        $conn,
        $_SESSION['id_admin'],
        "Mengonfirmasi Pengembalian",
        "peminjaman",
        $id_peminjaman
    );

    mysqli_commit($conn);

    $_SESSION['success'] = "Pengembalian barang berhasil dikonfirmasi.";

} catch (Exception $e) {

    mysqli_rollback($conn);

    $_SESSION['error'] = $e->getMessage();

}

header("Location: detail.php?id=$id_peminjaman");
exit;