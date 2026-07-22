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
    $where[] = "DATE(so.tanggal) >= '$tanggalAwal'";
}

if (!empty($tanggalAkhir)) {
    $where[] = "DATE(so.tanggal) <= '$tanggalAkhir'";
}

if (!empty($status)) {
    $status = mysqli_real_escape_string($conn, $status);
    $where[] = "so.status = '$status'";
}

$whereSQL = "";

if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}


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
    $whereSQL
    ORDER BY so.tanggal DESC
");


$totalData = mysqli_num_rows($queryLaporan);

$queryDraft = mysqli_query($conn,"
    SELECT COUNT(*) AS total
    FROM stock_opname
    WHERE status='Draft'
");
$totalDraft = mysqli_fetch_assoc($queryDraft)['total'];

$queryProses = mysqli_query($conn,"
    SELECT COUNT(*) AS total
    FROM stock_opname
    WHERE status='Proses'
");
$totalProses = mysqli_fetch_assoc($queryProses)['total'];

$querySelesai = mysqli_query($conn,"
    SELECT COUNT(*) AS total
    FROM stock_opname
    WHERE status='Selesai'
");
$totalSelesai = mysqli_fetch_assoc($querySelesai)['total'];

$page_title = "Laporan Stock Opname";

require_once "../../includes/header.php";
require_once "../../includes/navbar.php";
require_once "../../includes/sidebar.php";
?>

<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div>
                    <h2 class="fw-bold mb-0">Laporan Stock Opname</h2>
                    <small class="text-muted">
                        Rekapitulasi hasil kegiatan stock opname inventaris.
                    </small>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Laporan</li>
                        <li class="breadcrumb-item active">Stock Opname</li>
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
                    Gunakan filter di bawah ini untuk menampilkan data stock opname sesuai kebutuhan.
                </small>
            </div>

            <div class="card-body px-4 pb-4">

                <form method="GET">

                    <div class="row g-3 align-items-end">

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

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-medium">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select">

                                <option value="">Semua Status</option>

                                <option value="Draft"
                                    <?= $status == 'Draft' ? 'selected' : ''; ?>>
                                    Draft
                                </option>

                                <option value="Proses"
                                    <?= $status == 'Proses' ? 'selected' : ''; ?>>
                                    Proses
                                </option>

                                <option value="Selesai"
                                    <?= $status == 'Selesai' ? 'selected' : ''; ?>>
                                    Selesai
                                </option>

                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">

                            <div class="d-grid gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-primary">

                                    <i class="bi bi-search me-1"></i>
                                    Tampilkan Data

                                </button>

                                <a
                                    href="stock_opname.php"
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

        <!-- Statistik -->
<div class="row mb-4">

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center">

                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                    <i class="bi bi-clipboard-data fs-3 text-primary"></i>
                </div>

                <div>
                    <small class="text-muted d-block">Total Data</small>
                    <h3 class="fw-bold mb-0"><?= $totalData; ?></h3>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center">

                <div class="rounded-circle bg-secondary bg-opacity-10 p-3 me-3">
                    <i class="bi bi-pencil-square fs-3 text-secondary"></i>
                </div>

                <div>
                    <small class="text-muted d-block">Draft</small>
                    <h3 class="fw-bold mb-0"><?= $totalDraft; ?></h3>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center">

                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                    <i class="bi bi-arrow-repeat fs-3 text-warning"></i>
                </div>

                <div>
                    <small class="text-muted d-block">Proses</small>
                    <h3 class="fw-bold mb-0"><?= $totalProses; ?></h3>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center">

                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                    <i class="bi bi-check-circle fs-3 text-success"></i>
                </div>

                <div>
                    <small class="text-muted d-block">Selesai</small>
                    <h3 class="fw-bold mb-0"><?= $totalSelesai; ?></h3>
                </div>

            </div>
        </div>
    </div>

</div>

        <!-- Data Laporan -->
<div class="card shadow-sm border-0 rounded-4">

    <div class="card-header bg-white border-0 py-3 px-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>
                <h5 class="fw-semibold mb-1">
                    <i class="bi bi-table me-2 text-primary"></i>
                    Data Laporan Stock Opname
                </h5>
                <small class="text-muted">
                    Menampilkan seluruh data stock opname sesuai filter yang dipilih.
                </small>
            </div>

            <div class="d-flex gap-2">

                <a href="stock_opname_excel.php"
                    class="btn btn-outline-success btn-sm">
                    <i class="bi bi-file-earmark-excel-fill me-1"></i>
                    Excel
                </a>

                <a href="stock_opname_pdf.php"
                    class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i>
                    PDF
                </a>

            </div>

        </div>

    </div>

    <div class="card-body border-top">

        <div class="table-responsive">

            <table class="table table-bordered align-middle datatable">

                <thead class="table-secondary">

                    <tr class="text-center">
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

                    if (mysqli_num_rows($queryLaporan) > 0) :
                        while ($row = mysqli_fetch_assoc($queryLaporan)) :
                    ?>

                            <tr>

                                <td class="text-center">
                                    <?= $no++; ?>
                                </td>

                                <td>
                                    <strong><?= htmlspecialchars($row['kode_stock_opname']); ?></strong>
                                </td>

                                <td>
                                    <?= date('d-m-Y', strtotime($row['tanggal'])); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nama_admin']); ?>
                                </td>

                                <td class="text-center">

                                    <?php if ($row['status'] == 'Draft') : ?>

                                        <span class="badge bg-secondary">
                                            Draft
                                        </span>

                                    <?php elseif ($row['status'] == 'Proses') : ?>

                                        <span class="badge bg-warning text-dark">
                                            Proses
                                        </span>

                                    <?php else : ?>

                                        <span class="badge bg-success">
                                            Selesai
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td class="text-center">

                                    <a href="../transaksi/stock_opname/detail.php?id=<?= $row['id_stock_opname']; ?>"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Lihat Detail">

                                        <i class="bi bi-eye-fill"></i>

                                    </a>

                                    <a href="stock_opname_cetak.php?id=<?= $row['id_stock_opname']; ?>"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-secondary"
                                        title="Cetak Hasil Stock Opname">

                                        <i class="bi bi-printer-fill"></i>

                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else : ?>

                        <tr>

                            <td colspan="6" class="text-center py-5 text-muted">

                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>

                                Tidak ada data stock opname yang sesuai dengan filter.

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