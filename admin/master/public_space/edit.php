<?php
session_start();

$menu = "public_space";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

$query = mysqli_query($conn, "
    SELECT *
    FROM public_space
    WHERE id_public_space = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: index.php");
    exit;
}

$old = $_SESSION['old'] ?? [];

// Ambil Data Lantai + Lokasi
$lantai = mysqli_query($conn, "
    SELECT
        lantai.id_lantai,
        lantai.nama_lantai,
        lantai.nomor_lantai,
        lokasi.nama_lokasi
    FROM lantai
    INNER JOIN lokasi
        ON lantai.id_lokasi = lokasi.id_lokasi
    WHERE lantai.status = 'Aktif'
    ORDER BY
        lokasi.nama_lokasi ASC,
        lantai.nomor_lantai ASC
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
                    Edit Public Space
                </h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item">Public Space</li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>

            </div>

        </div>

    </div>

    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-header py-3">

                <h5 class="mb-0">
                    <i class="bi bi-pencil-square me-2"></i>
                    Form Edit Public Space
                </h5>

            </div>

            <div class="card-body">

                <form action="proses_edit.php" method="POST">

                    <input
                        type="hidden"
                        name="id_public_space"
                        value="<?= $data['id_public_space']; ?>">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Lantai
                            </label>

                            <select
                                name="id_lantai"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Pilih Lantai --
                                </option>

                                <?php while($row = mysqli_fetch_assoc($lantai)) : ?>

                                    <option
                                        value="<?= $row['id_lantai']; ?>"
                                        <?= (($old['id_lantai'] ?? $data['id_lantai']) == $row['id_lantai']) ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($row['nama_lokasi']); ?>
                                        -
                                        <?= htmlspecialchars($row['nama_lantai']); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Kode Public Space
                            </label>

                            <input
                                type="text"
                                name="kode_public_space"
                                class="form-control"
                                value="<?= htmlspecialchars($old['kode_public_space'] ?? $data['kode_public_space']); ?>"
                                readonly>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Nama Public Space
                            </label>

                            <input
                                type="text"
                                name="nama_public_space"
                                class="form-control"
                                value="<?= htmlspecialchars($old['nama_public_space'] ?? $data['nama_public_space']); ?>"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Luas (m²)
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0.01"
                                name="luas"
                                class="form-control"
                                value="<?= htmlspecialchars($old['luas'] ?? $data['luas']); ?>"
                                required>

                        </div>

                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Deskripsi
                            </label>

                            <textarea
                                name="deskripsi"
                                class="form-control"
                                rows="3"><?= htmlspecialchars($old['deskripsi'] ?? $data['deskripsi']); ?></textarea>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select"
                                required>

                                <option value="Aktif"
                                    <?= (($old['status'] ?? $data['status']) == "Aktif") ? "selected" : ""; ?>>
                                    Aktif
                                </option>

                                <option value="Nonaktif"
                                    <?= (($old['status'] ?? $data['status']) == "Nonaktif") ? "selected" : ""; ?>>
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
                        Simpan Perubahan
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