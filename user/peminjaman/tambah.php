<?php
session_start();

require_once '../../config/database.php';

$baseUrl = "../../";
$currentPage = 'peminjaman';

// ==============================
// Ambil ID Inventaris
// ==============================

$id_inventaris = isset($_GET['id_inventaris']) ? (int) $_GET['id_inventaris'] : 0;

if ($id_inventaris <= 0) {
    header("Location: index.php");
    exit;
}

// ==============================
// Ambil Data Inventaris
// ==============================

$queryInventaris = mysqli_query($conn, "
    SELECT
        i.id_inventaris,
        i.kode_inventaris,
        i.nama_barang,
        i.jumlah,
        i.kondisi,
        (
            i.jumlah -
            COALESCE((
                SELECT SUM(dp.jumlah)
                FROM detail_peminjaman dp
                INNER JOIN peminjaman p
                    ON dp.id_peminjaman = p.id_peminjaman
                WHERE dp.id_inventaris = i.id_inventaris
                AND p.status = 'Dipinjam'
            ), 0)
        ) AS stok_tersedia
    FROM inventaris i
    WHERE i.id_inventaris = '$id_inventaris'
    AND i.status = 'Aktif'
    HAVING stok_tersedia > 0
    LIMIT 1
");

if (!$queryInventaris || mysqli_num_rows($queryInventaris) == 0) {
    $_SESSION['error'] = "Barang tidak tersedia untuk dipinjam.";
    header("Location: index.php");
    exit;
}

$barang = mysqli_fetch_assoc($queryInventaris);

// ==============================
// Ambil Input Lama
// ==============================

$old = $_SESSION['old'] ?? [];

$nama_peminjam = $old['nama_peminjam'] ?? '';
$nim_nip = $old['nim_nip'] ?? '';
$no_hp = $old['no_hp'] ?? '';
$email = $old['email'] ?? '';
$tanggal_pinjam = $old['tanggal_pinjam'] ?? '';
$tanggal_kembali = $old['tanggal_kembali'] ?? '';
$tujuan_peminjaman = $old['tujuan_peminjaman'] ?? '';
$jumlah = $old['jumlah'] ?? 1;
$kondisi_sebelum = $old['kondisi_sebelum'] ?? '';
$catatan = $old['catatan'] ?? '';

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="id">

<?php include '../../includes/user/header.php'; ?>

<body>

<?php include '../../includes/user/navbar.php'; ?>

<style>
.peminjaman-page {
    background: #fff8fc;
    min-height: 100vh;
}

.page-hero {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #EC4899 0%, #FF7A45 100%);
    padding: 55px 0 90px;
}

.page-hero::before {
    content: "";
    position: absolute;
    width: 380px;
    height: 380px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
    top: -170px;
    right: -100px;
}

.page-hero .container {
    position: relative;
    z-index: 2;
}

.breadcrumb-custom {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 18px;
    font-size: 14px;
}

.breadcrumb-custom a {
    color: #fff;
    text-decoration: none;
}

.breadcrumb-custom span {
    color: rgba(255,255,255,.8);
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 17px;
    border-radius: 50px;
    background: rgba(255,255,255,.18);
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 15px;
}

.hero-title {
    color: #fff;
    font-size: 2.7rem;
    font-weight: 800;
    margin-bottom: 10px;
}

.hero-description {
    max-width: 650px;
    color: rgba(255,255,255,.92);
    line-height: 1.7;
    margin-bottom: 0;
}

.form-section {
    padding: 50px 0 70px;
    margin-top: -40px;
    position: relative;
    z-index: 3;
}

.form-card {
    background: #fff;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,.07);
}

.form-title {
    font-size: 22px;
    font-weight: 800;
    margin-bottom: 5px;
}

.form-subtitle {
    color: #6B7280;
    margin-bottom: 25px;
}

.form-label {
    font-weight: 600;
    margin-bottom: 8px;
}

.form-control,
.form-select {
    min-height: 46px;
    border-radius: 10px;
    border: 1px solid #D9DEE7;
}

.form-control:focus,
.form-select:focus {
    border-color: #EC4899;
    box-shadow: 0 0 0 .2rem rgba(236,72,153,.12);
}

.barang-card {
    background: linear-gradient(135deg, rgba(236,72,153,.08), rgba(255,122,69,.08));
    border: 1px solid rgba(236,72,153,.15);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 28px;
}

.barang-card-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 5px;
}

.barang-code {
    color: #6B7280;
    font-size: 13px;
}

.barang-info {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    margin-top: 15px;
    color: #4B5563;
    font-size: 14px;
}

.barang-info i {
    color: #EC4899;
    margin-right: 5px;
}

.btn-submit {
    border: none;
    border-radius: 50px;
    padding: 11px 24px;
    background: linear-gradient(135deg, #EC4899, #FF7A45);
    color: #fff;
    font-weight: 600;
}

.btn-submit:hover {
    color: #fff;
    transform: translateY(-1px);
}

.btn-back {
    border-radius: 50px;
    padding: 11px 24px;
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.1rem;
    }

    .form-card {
        padding: 22px;
    }
}
</style>

<main class="peminjaman-page">

    <!-- Hero -->
    <section class="page-hero">
        <div class="container">
            <div class="breadcrumb-custom">
                <a href="<?= $baseUrl; ?>index.php">Beranda</a>
                <span>/</span>
                <a href="index.php">Peminjaman</a>
                <span>/</span>
                <span>Ajukan Peminjaman</span>
            </div>

            <span class="hero-badge">
                <i class="bi bi-arrow-left-right"></i>
                Layanan Peminjaman
            </span>

            <h1 class="hero-title">Ajukan Peminjaman</h1>

            <p class="hero-description">
                Lengkapi data berikut untuk mengajukan peminjaman sarana dan prasarana Politeknik Nest.
            </p>
        </div>
    </section>

    <!-- Form -->
    <section class="form-section">
        <div class="container">

            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="form-card">

                <h2 class="form-title">
                    Data Peminjaman
                </h2>

                <p class="form-subtitle">
                    Isi data peminjam dan keperluan peminjaman dengan lengkap.
                </p>

                <form action="proses_tambah.php" method="POST">

                    <input
                        type="hidden"
                        name="id_inventaris"
                        value="<?= $barang['id_inventaris']; ?>">

                    <!-- Barang -->
                    <div class="barang-card">

                        <div class="barang-card-title">
                            <?= htmlspecialchars($barang['nama_barang']); ?>
                        </div>

                        <div class="barang-code">
                            <?= htmlspecialchars($barang['kode_inventaris']); ?>
                        </div>

                        <div class="barang-info">

                            <div>
                                <i class="bi bi-box-seam-fill"></i>
                                Stok tersedia:
                                <strong><?= (int) $barang['stok_tersedia']; ?> unit</strong>
                            </div>

                            <div>
                                <i class="bi bi-check-circle-fill"></i>
                                Kondisi:
                                <strong><?= htmlspecialchars($barang['kondisi']); ?></strong>
                            </div>

                        </div>

                    </div>

                    <!-- Data Peminjam -->
                    <h5 class="fw-bold mb-3">
                        Data Peminjam
                    </h5>

                    <div class="row g-3 mb-4">

                        <div class="col-md-6">
                            <label class="form-label">
                                Nama Peminjam <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="nama_peminjam"
                                class="form-control"
                                value="<?= htmlspecialchars($nama_peminjam); ?>"
                                placeholder="Masukkan nama lengkap"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                NIM / NIP
                            </label>

                            <input
                                type="text"
                                name="nim_nip"
                                class="form-control"
                                value="<?= htmlspecialchars($nim_nip); ?>"
                                placeholder="Masukkan NIM / NIP">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                No. HP
                            </label>

                            <input
                                type="text"
                                name="no_hp"
                                class="form-control"
                                value="<?= htmlspecialchars($no_hp); ?>"
                                placeholder="Masukkan nomor HP">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars($email); ?>"
                                placeholder="Masukkan email">
                        </div>

                    </div>

                    <!-- Detail Peminjaman -->
                    <h5 class="fw-bold mb-3">
                        Detail Peminjaman
                    </h5>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">
                                Jumlah <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                name="jumlah"
                                class="form-control"
                                value="<?= (int) $jumlah; ?>"
                                min="1"
                                max="<?= (int) $barang['stok_tersedia']; ?>"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Kondisi Sebelum <span class="text-danger">*</span>
                            </label>

                            <select name="kondisi_sebelum" class="form-select" required>
                                <option value="">Pilih Kondisi</option>
                                <option value="Baik" <?= ($kondisi_sebelum ?? '') == 'Baik' ? 'selected' : ''; ?>>
                                    Baik
                                </option>
                                <option value="Rusak Ringan" <?= ($kondisi_sebelum ?? '') == 'Rusak Ringan' ? 'selected' : ''; ?>>
                                    Rusak Ringan
                                </option>
                                <option value="Rusak Berat" <?= ($kondisi_sebelum ?? '') == 'Rusak Berat' ? 'selected' : ''; ?>>
                                    Rusak Berat
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Tanggal Pinjam <span class="text-danger">*</span>
                            </label>

                            <input
                                type="date"
                                name="tanggal_pinjam"
                                class="form-control"
                                value="<?= htmlspecialchars($tanggal_pinjam); ?>"
                                min="<?= date('Y-m-d'); ?>"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Tanggal Kembali <span class="text-danger">*</span>
                            </label>

                            <input
                                type="date"
                                name="tanggal_kembali"
                                class="form-control"
                                value="<?= htmlspecialchars($tanggal_kembali); ?>"
                                min="<?= date('Y-m-d'); ?>"
                                required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">
                                Tujuan Peminjaman <span class="text-danger">*</span>
                            </label>

                            <textarea
                                name="tujuan_peminjaman"
                                class="form-control"
                                rows="4"
                                placeholder="Jelaskan tujuan penggunaan barang"
                                required><?= htmlspecialchars($tujuan_peminjaman); ?></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">
                                Catatan
                            </label>

                            <textarea
                                name="catatan"
                                class="form-control"
                                rows="3"
                                placeholder="Catatan tambahan (opsional)"><?= htmlspecialchars($catatan); ?></textarea>
                        </div>

                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">

                        <a
                            href="index.php"
                            class="btn btn-secondary btn-back">

                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali

                        </a>

                        <button
                            type="submit"
                            class="btn btn-submit">

                            <i class="bi bi-send me-1"></i>
                            Ajukan Peminjaman

                        </button>

                    </div>

                </form>

            </div>

        </div>
    </section>

</main>

<?php include '../../includes/user/footer.php'; ?>

</body>
</html>