<?php
session_start();

$menu = "pelaporan";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";
require_once "../../../helpers/activity_log.php";

$id_kehilangan = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id_kehilangan <= 0) {
    header("Location: kehilangan.php");
    exit;
}

// Simpan catatan admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $catatan_admin = trim($_POST['catatan_admin'] ?? '');

    $stmtUpdate = mysqli_prepare($conn, "
        UPDATE kehilangan
        SET catatan_admin = ?
        WHERE id_kehilangan = ?
    ");

    if ($stmtUpdate) {
        mysqli_stmt_bind_param($stmtUpdate, "si", $catatan_admin, $id_kehilangan);

        if (mysqli_stmt_execute($stmtUpdate)) {

            simpanActivityLog(
                $conn,
                $_SESSION['id_admin'],
                "Memperbarui Catatan Laporan Kehilangan",
                "kehilangan",
                $id_kehilangan
            );

            $_SESSION['success'] = "Catatan admin berhasil disimpan.";
        } else {
            $_SESSION['error'] = "Catatan admin gagal disimpan.";
        }

        mysqli_stmt_close($stmtUpdate);
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat menyimpan catatan.";
    }

    header("Location: detail_kehilangan.php?id=" . $id_kehilangan);
    exit;
}

// Ambil data laporan
$stmt = mysqli_prepare($conn, "
    SELECT
        k.id_kehilangan,
        k.kode_kehilangan,
        k.tanggal_lapor,
        k.nama_pelapor,
        k.catatan_admin,
        dk.lokasi_kehilangan,
        dk.kronologi,
        i.kode_inventaris,
        i.nama_barang,
        i.kondisi
    FROM kehilangan k
    INNER JOIN detail_kehilangan dk
        ON k.id_kehilangan = dk.id_kehilangan
    INNER JOIN inventaris i
        ON dk.id_inventaris = i.id_inventaris
    WHERE k.id_kehilangan = ?
    LIMIT 1
");

mysqli_stmt_bind_param($stmt, "i", $id_kehilangan);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$data) {
    header("Location: kehilangan.php");
    exit;
}

require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";
?>

<main class="app-main">

    <!-- HEADER -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold mb-0">Detail Laporan Kehilangan</h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL; ?>/admin/dashboard.php">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL; ?>/admin/transaksi/pelaporan/index.php">Pelaporan</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="kehilangan.php">Kehilangan</a>
                    </li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="app-content">
        <div class="container-fluid">

            <!-- INFORMASI LAPORAN -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-file-earmark-text-fill text-danger me-2"></i>
                        Informasi Laporan
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row g-4">

                        <div class="col-md-4">
                            <label class="text-muted small">Kode Laporan</label>
                            <div class="fw-semibold">
                                <?= htmlspecialchars($data['kode_kehilangan']); ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="text-muted small">Tanggal Laporan</label>
                            <div class="fw-semibold">
                                <?= date('d-m-Y', strtotime($data['tanggal_lapor'])); ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="text-muted small">Nama Pelapor</label>
                            <div class="fw-semibold">
                                <?= htmlspecialchars($data['nama_pelapor']); ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- DETAIL KEHILANGAN -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-box-seam-fill text-danger me-2"></i>
                        Detail Kehilangan
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="text-muted small">Inventaris</label>
                            <div class="fw-semibold">
                                <?= htmlspecialchars($data['nama_barang']); ?>
                                <br>
                                <small class="text-muted">
                                    <?= htmlspecialchars($data['kode_inventaris']); ?>
                                </small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Kondisi Terakhir</label>
                            <div class="fw-semibold">
                                <?= htmlspecialchars($data['kondisi']); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Lokasi Kehilangan</label>
                            <div class="fw-semibold">
                                <?= htmlspecialchars($data['lokasi_kehilangan']); ?>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="text-muted small">Kronologi Kehilangan</label>
                            <div class="detail-box">
                                <?= nl2br(htmlspecialchars($data['kronologi'])); ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- CATATAN ADMIN -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-pencil-square text-danger me-2"></i>
                        Catatan Admin
                    </h5>
                </div>

                <div class="card-body">

                    <?php if (isset($_SESSION['success'])) : ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            <?= htmlspecialchars($_SESSION['success']); ?>
                        </div>
                    <?php unset($_SESSION['success']); endif; ?>

                    <?php if (isset($_SESSION['error'])) : ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <?= htmlspecialchars($_SESSION['error']); ?>
                        </div>
                    <?php unset($_SESSION['error']); endif; ?>

                    <form method="POST">
                        <textarea
                            name="catatan_admin"
                            class="form-control"
                            rows="4"
                            maxlength="2000"
                            placeholder="Tambahkan catatan atau tindak lanjut laporan..."><?= htmlspecialchars($data['catatan_admin'] ?? ''); ?></textarea>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-save me-1"></i>
                                Simpan Catatan
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <!-- KEMBALI -->
            <div class="d-flex justify-content-start mb-4">
                <a href="kehilangan.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali
                </a>
            </div>

        </div>
    </div>

</main>

<style>
.detail-box {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 14px 16px;
    line-height: 1.6;
    min-height: 80px;
}

.card-body label {
    display: block;
    margin-bottom: 5px;
}

@media (max-width: 767.98px) {
    .app-content-header {
        padding: 15px 12px 5px;
    }

    .app-content-header .d-flex {
        display: flex !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 8px;
    }

    .app-content-header h2 {
        font-size: 22px;
        margin-bottom: 8px !important;
    }

    .app-content-header .breadcrumb {
        font-size: 13px;
        margin: 0 !important;
        padding: 0 !important;
        flex-wrap: wrap;
    }

    .app-content {
        padding: 10px 12px;
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