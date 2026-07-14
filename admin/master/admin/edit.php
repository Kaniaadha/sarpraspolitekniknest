<?php
session_start();

$menu = "admin";

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
    FROM admin
    WHERE id_admin = '$id'
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
                    Edit Admin
                </h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item">Admin</li>
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
                    Form Edit Admin
                </h5>

            </div>

            <div class="card-body">

                <form action="proses_edit.php" method="POST">

                    <input
                        type="hidden"
                        name="id_admin"
                        value="<?= $data['id_admin']; ?>">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Admin</label>
                            <input
                                type="text"
                                name="nama_admin"
                                class="form-control"
                                value="<?= htmlspecialchars($data['nama_admin']); ?>"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                value="<?= htmlspecialchars($data['username']); ?>"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars($data['email']); ?>"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">No HP</label>
                            <input
                                type="tel"
                                name="no_hp"
                                class="form-control"
                                value="<?= htmlspecialchars($data['no_hp']); ?>"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>

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

                    <!-- Ubah Password -->

                    <div class="form-check mb-3">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="ubahPassword"
                            name="ubah_password"
                            value="1">

                        <label
                            class="form-check-label"
                            for="ubahPassword">

                            Ubah Password

                        </label>

                    </div>

                    <div id="passwordArea" style="display:none;">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Password Baru
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Konfirmasi Password
                                </label>

                                <input
                                    type="password"
                                    name="konfirmasi_password"
                                    class="form-control">

                            </div>

                        </div>

                        <div class="alert alert-info">

                            Password minimal
                            <strong>8 karakter</strong>,
                            mengandung
                            <strong>huruf besar</strong>,
                            <strong>huruf kecil</strong>,
                            dan
                            <strong>angka</strong>.

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

<script>
document.addEventListener("DOMContentLoaded", function () {

    const checkbox = document.getElementById("ubahPassword");
    const passwordArea = document.getElementById("passwordArea");

    checkbox.addEventListener("change", function () {

        passwordArea.style.display = this.checked ? "block" : "none";

    });

});
</script>

<?php
require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";
?>