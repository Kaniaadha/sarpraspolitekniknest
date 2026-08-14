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

// URL cetak mengikuti filter yang dipilih
$cetakUrl = "kehilangan_cetak.php";

if ($tanggalAwal !== '' || $tanggalAkhir !== '') {
    $params = [];

    if ($tanggalAwal !== '') {
        $params['tanggal_awal'] = $tanggalAwal;
    }

    if ($tanggalAkhir !== '') {
        $params['tanggal_akhir'] = $tanggalAkhir;
    }

    $cetakUrl .= '?' . http_build_query($params);
}

// Mengambil data laporan kehilangan
$query = mysqli_query($conn, "
    SELECT
        k.id_kehilangan,
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

require_once "../../includes/header.php";
require_once "../../includes/navbar.php";
require_once "../../includes/sidebar.php";
?>

<main class="app-main">

    <!-- Content Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold mb-0">Laporan Kehilangan</h2>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL ?>/admin/laporan/index.php">Laporan</a>
                    </li>
                    <li class="breadcrumb-item active">Kehilangan</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="app-content">
        <div class="container-fluid">

            <!-- Filter -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-funnel-fill text-primary me-2"></i>
                        Filter Laporan
                    </h5>
                </div>

                <div class="card-body">
                    <form method="GET">
                        <div class="row g-3 align-items-end">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tanggal Awal</label>
                                <input type="date" name="tanggal_awal" class="form-control" value="<?= htmlspecialchars($tanggalAwal) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tanggal Akhir</label>
                                <input type="date" name="tanggal_akhir" class="form-control" value="<?= htmlspecialchars($tanggalAkhir) ?>">
                            </div>

                            <div class="col-md-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search me-1"></i>
                                        Tampilkan Data
                                    </button>

                                    <a href="kehilangan.php" class="btn btn-outline-primary">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                                        Reset Filter
                                    </a>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- Data Laporan -->
            <div class="card border-0 shadow-sm">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="i bi-table me-2 text-primary"></i>
                            Daftar Laporan Kehilangan
                        </h5>

                        <div>
                            <a
                                href="<?= htmlspecialchars($cetakUrl); ?>"
                                target="_blank"
                                class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-printer-fill me-1"></i>
                                Cetak
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle datatable">
                            <thead class="table-secondary">
                                <tr>
                                    <th width="5%" class="text-center">No</th>
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
                                <?php
                                $no = 1;

                                if ($totalData > 0):
                                    while ($row = mysqli_fetch_assoc($query)):
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>

                                        <td>
                                            <strong><?= htmlspecialchars($row['kode_kehilangan']) ?></strong>
                                        </td>

                                        <td><?= date('d-m-Y', strtotime($row['tanggal_lapor'])) ?></td>

                                        <td><?= htmlspecialchars($row['nama_pelapor']) ?></td>

                                        <td>
                                            <strong><?= htmlspecialchars($row['nama_barang']) ?></strong><br>
                                            <small class="text-muted"><?= htmlspecialchars($row['kode_inventaris']) ?></small>
                                        </td>

                                        <td><?= htmlspecialchars($row['lokasi_kehilangan']) ?></td>

                                        <td><?= htmlspecialchars($row['kronologi']) ?></td>

                                        <td>
                                            <?= !empty($row['catatan_admin']) ? htmlspecialchars($row['catatan_admin']) : '-' ?>
                                        </td>
                                    </tr>
                                <?php
                                    endwhile;
                                else:
                                ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                                Belum ada laporan kehilangan.
                                            </div>
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