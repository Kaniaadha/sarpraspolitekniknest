<?php
session_start();

$menu = "lantai";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

$old = $_SESSION['old'] ?? [];

require_once "../../../config/database.php";
require_once "../../../helpers/generate_kode.php";
$kodeLantai = generateKode(
    $conn,
    "lantai",
    "kode_lantai",
    "LNT"
);
// Ambil data lokasi yang aktif
$lokasi = mysqli_query($conn, "
    SELECT *
    FROM lokasi
    WHERE status = 'Aktif'
    ORDER BY nama_lokasi ASC
");

require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";
?>

<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="fw-bold mb-0">
                    Tambah Lantai
                </h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item">Lantai</li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>

            </div>

        </div>
    </div>

    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-header py-3">

                <h5 class="mb-0">
                    <i class="bi bi-layers-fill me-2"></i>
                    Form Tambah Lantai
                </h5>

            </div>

            <div class="card-body">

                <form action="proses_tambah.php" method="POST">

                    <div class="row">

                        <!-- Lokasi -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Lokasi
                            </label>

                            <select
                                name="id_lokasi"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Pilih Lokasi --
                                </option>

                                <?php while($row = mysqli_fetch_assoc($lokasi)) : ?>

                                    <option
                                        value="<?= $row['id_lokasi']; ?>"
                                        <?= (($old['id_lokasi'] ?? '') == $row['id_lokasi']) ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($row['nama_lokasi']); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>

                        <!-- Kode Lantai -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Kode Lantai
                            </label>

                            <input
                                type="text"
                                name="kode_lantai"
                                class="form-control"
                                placeholder="Contoh : LNT001"
                                value="<?= htmlspecialchars($old['kode_lantai'] ?? $kodeLantai); ?>"
                                readonly>

                        </div>

                        <!-- Nama Lantai -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Nama Lantai
                            </label>

                            <input
                                type="text"
                                name="nama_lantai"
                                class="form-control"
                                placeholder="Contoh : Lantai 1"
                                value="<?= htmlspecialchars($old['nama_lantai'] ?? '') ?>"
                                required>

                        </div>

                        <!-- Nomor Lantai -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Nomor Lantai
                            </label>

                            <input
                                type="number"
                                name="nomor_lantai"
                                class="form-control"
                                placeholder="Contoh : 1"
                                value="<?= htmlspecialchars($old['nomor_lantai'] ?? '') ?>"
                                required>

                            <small class="text-muted">
                                Basement = -1, Lantai Dasar = 0, Lantai 1 = 1, dst.
                            </small>

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