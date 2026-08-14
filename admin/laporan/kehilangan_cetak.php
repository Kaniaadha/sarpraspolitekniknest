<?php
session_start();

$menu = "laporan";

// Cek login admin
if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../login.php");
    exit;
}

require_once "../../config/database.php";

// Filter tanggal
$tanggalAwal = $_GET['tanggal_awal'] ?? '';
$tanggalAkhir = $_GET['tanggal_akhir'] ?? '';

$where = [];

if ($tanggalAwal !== '') {
    $tanggalAwal = mysqli_real_escape_string($conn, $tanggalAwal);
    $where[] = "k.tanggal_lapor >= '$tanggalAwal'";
}

if ($tanggalAkhir !== '') {
    $tanggalAkhir = mysqli_real_escape_string($conn, $tanggalAkhir);
    $where[] = "k.tanggal_lapor <= '$tanggalAkhir'";
}

$whereSQL = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// Mengambil data laporan kehilangan
$query = mysqli_query($conn, "
    SELECT
        k.kode_kehilangan,
        k.tanggal_lapor,
        k.nama_pelapor,
        k.catatan_admin,
        dk.lokasi_kehilangan,
        dk.kronologi,
        i.kode_inventaris,
        i.nama_barang
    FROM kehilangan k
    INNER JOIN detail_kehilangan dk
        ON k.id_kehilangan = dk.id_kehilangan
    INNER JOIN inventaris i
        ON dk.id_inventaris = i.id_inventaris
    $whereSQL
    ORDER BY k.tanggal_lapor DESC, k.id_kehilangan DESC
");

if (!$query) {
    die("Query Error: " . mysqli_error($conn));
}

$totalData = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Kehilangan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h2 {
            margin: 0 0 5px;
            font-size: 20px;
        }

        .header p {
            margin: 3px 0;
        }

        .periode {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 7px;
            vertical-align: top;
        }

        th {
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
        }

        @media print {
            body {
                margin: 15px;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>LAPORAN KEHILANGAN</h2>
        <p>SISARPRAS Politeknik Nest</p>
    </div>

    <div class="periode">
        <strong>Periode:</strong>
        <?php if ($tanggalAwal !== '' && $tanggalAkhir !== ''): ?>
            <?= date('d-m-Y', strtotime($tanggalAwal)) ?>
            s/d
            <?= date('d-m-Y', strtotime($tanggalAkhir)) ?>
        <?php elseif ($tanggalAwal !== ''): ?>
            Mulai <?= date('d-m-Y', strtotime($tanggalAwal)) ?>
        <?php elseif ($tanggalAkhir !== ''): ?>
            Sampai <?= date('d-m-Y', strtotime($tanggalAkhir)) ?>
        <?php else: ?>
            Semua Data
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th>Kode Kehilangan</th>
                <th>Tanggal</th>
                <th>Pelapor</th>
                <th>Inventaris</th>
                <th>Lokasi Kehilangan</th>
                <th>Kronologi</th>
                <th>Catatan Admin</th>
            </tr>
        </thead>

        <tbody>
            <?php if ($totalData > 0): ?>
                <?php $no = 1; ?>

                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>

                        <td>
                            <?= htmlspecialchars($row['kode_kehilangan']) ?>
                        </td>

                        <td class="text-center">
                            <?= date('d-m-Y', strtotime($row['tanggal_lapor'])) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['nama_pelapor']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['nama_barang']) ?><br>
                            <small><?= htmlspecialchars($row['kode_inventaris']) ?></small>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['lokasi_kehilangan']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['kronologi']) ?>
                        </td>

                        <td>
                            <?= !empty($row['catatan_admin'])
                                ? htmlspecialchars($row['catatan_admin'])
                                : '-' ?>
                        </td>
                    </tr>
                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="8" class="text-center">
                        Tidak ada data laporan kehilangan.
                    </td>
                </tr>

            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <strong>Total Laporan: <?= $totalData ?></strong>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>

</body>
</html>