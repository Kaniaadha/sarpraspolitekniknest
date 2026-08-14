<?php
session_start();

$menu = "pelaporan";

// Cek login admin
if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";


// ==========================================
// AMBIL DATA LAPORAN KEHILANGAN
// ==========================================

$query = mysqli_query($conn, "
    SELECT
        k.id_kehilangan,
        k.kode_kehilangan,
        k.tanggal_lapor,
        k.nama_pelapor,
        dk.lokasi_kehilangan,
        i.kode_inventaris,
        i.nama_barang
    FROM kehilangan k
    INNER JOIN detail_kehilangan dk
        ON k.id_kehilangan = dk.id_kehilangan
    INNER JOIN inventaris i
        ON dk.id_inventaris = i.id_inventaris
    ORDER BY k.id_kehilangan DESC
");


// ==========================================
// TEMPLATE
// ==========================================

require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";
?>


<main class="app-main">

    <!-- ==========================================
         HEADER
    ========================================== -->

    <div class="app-content-header">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="fw-bold mb-0">
                    Laporan Kehilangan
                </h2>

                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL; ?>/admin/dashboard.php">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        Transaksi
                    </li>

                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL; ?>/admin/transaksi/pelaporan/index.php">
                            Pelaporan
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Kehilangan
                    </li>

                </ol>

            </div>

        </div>
    </div>


    <!-- ==========================================
         CONTENT
    ========================================== -->

    <div class="app-content">
        <div class="container-fluid">

            <div class="card border-0 shadow-sm">

                <!-- HEADER CARD -->
                <div class="card-header bg-white py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                            Daftar Laporan Kehilangan
                        </h5>

                        <a
                            href="tambah_kehilangan.php"
                            class="btn btn-kehilangan">

                            <i class="bi bi-plus-circle me-1"></i>
                            Tambah Laporan

                        </a>

                    </div>

                </div>


                <!-- BODY -->
                <div class="card-body">

                    <div class="table-responsive">

                        <table
                            class="table table-bordered table-hover align-middle mb-0 datatable">

                            <thead class="table-secondary">

                                <tr>

                                    <th width="5%" class="text-center">
                                        No
                                    </th>

                                    <th>
                                        Kode Laporan
                                    </th>

                                    <th>
                                        Tanggal
                                    </th>

                                    <th>
                                        Pelapor
                                    </th>

                                    <th>
                                        Inventaris
                                    </th>

                                    <th>
                                        Lokasi Kehilangan
                                    </th>

                                    <th width="90" class="text-center">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php
                                $no = 1;

                                if (mysqli_num_rows($query) > 0) :

                                    while ($data = mysqli_fetch_assoc($query)) :
                                ?>

                                    <tr>

                                        <!-- NO -->
                                        <td class="text-center">
                                            <?= $no++; ?>
                                        </td>


                                        <!-- KODE -->
                                        <td>
                                            <strong>
                                                <?= htmlspecialchars(
                                                    $data['kode_kehilangan']
                                                ); ?>
                                            </strong>
                                        </td>


                                        <!-- TANGGAL -->
                                        <td>
                                            <?= date(
                                                'd-m-Y',
                                                strtotime(
                                                    $data['tanggal_lapor']
                                                )
                                            ); ?>
                                        </td>


                                        <!-- PELAPOR -->
                                        <td>
                                            <?= htmlspecialchars(
                                                $data['nama_pelapor']
                                            ); ?>
                                        </td>


                                        <!-- INVENTARIS -->
                                        <td>

                                            <strong>
                                                <?= htmlspecialchars(
                                                    $data['nama_barang']
                                                ); ?>
                                            </strong>

                                            <br>

                                            <small class="text-muted">
                                                <?= htmlspecialchars(
                                                    $data['kode_inventaris']
                                                ); ?>
                                            </small>

                                        </td>


                                        <!-- LOKASI -->
                                        <td>
                                            <?= htmlspecialchars(
                                                $data['lokasi_kehilangan']
                                            ); ?>
                                        </td>


                                        <!-- AKSI -->
                                        <td class="text-center">

                                            <a
                                                href="detail_kehilangan.php?id=<?= $data['id_kehilangan']; ?>"
                                                class="btn btn-detail btn-sm"
                                                title="Lihat Detail">

                                                <i class="bi bi-eye-fill"></i>

                                            </a>

                                        </td>

                                    </tr>

                                <?php
                                    endwhile;

                                endif; 
                                ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>
    </div>

</main>


<style>

/* ==========================================
   TOMBOL TAMBAH LAPORAN
========================================== */

.btn-kehilangan {
    background: #dc3545;
    border: 1px solid #dc3545;
    color: #fff;
    font-weight: 500;
    border-radius: 6px;
}

.btn-kehilangan:hover {
    background: #c82333;
    border-color: #c82333;
    color: #fff;
}


/* ==========================================
   TOMBOL DETAIL
========================================== */

.btn-detail {
    background: #0dcaf0;
    border: 1px solid #0dcaf0;
    color: #000;
    border-radius: 6px;
}

.btn-detail:hover {
    background: #0bb5d8;
    border-color: #0bb5d8;
    color: #000;
}


/* ==========================================
   TABLE
========================================== */

.table thead th {
    background: #dee2e6;
    font-weight: 600;
    vertical-align: middle;
    white-space: nowrap;
}

.table tbody td {
    vertical-align: middle;
}


/* ==========================================
   RESPONSIVE
========================================== */

@media (max-width: 767.98px) {

    .app-content-header {
        padding: 15px 12px 5px;
    }

    .app-content-header .d-flex {
        display: block !important;
    }

    .app-content-header h2 {
        font-size: 22px;
        margin-bottom: 8px !important;
    }

    .app-content-header .breadcrumb {
        font-size: 13px;
    }

    .app-content {
        padding: 10px 12px;
    }

    .card-header {
        padding: 16px !important;
    }

    .card-header .d-flex {
        gap: 12px;
        align-items: flex-start !important;
    }

    .card-header h5 {
        font-size: 16px;
    }

    .btn-kehilangan {
        font-size: 13px;
        white-space: nowrap;
    }

    .card-body {
        padding: 16px !important;
    }

}

</style>


<?php

require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";

?>