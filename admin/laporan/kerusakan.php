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
    $where[] = "DATE(k.tanggal_lapor) >= '$tanggalAwal'";
}

if (!empty($tanggalAkhir)) {
    $where[] = "DATE(k.tanggal_lapor) <= '$tanggalAkhir'";
}

if (!empty($status)) {
    $status = mysqli_real_escape_string($conn, $status);
    $where[] = "k.status = '$status'";
}

$whereSQL = "";

if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}


$queryLaporan = mysqli_query($conn, "
    SELECT
        dk.id_detail,

        k.id_kerusakan,
        k.kode_kerusakan,
        k.tanggal_lapor,
        k.nama_pelapor,
        k.status,

        i.id_inventaris,
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

    ORDER BY
        k.tanggal_lapor DESC,
        k.kode_kerusakan DESC
");

if (!$queryLaporan) {
    die("Query Error : " . mysqli_error($conn));
}


$totalData = mysqli_num_rows($queryLaporan);

/* Menunggu */
$queryMenunggu = mysqli_query($conn,"
    SELECT COUNT(*) AS total
    FROM kerusakan k
    $whereSQL
    " . (empty($whereSQL) ? "WHERE" : "AND") . "
    k.status='Menunggu'
");
$totalMenunggu = mysqli_fetch_assoc($queryMenunggu)['total'];

/* Diproses */
$queryDiproses = mysqli_query($conn,"
    SELECT COUNT(*) AS total
    FROM kerusakan k
    $whereSQL
    " . (empty($whereSQL) ? "WHERE" : "AND") . "
    k.status='Diproses'
");
$totalDiproses = mysqli_fetch_assoc($queryDiproses)['total'];

/* Selesai */
$querySelesai = mysqli_query($conn,"
    SELECT COUNT(*) AS total
    FROM kerusakan k
    $whereSQL
    " . (empty($whereSQL) ? "WHERE" : "AND") . "
    k.status='Selesai'
");
$totalSelesai = mysqli_fetch_assoc($querySelesai)['total'];

$page_title = "Laporan Kerusakan";

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
                        Laporan Kerusakan
                    </h2>

                    <small class="text-muted">
                        Rekapitulasi seluruh laporan kerusakan inventaris.
                    </small>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Laporan</li>
                        <li class="breadcrumb-item active">Kerusakan</li>
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
                        Gunakan filter di bawah ini untuk menampilkan data kerusakan sesuai kebutuhan.
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
                                        <?= $status == 'Menunggu' ? 'selected' : ''; ?>>
                                        Menunggu
                                    </option>

                                    <option value="Diproses"
                                        <?= $status == 'Diproses' ? 'selected' : ''; ?>>
                                        Diproses
                                    </option>

                                    <option value="Selesai"
                                        <?= $status == 'Selesai' ? 'selected' : ''; ?>>
                                        Selesai
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
                                        href="kerusakan.php"
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
                                <i class="bi bi-exclamation-triangle fs-3 text-primary"></i>
                            </div>

                            <div>
                                <small class="text-muted d-block">
                                    Total Data
                                </small>

                                <h3 class="fw-bold mb-0">
                                    <?= $totalData; ?>
                                </h3>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body d-flex align-items-center">

                            <div class="rounded-circle bg-secondary bg-opacity-10 p-3 me-3">
                                <i class="bi bi-hourglass-split fs-3 text-secondary"></i>
                            </div>

                            <div>
                                <small class="text-muted d-block">
                                    Menunggu
                                </small>

                                <h3 class="fw-bold mb-0">
                                    <?= $totalMenunggu; ?>
                                </h3>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body d-flex align-items-center">

                            <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                <i class="bi bi-tools fs-3 text-warning"></i>
                            </div>

                            <div>
                                <small class="text-muted d-block">
                                    Diproses
                                </small>

                                <h3 class="fw-bold mb-0">
                                    <?= $totalDiproses; ?>
                                </h3>
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
                                <small class="text-muted d-block">
                                    Selesai
                                </small>

                                <h3 class="fw-bold mb-0">
                                    <?= $totalSelesai; ?>
                                </h3>
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
                                Data Laporan Kerusakan
                            </h5>

                            <small class="text-muted">
                                Menampilkan seluruh data kerusakan sesuai filter yang dipilih.
                            </small>

                        </div>

                        <div class="d-flex gap-2">

                            <a href="kerusakan_excel.php"
                                class="btn btn-outline-success btn-sm">

                                <i class="bi bi-file-earmark-excel-fill me-1"></i>
                                Excel

                            </a>

                            <a href="kerusakan_pdf.php"
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
                                    <th>Kode Kerusakan</th>
                                    <th>Tanggal</th>
                                    <th>Barang</th>
                                    <th>Pelapor</th>
                                    <th>Tingkat</th>
                                    <th>Status</th>
                                    <th width="10%">Aksi</th>

                                </tr>

                            </thead>

                            <tbody>

                            <tr>
                                <td>1</td>
                                <td>TES</td>
                                <td>22-07-2026</td>
                                <td>Barang Tes</td>
                                <td>Salsa</td>
                                <td>Ringan</td>
                                <td>Menunggu</td>
                                <td>OK</td>
                            </tr>

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