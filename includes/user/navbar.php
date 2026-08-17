<nav class="navbar navbar-expand-lg navbar-custom sticky-top">

    <div class="container">


        <!-- ==========================
             LOGO
        =========================== -->

        <a
            class="navbar-brand d-flex align-items-center"
            href="<?= $baseUrl; ?>index.php">

            <img
                src="<?= $baseUrl; ?>assets/img/logo/logo-polnest.png"
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



        <!-- ==========================
             TOGGLE MOBILE
        =========================== -->

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu"
            aria-controls="navbarMenu"
            aria-expanded="false"
            aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>



        <!-- ==========================
             MENU
        =========================== -->

        <div
            class="collapse navbar-collapse justify-content-end"
            id="navbarMenu">

            <ul class="navbar-nav align-items-lg-center">


                <!-- BERANDA -->

                <li class="nav-item">

                    <a
                        class="nav-link <?= ($currentPage == 'home') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>index.php">

                        Beranda

                    </a>

                </li>



                <!-- GEDUNG -->

                <li class="nav-item">

                    <a
                        class="nav-link <?= ($currentPage == 'gedung') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>user/gedung.php">

                        Gedung

                    </a>

                </li>



                <!-- RUANGAN -->

                <li class="nav-item">

                    <a
                        class="nav-link <?= ($currentPage == 'ruangan') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>user/ruangan.php">

                        Ruangan

                    </a>

                </li>



                <!-- PUBLIC SPACE -->

                <li class="nav-item">

                    <a
                        class="nav-link <?= ($currentPage == 'public_space') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>user/public_space.php">

                        Public Space

                    </a>

                </li>



                <!-- INVENTARIS -->

                <li class="nav-item">

                    <a
                        class="nav-link <?= ($currentPage == 'inventaris') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>user/inventaris.php">

                        Inventaris

                    </a>

                </li>



                <!-- PEMINJAMAN -->

                <li class="nav-item">

                    <a
                        class="nav-link <?= ($currentPage == 'peminjaman') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>user/peminjaman/index.php">

                        Peminjaman

                    </a>

                </li>



                <!-- LAPOR -->

                <li class="nav-item">

                    <a
                        class="nav-link <?= ($currentPage == 'lapor') ? 'active' : ''; ?>"
                        href="<?= $baseUrl; ?>user/lapor.php">

                        Lapor

                    </a>

                </li>


            </ul>

        </div>

    </div>

</nav>