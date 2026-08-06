<?php
session_start();

// Cek login admin
if (!isset($_SESSION['id_admin'])) {
    header("Location: ../login.php");
    exit;
}

$page = "profile";

// Memuat konfigurasi dan data admin
require_once "../config/database.php";
require_once "../config/config.php";

$id_admin = $_SESSION['id_admin'];

$query = mysqli_query($conn, "
    SELECT *
    FROM admin
    WHERE id_admin = '$id_admin'
");

$data = mysqli_fetch_assoc($query);

require_once "../includes/header.php";
require_once "../includes/navbar.php";
require_once "../includes/sidebar.php";
?>

<div class="app-main">

    <!-- Header -->
    <div class="app-content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">

                    <h3 class="mb-0 fw-bold">
                        Profil Admin
                    </h3>

                </div>

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-end">

                        <li class="breadcrumb-item">

                            <a href="<?= BASE_URL ?>/admin/dashboard.php">
                                Dashboard
                            </a>

                        </li>

                        <li class="breadcrumb-item active">

                            Profil

                        </li>

                    </ol>

                </div>

            </div>

        </div>

    </div>

    <!-- Informasi Profil -->
    <div class="app-content">

        <div class="container-fluid">

            <div class="row justify-content-center">

                <div class="col-lg-10">

                    <div class="card shadow-sm border-0">

                        <div class="card-body">

                            <div class="row">

                                <!-- FOTO -->

                                <div class="col-md-4 text-center border-end">

                                    <?php

                                    $foto = BASE_URL . "/assets/dist/img/user2-160x160.jpg";

                                    if (!empty($data['foto']) &&
                                        file_exists("../assets/uploads/admin/" . $data['foto'])) 
                                        {$foto = BASE_URL . "/assets/uploads/admin/" . $data['foto'];}?>

                                    <img
                                        src="<?= $foto; ?>"
                                        class="rounded-circle shadow"
                                        style="
                                            width:180px;
                                            height:180px;
                                            object-fit:cover;">

                                    <h4 class="fw-bold mt-4 mb-1">

                                        <?= htmlspecialchars($data['nama_admin']); ?>

                                    </h4>

                                    <?php
                                    if ($data['status'] == "Aktif") {
                                        $badge = "success";
                                    } else {
                                        $badge = "danger";
                                    }
                                    ?>

                                    <span class="badge bg-<?= $badge ?> fs-6 px-3 py-2">

                                        <?= $data['status']; ?>

                                    </span>

                                    <hr>

                                    <small class="text-muted">

                                        Administrator Sistem

                                    </small>

                                </div>

                                <!-- DATA -->

                                <div class="col-md-8">

                                    <h4 class="fw-bold mb-4">

                                        Informasi Akun

                                    </h4>

                                    <div class="row mb-3">

                                        <div class="col-md-4">

                                            <label class="fw-semibold">

                                                Nama Admin

                                            </label>

                                        </div>

                                        <div class="col-md-8">

                                            <div class="form-control bg-light">

                                                <?= htmlspecialchars($data['nama_admin']); ?>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row mb-3">

                                        <div class="col-md-4">

                                            <label class="fw-semibold">

                                                Username

                                            </label>

                                        </div>

                                        <div class="col-md-8">

                                            <div class="form-control bg-light">

                                                <?= htmlspecialchars($data['username']); ?>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row mb-3">

                                        <div class="col-md-4">

                                            <label class="fw-semibold">

                                                Email

                                            </label>

                                        </div>

                                        <div class="col-md-8">

                                            <div class="form-control bg-light">

                                                <?=
                                                !empty($data['email'])
                                                    ? htmlspecialchars($data['email'])
                                                    : '-';
                                                ?>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row mb-3">

                                        <div class="col-md-4">

                                            <label class="fw-semibold">

                                                Nomor HP

                                            </label>

                                        </div>

                                        <div class="col-md-8">

                                            <div class="form-control bg-light">

                                                <?=
                                                !empty($data['no_hp'])
                                                    ? htmlspecialchars($data['no_hp'])
                                                    : '-';
                                                ?>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row mb-3">

                                        <div class="col-md-4">

                                            <label class="fw-semibold">

                                                Status

                                            </label>

                                        </div>

                                        <div class="col-md-8">

                                            <div class="form-control bg-light">

                                                <?= htmlspecialchars($data['status']); ?>

                                            </div>

                                        </div>

                                    </div>

                                    <hr class="my-4">

                                    <div class="row mb-3">

                                        <div class="col-md-4">

                                            <label class="fw-semibold">

                                                Dibuat Pada

                                            </label>

                                        </div>

                                        <div class="col-md-8">

                                            <div class="form-control bg-light">

                                                <?= date('d F Y H:i', strtotime($data['created_at'])); ?>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row mb-4">

                                        <div class="col-md-4">

                                            <label class="fw-semibold">

                                                Terakhir Diubah

                                            </label>

                                        </div>

                                        <div class="col-md-8">

                                            <div class="form-control bg-light">

                                                <?= date('d F Y H:i', strtotime($data['updated_at'])); ?>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="d-flex justify-content-between">

                                        <a href="<?= BASE_URL ?>/admin/dashboard.php"
                                            class="btn btn-secondary">

                                            <i class="bi bi-arrow-left-circle"></i>

                                            Kembali

                                        </a>

                                        <a href="<?= BASE_URL ?>/admin/setting/admin.php"
                                            class="btn btn-primary">

                                            <i class="bi bi-pencil-square"></i>

                                            Edit Profil

                                        </a>

                                    </div>

                                </div>
                                

                            </div>
                            

                        </div>
                        

                    </div>
                    

                </div>
                

            </div>
            

        </div>
        

    </div>
    

</div>


<?php
require_once "../includes/footer.php";
require_once "../includes/scripts.php";
?>