<?php
session_start();

$menu = "pelaporan";

// Cek login admin
if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

// Template admin
require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";
?>

<main class="app-main">

    <!-- Header halaman -->
    <div class="app-content-header">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="fw-bold mb-0">
                    Data Pelaporan
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

                    <li class="breadcrumb-item active">
                        Pelaporan
                    </li>

                </ol>

            </div>

        </div>
    </div>


    <!-- Isi halaman -->
    <div class="app-content">
        <div class="container-fluid">

            <!-- Pilihan jenis laporan -->
            <div class="row g-4">


                <!-- Laporan Kerusakan -->
                <div class="col-md-6">

                    <div class="card border-0 shadow-sm h-100 laporan-card">

                        <div class="card-body p-4">

                            <div class="d-flex align-items-center">

                                <div class="laporan-icon icon-kerusakan me-3">
                                    <i class="bi bi-tools"></i>
                                </div>

                                <div>
                                    <h5 class="fw-bold text-dark mb-1">
                                        Laporan Kerusakan
                                    </h5>

                                    <p class="text-muted mb-0">
                                        Data laporan kerusakan sarana
                                        dan prasarana yang masuk.
                                    </p>
                                </div>

                            </div>

                            <div class="text-end mt-4">

                                <a href="<?= BASE_URL; ?>/admin/transaksi/pelaporan/kerusakan.php"
                                class="btn btn-laporan btn-sm">

                                    Lihat Detail
                                    <i class="bi bi-arrow-right ms-1"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Laporan Kehilangan -->
                <div class="col-md-6">

                    <div class="card border-0 shadow-sm h-100 laporan-card">

                        <div class="card-body p-4">

                            <div class="d-flex align-items-center">

                                <div class="laporan-icon icon-kehilangan me-3">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                </div>

                                <div>
                                    <h5 class="fw-bold text-dark mb-1">
                                        Laporan Kehilangan
                                    </h5>

                                    <p class="text-muted mb-0">
                                        Data laporan kehilangan sarana
                                        dan prasarana yang masuk.
                                    </p>
                                </div>

                            </div>

                            <div class="text-end mt-4">

                                <a href="<?= BASE_URL; ?>/admin/transaksi/pelaporan/kehilangan.php"
                                class="btn btn-laporan btn-sm">

                                    Lihat Detail
                                    <i class="bi bi-arrow-right ms-1"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

</main>


<style>

.laporan-card {
    border: 0;
    border-radius: 12px;
    transition: .2s ease;
}

.laporan-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,.10) !important;
}

.laporan-icon {
    width: 58px;
    height: 58px;
    min-width: 58px;
    border-radius: 12px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 25px;
}

.icon-kerusakan {
    background: rgba(220, 53, 69, .12);
    color: #dc3545;
}

.icon-kehilangan {
    background: rgba(255, 193, 7, .18);
    color: #d39e00;
}

.btn-laporan {
    background: #ff4f70;
    border: 1px solid #ff4f70;
    color: #fff;
    border-radius: 6px;
    font-weight: 500;
}

.btn-laporan:hover {
    background: #e94363;
    border-color: #e94363;
    color: #fff;
}

/* Tampilan HP */
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
        margin-bottom: 0;
    }

    .app-content {
        padding: 10px 12px;
    }

    .laporan-card .card-body {
        padding: 16px !important;
    }

    .laporan-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        font-size: 21px;
    }

    .laporan-card h5 {
        font-size: 15px;
    }

    .laporan-card p {
        font-size: 12px;
        line-height: 1.45;
    }

}

</style>


<?php
require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";
?>