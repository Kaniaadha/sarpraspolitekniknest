<?php
session_start();

$menu = "lokasi";

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
    FROM lokasi
    WHERE id_lokasi = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: index.php");
    exit;
}

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
                    Edit Lokasi
                </h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item">Lokasi</li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>

            </div>

        </div>
    </div>

    <!-- Content -->
    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-header py-3">

                <h5 class="mb-0">
                    <i class="bi bi-pencil-square me-2"></i>
                    Form Edit Lokasi
                </h5>

            </div>

            <div class="card-body">

                <form action="proses_edit.php" method="POST">

                    <input
                        type="hidden"
                        name="id_lokasi"
                        value="<?= $data['id_lokasi']; ?>">

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
                                value="<?= htmlspecialchars($data['kode_lokasi']); ?>"
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
                                value="<?= htmlspecialchars($data['nama_lokasi']); ?>"
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
                                required><?= htmlspecialchars($data['alamat']); ?></textarea>

                        </div>

                        <!-- Deskripsi -->
                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Deskripsi
                            </label>

                            <textarea
                                name="deskripsi"
                                class="form-control"
                                rows="3"><?= htmlspecialchars($data['deskripsi']); ?></textarea>

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

                                <option value="Aktif"
                                    <?= ($data['status'] == "Aktif") ? "selected" : ""; ?>>
                                    Aktif
                                </option>

                                <option value="Tidak Aktif"
                                    <?= ($data['status'] == "Tidak Aktif") ? "selected" : ""; ?>>
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
                        Simpan Perubahan
                    </button>

                </form>

            </div>

        </div>

    </div>

</main>

<?php
require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";
?>