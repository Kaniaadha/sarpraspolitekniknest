<?php
session_start();

$menu = "kategori";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";
require_once "../../../helpers/generate_kode.php";
$kodeKategori = generateKode(
    $conn,
    "kategori",
    "kode_kategori",
    "KTG"
);
$old = $_SESSION['old'] ?? [];

require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";
?>

<main class="app-main">

    <div class="app-content-header">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="fw-bold mb-0">
                    Tambah Kategori
                </h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item">Kategori</li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>

            </div>

        </div>

    </div>

    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-header py-3">

                <h5 class="mb-0">
                    <i class="bi bi-tags me-2"></i>
                    Form Tambah Kategori
                </h5>

            </div>

            <div class="card-body">

                <form action="proses_tambah.php" method="POST">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Kode Kategori
                            </label>

                            <input
                                type="text"
                                name="kode_kategori"
                                class="form-control"
                                placeholder="Contoh : KTG001"
                               value="<?= htmlspecialchars($old['kode_kategori'] ?? $kodeKategori); ?>"
                                readonly>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Nama Kategori
                            </label>

                            <input
                                type="text"
                                name="nama_kategori"
                                class="form-control"
                                placeholder="Contoh : Komputer"
                                value="<?= htmlspecialchars($old['nama_kategori'] ?? ''); ?>"
                                required>

                        </div>

                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Deskripsi
                            </label>

                            <textarea
                                name="deskripsi"
                                class="form-control"
                                rows="3"><?= htmlspecialchars($old['deskripsi'] ?? ''); ?></textarea>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Pilih Status --
                                </option>

                                <option
                                    value="Aktif"
                                    <?= (($old['status'] ?? '') == "Aktif") ? "selected" : ""; ?>>
                                    Aktif
                                </option>

                                <option
                                    value="Nonaktif"
                                    <?= (($old['status'] ?? '') == "Nonaktif") ? "selected" : ""; ?>>
                                    Nonaktif
                                </option>

                            </select>

                        </div>

                    </div>

                    <hr>

                    <a href="index.php" class="btn btn-secondary">

                        <i class="bi bi-arrow-left"></i>

                        Kembali

                    </a>

                    <button type="submit" class="btn btn-primary">

                        <i class="bi bi-save"></i>

                        Simpan

                    </button>

                </form>

            </div>

        </div>

    </div>

</main>

<?php
unset($_SESSION['old']);

require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";
?>