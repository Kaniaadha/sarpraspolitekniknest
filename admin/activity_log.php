<?php
session_start();

$menu = "activity_log";

// Cek apakah admin sudah login
if (!isset($_SESSION['id_admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";

// Mengambil data activity log
$query = mysqli_query($conn, "
    SELECT
        al.*,
        a.nama_admin
    FROM activity_log al
    INNER JOIN admin a
        ON al.id_admin = a.id_admin
    ORDER BY al.created_at DESC
");

// Statistik activity log
$total = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM activity_log
"));

$hariIni = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM activity_log
    WHERE DATE(created_at) = CURDATE()
"));

$mingguIni = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM activity_log
    WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)
"));

require_once "../includes/header.php";
require_once "../includes/navbar.php";
require_once "../includes/sidebar.php";
?>

<style>
@media (max-width: 767.98px) {

    .app-content-header .d-flex {
        display: flex !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 8px;
    }

    .app-content-header h2 {
        margin-bottom: 0 !important;
    }

    .app-content-header .breadcrumb {
        margin: 0 !important;
        padding: 0 !important;
        flex-wrap: wrap;
    }

}
</style>

<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold mb-0">Activity Log</h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active">Activity Log</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        <!-- Statistik -->
        <div class="row g-3 mb-4">

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Total Aktivitas</h6>
                        <h2 class="fw-bold"><?= $total['total']; ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Aktivitas Hari Ini</h6>
                        <h2 class="fw-bold"><?= $hariIni['total']; ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Aktivitas Minggu Ini</h6>
                        <h2 class="fw-bold"><?= $mingguIni['total']; ?></h2>
                    </div>
                </div>
            </div>

        </div>

        <!-- Tabel Activity Log -->
        <div class="card border-0 shadow-sm">

            <div class="card-header py-3">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Riwayat Aktivitas Admin
                </h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle datatable">

                        <thead class="table-secondary">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="20%">Tanggal & Waktu</th>
                                <th width="20%">Admin</th>
                                <th width="20%">Aktivitas</th>
                                <th width="20%">Modul</th>
                                <th width="10%" class="text-center">ID Data</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $no = 1;

                            while ($row = mysqli_fetch_assoc($query)) :
                            ?>

                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>

                                    <td>
                                        <?= date('d-m-Y H:i', strtotime($row['created_at'])); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_admin']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['aktivitas']); ?>
                                    </td>

                                    <td>
                                        <?= !empty($row['tabel_terkait'])
                                            ? htmlspecialchars($row['tabel_terkait'])
                                            : '-'; ?>
                                    </td>

                                    <td class="text-center">
                                        <?= !empty($row['id_data'])
                                            ? $row['id_data']
                                            : '-'; ?>
                                    </td>
                                </tr>

                            <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</main>

<?php
require_once "../includes/footer.php";
require_once "../includes/scripts.php";
?>