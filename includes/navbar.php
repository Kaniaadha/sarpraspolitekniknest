<nav class="app-header navbar navbar-expand bg-white shadow-sm">

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

                <a class="nav-link dropdown-toggle"
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown">

                    <i class="bi bi-person-circle"></i>
                    <?= $_SESSION['nama_admin']; ?>

                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item"
                            href="<?= BASE_URL; ?>/admin/setting/profile.php">

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