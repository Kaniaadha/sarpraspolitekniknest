<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";
require_once "../../../helpers/activity_log.php";
require_once "../foto/helper.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_inventaris = (int) $_GET['id'];
$uploadFolder = "../../../assets/uploads/inventaris/";

// ==============================
// Cek Data Inventaris
// ==============================

$cek = mysqli_query($conn, "
    SELECT *
    FROM inventaris
    WHERE id_inventaris = '$id_inventaris'
");

if (mysqli_num_rows($cek) == 0) {

    $_SESSION['error'] = "Data Inventaris tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($cek);


// ==============================
// Cek Riwayat Inventaris
// ==============================

$cekRiwayat = mysqli_query($conn, "
    SELECT
        (
            SELECT COUNT(*)
            FROM detail_peminjaman
            WHERE id_inventaris = '$id_inventaris'
        ) AS peminjaman,

        (
            SELECT COUNT(*)
            FROM detail_kerusakan
            WHERE id_inventaris = '$id_inventaris'
        ) AS kerusakan,

        (
            SELECT COUNT(*)
            FROM detail_kehilangan
            WHERE id_inventaris = '$id_inventaris'
        ) AS kehilangan,

        (
            SELECT COUNT(*)
            FROM detail_stock_opname
            WHERE id_inventaris = '$id_inventaris'
        ) AS stock_opname
");

$riwayat = mysqli_fetch_assoc($cekRiwayat);

$adaRiwayat =
    ($riwayat['peminjaman'] > 0) ||
    ($riwayat['kerusakan'] > 0) ||
    ($riwayat['kehilangan'] > 0) ||
    ($riwayat['stock_opname'] > 0);


// ==============================
// Jika Ada Riwayat
// ==============================

if ($adaRiwayat) {

    $query = mysqli_query($conn, "
        UPDATE inventaris
        SET
            status = 'Nonaktif',
            updated_at = NOW()
        WHERE id_inventaris = '$id_inventaris'
    ");

    if ($query) {

        simpanActivityLog(
            $conn,
            $_SESSION['id_admin'],
            "Menonaktifkan Inventaris",
            "inventaris",
            $id_inventaris
        );

        $_SESSION['success'] =
            "Inventaris memiliki riwayat transaksi sehingga tidak dihapus permanen. Data berhasil dinonaktifkan.";

    } else {

        $_SESSION['error'] =
            "Data Inventaris gagal dinonaktifkan.";
    }


// ==============================
// Jika Tidak Ada Riwayat
// ==============================

} else {

    $query = mysqli_query($conn, "
        DELETE FROM inventaris
        WHERE id_inventaris = '$id_inventaris'
    ");

    if ($query) {

        // Hapus foto fisik jika ada
        if (!empty($data['foto'])) {

            deletePhysicalFile(
                $uploadFolder . $data['foto']
            );
        }

        simpanActivityLog(
            $conn,
            $_SESSION['id_admin'],
            "Menghapus Inventaris",
            "inventaris",
            $id_inventaris
        );

        $_SESSION['success'] =
            "Data Inventaris berhasil dihapus.";

    } else {

        $_SESSION['error'] =
            "Data Inventaris gagal dihapus.";
    }
}


// ==============================
// Kembali ke Halaman Inventaris
// ==============================

header("Location: index.php");
exit;