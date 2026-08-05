<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">

    <div class="container">

        <a class="navbar-brand fw-bold" href="<?= $baseUrl; ?>index.php">
            SISARPRAS
        </a>

        <button class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'home') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>index.php">

                        Beranda

                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'gedung') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>user/gedung.php">

                        Gedung

                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'ruangan') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>user/ruangan.php">

                        Ruangan

                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'public_space') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>user/public_space.php">

                        Public Space

                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'inventaris') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>user/inventaris.php">

                        Inventaris

                    </a>
                </li>

                <li class="nav-item ms-lg-3 mt-3 mt-lg-0">

                    <a href="<?= $baseUrl; ?>login.php"
                        class="btn btn-primary">

                        Login

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>