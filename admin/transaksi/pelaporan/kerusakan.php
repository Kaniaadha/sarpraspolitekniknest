<?php
session_start();

$menu = "pelaporan";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";
require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";


// ==========================================
// AMBIL DATA LAPORAN KERUSAKAN
// ==========================================

$queryKerusakan = mysqli_query($conn, "
    SELECT
        k.id_kerusakan,
        k.kode_kerusakan,
        k.tanggal_lapor,
        k.nama_pelapor,
        k.status,

        i.kode_inventaris,
        i.nama_barang

    FROM kerusakan k

    INNER JOIN detail_kerusakan dk
        ON k.id_kerusakan = dk.id_kerusakan

    INNER JOIN inventaris i
        ON dk.id_inventaris = i.id_inventaris

    ORDER BY
        k.tanggal_lapor DESC,
        k.id_kerusakan DESC
");


if (!$queryKerusakan) {
    die("Query Error : " . mysqli_error($conn));
}
?>


<main class="app-main">

    <!-- ==========================================
         HEADER
    ========================================== -->

    <div class="app-content-header">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="fw-bold mb-0">
                    Laporan Kerusakan
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
                        Kerusakan
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

                <!-- CARD HEADER -->

                <div class="card-header bg-white py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <h4 class="card-title mb-0">

                            <i class="bi bi-tools me-2 text-danger"></i>

                            Daftar Laporan Kerusakan

                        </h4>


                        <!-- TAMBAH LAPORAN -->

                        <a
                            href="tambah_kerusakan.php"
                            class="btn btn-danger">

                            <i class="bi bi-plus-circle me-1"></i>

                            Tambah Laporan

                        </a>

                    </div>

                </div>


                <!-- CARD BODY -->

                <div class="card-body">

                    <div class="table-responsive">

                        <table
                            class="table table-bordered table-hover align-middle datatable">

                            <thead class="table-secondary">

                                <tr class="text-center">

                                    <th width="5%">
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
                                        Status
                                    </th>

                                    <th width="10%">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php

                                $no = 1;

                                if (mysqli_num_rows($queryKerusakan) > 0) :

                                    while ($row = mysqli_fetch_assoc($queryKerusakan)) :

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
                                                    $row['kode_kerusakan']
                                                ); ?>
                                            </strong>

                                        </td>


                                        <!-- TANGGAL -->

                                        <td>

                                            <?= date(
                                                'd-m-Y',
                                                strtotime($row['tanggal_lapor'])
                                            ); ?>

                                        </td>


                                        <!-- PELAPOR -->

                                        <td>

                                            <?= htmlspecialchars(
                                                $row['nama_pelapor']
                                            ); ?>

                                        </td>


                                        <!-- INVENTARIS -->

                                        <td>

                                            <strong>
                                                <?= htmlspecialchars(
                                                    $row['nama_barang']
                                                ); ?>
                                            </strong>

                                            <br>

                                            <small class="text-muted">
                                                <?= htmlspecialchars(
                                                    $row['kode_inventaris']
                                                ); ?>
                                            </small>

                                        </td>


                                        <!-- STATUS -->

                                        <td class="text-center">

                                            <?php

                                            if ($row['status'] == 'Menunggu') :

                                            ?>

                                                <span class="badge bg-secondary">
                                                    Menunggu
                                                </span>

                                            <?php

                                            elseif ($row['status'] == 'Diproses') :

                                            ?>

                                                <span class="badge bg-warning text-dark">
                                                    Diproses
                                                </span>

                                            <?php

                                            elseif ($row['status'] == 'Selesai') :

                                            ?>

                                                <span class="badge bg-success">
                                                    Selesai
                                                </span>

                                            <?php

                                            else :

                                            ?>

                                                <span class="badge bg-dark">
                                                    <?= htmlspecialchars(
                                                        $row['status']
                                                    ); ?>
                                                </span>

                                            <?php endif; ?>

                                        </td>


                                        <!-- AKSI -->

                                        <td class="text-center">

                                            <a
                                                href="detail_kerusakan.php?id=<?= $row['id_kerusakan']; ?>"
                                                class="btn btn-info btn-sm"
                                                title="Lihat Detail">

                                                <i class="bi bi-eye-fill"></i>

                                            </a>

                                        </td>

                                    </tr>

                                <?php

                                    endwhile;

                                else :

                                ?>

                                    <tr>

                                        <td
                                            colspan="7"
                                            class="text-center text-muted py-5">

                                            <i
                                                class="bi bi-inbox fs-1 d-block mb-3">
                                            </i>

                                            Belum ada laporan kerusakan.

                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</main>


<?php

require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";

?>