<nav class="app-header navbar navbar-expand bg-white shadow-sm">

<?php

require_once __DIR__ . "/../config/database.php";

$idAdmin = $_SESSION['id_admin'];

$queryAdmin = mysqli_query($conn, "
    SELECT foto
    FROM admin
    WHERE id_admin = '$idAdmin'
");

$dataAdmin = mysqli_fetch_assoc($queryAdmin);

$fotoNavbar = BASE_URL . "/assets/uploads/admin/" . $dataAdmin['foto'];

if (
    !empty($dataAdmin['foto']) &&
    file_exists(__DIR__ . "/../assets/uploads/admin/" . $dataAdmin['foto'])
) {

    $fotoNavbar = BASE_URL . "/assets/uploads/admin/" . $dataAdmin['foto'];

}

?>

    <div class="container-fluid">

        <ul class="navbar-nav">

            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#">
                    <i class="bi bi-list"></i>
                </a>
            </li>

        </ul>

        <ul class="navbar-nav ms-auto">

            <li class="nav-item dropdown">

                <a class="nav-link dropdown-toggle d-flex align-items-center"
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown">

                    <img
                        src="<?= $fotoNavbar; ?>"
                        alt="Foto Profil"
                        width="36"
                        height="36"
                        class="rounded-circle me-2"
                        style="object-fit: cover; border:2px solid #dee2e6;">

                    <?= $_SESSION['nama_admin']; ?>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item"
                            href="<?= BASE_URL; ?>/admin/profile.php">

                            <i class="bi bi-person"></i>

                            Profil

                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>

                        <a class="dropdown-item text-danger"
                            href="<?= BASE_URL; ?>/logout.php">

                            <i class="bi bi-box-arrow-right"></i>

                            Logout

                        </a>

                    </li>

                </ul>

            </li>

        </ul>

    </div>

</nav>