<?php
session_start();

$menu = "laporan";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../login.php");
    exit;
}

require_once "../../config/database.php";

$tanggalAwal  = $_GET['tanggal_awal'] ?? '';
$tanggalAkhir = $_GET['tanggal_akhir'] ?? '';
$status       = $_GET['status'] ?? '';

$where = [];

if (!empty($tanggalAwal)) {
    $where[] = "DATE(p.tanggal_pinjam) >= '$tanggalAwal'";
}

if (!empty($tanggalAkhir)) {
    $where[] = "DATE(p.tanggal_pinjam) <= '$tanggalAkhir'";
}

if (!empty($status)) {
    $status = mysqli_real_escape_string($conn, $status);
    $where[] = "p.status = '$status'";
}

$whereSQL = "";

if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

$queryPeminjaman = mysqli_query($conn, "
    SELECT
        p.id_peminjaman,
        p.kode_peminjaman,
        p.nama_peminjam,
        p.tanggal_pinjam,
        p.tanggal_kembali,
        p.status
    FROM peminjaman p
    $whereSQL
    ORDER BY p.tanggal_pinjam DESC
");

$page_title = "Laporan Peminjaman";

require_once "../../includes/header.php";
require_once "../../includes/navbar.php";
require_once "../../includes/sidebar.php";
?>

<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div>
                    <h2 class="fw-bold mb-0">
                        Laporan Peminjaman
                    </h2>

                    <small class="text-muted">
                        Rekapitulasi seluruh data peminjaman inventaris.
                    </small>
                </div>

                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="<?= BASE_URL ?>/admin/dashboard.php">
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= BASE_URL ?>/admin/laporan/index.php">
                                Laporan
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Peminjaman
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
                        Gunakan filter di bawah ini untuk menampilkan data peminjaman sesuai kebutuhan.
                    </small>

                </div>

                <div class="card-body px-4 pb-4">

                    <form method="GET">

                        <div class="row g-3 align-items-end">

                            <!-- Tanggal Awal -->
                            <div class="col-lg-3 col-md-6">

                                <label class="form-label fw-medium">
                                    Tanggal Awal
                                </label>

                                <input
                                    type="date"
                                    name="tanggal_awal"
                                    class="form-control"
                                    value="<?= htmlspecialchars($tanggalAwal); ?>">

                            </div>

                            <!-- Tanggal Akhir -->
                            <div class="col-lg-3 col-md-6">

                                <label class="form-label fw-medium">
                                    Tanggal Akhir
                                </label>

                                <input
                                    type="date"
                                    name="tanggal_akhir"
                                    class="form-control"
                                    value="<?= htmlspecialchars($tanggalAkhir); ?>">

                            </div>

                            <!-- Status -->
                            <div class="col-lg-3 col-md-6">

                                <label class="form-label fw-medium">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    class="form-select">

                                    <option value="">Semua Status</option>

                                    <option value="Menunggu"
                                        <?= ($status == 'Menunggu') ? 'selected' : ''; ?>>
                                        Menunggu
                                    </option>

                                    <option value="Dipinjam"
                                        <?= ($status == 'Dipinjam') ? 'selected' : ''; ?>>
                                        Dipinjam
                                    </option>

                                    <option value="Disetujui"
                                        <?= ($status == 'Disetujui') ? 'selected' : ''; ?>>
                                        Disetujui
                                    </option>

                                    <option value="Ditolak"
                                        <?= ($status == 'Ditolak') ? 'selected' : ''; ?>>
                                        Ditolak
                                    </option>

                                </select>

                            </div>

                            <!-- Tombol -->
                            <div class="col-lg-3 col-md-6">

                                <div class="d-grid gap-2">

                                    <button
                                        type="submit"
                                        class="btn btn-primary">

                                        <i class="bi bi-search me-1"></i>
                                        Tampilkan Data

                                    </button>

                                    <a
                                        href="peminjaman.php"
                                        class="btn btn-outline-secondary">

                                        <i class="bi bi-arrow-clockwise me-1"></i>
                                        Reset Filter

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
                    Data Laporan Peminjaman
                </h5>

                <small class="text-muted">
                    Menampilkan seluruh data peminjaman sesuai filter yang dipilih.
                </small>

            </div>

            <div class="d-flex gap-2">

                <a href="peminjaman_cetak.php"
                    target="_blank"
                    class="btn btn-outline-secondary btn-sm">

                    <i class="bi bi-printer-fill me-1"></i>
                    Cetak

                </a>

                <a href="peminjaman_excel.php"
                    class="btn btn-outline-success btn-sm">

                    <i class="bi bi-file-earmark-excel-fill me-1"></i>
                    Excel

                </a>

                <a href="peminjaman_pdf.php"
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

                    <tr class="text-center">

                        <th width="5%">No</th>
                        <th>Kode Peminjaman</th>
                        <th>Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    if (mysqli_num_rows($queryPeminjaman) > 0) :

                        while ($row = mysqli_fetch_assoc($queryPeminjaman)) :
                    ?>

                            <tr>

                                <td class="text-center">
                                    <?= $no++; ?>
                                </td>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars($row['kode_peminjaman']); ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nama_peminjam']); ?>
                                </td>

                                <td class="text-center">
                                    <?= date('d-m-Y', strtotime($row['tanggal_pinjam'])); ?>
                                </td>

                                <td class="text-center">
                                    <?= date('d-m-Y', strtotime($row['tanggal_kembali'])); ?>
                                </td>

                                <td class="text-center">

                                    <?php if ($row['status'] == 'Menunggu') : ?>

                                        <span class="badge bg-secondary">
                                            Menunggu
                                        </span>

                                    <?php elseif ($row['status'] == 'Disetujui') : ?>

                                        <span class="badge bg-warning text-dark">
                                            Disetujui
                                        </span>
                                    
                                        <?php elseif ($row['status'] == 'Dipinjam') : ?>

                                        <span class="badge bg-warning text-dark">
                                            Dipinjam
                                        </span>

                                    <?php elseif ($row['status'] == 'Dikembalikan') : ?>

                                        <span class="badge bg-success">
                                            Dikembalikan
                                        </span>

                                    <?php else : ?>

                                        <span class="badge bg-danger">
                                            Ditolak
                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else : ?>

                        <tr>

                            <td colspan="6" class="text-center py-5 text-muted">

                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>

                                Tidak ada data peminjaman yang sesuai dengan filter.

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