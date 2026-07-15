<?php
session_start();

$menu = "kategori";

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
    FROM kategori
    WHERE id_kategori = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: index.php");
    exit;
}

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
                    Edit Kategori
                </h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item">Kategori</li>
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
                    Form Edit Kategori
                </h5>

            </div>

            <div class="card-body">

                <form action="proses_edit.php" method="POST">

                    <input
                        type="hidden"
                        name="id_kategori"
                        value="<?= $data['id_kategori']; ?>">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Kode Kategori
                            </label>

                            <input
                                type="text"
                                name="kode_kategori"
                                class="form-control"
                                value="<?= htmlspecialchars($old['kode_kategori'] ?? $data['kode_kategori']); ?>"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Nama Kategori
                            </label>

                            <input
                                type="text"
                                name="nama_kategori"
                                class="form-control"
                                value="<?= htmlspecialchars($old['nama_kategori'] ?? $data['nama_kategori']); ?>"
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