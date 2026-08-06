<?php
session_start();

// Cek login admin
if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";
require_once "../../../helpers/activity_log.php";

// Validasi ID stock opname
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: riwayat.php");
    exit;
}

$id_stock_opname = mysqli_real_escape_string($conn, $_GET['id']);

// Mengambil data stock opname
$queryStockOpname = mysqli_query($conn, "
    SELECT *
    FROM stock_opname
    WHERE id_stock_opname = '$id_stock_opname'
");

$stockOpname = mysqli_fetch_assoc($queryStockOpname);

if (!$stockOpname) {
    header("Location: riwayat.php");
    exit;
}

if ($stockOpname['status'] == 'Selesai') {
    header("Location: detail.php?id=$id_stock_opname");
    exit;
}

mysqli_begin_transaction($conn);

// Mengambil detail stock opname
$queryDetail = mysqli_query($conn, "
    SELECT
        dso.id_inventaris,
        dso.stok_fisik,
        dso.kondisi
    FROM detail_stock_opname dso
    WHERE dso.id_stock_opname = '$id_stock_opname'
");

if (!$queryDetail) {

    mysqli_rollback($conn);

    echo "
        <script>
            alert('Data detail Stock Opname tidak ditemukan.');
            window.location='detail.php?id=$id_stock_opname';
        </script>
    ";

    exit;
}

if (mysqli_num_rows($queryDetail) == 0) {

    mysqli_rollback($conn);

    echo "
        <script>
            alert('Belum ada data detail Stock Opname.');
            window.location='detail.php?id=$id_stock_opname';
        </script>
    ";

    exit;
}

// Memperbarui data inventaris
while ($detail = mysqli_fetch_assoc($queryDetail)) {

    $idInventaris = $detail['id_inventaris'];
    $stokFisik = $detail['stok_fisik'];

    $kondisi = mysqli_real_escape_string(
        $conn,
        $detail['kondisi']
    );

    $queryUpdateInventaris = mysqli_query($conn, "
        UPDATE inventaris
        SET
            jumlah = '$stokFisik',
            kondisi = '$kondisi'
        WHERE id_inventaris = '$idInventaris'
    ");

    if (!$queryUpdateInventaris) {

        mysqli_rollback($conn);

        echo "
            <script>
                alert('Gagal memperbarui data inventaris.');
                window.location='detail.php?id=$id_stock_opname';
            </script>
        ";

        exit;
    }
}

// Mengubah status stock opname
$queryUpdateStatus = mysqli_query($conn, "
    UPDATE stock_opname
    SET status = 'Selesai'
    WHERE id_stock_opname = '$id_stock_opname'
");

if (!$queryUpdateStatus) {

    mysqli_rollback($conn);

    $_SESSION['error'] = "Gagal menyelesaikan Stock Opname.";

    header("Location: detail.php?id=" . $id_stock_opname);
    exit;
}

mysqli_commit($conn);

// Menyimpan activity log
simpanActivityLog(
    $conn,
    $_SESSION['id_admin'],
    "Menyelesaikan Stock Opname",
    "stock_opname",
    $id_stock_opname
);

$_SESSION['success'] = "Stock Opname berhasil diselesaikan.";

header("Location: detail.php?id=" . $id_stock_opname);
exit;