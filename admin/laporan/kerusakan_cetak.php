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
    $where[] = "DATE(k.tanggal_lapor) >= '$tanggalAwal'";
}

if (!empty($tanggalAkhir)) {
    $tanggalAkhir = mysqli_real_escape_string($conn, $tanggalAkhir);
    $where[] = "DATE(k.tanggal_lapor) <= '$tanggalAkhir'";
}

if (!empty($status)) {
    $status = mysqli_real_escape_string($conn, $status);
    $where[] = "k.status = '$status'";
}

$whereSQL = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

$query = mysqli_query($conn, "
    SELECT
        k.kode_kerusakan,
        k.tanggal_lapor,
        k.nama_pelapor,
        k.status,
        i.kode_inventaris,
        i.nama_barang,
        dk.bagian_rusak,
        dk.jenis_kerusakan,
        dk.tingkat_kerusakan
    FROM detail_kerusakan dk
    INNER JOIN kerusakan k
        ON dk.id_kerusakan = k.id_kerusakan
    INNER JOIN inventaris i
        ON dk.id_inventaris = i.id_inventaris
    $whereSQL
    ORDER BY k.tanggal_lapor DESC, k.kode_kerusakan DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Kerusakan</title>

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
            float: right;
            width: 250px;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header">
        <h2>POLITEKNIK NEST</h2>
        <p>
            <strong>LAPORAN KERUSAKAN INVENTARIS</strong>
        </p>
        <hr>
    </div>

    <!-- Data -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Pelapor</th>
                <th>Bagian Rusak</th>
                <th>Jenis Kerusakan</th>
                <th>Tingkat</th>
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
                    <td class="text-center">
                        <?= $no++; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['kode_kerusakan']); ?>
                    </td>

                    <td class="text-center">
                        <?= date('d-m-Y', strtotime($row['tanggal_lapor'])); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['nama_barang']); ?>
                        <br>
                        <small>
                            <?= htmlspecialchars($row['kode_inventaris']); ?>
                        </small>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['nama_pelapor']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['bagian_rusak']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['jenis_kerusakan']); ?>
                    </td>

                    <td class="text-center">
                        <?= htmlspecialchars($row['tingkat_kerusakan']); ?>
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
                    <td colspan="9" class="text-center">
                        Tidak ada data kerusakan yang sesuai dengan filter.
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