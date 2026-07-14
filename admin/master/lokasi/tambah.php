<?php
session_start();

$menu = "lokasi";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

$old = $_SESSION['old'] ?? [];

require_once "../../../config/database.php";
require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";
?>

<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="fw-bold mb-0">
                    Tambah Lokasi
                </h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item">Lokasi</li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>

            </div>

        </div>
    </div>

    <!-- Content -->
    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-header py-3">

                <h5 class="mb-0">
                    <i class="bi bi-geo-alt-fill me-2"></i>
                    Form Tambah Lokasi
                </h5>

            </div>

            <div class="card-body">

                <form action="proses_tambah.php" method="POST">

                    <div class="row">

                        <!-- Kode Lokasi -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Kode Lokasi
                            </label>

                            <input
                                type="text"
                                name="kode_lokasi"
                                class="form-control"
                                placeholder="Contoh : LOC001"
                                value="<?= htmlspecialchars($old['kode_lokasi'] ?? '') ?>"
                                required>

                        </div>

                        <!-- Nama Lokasi -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Nama Lokasi
                            </label>

                            <input
                                type="text"
                                name="nama_lokasi"
                                class="form-control"
                                placeholder="Masukkan Nama Lokasi"
                                value="<?= htmlspecialchars($old['nama_lokasi'] ?? '') ?>"
                                required>

                        </div>

                        <!-- Alamat -->
                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Alamat
                            </label>

                            <textarea
                                name="alamat"
                                class="form-control"
                                rows="3"
                                placeholder="Masukkan Alamat Lokasi"
                                required><?= htmlspecialchars($old['alamat'] ?? '') ?></textarea>

                        </div>

                        <!-- Deskripsi -->
                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Deskripsi
                            </label>

                            <textarea
                                name="deskripsi"
                                class="form-control"
                                rows="3"
                                placeholder="Masukkan Deskripsi (Opsional)"><?= htmlspecialchars($old['deskripsi'] ?? '') ?></textarea>

                        </div>

                        <!-- Status -->
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

                                <option value="Aktif"
                                    <?= (($old['status'] ?? '') == 'Aktif') ? 'selected' : ''; ?>>
                                    Aktif
                                </option>

                                <option value="Tidak Aktif"
                                    <?= (($old['status'] ?? '') == 'Tidak Aktif') ? 'selected' : ''; ?>>
                                    Tidak Aktif
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