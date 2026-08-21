<?php
session_start();

$menu = "pelaporan";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";
require_once "../../../helpers/activity_log.php";


// ==========================================
// PROSES SIMPAN LAPORAN
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama_pelapor       = trim($_POST['nama_pelapor'] ?? '');
    $id_inventaris      = (int) ($_POST['id_inventaris'] ?? 0);
    $bagian_rusak       = trim($_POST['bagian_rusak'] ?? '');
    $jenis_kerusakan    = trim($_POST['jenis_kerusakan'] ?? '');
    $tingkat_kerusakan  = trim($_POST['tingkat_kerusakan'] ?? '');
    $kronologi          = trim($_POST['kronologi'] ?? '');

    $foto = $_FILES['foto'] ?? null;


    // ==========================================
    // VALIDASI
    // ==========================================

    if (
        $nama_pelapor === '' ||
        $id_inventaris <= 0 ||
        $bagian_rusak === '' ||
        $jenis_kerusakan === '' ||
        $tingkat_kerusakan === '' ||
        $kronologi === ''
    ) {
        $_SESSION['error'] = "Semua data wajib diisi.";
        header("Location: tambah_kerusakan.php");
        exit;
    }


    // ==========================================
    // VALIDASI TINGKAT KERUSAKAN
    // ==========================================

    $tingkatValid = ['Ringan', 'Sedang', 'Berat'];

    if (!in_array($tingkat_kerusakan, $tingkatValid, true)) {
        $_SESSION['error'] = "Tingkat kerusakan tidak valid.";
        header("Location: tambah_kerusakan.php");
        exit;
    }


    // ==========================================
    // VALIDASI INVENTARIS
    // ==========================================

    $stmtInventaris = mysqli_prepare($conn, "
        SELECT id_inventaris
        FROM inventaris
        WHERE id_inventaris = ?
        AND status = 'Aktif'
        LIMIT 1
    ");

    mysqli_stmt_bind_param(
        $stmtInventaris,
        "i",
        $id_inventaris
    );

    mysqli_stmt_execute($stmtInventaris);

    $resultInventaris = mysqli_stmt_get_result($stmtInventaris);

    if (mysqli_num_rows($resultInventaris) === 0) {

        mysqli_stmt_close($stmtInventaris);

        $_SESSION['error'] = "Inventaris tidak ditemukan atau tidak aktif.";
        header("Location: tambah_kerusakan.php");
        exit;
    }

    mysqli_stmt_close($stmtInventaris);


    // ==========================================
    // VALIDASI FOTO
    // ==========================================

    if (!$foto || $foto['error'] !== UPLOAD_ERR_OK) {

        $_SESSION['error'] = "Foto kerusakan wajib diunggah.";
        header("Location: tambah_kerusakan.php");
        exit;
    }


    $maxSize = 10 * 1024 * 1024;

    if ($foto['size'] > $maxSize) {

        $_SESSION['error'] = "Ukuran foto maksimal 10 MB.";
        header("Location: tambah_kerusakan.php");
        exit;
    }


    $mimeValid = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $foto['tmp_name']);
    finfo_close($finfo);


    if (!isset($mimeValid[$mimeType])) {

        $_SESSION['error'] = "Format foto harus JPG, PNG, atau WEBP.";
        header("Location: tambah_kerusakan.php");
        exit;
    }


    // ==========================================
    // FOLDER UPLOAD
    // ==========================================

    $folderUpload = __DIR__ . "/../../../uploads/kerusakan/";

    if (!is_dir($folderUpload)) {

        if (!mkdir($folderUpload, 0755, true)) {

            $_SESSION['error'] =
                "Folder penyimpanan foto tidak dapat dibuat.";

            header("Location: tambah_kerusakan.php");
            exit;
        }
    }


    // ==========================================
    // NAMA FILE
    // ==========================================

    $ekstensi = $mimeValid[$mimeType];

    $namaFile =
        bin2hex(random_bytes(16)) . '.' . $ekstensi;

    $pathFile =
        $folderUpload . $namaFile;

    $pathDatabase =
        'uploads/kerusakan/' . $namaFile;


    // ==========================================
    // UPLOAD FOTO
    // ==========================================

    if (!move_uploaded_file($foto['tmp_name'], $pathFile)) {

        $_SESSION['error'] = "Foto gagal diunggah.";

        header("Location: tambah_kerusakan.php");
        exit;
    }


    // ==========================================
    // SIMPAN DATABASE
    // ==========================================

    mysqli_begin_transaction($conn);

    try {

        // ======================================
        // Generate Kode Laporan
        // Format: KRS-YYMMDD-001
        // ======================================

        $tanggalKode = date('ymd');

        $stmtKode = mysqli_prepare($conn, "
            SELECT MAX(
                CAST(SUBSTRING_INDEX(kode_kerusakan, '-', -1) AS UNSIGNED)
            ) AS nomor_terakhir
            FROM kerusakan
            WHERE tanggal_lapor = CURDATE()
            AND kode_kerusakan LIKE ?
        ");

        if (!$stmtKode) {
            throw new Exception(
                "Gagal membuat kode laporan kerusakan."
            );
        }

        $patternKode = 'KRS-' . $tanggalKode . '-%';

        mysqli_stmt_bind_param(
            $stmtKode,
            "s",
            $patternKode
        );

        mysqli_stmt_execute($stmtKode);

        $resultKode = mysqli_stmt_get_result($stmtKode);
        $rowKode = mysqli_fetch_assoc($resultKode);

        $nomorBerikutnya =
            ($rowKode['nomor_terakhir'] ?? 0) + 1;

        mysqli_stmt_close($stmtKode);

        $kodeKerusakan =
            'KRS-' .
            $tanggalKode .
            '-' .
            str_pad(
                $nomorBerikutnya,
                3,
                '0',
                STR_PAD_LEFT
            );


        // ======================================
        // SIMPAN DATA UTAMA
        // ======================================

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
            throw new Exception(
                "Gagal menyiapkan data laporan."
            );
        }

        mysqli_stmt_bind_param(
            $stmtKerusakan,
            "ss",
            $kodeKerusakan,
            $nama_pelapor
        );

        if (!mysqli_stmt_execute($stmtKerusakan)) {
            throw new Exception(
                "Gagal menyimpan laporan kerusakan."
            );
        }

        $idKerusakan = mysqli_insert_id($conn);

        mysqli_stmt_close($stmtKerusakan);


        // ======================================
        // SIMPAN DETAIL
        // ======================================

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
            throw new Exception(
                "Gagal menyiapkan detail laporan."
            );
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
            throw new Exception(
                "Gagal menyimpan detail kerusakan."
            );
        }

        mysqli_stmt_close($stmtDetail);


        // ======================================
        // COMMIT
        // ======================================

        mysqli_commit($conn);

        simpanActivityLog(
            $conn,
            $_SESSION['id_admin'],
            "Menambah Laporan Kerusakan",
            "kerusakan",
            $idKerusakan
        );

        $_SESSION['success'] =
            "Laporan kerusakan berhasil ditambahkan. Kode laporan: "
            . $kodeKerusakan;

        header("Location: kerusakan.php");
        exit;


    } catch (Exception $e) {

        mysqli_rollback($conn);


        // Hapus foto apabila database gagal
        if (file_exists($pathFile)) {
            unlink($pathFile);
        }


        $_SESSION['error'] = $e->getMessage();

        header("Location: tambah_kerusakan.php");
        exit;
    }
}


// ==========================================
// AMBIL DATA INVENTARIS
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


// ==========================================
// TEMPLATE
// ==========================================

require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";
?>


<main class="app-main">

    <!-- HEADER -->

    <div class="app-content-header">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="fw-bold mb-0">
                    Tambah Laporan Kerusakan
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

                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL; ?>/admin/transaksi/pelaporan/index.php">
                            Pelaporan
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="kerusakan.php">
                            Kerusakan
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Tambah
                    </li>

                </ol>

            </div>

        </div>

    </div>


    <!-- CONTENT -->

    <div class="app-content">

        <div class="container-fluid">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold mb-0">

                        <i class="bi bi-tools text-danger me-2"></i>

                        Form Laporan Kerusakan

                    </h5>

                </div>


                <div class="card-body p-4">


                    <?php if (isset($_SESSION['error'])) : ?>

                        <div class="alert alert-danger">

                            <i class="bi bi-exclamation-circle me-2"></i>

                            <?= htmlspecialchars($_SESSION['error']); ?>

                        </div>

                    <?php
                        unset($_SESSION['error']);
                    endif;
                    ?>


                    <form
                        method="POST"
                        enctype="multipart/form-data">


                        <div class="row g-4">


                            <!-- PELAPOR -->

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Nama Pelapor
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="text"
                                    name="nama_pelapor"
                                    class="form-control"
                                    placeholder="Masukkan nama pelapor"
                                    required>

                            </div>


                            <!-- INVENTARIS -->

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Inventaris
                                    <span class="text-danger">*</span>

                                </label>

                                <select
                                    name="id_inventaris"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Pilih inventaris
                                    </option>

                                    <?php while ($inventaris = mysqli_fetch_assoc($queryInventaris)) : ?>

                                        <option
                                            value="<?= $inventaris['id_inventaris']; ?>">

                                            <?= htmlspecialchars(
                                                $inventaris['nama_barang']
                                            ); ?>

                                            -
                                            <?= htmlspecialchars(
                                                $inventaris['kode_inventaris']
                                            ); ?>

                                        </option>

                                    <?php endwhile; ?>

                                </select>

                            </div>


                            <!-- BAGIAN RUSAK -->

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Bagian yang Rusak
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="text"
                                    name="bagian_rusak"
                                    class="form-control"
                                    placeholder="Contoh: Layar, kabel, roda"
                                    required>

                            </div>


                            <!-- JENIS KERUSAKAN -->

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Jenis Kerusakan
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="text"
                                    name="jenis_kerusakan"
                                    class="form-control"
                                    placeholder="Contoh: Pecah, tidak menyala"
                                    required>

                            </div>


                            <!-- TINGKAT -->

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Tingkat Kerusakan
                                    <span class="text-danger">*</span>

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

                                <small class="text-muted">
                                    Ringan: masih dapat digunakan.
                                    Sedang: fungsi terganggu.
                                    Berat: tidak dapat digunakan.
                                </small>

                            </div>


                            <!-- FOTO -->

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Foto Kerusakan
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="file"
                                    name="foto"
                                    class="form-control"
                                    accept="image/jpeg,image/png,image/webp"
                                    required>

                                <small class="text-muted">
                                    Foto wajib diunggah sebagai bukti kerusakan.
                                    Format JPG, PNG, atau WEBP. Maksimal 10 MB.
                                </small>

                            </div>


                            <!-- KRONOLOGI -->

                            <div class="col-12">

                                <label class="form-label fw-semibold">

                                    Kronologi Kerusakan
                                    <span class="text-danger">*</span>

                                </label>

                                <textarea
                                    name="kronologi"
                                    class="form-control"
                                    rows="5"
                                    maxlength="2000"
                                    placeholder="Jelaskan kondisi atau kronologi terjadinya kerusakan..."
                                    required></textarea>

                            </div>


                            <!-- BUTTON -->

                            <div class="col-12">

                                <hr>

                                <div class="d-flex justify-content-end gap-2">

                                    <a
                                        href="kerusakan.php"
                                        class="btn btn-secondary">

                                        <i class="bi bi-arrow-left me-1"></i>

                                        Batal

                                    </a>

                                    <button
                                        type="submit"
                                        class="btn btn-danger">

                                        <i class="bi bi-save me-1"></i>

                                        Simpan Laporan

                                    </button>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</main>


<?php

require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";

?>