<?php

/*
|--------------------------------------------------------------------------
| Inisialisasi
|--------------------------------------------------------------------------
*/

session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";
require_once "../../../helpers/activity_log.php";

/*
|--------------------------------------------------------------------------
| Validasi ID
|--------------------------------------------------------------------------
*/

if (!isset($_POST['id_peminjaman']) || empty($_POST['id_peminjaman'])) {
    $_SESSION['error'] = "Data peminjaman tidak ditemukan.";
    header("Location: index.php");
    exit;
}

$id_peminjaman = (int) $_POST['id_peminjaman'];

/*
|--------------------------------------------------------------------------
| Data Peminjaman
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Validasi Status
|--------------------------------------------------------------------------
*/

if ($peminjaman['status'] != "Dipinjam") {
    $_SESSION['error'] = "Status peminjaman tidak valid.";
    header("Location: detail.php?id=" . $id_peminjaman);
    exit;
}

/*
|--------------------------------------------------------------------------
| Detail Barang
|--------------------------------------------------------------------------
*/

$id_detail = $_POST['id_detail'] ?? [];
$kondisi_sesudah = $_POST['kondisi_sesudah'] ?? [];
$catatan = $_POST['catatan'] ?? [];

if (empty($id_detail) || empty($kondisi_sesudah)) {
    $_SESSION['error'] = "Data pengembalian belum lengkap.";
    header("Location: pengembalian.php?id=" . $id_peminjaman);
    exit;
}

/*
|--------------------------------------------------------------------------
| Proses Pengembalian
|--------------------------------------------------------------------------
*/

mysqli_begin_transaction($conn);

try {

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

        /*
        |--------------------------------------------------------------------------
        | Data Detail Barang
        |--------------------------------------------------------------------------
        */

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

        $dataDetail = mysqli_fetch_assoc($queryDetail);

        /*
        |--------------------------------------------------------------------------
        | Update Detail Peminjaman
        |--------------------------------------------------------------------------
        */

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

    /*
    |--------------------------------------------------------------------------
    | Update Status Peminjaman
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Activity Log
    |--------------------------------------------------------------------------
    */

    simpanActivityLog(
        $conn,
        $_SESSION['id_admin'],
        "Mengonfirmasi Pengembalian Peminjaman",
        "Kode Peminjaman : " . $peminjaman['kode_peminjaman']
    );

    /*
    |--------------------------------------------------------------------------
    | Commit
    |--------------------------------------------------------------------------
    */

    mysqli_commit($conn);

    $_SESSION['success'] = "Pengembalian barang berhasil dikonfirmasi.";

} catch (Exception $e) {

    /*
    |--------------------------------------------------------------------------
    | Rollback
    |--------------------------------------------------------------------------
    */

    mysqli_rollback($conn);

    $_SESSION['error'] = $e->getMessage();

}

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: detail.php?id=" . $id_peminjaman);
exit;