<?php
session_start();

require_once '../config/database.php';

$baseUrl = "../";
$currentPage = 'lapor';

// ==========================================
// PROSES LAPORAN KERUSAKAN
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ==========================================
    // Ambil Data Form
    // ==========================================

    $nama_pelapor = trim($_POST['nama_pelapor'] ?? '');
    $id_inventaris = (int) ($_POST['id_inventaris'] ?? 0);
    $bagian_rusak = trim($_POST['bagian_rusak'] ?? '');
    $jenis_kerusakan = trim($_POST['jenis_kerusakan'] ?? '');
    $tingkat_kerusakan = trim($_POST['tingkat_kerusakan'] ?? '');
    $kronologi = trim($_POST['kronologi'] ?? '');

    // ==========================================
    // Validasi Data Wajib
    // ==========================================

    if (
        $nama_pelapor === '' ||
        $id_inventaris <= 0 ||
        $bagian_rusak === '' ||
        $jenis_kerusakan === '' ||
        $tingkat_kerusakan === '' ||
        $kronologi === ''
    ) {
        $_SESSION['user_error'] = "Semua data wajib diisi.";
        header("Location: lapor_kerusakan.php");
        exit;
    }

    // ==========================================
    // Validasi Tingkat Kerusakan
    // ==========================================

    $tingkatValid = ['Ringan', 'Sedang', 'Berat'];

    if (!in_array($tingkat_kerusakan, $tingkatValid, true)) {
        $_SESSION['user_error'] = "Tingkat kerusakan tidak valid.";
        header("Location: lapor_kerusakan.php");
        exit;
    }

    // ==========================================
    // Validasi Panjang Data
    // ==========================================

    if (strlen($nama_pelapor) > 100) {
        $_SESSION['user_error'] = "Nama pelapor terlalu panjang.";
        header("Location: lapor_kerusakan.php");
        exit;
    }

    if (strlen($bagian_rusak) > 255) {
        $_SESSION['user_error'] = "Bagian yang rusak terlalu panjang.";
        header("Location: lapor_kerusakan.php");
        exit;
    }

    if (strlen($jenis_kerusakan) > 255) {
        $_SESSION['user_error'] = "Jenis kerusakan terlalu panjang.";
        header("Location: lapor_kerusakan.php");
        exit;
    }

    // ==========================================
    // Validasi Foto
    // ==========================================

    if (
        !isset($_FILES['foto']) ||
        $_FILES['foto']['error'] !== UPLOAD_ERR_OK
    ) {
        $_SESSION['user_error'] = "Foto kerusakan wajib diunggah.";
        header("Location: lapor_kerusakan.php");
        exit;
    }

    $foto = $_FILES['foto'];

    // Maksimal 5 MB
    if ($foto['size'] > 5 * 1024 * 1024) {
        $_SESSION['user_error'] = "Ukuran foto maksimal 5 MB.";
        header("Location: lapor_kerusakan.php");
        exit;
    }

    // Pastikan file benar-benar gambar
    $imageInfo = @getimagesize($foto['tmp_name']);

    if ($imageInfo === false) {
        $_SESSION['user_error'] = "File yang diunggah bukan gambar yang valid.";
        header("Location: lapor_kerusakan.php");
        exit;
    }

    // Cek MIME type dari file asli
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $foto['tmp_name']);
    finfo_close($finfo);

    $mimeValid = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    if (!array_key_exists($mimeType, $mimeValid)) {
        $_SESSION['user_error'] = "Format foto hanya boleh JPG, JPEG, PNG, atau WEBP.";
        header("Location: lapor_kerusakan.php");
        exit;
    }

    // ==========================================
    // Cek Inventaris
    // ==========================================

    $stmtInventaris = mysqli_prepare($conn, "
        SELECT id_inventaris
        FROM inventaris
        WHERE id_inventaris = ?
        AND status = 'Aktif'
        LIMIT 1
    ");

    if (!$stmtInventaris) {
        $_SESSION['user_error'] = "Terjadi kesalahan saat memeriksa data inventaris.";
        header("Location: lapor_kerusakan.php");
        exit;
    }

    mysqli_stmt_bind_param(
        $stmtInventaris,
        "i",
        $id_inventaris
    );

    mysqli_stmt_execute($stmtInventaris);
    mysqli_stmt_store_result($stmtInventaris);

    if (mysqli_stmt_num_rows($stmtInventaris) === 0) {
        mysqli_stmt_close($stmtInventaris);

        $_SESSION['user_error'] = "Barang atau inventaris tidak ditemukan.";
        header("Location: lapor_kerusakan.php");
        exit;
    }

    mysqli_stmt_close($stmtInventaris);

    // ==========================================
    // Siapkan Folder Upload
    // ==========================================

    $folderUpload = __DIR__ . '/../uploads/kerusakan/';

    if (!is_dir($folderUpload)) {
        if (!mkdir($folderUpload, 0755, true)) {
            $_SESSION['user_error'] = "Folder penyimpanan foto tidak dapat dibuat.";
            header("Location: lapor_kerusakan.php");
            exit;
        }
    }

    // ==========================================
    // Buat Nama File Aman
    // ==========================================

    $ekstensi = $mimeValid[$mimeType];
    $namaFile = bin2hex(random_bytes(16)) . '.' . $ekstensi;
    $pathFile = $folderUpload . $namaFile;
    $pathDatabase = 'uploads/kerusakan/' . $namaFile;

    // ==========================================
    // Upload Foto
    // ==========================================

    if (!move_uploaded_file($foto['tmp_name'], $pathFile)) {
        $_SESSION['user_error'] = "Foto gagal diunggah.";
        header("Location: lapor_kerusakan.php");
        exit;
    }

    // ==========================================
    // Simpan Data ke Database
    // ==========================================

    mysqli_begin_transaction($conn);

    try {

        // ==========================================
        // Generate Kode Laporan
        // Format: KRS-YYYYMMDD-001
        // ==========================================

        $tanggalKode = date('Ymd');

        $stmtKode = mysqli_prepare($conn, "
            SELECT MAX(
                CAST(SUBSTRING_INDEX(kode_kerusakan, '-', -1) AS UNSIGNED)
            ) AS nomor_terakhir
            FROM kerusakan
            WHERE tanggal_lapor = CURDATE()
            AND kode_kerusakan LIKE ?
        ");

        $patternKode = 'KRS-' . $tanggalKode . '-%';

        mysqli_stmt_bind_param(
            $stmtKode,
            "s",
            $patternKode
        );

        mysqli_stmt_execute($stmtKode);

        $resultKode = mysqli_stmt_get_result($stmtKode);
        $rowKode = mysqli_fetch_assoc($resultKode);

        $nomorBerikutnya = ($rowKode['nomor_terakhir'] ?? 0) + 1;

        mysqli_stmt_close($stmtKode);

        $kodeKerusakan =
            'KRS-' .
            $tanggalKode .
            '-' .
            str_pad($nomorBerikutnya, 3, '0', STR_PAD_LEFT);

        // ==========================================
        // Simpan Data Utama Kerusakan
        // ==========================================

        $stmtKerusakan = mysqli_prepare($conn, "
            INSERT INTO kerusakan
            (
                kode_kerusakan,
                tanggal_lapor,
                nama_pelapor,
                status
            )
            VALUES
            (
                ?,
                CURDATE(),
                ?,
                'Menunggu'
            )
        ");

        if (!$stmtKerusakan) {
            throw new Exception("Gagal menyiapkan data laporan.");
        }

        mysqli_stmt_bind_param(
            $stmtKerusakan,
            "ss",
            $kodeKerusakan,
            $nama_pelapor
        );

        if (!mysqli_stmt_execute($stmtKerusakan)) {
            throw new Exception("Gagal menyimpan laporan kerusakan.");
        }

        $idKerusakan = mysqli_insert_id($conn);

        mysqli_stmt_close($stmtKerusakan);

        // ==========================================
        // Simpan Detail Kerusakan
        // ==========================================

        $stmtDetail = mysqli_prepare($conn, "
            INSERT INTO detail_kerusakan
            (
                id_kerusakan,
                id_inventaris,
                bagian_rusak,
                jenis_kerusakan,
                tingkat_kerusakan,
                kronologi,
                foto
            )
            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
            )
        ");

        if (!$stmtDetail) {
            throw new Exception("Gagal menyiapkan detail laporan.");
        }

        mysqli_stmt_bind_param(
            $stmtDetail,
            "iisssss",
            $idKerusakan,
            $id_inventaris,
            $bagian_rusak,
            $jenis_kerusakan,
            $tingkat_kerusakan,
            $kronologi,
            $pathDatabase
        );

        if (!mysqli_stmt_execute($stmtDetail)) {
            throw new Exception("Gagal menyimpan detail kerusakan.");
        }

        mysqli_stmt_close($stmtDetail);

        // ==========================================
        // Commit
        // ==========================================

        mysqli_commit($conn);

        $_SESSION['user_success'] =
            "Laporan kerusakan berhasil dikirim. Kode laporan: " . $kodeKerusakan;

        header("Location: lapor.php");
        exit;

    } catch (Exception $e) {

        // ==========================================
        // Rollback Jika Gagal
        // ==========================================

        mysqli_rollback($conn);

        // Hapus foto jika database gagal disimpan
        if (file_exists($pathFile)) {
            unlink($pathFile);
        }

        $_SESSION['user_error'] = $e->getMessage();

        header("Location: lapor_kerusakan.php");
        exit;
    }
}

// ==========================================
// Ambil Data Inventaris
// ==========================================

$queryInventaris = mysqli_query($conn, "
    SELECT
        id_inventaris,
        kode_inventaris,
        nama_barang
    FROM inventaris
    WHERE status = 'Aktif'
    ORDER BY nama_barang ASC
");
?>

<!DOCTYPE html>
<html lang="id">

<?php include '../includes/user/header.php'; ?>

<body>

<?php include '../includes/user/navbar.php'; ?>

<style>
/* ==========================================
   PAGE
========================================== */

.lapor-page{
    background:#fff8fc;
    min-height:100vh;
}

/* ==========================================
   HERO
========================================== */

.lapor-hero{
    position:relative;
    overflow:hidden;
    background:linear-gradient(135deg,#EC4899 0%,#FF7A45 100%);
    padding:65px 0 75px;
}

.lapor-hero::before{
    content:"";
    position:absolute;
    width:420px;
    height:420px;
    border-radius:50%;
    background:rgba(255,255,255,.08);
    top:-180px;
    right:-120px;
}

.lapor-hero::after{
    content:"";
    position:absolute;
    width:260px;
    height:260px;
    border-radius:50%;
    background:rgba(255,255,255,.06);
    left:-80px;
    bottom:-150px;
}

.lapor-hero .container{
    position:relative;
    z-index:2;
}

/* ==========================================
   BREADCRUMB
========================================== */

.breadcrumb-custom{
    display:flex;
    align-items:center;
    gap:8px;
    margin-bottom:18px;
    font-size:14px;
}

.breadcrumb-custom a{
    color:#fff;
    text-decoration:none;
}

.breadcrumb-custom span{
    color:rgba(255,255,255,.8);
}

/* ==========================================
   HERO CONTENT
========================================== */

.hero-badge{
    display:inline-flex;
    align-items:center;
    gap:10px;
    padding:10px 18px;
    border-radius:50px;
    background:rgba(255,255,255,.18);
    color:#fff;
    font-size:.9rem;
    font-weight:600;
    margin-bottom:18px;
}

.hero-title{
    color:#fff;
    font-size:3rem;
    font-weight:800;
    margin-bottom:14px;
    line-height:1.2;
}

.hero-description{
    max-width:700px;
    color:rgba(255,255,255,.92);
    line-height:1.8;
    margin-bottom:0;
}

/* ==========================================
   FORM SECTION
========================================== */

.lapor-section{
    padding:50px 0 80px;
}

.lapor-card{
    background:#fff;
    border-radius:22px;
    padding:35px;
    box-shadow:0 10px 30px rgba(0,0,0,.06);
}

.form-title{
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:8px;
}

.form-title-icon{
    width:48px;
    height:48px;
    border-radius:14px;
    background:#FFF1F6;
    color:#EC4899;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
}

.form-title h3{
    margin:0;
    font-size:24px;
    font-weight:800;
    color:#374151;
}

.form-description{
    color:#6B7280;
    margin-bottom:30px;
}

/* ==========================================
   FORM
========================================== */

.form-label{
    color:#374151;
    font-size:14px;
    font-weight:700;
    margin-bottom:8px;
}

.form-control,
.form-select{
    min-height:48px;
    border:1px solid #E5E7EB;
    border-radius:12px;
    padding:10px 14px;
    font-size:14px;
}

.form-control:focus,
.form-select:focus{
    border-color:#EC4899;
    box-shadow:0 0 0 3px rgba(236,72,153,.1);
}

textarea.form-control{
    min-height:130px;
    resize:vertical;
}

.form-text{
    color:#9CA3AF;
    font-size:12px;
    margin-top:6px;
}

/* ==========================================
   BUTTON
========================================== */

.btn-lapor{
    width:100%;
    min-height:50px;
    border:none;
    border-radius:50px;
    background:linear-gradient(135deg,#EC4899,#FF7A45);
    color:#fff;
    font-size:15px;
    font-weight:700;
    transition:.3s;
}

.btn-lapor:hover{
    color:#fff;
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(236,72,153,.2);
}

/* ==========================================
   INFO
========================================== */

.info-box{
    background:#FFF8FC;
    border:1px solid #FCE7F3;
    border-radius:14px;
    padding:15px 18px;
    margin-bottom:25px;
}

.info-box i{
    color:#EC4899;
    margin-right:8px;
}

.info-box span{
    color:#6B7280;
    font-size:13px;
}

/* ==========================================
   RESPONSIVE
========================================== */

@media(max-width:768px){
    .lapor-hero{
        padding:50px 0 60px;
    }

    .hero-title{
        font-size:40px;
    }

    .lapor-card{
        padding:25px 20px;
    }
}

@media(max-width:576px){
    .hero-title{
        font-size:32px;
    }
}
</style>

<main class="lapor-page">

    <!-- ==========================================
         Hero
    ========================================== -->

    <section class="lapor-hero">
        <div class="container">

            <div class="breadcrumb-custom">
                <a href="<?= $baseUrl ?>index.php">Beranda</a>
                <span>/</span>
                <span>Lapor Kerusakan</span>
            </div>

            <span class="hero-badge">
                <i class="bi bi-tools"></i>
                Layanan Pelaporan
            </span>

            <h1 class="hero-title">
                Lapor Kerusakan
            </h1>

            <p class="hero-description">
                Laporkan kerusakan sarana dan prasarana Politeknik Nest
                agar dapat segera ditindaklanjuti oleh pihak terkait.
            </p>

        </div>
    </section>

    <!-- ==========================================
         Form Pelaporan
    ========================================== -->

    <section class="lapor-section">
        <div class="container">

            <div class="lapor-card">

                <div class="form-title">
                    <div class="form-title-icon">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>

                    <h3>Form Laporan Kerusakan</h3>
                </div>

                <p class="form-description">
                    Silakan isi data kerusakan dengan lengkap dan sesuai kondisi sebenarnya.
                </p>

                <div class="info-box">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>
                        Laporan akan diperiksa oleh admin sebelum diproses.
                    </span>
                </div>

                <!-- ==========================================
                     Form
                ========================================== -->

                <form action="lapor_kerusakan.php" method="POST" enctype="multipart/form-data">

                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="form-label">
                                Nama Pelapor
                            </label>

                            <input
                                type="text"
                                name="nama_pelapor"
                                class="form-control"
                                placeholder="Masukkan nama lengkap"
                                maxlength="100"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Barang / Inventaris
                            </label>

                            <select
                                name="id_inventaris"
                                class="form-select"
                                required>

                                <option value="">
                                    Pilih barang
                                </option>

                                <?php if ($queryInventaris && mysqli_num_rows($queryInventaris) > 0) : ?>

                                    <?php while ($barang = mysqli_fetch_assoc($queryInventaris)) : ?>

                                        <option value="<?= (int) $barang['id_inventaris']; ?>">
                                            <?= htmlspecialchars($barang['kode_inventaris']); ?>
                                            -
                                            <?= htmlspecialchars($barang['nama_barang']); ?>
                                        </option>

                                    <?php endwhile; ?>

                                <?php else : ?>

                                    <option value="" disabled>
                                        Tidak ada barang tersedia
                                    </option>

                                <?php endif; ?>

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Bagian yang Rusak
                            </label>

                            <input
                                type="text"
                                name="bagian_rusak"
                                class="form-control"
                                placeholder="Contoh: Layar, kabel, roda, dll."
                                maxlength="255"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Jenis Kerusakan
                            </label>

                            <input
                                type="text"
                                name="jenis_kerusakan"
                                class="form-control"
                                placeholder="Contoh: Pecah, tidak menyala, rusak"
                                maxlength="255"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Tingkat Kerusakan
                            </label>

                            <select
                                name="tingkat_kerusakan"
                                class="form-select"
                                required>

                                <option value="">
                                    Pilih tingkat kerusakan
                                </option>

                                <option value="Ringan">
                                    Ringan
                                </option>

                                <option value="Sedang">
                                    Sedang
                                </option>

                                <option value="Berat">
                                    Berat
                                </option>

                            </select>

                            <div class="form-text">
                                Ringan: masih dapat digunakan.
                                Sedang: fungsi terganggu.
                                Berat: tidak dapat digunakan.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Foto Kerusakan <span style="color:#EC4899;">*</span>
                            </label>

                            <input
                                type="file"
                                name="foto"
                                class="form-control"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                required>

                            <div class="form-text">
                                Foto wajib diunggah sebagai bukti kerusakan.
                                Format JPG, PNG, atau WEBP..Maksimal 5 MB.
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">
                                Kronologi Kerusakan
                            </label>

                            <textarea
                                name="kronologi"
                                class="form-control"
                                placeholder="Jelaskan bagaimana kerusakan terjadi..."
                                maxlength="2000"
                                required></textarea>
                        </div>

                        <div class="col-12">
                            <button
                                type="submit"
                                class="btn-lapor">

                                <i class="bi bi-send-fill me-2"></i>
                                Kirim Laporan Kerusakan

                            </button>
                        </div>

                    </div>

                </form>

            </div>

        </div>
    </section>

</main>

<?php include '../includes/user/footer.php'; ?>

<!-- ==========================================
     SweetAlert
========================================== -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION['user_error'])) : ?>

<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal',
    text: <?= json_encode($_SESSION['user_error']); ?>,
    confirmButtonText: 'OK'
});
</script>

<?php
unset($_SESSION['user_error']);
endif;
?>

</body>
</html>