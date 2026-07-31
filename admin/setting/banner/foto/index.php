<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ======================================================
// Cek Login
// ======================================================

if (!isset($_SESSION['id_admin'])) {

    header("Location: ../../../login.php");
    exit;

}

// ======================================================
// Load File
// ======================================================

require_once "../../../../config/database.php";

require_once "config.php";
require_once "helper.php";
require_once "service.php";

require_once "../../../../includes/header.php";
require_once "../../../../includes/navbar.php";
require_once "../../../../includes/sidebar.php";

// ======================================================
// Validasi ID Banner
// ======================================================

$idBanner = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$idBanner) {

    $_SESSION['gagal'] = "Data banner tidak valid.";

    header("Location: ../index.php");
    exit;

}

// ======================================================
// Data Banner
// ======================================================

$banner = getBanner($idBanner);

if (!$banner) {

    $_SESSION['gagal'] = "Data banner tidak ditemukan.";

    header("Location: ../index.php");
    exit;

}

// ======================================================
// Gallery Banner
// ======================================================

$photos = [];

$result = getBannerPhotos($idBanner);

while ($row = mysqli_fetch_assoc($result)) {

    $photos[] = $row;

}

$totalPhoto = count($photos);

// ======================================================
// Upload Path
// ======================================================

$uploadPath = "../../../../assets/uploads/banner/";
?>

<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>

                    <h2 class="fw-bold mb-1">
                        Kelola Foto Banner
                    </h2>

                    <span class="text-muted">

                        <?= htmlspecialchars($banner['judul']); ?>

                    </span>

                </div>

                <div>

                    <a
                        href="../index.php"
                        class="btn btn-outline-secondary">

                        <i class="bi bi-arrow-left me-1"></i>

                        Kembali

                    </a>

                </div>

            </div>

        </div>

    </div>

    <!-- Content -->
    <div class="app-content">

        <div class="container-fluid">

            <!-- Upload Card -->

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="mb-1">

                                <i class="bi bi-images me-2 text-warning"></i>

                                Gallery Banner

                            </h5>

                            <small class="text-muted">

                                Kelola slide banner website

                            </small>

                        </div>

                        <span class="badge bg-warning text-dark fs-6">

                            <?= $totalPhoto; ?> Foto

                        </span>

                    </div>

                </div>

                <div class="card-body">

                    <div class="row align-items-center">

                        <!-- Informasi Banner -->

                        <div class="col-lg-5 mb-3 mb-lg-0">

                            <div class="mb-3">

                                <small class="text-muted d-block">

                                    Judul Banner

                                </small>

                                <strong>

                                    <?= htmlspecialchars($banner['judul']); ?>

                                </strong>

                            </div>

                            <div class="mb-3">

                                <small class="text-muted d-block">

                                    Subjudul

                                </small>

                                <span>

                                    <?= !empty($banner['subjudul'])
                                        ? htmlspecialchars($banner['subjudul'])
                                        : '-'; ?>

                                </span>

                            </div>

                            <div>

                                <small class="text-muted d-block">

                                    Status

                                </small>

                                <?php if ($banner['status'] == 'aktif') : ?>

                                    <span class="badge bg-success">

                                        Aktif

                                    </span>

                                <?php else : ?>

                                    <span class="badge bg-secondary">

                                        Nonaktif

                                    </span>

                                <?php endif; ?>

                            </div>

                        </div>

                        <!-- Upload -->

                        <div class="col-lg-7">

                            <form
                                action="upload.php?id=<?= $idBanner; ?>"
                                method="POST"
                                enctype="multipart/form-data">

                                <input
                                    type="hidden"
                                    name="id_banner"
                                    value="<?= $idBanner; ?>">

                                <div class="input-group">

                                    <input
                                        type="file"
                                        name="foto"
                                        id="foto"
                                        class="form-control"
                                        accept=".jpg,.jpeg,.png,.webp"
                                        required>

                                    <button
                                        type="submit"
                                        class="btn btn-warning px-4">

                                        <i class="bi bi-upload me-2"></i>

                                        Upload Foto

                                    </button>

                                </div>

                                <div class="form-text mt-2">

                                    Format:
                                    JPG, JPEG, PNG, WEBP
                                    • Maksimal 5 MB

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

<!-- Gallery -->

<div class="row">

<?php foreach ($photos as $photo) : ?>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">

        <div class="card border-0 shadow-sm h-100">

            <!-- Thumbnail -->

            <div class="position-relative">

                <img
                    src="<?= $uploadPath . htmlspecialchars($photo['nama_file']); ?>"
                    class="card-img-top"
                    alt="Banner"
                    style="height:220px; object-fit:cover;">

                <?php if ((int)$photo['is_cover'] === 1) : ?>

                    <span
                        class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 px-3 py-2">

                        <i class="bi bi-star-fill me-1"></i>

                        Cover

                    </span>

                <?php endif; ?>

            </div>

            <!-- Body -->

            <div class="card-body">

                <h6
                    class="fw-semibold text-truncate mb-3"
                    title="<?= htmlspecialchars($photo['nama_file']); ?>">

                    <?= htmlspecialchars($photo['nama_file']); ?>

                </h6>

                <div class="small text-muted mb-2">

                    <i class="bi bi-sort-numeric-down me-1"></i>

                    Urutan :
                    <?= (int)$photo['urutan']; ?>

                </div>

                <div class="small text-muted">

                    <i class="bi bi-calendar-event me-1"></i>

                    <?= date(
                        'd M Y',
                        strtotime($photo['created_at'])
                    ); ?>

                </div>

            </div>

            <!-- Footer -->

            <div class="card-footer bg-white border-0">

                <div class="d-grid gap-2">

                    <!-- Preview -->

                    <button
                        type="button"
                        class="btn btn-outline-primary btn-sm btn-preview"
                        data-image="<?= $uploadPath . htmlspecialchars($photo['nama_file']); ?>">

                        <i class="bi bi-eye me-1"></i>

                        Preview

                    </button>

                    <!-- Cover -->

                    <?php if ((int)$photo['is_cover'] === 0) : ?>

                        <a
                            href="cover.php?id=<?= $idBanner; ?>&foto=<?= $photo['id_foto_banner']; ?>"
                            class="btn btn-warning btn-sm btn-cover">

                            <i class="bi bi-star-fill me-1"></i>

                            Jadikan Cover

                        </a>

                    <?php else : ?>

                        <button
                            class="btn btn-success btn-sm"
                            disabled>

                            <i class="bi bi-check-circle-fill me-1"></i>

                            Cover Aktif

                        </button>

                    <?php endif; ?>

                    <!-- Delete -->

                    <?php if ((int)$photo['is_cover'] === 0) : ?>

                        <a
                            href="hapus.php?id=<?= $idBanner; ?>&foto=<?= $photo['id_foto_banner']; ?>"
                            class="btn btn-outline-danger btn-sm btn-delete">

                            <i class="bi bi-trash me-1"></i>

                            Hapus

                        </a>

                    <?php else : ?>

                        <button
                            class="btn btn-outline-secondary btn-sm"
                            disabled>

                            <i class="bi bi-lock-fill me-1"></i>

                            Tidak Bisa Dihapus

                        </button>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

<?php endforeach; ?>

</div>

<?php if (empty($photos)) : ?>

<div class="row">

    <div class="col-12">

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <div
                    class="rounded-circle bg-light d-inline-flex justify-content-center align-items-center mb-4"
                    style="width:100px;height:100px;">

                    <i
                        class="bi bi-images text-warning"
                        style="font-size:48px;">
                    </i>

                </div>

                <h4 class="fw-bold mb-2">

                    Belum Ada Foto Banner

                </h4>

                <p class="text-muted mb-4">

                    Banner ini belum memiliki slide.
                    <br>

                    Upload foto pertama untuk mulai menampilkan banner di halaman website.

                </p>

                <label
                    for="foto"
                    class="btn btn-warning px-4">

                    <i class="bi bi-upload me-2"></i>

                    Upload Foto

                </label>

            </div>

        </div>

    </div>

</div>

<?php endif; ?>

        </div>

    </div>

</main>
<?php
require_once "../../../../includes/footer.php";
require_once "../../../../includes/scripts.php";
?>

<?php if (isset($_SESSION['berhasil'])) : ?>

<script>

Swal.fire({

    icon: 'success',

    title: 'Berhasil',

    text: <?= json_encode($_SESSION['berhasil']); ?>,

    confirmButtonColor: '#f59e0b'

});

</script>

<?php unset($_SESSION['berhasil']); ?>

<?php endif; ?>


<?php if (isset($_SESSION['gagal'])) : ?>

<script>

Swal.fire({

    icon: 'error',

    title: 'Gagal',

    text: <?= json_encode($_SESSION['gagal']); ?>,

    confirmButtonColor: '#dc3545'

});

</script>

<?php unset($_SESSION['gagal']); ?>

<?php endif; ?>

<script>

document.addEventListener('DOMContentLoaded', function () {

    // =====================================================
    // Preview Banner
    // =====================================================

    document.querySelectorAll('.btn-preview').forEach(button => {

        button.addEventListener('click', function () {

            Swal.fire({

                imageUrl: this.dataset.image,

                imageAlt: 'Preview Banner',

                width: 900,

                background: '#ffffff',

                showCloseButton: true,

                showConfirmButton: false

            });

        });

    });

    // =====================================================
    // Jadikan Cover
    // =====================================================

    document.querySelectorAll('.btn-cover').forEach(button => {

        button.addEventListener('click', function (e) {

            e.preventDefault();

            Swal.fire({

                title: 'Jadikan Cover?',

                text: 'Foto ini akan dijadikan cover utama banner.',

                icon: 'question',

                showCancelButton: true,

                confirmButtonColor: '#f59e0b',

                cancelButtonColor: '#6c757d',

                confirmButtonText: 'Ya, Jadikan',

                cancelButtonText: 'Batal',

                reverseButtons: true

            }).then((result) => {

                if (result.isConfirmed) {

                    window.location.href = this.href;

                }

            });

        });

    });

    // =====================================================
    // Hapus Foto
    // =====================================================

    document.querySelectorAll('.btn-delete').forEach(button => {

        button.addEventListener('click', function (e) {

            e.preventDefault();

            Swal.fire({

                title: 'Hapus Foto?',

                text: 'Foto banner yang dihapus tidak dapat dikembalikan.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#dc3545',

                cancelButtonColor: '#6c757d',

                confirmButtonText: 'Ya, Hapus',

                cancelButtonText: 'Batal',

                reverseButtons: true

            }).then((result) => {

                if (result.isConfirmed) {

                    window.location.href = this.href;

                }

            });

        });

    });

});

</script>