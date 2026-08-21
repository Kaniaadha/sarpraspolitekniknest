<?php
session_start();

// Cek login admin
if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../login.php");
    exit;
}

$page = "setting-admin";
$menu = "profil";

require_once "../../config/database.php";
require_once "../../config/config.php";

// Mengambil data admin

$id_admin = $_SESSION['id_admin'];

$query = mysqli_query($conn, "
    SELECT *
    FROM admin
    WHERE id_admin='$id_admin'
");

$data = mysqli_fetch_assoc($query);

require_once "../../includes/header.php";
require_once "../../includes/navbar.php";
require_once "../../includes/sidebar.php";
?>

<div class="app-main">

    <div class="app-content-header">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-6">

                    <h3 class="fw-bold">

                        Pengaturan Admin

                    </h3>

                </div>

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-end">

                        <li class="breadcrumb-item">

                            <a href="<?= BASE_URL ?>/admin/dashboard.php">

                                Dashboard

                            </a>

                        </li>

                        <li class="breadcrumb-item">

                            Setting

                        </li>

                        <li class="breadcrumb-item active">

                            Admin

                        </li>

                    </ol>

                </div>

            </div>

        </div>

    </div>

    <div class="app-content">

        <div class="container-fluid">

            <!-- Foto Profil -->

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-body py-5 text-center">

                <?php

                $foto = !empty($data['foto'])
                    ? BASE_URL . "/assets/uploads/admin/" . $data['foto']
                    : BASE_URL . "/assets/dist/img/user2-160x160.jpg";

                ?>
                
                <img
                    id="preview"
                    src="<?= $foto; ?>"
                    class="rounded-circle shadow border"
                    width="110"
                    height="110"
                    style="object-fit:cover;">

                    <h4 class="fw-bold mt-3 mb-1">

                        <?= htmlspecialchars($data['nama_admin']); ?>

                    </h4>

                    <span class="badge bg-success rounded-pill px-3 py-2">

                        <?= $data['status']; ?>

                    </span>

                    <div class="mt-4">

                        <label
                            for="foto"
                            class="btn btn-outline-primary rounded-pill">

                            <i class="bi bi-camera-fill me-2"></i>

                            Ganti Foto 

                        </label>

                    </div>

                    <small class="text-muted d-block mt-2">

                        JPG, JPEG atau PNG (Maksimal 10 MB)

                    </small>

                </div>

            </div>

            <!-- Informasi Admin -->

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="fw-bold mb-0">

                        <i class="bi bi-person-vcard me-2"></i>

                        Informasi Admin

                    </h5>

                </div>

                <div class="card-body">

                    <form
                        action="proses_edit_admin.php"
                        method="POST"
                        enctype="multipart/form-data">

                        <input
                            type="hidden"
                            name="id_admin"
                            value="<?= $data['id_admin']; ?>">
                        
                        <input
                            type="file"
                            id="foto"
                            name="foto"
                            accept=".jpg,.jpeg,.png"
                            hidden>

                        <div class="row">

                            <div class="col-md-6 mb-4">

                                <label class="form-label">

                                    Nama Admin

                                </label>

                                <input
                                    type="text"
                                    name="nama_admin"
                                    class="form-control"
                                    value="<?= htmlspecialchars($data['nama_admin']); ?>"
                                    required>

                            </div>

                            <div class="col-md-6 mb-4">

                                <label class="form-label">

                                    Username

                                </label>

                                <input
                                    type="text"
                                    name="username"
                                    class="form-control"
                                    value="<?= htmlspecialchars($data['username']); ?>"
                                    required>

                            </div>

                            <div class="col-md-6 mb-4">

                                <label class="form-label">

                                    Email

                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="<?= htmlspecialchars($data['email']); ?>">

                            </div>

                            <div class="col-md-6 mb-4">

                                <label class="form-label">

                                    Nomor HP

                                </label>

                                <input
                                    type="text"
                                    name="no_hp"
                                    class="form-control"
                                    value="<?= htmlspecialchars($data['no_hp']); ?>">

                            </div>

                                                    </div>

                        <hr>

                        <div class="d-flex justify-content-end">

                            <button
                                type="submit"
                                class="btn btn-primary px-4">

                                <i class="bi bi-floppy me-2"></i>

                                Simpan

                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <!-- Keamanan Akun -->

            <div class="card shadow-sm border-0 mt-4">

                <div class="card-header bg-white">

                    <h5 class="fw-bold mb-0">

                        <i class="bi bi-shield-lock me-2"></i>

                        Keamanan Akun

                    </h5>

                </div>

                <div class="card-body">

                    <form
                        action="proses_password.php"
                        method="POST">

                        <input
                            type="hidden"
                            name="id_admin"
                            value="<?= $data['id_admin']; ?>">

                        <div class="row">

                            <div class="col-md-12 mb-4">

                                <label class="form-label">

                                    Password Lama

                                </label>

                                <div class="input-group">

                                    <input
                                        type="password"
                                        id="password_lama"
                                        name="password_lama"
                                        class="form-control"
                                        required>

                                    <button
                                        class="btn btn-outline-secondary"
                                        type="button"
                                        onclick="togglePassword('password_lama', this)">

                                        <i class="bi bi-eye"></i>

                                    </button>

                                </div>

                            </div>

                            <div class="col-md-6 mb-4">

                                <label class="form-label">

                                    Password Baru

                                </label>

                                <div class="input-group">

                                    <input
                                        type="password"
                                        id="password_baru"
                                        name="password_baru"
                                        class="form-control"
                                        required>

                                    <button
                                        class="btn btn-outline-secondary"
                                        type="button"
                                        onclick="togglePassword('password_baru', this)">

                                        <i class="bi bi-eye"></i>

                                    </button>

                                </div>

                            </div>

                            <div class="col-md-6 mb-4">

                                <label class="form-label">

                                    Konfirmasi Password

                                </label>

                                <div class="input-group">

                                    <input
                                        type="password"
                                        id="konfirmasi_password"
                                        name="konfirmasi_password"
                                        class="form-control"
                                        required>

                                    <button
                                        class="btn btn-outline-secondary"
                                        type="button"
                                        onclick="togglePassword('konfirmasi_password', this)">

                                        <i class="bi bi-eye"></i>

                                    </button>

                                </div>

                            </div>

                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">

                            <button
                                type="submit"
                                class="btn btn-warning px-4">

                                <i class="bi bi-key-fill me-2"></i>

                                Ubah Password

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

// Preview foto

const foto = document.getElementById('foto');

const preview = document.getElementById('preview');

foto.addEventListener('change', function () {

    const file = this.files[0];

    if (file) {

        preview.src = URL.createObjectURL(file);

    }

});

// Tampilkan / sembunyikan password

function togglePassword(id, button) {

    const input = document.getElementById(id);

    const icon = button.querySelector("i");

    if(input.type === "password"){

        input.type = "text";

        icon.classList.remove("bi-eye");

        icon.classList.add("bi-eye-slash");

    }else{

        input.type = "password";

        icon.classList.remove("bi-eye-slash");

        icon.classList.add("bi-eye");

    }

}

</script>

<?php
require_once "../../includes/footer.php";
require_once "../../includes/scripts.php";
?>
