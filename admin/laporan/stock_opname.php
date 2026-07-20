<?php
session_start();

$menu = "laporan";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../login.php");
    exit;
}

require_once "../../config/database.php";

$queryLaporan = mysqli_query($conn, "
SELECT
    so.id_stock_opname,
    so.kode_stock_opname,
    so.tanggal,
    so.status,
    a.nama_admin

FROM stock_opname so

INNER JOIN admin a
ON so.id_admin = a.id_admin

ORDER BY so.tanggal DESC
");

$page_title = "Laporan Stock Opname";

require_once "../../includes/header.php";
require_once "../../includes/navbar.php";
require_once "../../includes/sidebar.php";
?>

<main class="app-main">

<div class="app-content-header">

<div class="container-fluid">

<div class="row">

<div class="col-sm-6">

<h3 class="mb-0">

Laporan Stock Opname

</h3>

</div>

<div class="col-sm-6">

<ol class="breadcrumb float-sm-end">

<li class="breadcrumb-item">

Dashboard

</li>

<li class="breadcrumb-item">

Laporan

</li>

<li class="breadcrumb-item active">

Stock Opname

</li>

</ol>

</div>

</div>

</div>

</div>

<div class="app-content">

<div class="container-fluid">


<div class="card border-0 shadow-sm mb-4">

    <div class="card-header">

        <h5 class="card-title mb-0">

            <i class="bi bi-funnel-fill me-2"></i>

            Filter Laporan

        </h5>

    </div>

    <div class="card-body">

        <form method="GET">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <label class="form-label">

                        Tanggal Awal

                    </label>

                    <input
                        type="date"
                        name="tanggal_awal"
                        class="form-control"
                        value="<?= $_GET['tanggal_awal'] ?? ''; ?>">

                </div>

                <div class="col-md-3 mb-3">

                    <label class="form-label">

                        Tanggal Akhir

                    </label>

                    <input
                        type="date"
                        name="tanggal_akhir"
                        class="form-control"
                        value="<?= $_GET['tanggal_akhir'] ?? ''; ?>">

                </div>

                <div class="col-md-3 mb-3">

                    <label class="form-label">

                        Status

                    </label>

                    <select
                        name="status"
                        class="form-select">

                        <option value="">Semua Status</option>

                        <option value="Draft"
                            <?= (($_GET['status'] ?? '') == 'Draft') ? 'selected' : ''; ?>>
                            Draft
                        </option>

                        <option value="Proses"
                            <?= (($_GET['status'] ?? '') == 'Proses') ? 'selected' : ''; ?>>
                            Proses
                        </option>

                        <option value="Selesai"
                            <?= (($_GET['status'] ?? '') == 'Selesai') ? 'selected' : ''; ?>>
                            Selesai
                        </option>

                    </select>

                </div>

                <div class="col-md-3 d-flex align-items-end mb-3">

                    <button
                        type="submit"
                        class="btn btn-primary me-2">

                        <i class="bi bi-search"></i>

                        Tampilkan

                    </button>

                    <a
                        href="stock_opname.php"
                        class="btn btn-secondary">

                        <i class="bi bi-arrow-clockwise"></i>

                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>


<div class="card border-0 shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="card-title mb-0">

            <i class="bi bi-table me-2"></i>

            Data Laporan Stock Opname

        </h5>

        <div>

            <a
                href="stock_opname_cetak.php"
                target="_blank"
                class="btn btn-secondary btn-sm">

                <i class="bi bi-printer-fill"></i>

                Cetak

            </a>

            <a
                href="stock_opname_excel.php"
                class="btn btn-success btn-sm">

                <i class="bi bi-file-earmark-excel-fill"></i>

                Excel

            </a>

            <a
                href="stock_opname_pdf.php"
                class="btn btn-danger btn-sm">

                <i class="bi bi-file-earmark-pdf-fill"></i>

                PDF

            </a>

        </div>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle datatable">

                <thead class="table-light">

                    <tr>

                        <th width="5%">No</th>

                        <th>Kode Stock Opname</th>

                        <th>Tanggal</th>

                        <th>Petugas</th>

                        <th>Status</th>

                        <th width="10%">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                <?php

                $no = 1;

                while ($row = mysqli_fetch_assoc($queryLaporan)) :

                ?>

                    <tr>

                        <td class="text-center">

                            <?= $no++; ?>

                        </td>

                        <td>

                            <strong>

                                <?= htmlspecialchars($row['kode_stock_opname']); ?>

                            </strong>

                        </td>

                        <td>

                            <?= date('d-m-Y', strtotime($row['tanggal'])); ?>

                        </td>

                        <td>

                            <?= htmlspecialchars($row['nama_admin']); ?>

                        </td>

                        <td>

                            <?php

                            if ($row['status'] == 'Selesai') {

                                echo '<span class="badge bg-success">Selesai</span>';

                            } elseif ($row['status'] == 'Proses') {

                                echo '<span class="badge bg-warning text-dark">Proses</span>';

                            } else {

                                echo '<span class="badge bg-secondary">Draft</span>';

                            }

                            ?>

                        </td>

                        <td class="text-center">

                            <a
                                href="../transaksi/stock_opname/detail.php?id=<?= $row['id_stock_opname']; ?>"
                                class="btn btn-info btn-sm"
                                title="Lihat Detail">

                                <i class="bi bi-eye-fill"></i>

                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


</div>

</div>

</main>

<?php
require_once "../../includes/footer.php";
require_once "../../includes/scripts.php";
?>