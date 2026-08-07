<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$menu = "banner";

// Cek login admin
if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";

// Mengambil data banner
$id_banner = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = mysqli_query($conn, "
    SELECT *
    FROM banner
    WHERE id_banner = '$id_banner'
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {
    $_SESSION['gagal'] = "Data banner tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$banner = mysqli_fetch_assoc($query);

require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";
?>

<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h2 class="fw-bold mb-1">
                        Edit Banner
                    </h2>

                    <p class="text-muted mb-0">
                        Perbarui informasi banner website.
                    </p>

                </div>

            </div>

        </div>

    </div>

    <div class="container-fluid">

        <form action="proses_edit.php" method="POST">

            <input
                type="hidden"
                name="id_banner"
                value="<?= $banner['id_banner']; ?>">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>
                        Informasi Banner
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row justify-content-center">

                        <div class="col-lg-8">

                            <!-- Form Banner -->
                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Judul Banner
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="judul"
                                    class="form-control"
                                    maxlength="100"
                                    autocomplete="off"
                                    placeholder="Contoh: Selamat Datang di Politeknik Nest"
                                    value="<?= htmlspecialchars($_SESSION['old']['judul'] ?? $banner['judul']); ?>"
                                    required>

                            </div>

                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Subjudul
                                </label>

                                <input
                                    type="text"
                                    name="subjudul"
                                    class="form-control"
                                    maxlength="150"
                                    autocomplete="off"
                                    placeholder="Contoh: Kampus Vokasi Berbasis Industri"
                                    value="<?= htmlspecialchars($_SESSION['old']['subjudul'] ?? $banner['subjudul']); ?>">

                            </div>

                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Deskripsi
                                </label>

                                <textarea
                                    name="deskripsi"
                                    rows="3"
                                    maxlength="255"
                                    class="form-control"
                                    placeholder="Masukkan deskripsi banner..."><?= htmlspecialchars($_SESSION['old']['deskripsi'] ?? $banner['deskripsi']); ?></textarea>

                                <div class="form-text">
                                    Maksimal 255 karakter.
                                </div>

                            </div>

                            <div class="mb-4">

                                <label class="form-label fw-semibold d-block">
                                    Status Banner
                                </label>

                                <div class="form-check form-check-inline">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="status"
                                        id="aktif"
                                        value="aktif"
                                        <?= (($_SESSION['old']['status'] ?? $banner['status']) == 'aktif') ? 'checked' : ''; ?>>

                                    <label class="form-check-label" for="aktif">
                                        Aktif
                                    </label>

                                </div>

                                <div class="form-check form-check-inline">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="status"
                                        id="nonaktif"
                                        value="nonaktif"
                                        <?= (($_SESSION['old']['status'] ?? $banner['status']) == 'nonaktif') ? 'checked' : ''; ?>>

                                    <label class="form-check-label" for="nonaktif">
                                        Nonaktif
                                    </label>

                                </div>

                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end">

                                <a href="index.php" class="btn btn-secondary me-2">
                                    <i class="bi bi-arrow-left-circle me-1"></i>
                                    Kembali
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Simpan Perubahan
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

</main>

<?php
require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";
?>

<?php if (isset($_SESSION['gagal'])) : ?>

<script>
document.addEventListener("DOMContentLoaded", function () {

    Swal.fire({
        icon: "error",
        title: "Gagal",
        text: "<?= $_SESSION['gagal']; ?>",
        confirmButtonColor: "#0d6efd"
    });

});
</script>

<?php
unset($_SESSION['gagal']);
endif;
?>