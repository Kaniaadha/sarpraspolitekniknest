<nav class="navbar navbar-expand-lg navbar-custom sticky-top">

    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="<?= $baseUrl; ?>index.php">

            <img src="<?= $baseUrl; ?>assets/img/logo/logo-polnest.png"
                alt="Logo Politeknik Nest"
                class="navbar-logo">

            <div class="brand-text">

                <span class="brand-title">
                    SISARPRAS
                </span>

                <span class="brand-subtitle">
                    Politeknik Nest
                </span>

            </div>

        </a>

        <!-- Toggle -->
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse justify-content-end"
            id="navbarMenu">

            <ul class="navbar-nav align-items-lg-center">

                <li class="nav-item">

                    <a
                        class="nav-link <?= ($currentPage == 'home') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>index.php">

                        Beranda

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link <?= ($currentPage == 'gedung') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>user/gedung.php">

                        Gedung

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link <?= ($currentPage == 'ruangan') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>user/ruangan.php">

                        Ruangan

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link <?= ($currentPage == 'public_space') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>user/public_space.php">

                        Public Space

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link <?= ($currentPage == 'inventaris') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>user/inventaris.php">

                        Inventaris

                    </a>

                </li>


                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'peminjaman') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>user/peminjaman/index.php">

                        Peminjaman

                    </a>

                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'lapor') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>user/lapor.php">

                        Lapor

                    </a>

                </li>

                <!-- Login -->

                <li class="nav-item ms-lg-3 mt-3 mt-lg-0">

                    <a
                        href="<?= $baseUrl; ?>login.php"
                        class="btn btn-login">

                        <i class="bi bi-box-arrow-in-right"></i>

                        Login

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>