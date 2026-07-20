<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

$menu = "stock_opname";

require_once "../../../config/database.php";


$queryRiwayat = mysqli_query($conn, "
    SELECT
        so.*,
        a.nama_admin
    FROM stock_opname so
    JOIN admin a
        ON so.id_admin = a.id_admin
    ORDER BY so.tanggal DESC, so.id_stock_opname DESC
");


require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";
?>

<main class="app-main">

    <div class="app-content-header">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-6">

                    <h3 class="mb-0">

                        Riwayat Stock Opname

                    </h3>

                </div>

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-end">

                        <li class="breadcrumb-item">

                            <a href="../dashboard/index.php">

                                Dashboard

                            </a>

                        </li>

                        <li class="breadcrumb-item active">

                            Riwayat Stock Opname

                        </li>

                    </ol>

                </div>

            </div>

        </div>

    </div>

    <div class="app-content">

        <div class="container-fluid">

<div class="card">

    <div class="card-header">

        <div class="d-flex justify-content-between align-items-center">

            <h3 class="card-title">

                Data Riwayat Stock Opname

            </h3>

            <a
                href="index.php"
                class="btn btn-primary">

                <i class="bi bi-plus-circle me-1"></i>

                Stock Opname Baru

            </a>

        </div>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table
                id="tabelRiwayat"
                class="table table-bordered table-hover align-middle">

                <thead class="table-light">

                    <tr class="text-center">

                        <th width="5%">No</th>

                        <th width="15%">Kode</th>

                        <th width="20%">Tanggal</th>

                        <th>Petugas</th>

                        <th width="15%">Status</th>

                        <th width="12%">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $no = 1;

                    while ($row = mysqli_fetch_assoc($queryRiwayat)) {

                    ?>

                    <tr>

                        <td class="text-center">

                            <?= $no++; ?>

                        </td>

                        <td class="text-center">

                            <?= $row['kode_stock_opname']; ?>

                        </td>

                        <td class="text-center">

                            <?= date('d F Y', strtotime($row['tanggal'])); ?>

                        </td>

                        <td>

                            <?= $row['nama_admin']; ?>

                        </td>

                        <td class="text-center">

                            <?php if ($row['status'] == 'Draft') { ?>

                                <span class="badge bg-warning">

                                    Draft

                                </span>

                            <?php } else { ?>

                                <span class="badge bg-success">

                                    Selesai

                                </span>

                            <?php } ?>

                        </td>

                        <td class="text-center">

                            <a
                                href="detail.php?id=<?= $row['id_stock_opname']; ?>"
                                class="btn btn-info btn-sm">

                                <i class="bi bi-eye"></i>

                            </a>

                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

        </div>

    </div>

</main>

<?php require_once "../../../includes/footer.php"; ?>

<script>

$(document).ready(function () {

    $('#tabelRiwayat').DataTable({

        "responsive": true,

        "autoWidth": false,

        "lengthChange": true,

        "pageLength": 10,

        "language": {

            "search": "Cari :",

            "lengthMenu": "Tampilkan _MENU_ data",

            "zeroRecords": "Data tidak ditemukan",

            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

            "infoEmpty": "Tidak ada data",

            "infoFiltered": "(difilter dari _MAX_ total data)",

            "paginate": {

                "first": "Awal",

                "last": "Akhir",

                "next": "Berikutnya",

                "previous": "Sebelumnya"

            }

        }

    });

});

</script>