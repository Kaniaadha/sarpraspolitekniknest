<?php
session_start();
$old = $_SESSION['old'] ?? [];
$menu = "admin";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";
require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";
?>

<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="fw-bold mb-0">
                    Tambah Admin
                </h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item">Admin</li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>

            </div>

        </div>
    </div>

    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-header py-3">

                <h5 class="mb-0">
                    <i class="bi bi-person-plus-fill me-2"></i>
                    Form Tambah Admin
                </h5>

            </div>

            <div class="card-body">

                <form action="proses_tambah.php" method="POST">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Admin</label>
                            <input
                                type="text"
                                name="nama_admin"
                                class="form-control"
                                placeholder="Masukkan Nama Admin"
                                value="<?= htmlspecialchars($old['nama_admin'] ?? '') ?>"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                placeholder="Masukkan Username"
                                value="<?= htmlspecialchars($old['username'] ?? '') ?>"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Masukkan Email"
                                value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">No HP</label>
                            <input
                                type="tel"
                                name="no_hp"
                                class="form-control"
                                placeholder="Masukkan No HP"
                                value="<?= htmlspecialchars($old['no_hp'] ?? '') ?>"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Masukkan Password"
                                required>

                        <div class="alert alert-info mt-2 mb-0 py-2">
                            <small>
                                Password minimal <b>8 karakter</b>,
                                mengandung <b>huruf besar</b>,
                                <b>huruf kecil</b>, dan
                                <b>angka</b>.
                            </small>
                        </div>

                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Konfirmasi Password
                            </label>

                            <input
                                type="password"
                                name="konfirmasi_password"
                                class="form-control"
                                placeholder="Masukkan Konfirmasi Password"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Status</label>

                            <select
                                name="status"
                                class="form-select"
                                required>

                            <option value="">-- Pilih Status --</option>

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
<?php unset($_SESSION['old']); ?>
<?php
require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";
?>