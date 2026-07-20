<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";


if ($_SERVER['REQUEST_METHOD'] != 'POST') {

    header("Location: index.php");
    exit;

}


$kode_stock_opname = mysqli_real_escape_string(
    $conn,
    $_POST['kode_stock_opname']
);

$id_admin = mysqli_real_escape_string(
    $conn,
    $_POST['id_admin']
);

$tanggal = mysqli_real_escape_string(
    $conn,
    $_POST['tanggal']
);

$status = mysqli_real_escape_string(
    $conn,
    $_POST['status']
);


$id_inventaris = $_POST['id_inventaris'];

$stok_sistem = $_POST['stok_sistem'];

$stok_fisik = $_POST['stok_fisik'];

$kondisi = $_POST['kondisi'];

$catatan = $_POST['catatan'];


if (
    empty($id_inventaris) ||
    empty($stok_sistem) ||
    empty($stok_fisik)
) {

    echo "
        <script>

            alert('Data Stock Opname tidak lengkap!');

            window.location='index.php';

        </script>
    ";

    exit;

}


mysqli_begin_transaction($conn);


$queryStockOpname = mysqli_query($conn, "
    INSERT INTO stock_opname (
        kode_stock_opname,
        id_admin,
        tanggal,
        status
    ) VALUES (
        '$kode_stock_opname',
        '$id_admin',
        '$tanggal',
        '$status'
    )
");


if (!$queryStockOpname) {

    mysqli_rollback($conn);

    echo "
        <script>

            alert('Gagal menyimpan data Stock Opname!');

            window.location='index.php';

        </script>
    ";

    exit;

}


$id_stock_opname = mysqli_insert_id($conn);


for ($i = 0; $i < count($id_inventaris); $i++) {

    $idInventaris = mysqli_real_escape_string(
        $conn,
        $id_inventaris[$i]
    );

    $stokSistem = mysqli_real_escape_string(
        $conn,
        $stok_sistem[$i]
    );

    $stokFisik = mysqli_real_escape_string(
        $conn,
        $stok_fisik[$i]
    );

    $kondisiBarang = mysqli_real_escape_string(
        $conn,
        $kondisi[$i]
    );

    $catatanBarang = mysqli_real_escape_string(
        $conn,
        $catatan[$i]
    );

    $selisih = $stokFisik - $stokSistem;

    $queryDetail = mysqli_query($conn, "
        INSERT INTO detail_stock_opname (
            id_stock_opname,
            id_inventaris,
            stok_sistem,
            stok_fisik,
            selisih,
            kondisi,
            catatan
        ) VALUES (
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

        mysqli_rollback($conn);

        echo "
            <script>

                alert('Gagal menyimpan detail Stock Opname!');

                window.location='index.php';

            </script>
        ";

        exit;

    }

}


mysqli_commit($conn);


echo "
    <script>

        alert('Stock Opname berhasil disimpan.');

        window.location='detail.php?id=$id_stock_opname';

    </script>
";