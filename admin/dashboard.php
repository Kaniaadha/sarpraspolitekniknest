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
// Monitoring Peminjaman
// ==============================

// Menunggu Persetujuan
$menunggu = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM peminjaman
        WHERE status = 'Menunggu'
    ")
);

// Sedang Dipinjam
$dipinjam = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM peminjaman
        WHERE status = 'Dipinjam'
    ")
);

// Terlambat Mengembalikan
$terlambat = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM peminjaman
        WHERE
            status = 'Dipinjam'
        AND
            tanggal_kembali < CURDATE()
    ")
);

// ==============================
// Activity Log Terbaru
// ==============================

$queryActivity = mysqli_query($conn, "
    SELECT
        al.aktivitas,
        al.created_at,
        a.nama_admin
    FROM activity_log al
    INNER JOIN admin a
        ON al.id_admin = a.id_admin
    ORDER BY al.created_at DESC
    LIMIT 1
");

$activity = mysqli_fetch_assoc($queryActivity);

// Menunggu Persetujuan
$queryMenunggu = mysqli_query($conn, "
    SELECT
        p.id_peminjaman,
        p.kode_peminjaman,
        p.nama_peminjam,
        COUNT(dp.id_detail) AS total_barang
    FROM peminjaman p
    INNER JOIN detail_peminjaman dp
        ON p.id_peminjaman = dp.id_peminjaman
    WHERE p.status = 'Menunggu'
    GROUP BY
        p.id_peminjaman,
        p.kode_peminjaman,
        p.nama_peminjam
    ORDER BY p.created_at DESC
    LIMIT 5
");

// ==============================
// Stock Opname Terakhir
// ==============================

$queryStockTerakhir = mysqli_query($conn, "
    SELECT tanggal
    FROM stock_opname
    WHERE status = 'Selesai'
    ORDER BY tanggal DESC
    LIMIT 1
");

$dataStock = mysqli_fetch_assoc($queryStockTerakhir);

if ($dataStock) {

    $stockTerakhir = date(
        'd F Y',
        strtotime($dataStock['tanggal'])
    );

    $jadwalBerikutnya = strtotime($dataStock['tanggal'] . ' +6 months');

} else {

    $stockTerakhir = "Belum Pernah";

    $jadwalBerikutnya = null;

}

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
                                <?= $menunggu['total']; ?>
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
                                <?= $dipinjam['total']; ?>
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
                                <?= $terlambat['total']; ?>
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
                <a href="transaksi/stock_opname/riwayat.php" class="text-decoration-none">
                    <div class="card dashboard-card h-100">
                        <div class="card-body text-center">

                            <div class="icon-orange mb-3">
                                <i class="bi bi-calendar-check"></i>
                            </div>

                            <h6 class="fw-bold">Stock Opname</h6>

                            <p class="fw-semibold text-dark mb-1">
                                Terakhir Dilakukan
                            </p>

                            <p class="fs-5 fw-semibold text-dark mb-4">
                                <?= $stockTerakhir; ?>
                            </p>

                            <p class="fw-semibold text-dark mb-1">
                                Jadwal Berikutnya
                            </p>

                            <?php if ($jadwalBerikutnya != null) : ?>

                                <?php if (time() >= $jadwalBerikutnya) : ?>

                                    <h6 class="fs-5 fw-semibold text-danger mb-3">
                                        Sudah Waktunya
                                    </h6>

                                <?php else : ?>

                                    <p class="fs-5 fw-semibold text-dark mb-3">
                                        <?= date('d F Y', $jadwalBerikutnya); ?>
                                    </p>

                                <?php endif; ?>

                            <?php else : ?>

                                <h6 class="fs-5 fw-semibold text-secondary mb-3">
                                    Belum Ada Jadwal
                                </h6>

                            <?php endif; ?>

                            <div class="mt-3">
                                <span class="dashboard-link">
                                    Lihat Detail
                                    <i class="bi bi-arrow-right"></i>
                                </span>
                            </div>

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

                                <?php if ($rusak['total'] > 0) : ?>

                                    ⚠ Terdapat <strong><?= $rusak['total']; ?></strong> inventaris rusak
                                    <br>
                                    <small class="text-muted">
                                        Segera lakukan pengecekan
                                    </small>

                                <?php else : ?>

                                    ✅ Tidak ada inventaris rusak
                                    <br>
                                    <small class="text-muted">
                                        Semua inventaris dalam kondisi baik
                                    </small>

                                <?php endif; ?>

                            </li>

                            <li class="list-group-item">

                                <?php if ($activity) : ?>

                                    🕒 <?= htmlspecialchars($activity['nama_admin']); ?>
                                    <?= htmlspecialchars($activity['aktivitas']); ?>

                                    <br>

                                    <small class="text-muted">
                                        <?= date('d M Y H:i', strtotime($activity['created_at'])); ?>
                                    </small>

                                <?php else : ?>

                                    🕒 Belum ada aktivitas

                                    <br>

                                    <small class="text-muted">-</small>

                                <?php endif; ?>

                            </li>

                            <li class="list-group-item">

                                <?php if ($jadwalBerikutnya != null) : ?>

                                    <?php if (time() >= $jadwalBerikutnya) : ?>

                                        📋 Stock Opname sudah waktunya dilakukan
                                        <br>
                                        <small class="text-danger">
                                            Segera lakukan stock opname
                                        </small>

                                    <?php else : ?>

                                        📋 Stock Opname berikutnya
                                        <br>
                                        <small class="text-muted">
                                            <?= date('d F Y', $jadwalBerikutnya); ?>
                                        </small>

                                    <?php endif; ?>

                                <?php else : ?>

                                    📋 Belum ada jadwal Stock Opname
                                    <br>
                                    <small class="text-muted">
                                        Silakan lakukan stock opname pertama
                                    </small>

                                <?php endif; ?>

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

                                    <th width="45%">
                                        Kode / Peminjam
                                    </th>

                                    <th width="25%" class="text-center">
                                        Jumlah Barang
                                    </th>

                                    <th width="30%" class="text-center">
                                        Aksi
                                    </th>

                                </tr>

                                </thead>

                                <tbody>

                            <?php if (mysqli_num_rows($queryMenunggu) > 0) : ?>

                                <?php while ($row = mysqli_fetch_assoc($queryMenunggu)) : ?>

                                    <tr>

                                        <td>
                                            <strong><?= htmlspecialchars($row['kode_peminjaman']); ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($row['nama_peminjam']); ?>
                                            </small>
                                        </td>

                                        <td class="text-center">

                                            <span class="badge bg-primary">

                                                <?= $row['total_barang']; ?> Barang

                                            </span>

                                        </td>

                                        <td>

                                            <a
                                                href="transaksi/peminjaman/detail.php?id=<?= $row['id_peminjaman']; ?>"
                                                class="btn btn-info btn-sm">

                                                <i class="bi bi-eye"></i>
                                                Detail

                                            </a>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                            <?php else : ?>

                                <tr>

                                    <td colspan="3" class="text-center text-muted">

                                        Tidak ada pengajuan peminjaman.

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

    </div>

</main>

<?php

require_once "../includes/footer.php";
require_once "../includes/scripts.php";
?>