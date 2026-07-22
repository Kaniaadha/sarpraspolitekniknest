<?php
session_start();
$menu = "dashboard";
if (!isset($_SESSION['id_admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";
require_once "../includes/header.php";
require_once "../includes/navbar.php";
require_once "../includes/sidebar.php";

// Jumlah Gedung
$gedung = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM lokasi")
);

// Jumlah Ruangan
$ruangan = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM ruangan")
);

// Jumlah Inventaris
$inventaris = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM inventaris")
);

// Jumlah Public Space
$public = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM public_space")
);

// Jumlah APAR
$apar = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM inventaris i
        INNER JOIN kategori k
            ON i.id_kategori = k.id_kategori
        WHERE k.nama_kategori = 'APAR'
    ")
);

// Barang Rusak
$rusak = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM inventaris
        WHERE kondisi='Rusak'
    ")
);

// ==============================
// Monitoring (Dummy Data)
// ==============================

$menunggu = 5;

$dipinjam = 12;

$terlambat = 2;

$stockTerakhir = "20 Juli 2026";

?>

<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="mb-0">Dashboard Admin</h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>

            </div>

        </div>
    </div>

    <div class="container-fluid">

        <!-- Greeting -->
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h2 class="fw-bold mb-2">
                            Halo, <?= $_SESSION['nama_admin']; ?>
                        </h2>

                        <p class="text-muted mb-0">
                            Selamat datang di Sistem Informasi Sarana dan Prasarana
                            Politeknik Nest.
                        </p>

                    </div>

                    <i class="bi bi-building-fill-check"
                        style="font-size:70px;color:#ff8a00;opacity:.15;"></i>

                </div>

            </div>

        </div>

        <!-- Statistik -->
        <div class="row g-3">

            <!-- Gedung -->
            <div class="col-xl col-lg-4 col-md-6">
                <a
                    href="master/lokasi/index.php"
                    class="text-decoration-none">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="icon-orange">
                                <i class="bi bi-building"></i>
                            </div>
                            <h6>Lokasi</h6>
                            <h2><?= $gedung['total']; ?></h2>
                            <span class="dashboard-link">
                                Lihat Detail
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Ruangan -->
            <div class="col-xl col-lg-4 col-md-6">
                <a href="master/ruangan/index.php"
                class="text-decoration-none">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="icon-purple">
                                <i class="bi bi-door-open"></i>
                            </div>
                            <h6>Ruangan</h6>
                            <h2><?= $ruangan['total']; ?></h2>
                            <span class="dashboard-link">
                                Lihat Detail
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Inventaris -->
            <div class="col-xl col-lg-4 col-md-6">
                <a href="master/inventaris/index.php"
                class="text-decoration-none">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="icon-green">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <h6>Inventaris</h6>
                            <h2><?= $inventaris['total']; ?></h2>
                            <span class="dashboard-link">
                                Lihat Detail
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Public Space -->
            <div class="col-xl col-lg-4 col-md-6">
                <a href="master/public_space/index.php"
                class="text-decoration-none">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="icon-pink">
                                <i class="bi bi-tree"></i>
                            </div>
                            <h6>Public Space</h6>
                            <h2><?= $public['total']; ?></h2>
                            <span class="dashboard-link">
                                Lihat Detail
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- APAR -->
            <div class="col-xl col-lg-4 col-md-6">
                <a
                    href="master/inventaris/index.php?kategori=APAR"
                    class="text-decoration-none">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="icon-red">
                                <i class="bi bi-fire"></i>
                            </div>
                            <h6>APAR</h6>
                            <h2><?= $apar['total']; ?></h2>
                            <span class="dashboard-link">
                                Lihat Detail
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Transaksi -->
        <div class="row mt-4">

            <!-- Menunggu -->
            <div class="col-lg-3 col-md-6 mb-4">
                <a href="transaksi/peminjaman/index.php?status=Menunggu" class="text-decoration-none">
                    <div class="card dashboard-card h-100">
                        <div class="card-body text-center">

                            <div class="icon-blue mb-3">
                                <i class="bi bi-hourglass-split"></i>
                            </div>

                            <h6 class="fw-bold">Menunggu Persetujuan</h6>

                            <h2 class="fw-bold">
                                <?= $menunggu ?>
                            </h2>

                            <span class="dashboard-link">
                                Lihat Detail
                                <i class="bi bi-arrow-right"></i>
                            </span>

                        </div>
                    </div>
                </a>
            </div>

            <!-- Dipinjam -->
            <div class="col-lg-3 col-md-6 mb-4">
                <a href="transaksi/peminjaman/index.php?status=Dipinjam" class="text-decoration-none">
                    <div class="card dashboard-card h-100">
                        <div class="card-body text-center">

                            <div class="icon-green mb-3">
                                <i class="bi bi-box-arrow-up"></i>
                            </div>

                            <h6 class="fw-bold">Sedang Dipinjam</h6>

                            <h2 class="fw-bold">
                                <?= $dipinjam ?>
                            </h2>

                            <span class="dashboard-link">
                                Lihat Detail
                                <i class="bi bi-arrow-right"></i>
                            </span>

                        </div>
                    </div>
                </a>
            </div>

            <!-- Terlambat -->
            <div class="col-lg-3 col-md-6 mb-4">
                <a href="transaksi/peminjaman/index.php?status=Terlambat" class="text-decoration-none">
                    <div class="card dashboard-card h-100">
                        <div class="card-body text-center">

                            <div class="icon-red mb-3">
                                <i class="bi bi-clock-history"></i>
                            </div>

                            <h6 class="fw-bold">Terlambat Mengembalikan</h6>

                            <h2 class="fw-bold">
                                <?= $terlambat ?>
                            </h2>

                            <span class="dashboard-link">
                                Lihat Detail
                                <i class="bi bi-arrow-right"></i>
                            </span>

                        </div>
                    </div>
                </a>
            </div>

            <!-- Stock Opname -->
            <div class="col-lg-3 col-md-6 mb-4">
                <a href="transaksi/stock-opname/index.php" class="text-decoration-none">
                    <div class="card dashboard-card h-100">
                        <div class="card-body text-center">

                            <div class="icon-orange mb-3">
                                <i class="bi bi-calendar-check"></i>
                            </div>

                            <h6 class="fw-bold">Stock Opname</h6>

                            <p class="mb-1 text-muted">
                                Terakhir :
                            </p>

                            <h5 class="fw-bold">
                                <?= $stockTerakhir ?>
                            </h5>

                            <span class="dashboard-link">
                                Lihat Detail
                                <i class="bi bi-arrow-right"></i>
                            </span>

                        </div>
                    </div>
                </a>
            </div>

        </div>           

        <!-- Grafik -->
        <div class="row mt-4">

            <div class="col-lg-6">

                <div class="card">

                    <div class="card-header">
                        <h5 class="mb-0">Inventaris per Kategori</h5>
                    </div>

                    <div class="card-body" style="height:300px;">
                        <canvas id="kategoriChart"></canvas>
                    </div>

                </div>

            </div>

            <div class="col-lg-6">

                <div class="card">

                    <div class="card-header">
                        <h5 class="mb-0">Statistik Peminjaman per Bulan</h5>
                    </div>

                    <div class="card-body" style="height:300px;">
                        <canvas id="peminjamanChart"></canvas>
                    </div>

                </div>

            </div>

        </div>

        <!-- Notifikasi -->
        <div class="row mt-4">

            <div class="col-lg-6">

                <div class="card">

                    <div class="card-header">
                        <h5 class="mb-0">Notifikasi Terbaru</h5>
                    </div>

                    <div class="card-body p-0">

                        <ul class="list-group list-group-flush">

                            <li class="list-group-item">
                                ⚠ Barang rusak dilaporkan
                                <br>
                                <small class="text-muted">5 menit lalu</small>
                            </li>

                            <li class="list-group-item">
                                📦 Pengajuan peminjaman baru
                                <br>
                                <small class="text-muted">20 menit lalu</small>
                            </li>

                            <li class="list-group-item">
                                📋 Stock opname bulan ini belum dilakukan
                                <br>
                                <small class="text-muted">Hari ini</small>
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

            <div class="col-lg-6">

                <div class="card">

                    <div class="card-header">
                        <h5 class="mb-0">Peminjaman Menunggu Persetujuan</h5>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-hover align-middle">

                                <thead>

                                    <tr>
                                        <th>Peminjam</th>
                                        <th>Barang</th>
                                        <th>Aksi</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <tr>

                                        <td>Andi</td>
                                        <td>Laptop Asus</td>

                                        <td>

                                            <button class="btn btn-success btn-sm">
                                                <i class="bi bi-check"></i>
                                            </button>

                                            <button class="btn btn-danger btn-sm">
                                                <i class="bi bi-x"></i>
                                            </button>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</main>

<?php

require_once "../includes/footer.php";
require_once "../includes/scripts.php";
?>