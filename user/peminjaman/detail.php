<?php
session_start();

require_once '../../config/database.php';

$baseUrl = "../../";
$currentPage = 'peminjaman';

// ==============================
// Ambil ID Peminjaman
// ==============================

$id_peminjaman = (int) ($_GET['id'] ?? 0);

if ($id_peminjaman <= 0) {
    $_SESSION['user_error'] = "ID peminjaman tidak ditemukan.";
    header("Location: index.php");
    exit;
}

// ==============================
// Ambil Data Peminjaman
// ==============================

$queryPeminjaman = mysqli_query($conn, "
    SELECT
        p.id_peminjaman,
        p.kode_peminjaman,
        p.nama_peminjam,
        p.nim_nip,
        p.no_hp,
        p.email,
        p.tanggal_pinjam,
        p.tanggal_kembali,
        p.tujuan_peminjaman,
        p.status
    FROM peminjaman p
    WHERE p.id_peminjaman = '$id_peminjaman'
    LIMIT 1
");

if (!$queryPeminjaman || mysqli_num_rows($queryPeminjaman) == 0) {
    $_SESSION['user_error'] = "Data peminjaman tidak ditemukan.";
    header("Location: index.php");
    exit;
}

$peminjaman = mysqli_fetch_assoc($queryPeminjaman);

// ==============================
// Ambil Detail Barang
// ==============================

$queryDetail = mysqli_query($conn, "
    SELECT
        dp.id_detail,
        dp.id_inventaris,
        dp.jumlah,
        dp.kondisi_sebelum,
        dp.kondisi_sesudah,
        dp.catatan,
        i.kode_inventaris,
        i.nama_barang
    FROM detail_peminjaman dp
    INNER JOIN inventaris i
        ON dp.id_inventaris = i.id_inventaris
    WHERE dp.id_peminjaman = '$id_peminjaman'
    ORDER BY dp.id_detail ASC
");

if (!$queryDetail || mysqli_num_rows($queryDetail) == 0) {
    $_SESSION['user_error'] = "Detail barang peminjaman tidak ditemukan.";
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<?php include '../../includes/user/header.php'; ?>

<body>

<?php include '../../includes/user/navbar.php'; ?>

<style>

/*==================================================
PAGE
==================================================*/

.detail-peminjaman-page {
    background: #fff8fc;
    min-height: 100vh;
}

/*==================================================
HERO
==================================================*/

.detail-hero {
    background:
        linear-gradient(
            135deg,
            #EC4899 0%,
            #FF7A45 100%
        );

    padding: 55px 0 90px;
    color: #fff;
}

.detail-hero .breadcrumb-custom {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 18px;
    font-size: 14px;
}

.detail-hero .breadcrumb-custom a {
    color: #fff;
    text-decoration: none;
}

.detail-hero .breadcrumb-custom span {
    color: rgba(255,255,255,.8);
}

.detail-hero h1 {
    font-size: 38px;
    font-weight: 800;
    margin-bottom: 8px;
}

.detail-hero p {
    margin: 0;
    color: rgba(255,255,255,.9);
}

/*==================================================
CONTENT
==================================================*/

.detail-section {
    padding: 55px 0 70px;
    margin-top: -35px;
    position: relative;
    z-index: 2;
}

/*==================================================
CARD
==================================================*/

.detail-card {
    background: #fff;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 8px 25px rgba(0,0,0,.06);
    margin-bottom: 25px;
}

.detail-card-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 20px;
    font-weight: 800;
    margin-bottom: 25px;
}

.detail-card-title i {
    color: #EC4899;
}

/*==================================================
INFO
==================================================*/

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
}

.info-item {
    background: #fff8fc;
    border-radius: 14px;
    padding: 16px 18px;
}

.info-label {
    display: block;
    color: #6B7280;
    font-size: 13px;
    margin-bottom: 5px;
}

.info-value {
    color: #374151;
    font-size: 15px;
    font-weight: 600;
}

/*==================================================
STATUS
==================================================*/

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 7px 15px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 700;
}

.status-menunggu {
    background: #FEF3C7;
    color: #92400E;
}

.status-dipinjam {
    background: #DBEAFE;
    color: #1D4ED8;
}

.status-pengembalian {
    background: #CFFAFE;
    color: #0E7490;
}

.status-selesai {
    background: #D1FAE5;
    color: #047857;
}

.status-ditolak {
    background: #FEE2E2;
    color: #B91C1C;
}

.status-default {
    background: #F3F4F6;
    color: #4B5563;
}

/*==================================================
BARANG
==================================================*/

.barang-detail {
    border: 1px solid #F3E8EF;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 15px;
}

.barang-detail:last-child {
    margin-bottom: 0;
}

.barang-detail-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.barang-detail h5 {
    margin: 0 0 4px;
    font-size: 17px;
    font-weight: 700;
}

.barang-kode {
    color: #6B7280;
    font-size: 13px;
}

.barang-detail-info {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.barang-detail-item {
    background: #fff8fc;
    border-radius: 12px;
    padding: 14px;
}

.barang-detail-item span {
    display: block;
    color: #6B7280;
    font-size: 12px;
    margin-bottom: 4px;
}

.barang-detail-item strong {
    color: #374151;
    font-size: 14px;
}

/*==================================================
TEXT
==================================================*/

.text-box {
    background: #fff8fc;
    border-radius: 14px;
    padding: 18px;
    color: #4B5563;
    line-height: 1.7;
}

/*==================================================
BUTTON
==================================================*/

.btn-kembali {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    border-radius: 50px;
    padding: 11px 22px;
    background: linear-gradient(135deg, #EC4899, #FF7A45);
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: .3s;
}

.btn-kembali:hover {
    color: #fff;
    transform: translateY(-2px);
}

/*==================================================
RESPONSIVE
==================================================*/

@media(max-width:768px) {

    .detail-hero h1 {
        font-size: 30px;
    }

    .detail-card {
        padding: 22px;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .barang-detail-info {
        grid-template-columns: 1fr;
    }

    .barang-detail-header {
        display: block;
    }

}

</style>

<main class="detail-peminjaman-page">

    <!-- Hero -->
    <section class="detail-hero">

        <div class="container">

            <div class="breadcrumb-custom">

                <a href="<?= $baseUrl ?>index.php">
                    Beranda
                </a>

                <span>/</span>

                <a href="index.php">
                    Peminjaman
                </a>

                <span>/</span>

                <span>Detail</span>

            </div>

            <h1>
                Detail Peminjaman
            </h1>

            <p>
                Informasi lengkap pengajuan peminjaman sarana dan prasarana.
            </p>

        </div>

    </section>

    <!-- Content -->
    <section class="detail-section">

        <div class="container">

            <!-- Informasi Peminjaman -->
            <div class="detail-card">

                <div class="detail-card-title">

                    <i class="bi bi-file-earmark-text-fill"></i>

                    Informasi Peminjaman

                </div>

                <div class="info-grid">

                    <div class="info-item">

                        <span class="info-label">
                            Kode Peminjaman
                        </span>

                        <span class="info-value">
                            <?= htmlspecialchars($peminjaman['kode_peminjaman']); ?>
                        </span>

                    </div>

                    <div class="info-item">

                        <span class="info-label">
                            Status
                        </span>

                        <?php
                        switch ($peminjaman['status']) {

                            case 'Menunggu':
                                $statusClass = 'status-menunggu';
                                break;

                            case 'Dipinjam':
                                $statusClass = 'status-dipinjam';
                                break;

                            case 'Menunggu Pengembalian':
                                $statusClass = 'status-pengembalian';
                                break;

                            case 'Selesai':
                                $statusClass = 'status-selesai';
                                break;

                            case 'Ditolak':
                                $statusClass = 'status-ditolak';
                                break;

                            default:
                                $statusClass = 'status-default';
                                break;
                        }
                        ?>

                        <span class="status-badge <?= $statusClass; ?>">
                            <?= htmlspecialchars($peminjaman['status']); ?>
                        </span>

                    </div>

                    <div class="info-item">

                        <span class="info-label">
                            Tanggal Pinjam
                        </span>

                        <span class="info-value">
                            <?= date('d-m-Y', strtotime($peminjaman['tanggal_pinjam'])); ?>
                        </span>

                    </div>

                    <div class="info-item">

                        <span class="info-label">
                            Tanggal Kembali
                        </span>

                        <span class="info-value">
                            <?= date('d-m-Y', strtotime($peminjaman['tanggal_kembali'])); ?>
                        </span>

                    </div>

                </div>

            </div>

            <!-- Data Peminjam -->
            <div class="detail-card">

                <div class="detail-card-title">

                    <i class="bi bi-person-fill"></i>

                    Data Peminjam

                </div>

                <div class="info-grid">

                    <div class="info-item">

                        <span class="info-label">
                            Nama Peminjam
                        </span>

                        <span class="info-value">
                            <?= htmlspecialchars($peminjaman['nama_peminjam']); ?>
                        </span>

                    </div>

                    <div class="info-item">

                        <span class="info-label">
                            NIM / NIP
                        </span>

                        <span class="info-value">
                            <?= htmlspecialchars($peminjaman['nim_nip']); ?>
                        </span>

                    </div>

                    <div class="info-item">

                        <span class="info-label">
                            No. HP
                        </span>

                        <span class="info-value">
                            <?= htmlspecialchars($peminjaman['no_hp']); ?>
                        </span>

                    </div>

                    <div class="info-item">

                        <span class="info-label">
                            Email
                        </span>

                        <span class="info-value">
                            <?= htmlspecialchars($peminjaman['email']); ?>
                        </span>

                    </div>

                </div>

            </div>

            <!-- Detail Barang -->
            <div class="detail-card">

                <div class="detail-card-title">

                    <i class="bi bi-box-seam-fill"></i>

                    Detail Barang

                </div>

                <?php while ($barang = mysqli_fetch_assoc($queryDetail)) : ?>

                    <div class="barang-detail">

                        <div class="barang-detail-header">

                            <div>

                                <h5>
                                    <?= htmlspecialchars($barang['nama_barang']); ?>
                                </h5>

                                <span class="barang-kode">
                                    <?= htmlspecialchars($barang['kode_inventaris']); ?>
                                </span>

                            </div>

                        </div>

                        <div class="barang-detail-info">

                            <div class="barang-detail-item">

                                <span>
                                    Jumlah
                                </span>

                                <strong>
                                    <?= (int) $barang['jumlah']; ?> unit
                                </strong>

                            </div>

                            <div class="barang-detail-item">

                                <span>
                                    Kondisi Sebelum
                                </span>

                                <strong>
                                    <?= htmlspecialchars($barang['kondisi_sebelum']); ?>
                                </strong>

                            </div>

                            <div class="barang-detail-item">

                                <span>
                                    Kondisi Sesudah
                                </span>

                                <strong>
                                    <?= !empty($barang['kondisi_sesudah'])
                                        ? htmlspecialchars($barang['kondisi_sesudah'])
                                        : '-'; ?>
                                </strong>

                            </div>

                        </div>

                        <?php if (!empty($barang['catatan'])) : ?>

                            <div class="text-box mt-3">

                                <strong>
                                    Catatan
                                </strong>

                                <br>

                                <?= nl2br(htmlspecialchars($barang['catatan'])); ?>

                            </div>

                        <?php endif; ?>

                    </div>

                <?php endwhile; ?>

            </div>

            <!-- Tujuan Peminjaman -->
            <div class="detail-card">

                <div class="detail-card-title">

                    <i class="bi bi-chat-left-text-fill"></i>

                    Tujuan Peminjaman

                </div>

                <div class="text-box">

                    <?= nl2br(htmlspecialchars($peminjaman['tujuan_peminjaman'])); ?>

                </div>

            </div>

            <div class="text-end">

                <a href="index.php" class="btn-kembali">

                    <i class="bi bi-arrow-left"></i>

                    Kembali ke Peminjaman

                </a>

            </div>

        </div>

    </section>

</main>

<?php include '../../includes/user/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION['user_success'])) : ?>

<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: <?= json_encode($_SESSION['user_success']); ?>,
    confirmButtonText: 'OK',
    customClass: {
        popup: 'user-swal-popup',
        title: 'user-swal-title',
        htmlContainer: 'user-swal-text',
        confirmButton: 'user-swal-button'
    },
    buttonsStyling: false
});
</script>

<?php
unset($_SESSION['user_success']);
endif;
?>

<?php if (isset($_SESSION['user_error'])) : ?>

<script>
Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: <?= json_encode($_SESSION['user_error']); ?>,
    confirmButtonText: 'OK',
    customClass: {
        popup: 'user-swal-popup',
        title: 'user-swal-title',
        htmlContainer: 'user-swal-text',
        confirmButton: 'user-swal-button'
    },
    buttonsStyling: false
});
</script>

<?php
unset($_SESSION['user_error']);
endif;
?>

</body>
</html>