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

// Barang Rusak
$rusak = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM inventaris
        WHERE kondisi='Rusak'
    ")
);

?>

<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="mb-0">Dashboard</h2>

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
                            Halo, <?= $_SESSION['nama_admin']; ?> 👋
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
            <div class="col-xl-3 col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="bi bi-building dashboard-icon"></i>
                        <h6>Gedung</h6>
                        <h2><?= $gedung['total']; ?></h2>
                    </div>
                </div>
            </div>

            <!-- Ruangan -->
            <div class="col-xl-3 col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="bi bi-door-open dashboard-icon"></i>
                        <h6>Ruangan</h6>
                        <h2><?= $ruangan['total']; ?></h2>
                    </div>
                </div>
            </div>

            <!-- Inventaris -->
            <div class="col-xl-3 col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="bi bi-box-seam dashboard-icon"></i>
                        <h6>Inventaris</h6>
                        <h2><?= $inventaris['total']; ?></h2>
                    </div>
                </div>
            </div>

            <!-- Public Space -->
            <div class="col-xl-3 col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="bi bi-tree dashboard-icon"></i>
                        <h6>Public Space</h6>
                        <h2><?= $public['total']; ?></h2>
                    </div>
                </div>
            </div>

            <!-- Peminjaman Aktif -->
            <div class="col-xl-3 col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="bi bi-arrow-left-right dashboard-icon text-primary"></i>
                        <h6>Peminjaman Aktif</h6>
                        <h2>0</h2>
                    </div>
                </div>
            </div>

            <!-- Laporan Masuk -->
            <div class="col-xl-3 col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="bi bi-envelope-paper dashboard-icon text-danger"></i>
                        <h6>Laporan Masuk</h6>
                        <h2>0</h2>
                    </div>
                </div>
            </div>

            <!-- Barang Rusak -->
            <div class="col-xl-3 col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="bi bi-exclamation-triangle dashboard-icon text-warning"></i>
                        <h6>Barang Rusak</h6>
                        <h2><?= $rusak['total']; ?></h2>
                    </div>
                </div>
            </div>

            <!-- Stock Opname -->
            <div class="col-xl-3 col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-check dashboard-icon text-success"></i>
                        <h6>Stock Opname</h6>
                        <small class="text-muted">
                            Belum Dilakukan
                        </small>
                    </div>
                </div>
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