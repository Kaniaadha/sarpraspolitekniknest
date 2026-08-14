<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../login.php");
    exit;
}

require_once "../../config/database.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: stock_opname.php");
    exit;
}

$id_stock_opname = mysqli_real_escape_string($conn, $_GET['id']);

$queryStockOpname = mysqli_query($conn, "
    SELECT
        so.*,
        a.nama_admin
    FROM stock_opname so
    JOIN admin a
        ON so.id_admin = a.id_admin
    WHERE so.id_stock_opname = '$id_stock_opname'
");

$stockOpname = mysqli_fetch_assoc($queryStockOpname);

if (!$stockOpname) {
    die("Data Stock Opname tidak ditemukan.");
}

$queryDetail = mysqli_query($conn, "
    SELECT
        dso.*,
        i.kode_inventaris,
        i.nama_barang
    FROM detail_stock_opname dso
    JOIN inventaris i
        ON dso.id_inventaris = i.id_inventaris
    WHERE dso.id_stock_opname = '$id_stock_opname'
    ORDER BY i.nama_barang ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Stock Opname</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .header p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px;
        }

        th {
            background: #efefef;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 40px;
        }

        .ttd {
            width: 250px;
            float: right;
            text-align: center;
        }

        .info-table {
            width: 60%;
            margin-bottom: 20px;
            border: none;
        }

        .info-table td {
            border: none;
            padding: 3px;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header">
        <h2>POLITEKNIK NEST</h2>
        <p><strong>LAPORAN STOCK OPNAME</strong></p>
        <hr>
    </div>

    <!-- Informasi Stock Opname -->
    <table class="info-table">
        <tr>
            <td><b>Kode Stock Opname</b></td>
            <td>:</td>
            <td><?= htmlspecialchars($stockOpname['kode_stock_opname']); ?></td>
        </tr>

        <tr>
            <td><b>Tanggal</b></td>
            <td>:</td>
            <td>
                <?= date('d F Y', strtotime($stockOpname['tanggal'])); ?>
            </td>
        </tr>

        <tr>
            <td><b>Petugas</b></td>
            <td>:</td>
            <td><?= htmlspecialchars($stockOpname['nama_admin']); ?></td>
        </tr>

        <tr>
            <td><b>Status</b></td>
            <td>:</td>
            <td><?= htmlspecialchars($stockOpname['status']); ?></td>
        </tr>
    </table>

    <!-- Detail Stock Opname -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Stok Sistem</th>
                <th>Stok Fisik</th>
                <th>Selisih</th>
                <th>Kondisi</th>
                <th>Catatan</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $no = 1;

            while ($row = mysqli_fetch_assoc($queryDetail)) :
            ?>

                <tr>
                    <td class="text-center">
                        <?= $no++; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['kode_inventaris']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['nama_barang']); ?>
                    </td>

                    <td class="text-center">
                        <?= $row['stok_sistem']; ?>
                    </td>

                    <td class="text-center">
                        <?= $row['stok_fisik']; ?>
                    </td>

                    <td class="text-center">
                        <?= $row['selisih']; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['kondisi']); ?>
                    </td>

                    <td>
                        <?= !empty($row['catatan'])
                            ? htmlspecialchars($row['catatan'])
                            : '-'; ?>
                    </td>
                </tr>

            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Tanda Tangan -->
    <div class="footer">
        <div class="ttd">
            <p>Sukoharjo, <?= date('d F Y'); ?></p>
            <br><br><br>
            <p><b>Administrator</b></p>
        </div>
    </div>

    <script>
        window.print();
    </script>

</body>
</html>