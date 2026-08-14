<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../login.php");
    exit;
}

require_once "../../config/database.php";

$tanggalAwal = $_GET['tanggal_awal'] ?? '';
$tanggalAkhir = $_GET['tanggal_akhir'] ?? '';
$status = $_GET['status'] ?? '';

$where = [];

if (!empty($tanggalAwal)) {
    $tanggalAwal = mysqli_real_escape_string($conn, $tanggalAwal);
    $where[] = "DATE(p.tanggal_pinjam) >= '$tanggalAwal'";
}

if (!empty($tanggalAkhir)) {
    $tanggalAkhir = mysqli_real_escape_string($conn, $tanggalAkhir);
    $where[] = "DATE(p.tanggal_pinjam) <= '$tanggalAkhir'";
}

if (!empty($status)) {
    $status = mysqli_real_escape_string($conn, $status);
    $where[] = "p.status = '$status'";
}

$whereSQL = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

$query = mysqli_query($conn, "
    SELECT
        p.kode_peminjaman,
        p.nama_peminjam,
        p.tanggal_pinjam,
        p.tanggal_kembali,
        p.status
    FROM peminjaman p
    $whereSQL
    ORDER BY p.tanggal_pinjam DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Peminjaman</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .header p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 7px;
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
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header">
        <h2>POLITEKNIK NEST</h2>
        <p><strong>LAPORAN PEMINJAMAN INVENTARIS</strong></p>
        <hr>
    </div>

    <!-- Data -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Kode Peminjaman</th>
                <th>Nama Peminjam</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $no = 1;

            if (mysqli_num_rows($query) > 0) :
                while ($row = mysqli_fetch_assoc($query)) :
            ?>

                <tr>
                    <td class="text-center"><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['kode_peminjaman']); ?></td>
                    <td><?= htmlspecialchars($row['nama_peminjam']); ?></td>
                    <td class="text-center">
                        <?= date('d-m-Y', strtotime($row['tanggal_pinjam'])); ?>
                    </td>
                    <td class="text-center">
                        <?= date('d-m-Y', strtotime($row['tanggal_kembali'])); ?>
                    </td>
                    <td class="text-center">
                        <?= htmlspecialchars($row['status']); ?>
                    </td>
                </tr>

            <?php
                endwhile;
            else :
            ?>

                <tr>
                    <td colspan="6" class="text-center">
                        Tidak ada data peminjaman yang sesuai dengan filter.
                    </td>
                </tr>

            <?php endif; ?>
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