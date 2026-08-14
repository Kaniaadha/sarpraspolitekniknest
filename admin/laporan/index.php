<?php
session_start();

$menu = "laporan";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../login.php");
    exit;
}

$page_title = "Laporan";

require_once "../../includes/header.php";
require_once "../../includes/navbar.php";
require_once "../../includes/sidebar.php";
?>

<main class="app-main">

    <!-- Content Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h2 class="fw-bold mb-0">Laporan</h2>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item">
                            <a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Laporan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">

                <!-- Laporan Inventaris -->
                <div class="col-xl-4 col-md-6">
                    <div class="card laporan-card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body text-center p-4 d-flex flex-column">
                            <div class="mb-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center" style="width:90px;height:90px;">
                                    <i class="bi bi-box-seam-fill fs-1 text-primary"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold text-primary mb-3">Laporan Inventaris</h4>
                            <p class="text-muted lh-lg flex-grow-1">
                                Menampilkan seluruh data inventaris beserta kondisi,
                                kategori, dan lokasi penempatannya.
                            </p>
                            <a href="inventaris.php" class="btn btn-primary rounded-pill px-4 py-2 mt-3">
                                <i class="bi bi-eye-fill me-2"></i>Lihat Laporan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Laporan Peminjaman -->
                <div class="col-xl-4 col-md-6">
                    <div class="card laporan-card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body text-center p-4 d-flex flex-column">
                            <div class="mb-4">
                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center" style="width:90px;height:90px;">
                                    <i class="bi bi-arrow-left-right fs-1 text-success"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold text-success mb-3">Laporan Peminjaman</h4>
                            <p class="text-muted lh-lg flex-grow-1">
                                Menampilkan riwayat peminjaman barang maupun ruangan,
                                lengkap dengan status peminjaman.
                            </p>
                            <a href="peminjaman.php" class="btn btn-success rounded-pill px-4 py-2 mt-3">
                                <i class="bi bi-eye-fill me-2"></i>Lihat Laporan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Laporan Stock Opname -->
                <div class="col-xl-4 col-md-6">
                    <div class="card laporan-card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body text-center p-4 d-flex flex-column">
                            <div class="mb-4">
                                <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center" style="width:90px;height:90px;">
                                    <i class="bi bi-clipboard-check-fill fs-1 text-warning"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold text-warning mb-3">Laporan Stock Opname</h4>
                            <p class="text-muted lh-lg flex-grow-1">
                                Menampilkan hasil kegiatan stock opname beserta status
                                pelaksanaan dan hasil pemeriksaannya.
                            </p>
                            <a href="stock_opname.php" class="btn btn-warning rounded-pill px-4 py-2 mt-3">
                                <i class="bi bi-eye-fill me-2"></i>Lihat Laporan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Laporan Kerusakan -->
                <div class="col-xl-4 col-md-6">
                    <div class="card laporan-card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body text-center p-4 d-flex flex-column">
                            <div class="mb-4">
                                <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center" style="width:90px;height:90px;">
                                    <i class="bi bi-tools fs-1 text-danger"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold text-danger mb-3">Laporan Kerusakan</h4>
                            <p class="text-muted lh-lg flex-grow-1">
                                Menampilkan laporan kerusakan sarana dan prasarana yang
                                telah dilaporkan serta status penanganannya.
                            </p>
                            <a href="kerusakan.php" class="btn btn-danger rounded-pill px-4 py-2 mt-3">
                                <i class="bi bi-eye-fill me-2"></i>Lihat Laporan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Laporan Kehilangan -->
                <div class="col-xl-4 col-md-6">
                    <div class="card laporan-card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body text-center p-4 d-flex flex-column">
                            <div class="mb-4">
                                <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center" style="width:90px;height:90px;">
                                    <i class="bi bi-exclamation-triangle-fill fs-1 text-secondary"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold text-secondary mb-3">Laporan Kehilangan</h4>
                            <p class="text-muted lh-lg flex-grow-1">
                                Menampilkan laporan kehilangan sarana dan prasarana yang
                                telah dilaporkan serta status penanganannya.
                            </p>
                            <a href="kehilangan.php" class="btn btn-secondary rounded-pill px-4 py-2 mt-3">
                                <i class="bi bi-eye-fill me-2"></i>Lihat Laporan
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<style>
.laporan-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.laporan-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12) !important;
}
</style>

</main>

<?php
require_once "../../includes/footer.php";
require_once "../../includes/scripts.php";
?>