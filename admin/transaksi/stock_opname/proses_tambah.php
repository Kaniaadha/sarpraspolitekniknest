<?php

session_start();

// Cek login admin
if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";
require_once "../../../helpers/activity_log.php";

// Pastikan request berasal dari form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// Ambil data header
$kode_stock_opname = trim($_POST['kode_stock_opname'] ?? '');
$id_admin = (int) ($_POST['id_admin'] ?? 0);
$tanggal = $_POST['tanggal'] ?? date('Y-m-d');
$status = 'Draft';

// Ambil data detail
$id_inventaris = $_POST['id_inventaris'] ?? [];
$stok_sistem = $_POST['stok_sistem'] ?? [];
$stok_fisik = $_POST['stok_fisik'] ?? [];
$kondisi = $_POST['kondisi'] ?? [];
$catatan = $_POST['catatan'] ?? [];

// Validasi data utama
if (
    empty($kode_stock_opname) ||
    $id_admin <= 0 ||
    empty($id_inventaris)
) {
    $_SESSION['error'] = "Data Stock Opname tidak lengkap.";
    header("Location: index.php");
    exit;
}

// Pastikan jumlah data detail konsisten
$jumlahData = count($id_inventaris);

if (
    count($stok_sistem) !== $jumlahData ||
    count($stok_fisik) !== $jumlahData ||
    count($kondisi) !== $jumlahData ||
    count($catatan) !== $jumlahData
) {
    $_SESSION['error'] = "Data detail Stock Opname tidak lengkap.";
    header("Location: index.php");
    exit;
}

// Mulai transaction
mysqli_begin_transaction($conn);

try {

    /*
    |--------------------------------------------------------------------------
    | 1. Simpan data utama Stock Opname
    |--------------------------------------------------------------------------
    */

    $kode_stock_opname = mysqli_real_escape_string(
        $conn,
        $kode_stock_opname
    );

    $tanggal = mysqli_real_escape_string(
        $conn,
        $tanggal
    );

    $queryStockOpname = mysqli_query($conn, "
        INSERT INTO stock_opname
        (
            kode_stock_opname,
            id_admin,
            tanggal,
            status
        )
        VALUES
        (
            '$kode_stock_opname',
            '$id_admin',
            '$tanggal',
            '$status'
        )
    ");

    if (!$queryStockOpname) {
        throw new Exception(
            "Gagal menyimpan data Stock Opname."
        );
    }

    // Ambil ID Stock Opname yang baru dibuat
    $id_stock_opname = mysqli_insert_id($conn);


    /*
    |--------------------------------------------------------------------------
    | 2. Simpan detail Stock Opname
    |--------------------------------------------------------------------------
    */

    for ($i = 0; $i < $jumlahData; $i++) {

        $idInventaris = (int) $id_inventaris[$i];

        $stokSistem = (int) $stok_sistem[$i];
        $stokFisik = (int) $stok_fisik[$i];

        // Selisih = stok fisik - stok sistem
        $selisih = $stokFisik - $stokSistem;

        $kondisiBarang = mysqli_real_escape_string(
            $conn,
            trim($kondisi[$i])
        );

        $catatanBarang = trim($catatan[$i] ?? '');

        $catatanBarang = mysqli_real_escape_string(
            $conn,
            $catatanBarang
        );

        // Validasi stok fisik tidak boleh negatif
        if ($stokFisik < 0) {
            throw new Exception(
                "Stok fisik tidak boleh kurang dari 0."
            );
        }

        $queryDetail = mysqli_query($conn, "
            INSERT INTO detail_stock_opname
            (
                id_stock_opname,
                id_inventaris,
                stok_sistem,
                stok_fisik,
                selisih,
                kondisi,
                catatan
            )
            VALUES
            (
                '$id_stock_opname',
                '$idInventaris',
                '$stokSistem',
                '$stokFisik',
                '$selisih',
                '$kondisiBarang',
                '$catatanBarang'
            )
        ");

        if (!$queryDetail) {
            throw new Exception(
                "Gagal menyimpan detail Stock Opname."
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | 3. Simpan Activity Log
    |--------------------------------------------------------------------------
    */

    simpanActivityLog(
        $conn,
        $_SESSION['id_admin'],
        "Membuat Stock Opname",
        "stock_opname",
        $id_stock_opname
    );


    /*
    |--------------------------------------------------------------------------
    | 4. Semua berhasil → Commit
    |--------------------------------------------------------------------------
    */

    mysqli_commit($conn);

    $_SESSION['success'] =
        "Stock Opname berhasil disimpan sebagai Draft.";

    header(
        "Location: detail.php?id=" . $id_stock_opname
    );
    exit;


} catch (Exception $e) {

    /*
    |--------------------------------------------------------------------------
    | Jika terjadi error → Rollback
    |--------------------------------------------------------------------------
    */

    mysqli_rollback($conn);

    $_SESSION['error'] =
        $e->getMessage();

    header("Location: index.php");
    exit;
}