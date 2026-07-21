<?php
session_start();

$menu = "laporan";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../login.php");
    exit;
}

require_once "../../config/database.php";

$kategori = $_GET['kategori'] ?? '';
$kondisi  = $_GET['kondisi'] ?? '';

$where = [];

if (!empty($kategori)) {
    $kategori = mysqli_real_escape_string($conn, $kategori);
    $where[] = "k.id_kategori = '$kategori'";
}

if (!empty($kondisi)) {
    $kondisi = mysqli_real_escape_string($conn, $kondisi);
    $where[] = "i.kondisi = '$kondisi'";
}

$whereSQL = "";

if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

$queryInventaris = mysqli_query($conn, "
    SELECT
        i.id_inventaris,
        i.kode_inventaris,
        i.nama_barang,
        i.jumlah,
        i.kondisi,

        k.nama_kategori,

        r.nama_ruangan,
        ps.nama_public_space

    FROM inventaris i

    LEFT JOIN kategori k
        ON i.id_kategori = k.id_kategori

    LEFT JOIN ruangan r
        ON i.id_ruangan = r.id_ruangan

    LEFT JOIN public_space ps
        ON i.id_public_space = ps.id_public_space

    $whereSQL

    ORDER BY i.kode_inventaris ASC
");

$queryKategori = mysqli_query($conn,"
    SELECT *
    FROM kategori
    ORDER BY nama_kategori ASC
");

$page_title = "Laporan Inventaris";

require_once "../../includes/header.php";
require_once "../../includes/navbar.php";
require_once "../../includes/sidebar.php";
?>

<style>
.datatable thead th{
    background:#f3f4f6 !important;
    color:#212529 !important;
    font-weight:600;
    border:1px solid #dee2e6 !important;
    vertical-align:middle;
    text-align:center;
}

.datatable tbody td{
    border:1px solid #dee2e6 !important;
    vertical-align:middle;
}

.datatable tbody tr:hover{
    background:#f8f9fa;
}
</style>

<main class="app-main">

<div class="app-content-header">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>

                <h2 class="fw-bold mb-0">
                    Laporan Inventaris
                </h2>

                <small class="text-muted">
                    Rekapitulasi seluruh data inventaris sarana dan prasarana.
                </small>

            </div>

            <nav>

                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">
                        Dashboard
                    </li>

                    <li class="breadcrumb-item">
                        Laporan
                    </li>

                    <li class="breadcrumb-item active">
                        Inventaris
                    </li>

                </ol>

            </nav>

        </div>

    </div>

</div>

<div class="app-content">
    <div class="container-fluid">

        <!-- Filter -->
        <div class="card shadow-sm border-0 rounded-4 mb-4">

            <div class="card-header bg-white border-0 pt-4 px-4">

                <h5 class="fw-semibold mb-1">
                    <i class="bi bi-funnel-fill me-2 text-primary"></i>
                    Filter Laporan
                </h5>

                <small class="text-muted">
                    Gunakan filter di bawah ini untuk menampilkan data inventaris sesuai kebutuhan.
                </small>

            </div>

            <div class="card-body px-4 pb-4">

                <form method="GET">

                    <div class="row g-3 align-items-end">

                        <!-- Kategori -->
                        <div class="col-lg-4 col-md-6">

                            <label class="form-label fw-medium">
                                Kategori
                            </label>

                            <select
                                name="kategori"
                                class="form-select">

                                <option value="">Semua Kategori</option>

                                <?php while($rowKategori = mysqli_fetch_assoc($queryKategori)) : ?>

                                    <option
                                        value="<?= $rowKategori['id_kategori']; ?>"
                                        <?= ($kategori == $rowKategori['id_kategori']) ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($rowKategori['nama_kategori']); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>

                        <!-- Kondisi -->
                        <div class="col-lg-2 col-md-6">

                            <label class="form-label fw-medium">
                                Kondisi
                            </label>

                            <select
                                name="kondisi"
                                class="form-select">

                                <option value="">Semua</option>

                                <option value="Baik"
                                    <?= ($kondisi == 'Baik') ? 'selected' : ''; ?>>
                                    Baik
                                </option>

                                <option value="Rusak Ringan"
                                    <?= ($kondisi == 'Rusak Ringan') ? 'selected' : ''; ?>>
                                    Rusak Ringan
                                </option>

                                <option value="Rusak Berat"
                                    <?= ($kondisi == 'Rusak Berat') ? 'selected' : ''; ?>>
                                    Rusak Berat
                                </option>

                            </select>

                        </div>

                        <!-- Tombol -->
                        <div class="col-lg-2 col-md-6">

                            <div class="d-grid gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-primary">

                                    <i class="bi bi-search me-1"></i>
                                    Tampilkan

                                </button>

                                <a
                                    href="inventaris.php"
                                    class="btn btn-outline-secondary">

                                    <i class="bi bi-arrow-clockwise me-1"></i>
                                    Reset

                                </a>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        <!-- Data Laporan -->
<div class="card shadow-sm border-0 rounded-4">

    <div class="card-header bg-white border-0 py-3 px-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>

                <h5 class="fw-semibold mb-1">
                    <i class="bi bi-table me-2 text-primary"></i>
                    Data Laporan Inventaris
                </h5>

                <small class="text-muted">
                    Menampilkan seluruh data inventaris sesuai filter yang dipilih.
                </small>

            </div>

            <div class="d-flex gap-2">

                <a href="inventaris_cetak.php"
                    target="_blank"
                    class="btn btn-outline-secondary btn-sm">

                    <i class="bi bi-printer-fill me-1"></i>
                    Cetak

                </a>

                <a href="inventaris_excel.php"
                    class="btn btn-outline-success btn-sm">

                    <i class="bi bi-file-earmark-excel-fill me-1"></i>
                    Excel

                </a>

                <a href="inventaris_pdf.php"
                    class="btn btn-outline-danger btn-sm">

                    <i class="bi bi-file-earmark-pdf-fill me-1"></i>
                    PDF

                </a>

            </div>

        </div>

    </div>

    <div class="card-body border-top">

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle datatable">

                <thead class="table-secondary">

                    <tr>

                        <th width="5%">No</th>
                        <th>Kode Inventaris</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Ruangan / Public Space</th>
                        <th width="8%">Jumlah</th>
                        <th width="12%">Kondisi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    if (mysqli_num_rows($queryInventaris) > 0) :

                        while ($row = mysqli_fetch_assoc($queryInventaris)) :
                    ?>

                            <tr>

                                <td class="text-center">
                                    <?= $no++; ?>
                                </td>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars($row['kode_inventaris']); ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nama_barang']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nama_kategori']); ?>
                                </td>

                                <td>

                                    <?php

                                    if (!empty($row['nama_ruangan'])) {

                                        echo htmlspecialchars($row['nama_ruangan']);

                                    } elseif (!empty($row['nama_public_space'])) {

                                        echo htmlspecialchars($row['nama_public_space']);

                                    } else {

                                        echo "-";

                                    }

                                    ?>

                                </td>

                                <td class="text-center">

                                    <span class="badge bg-primary">

                                        <?= $row['jumlah']; ?>

                                    </span>

                                </td>

                                <td class="text-center">

                                    <?php if ($row['kondisi'] == 'Baik') : ?>

                                        <span class="badge bg-success">
                                            Baik
                                        </span>

                                    <?php elseif ($row['kondisi'] == 'Rusak Ringan') : ?>

                                        <span class="badge bg-warning text-dark">
                                            Rusak Ringan
                                        </span>

                                    <?php else : ?>

                                        <span class="badge bg-danger">
                                            Rusak Berat
                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else : ?>

                        <tr>

                            <td colspan="7" class="text-center py-5 text-muted">

                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>

                                Tidak ada data inventaris yang sesuai dengan filter.

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
require_once "../../includes/footer.php";
require_once "../../includes/scripts.php";
?>