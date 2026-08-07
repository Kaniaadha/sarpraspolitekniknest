<footer class="footer">

    <div class="container">

        <div class="row gy-5">

            <!-- ==========================
                 BRAND
            =========================== -->

            <div class="col-lg-4">

                <div class="footer-brand">

                    <img
                        src="<?= $baseUrl; ?>assets/img/logo/logo-polnest.png"
                        alt="Logo Politeknik Nest"
                        class="footer-logo">

                    <div>

                        <h3>SISARPRAS</h3>

                        <span>Politeknik Nest</span>

                    </div>

                </div>

                <p class="footer-desc">

                    Sistem Informasi Sarana dan Prasarana
                    Politeknik Nest yang digunakan untuk
                    memudahkan pengelolaan gedung,
                    ruangan, public space, inventaris,
                    proses peminjaman, serta pelaporan
                    kerusakan secara digital.

                </p>

            </div>

            <!-- ==========================
                 MENU
            =========================== -->

            <div class="col-lg-3">

                <h5 class="footer-title">

                    Menu

                </h5>

                <ul class="footer-menu">

                    <li>
                        <a href="<?= $baseUrl; ?>index.php">
                            Beranda
                        </a>
                    </li>

                    <li>
                        <a href="<?= $baseUrl; ?>user/gedung.php">
                            Gedung
                        </a>
                    </li>

                    <li>
                        <a href="<?= $baseUrl; ?>user/ruangan.php">
                            Ruangan
                        </a>
                    </li>

                    <li>
                        <a href="<?= $baseUrl; ?>user/public_space.php">
                            Public Space
                        </a>
                    </li>

                    <li>
                        <a href="<?= $baseUrl; ?>user/inventaris.php">
                            Inventaris
                        </a>
                    </li>

                    <li>
                        <a href="<?= $baseUrl; ?>user/peminjaman.php">
                            Peminjaman
                        </a>
                    </li>

                    <li>
                        <a href="<?= $baseUrl; ?>user/lapor_kerusakan.php">
                            Lapor Kerusakan
                        </a>
                    </li>

                </ul>

            </div>

            <!-- ==========================
                 KONTAK
            =========================== -->

            <div class="col-lg-3">

                <h5 class="footer-title">

                    Kontak

                </h5>

                <ul class="footer-contact">

                    <li>

                        <i class="bi bi-geo-alt-fill"></i>

                        <span>

                            Jl. Raya Solo - Sukoharjo,
                            Jawa Tengah

                        </span>

                    </li>

                    <li>

                        <i class="bi bi-envelope-fill"></i>

                        <span>

                            info@polinest.ac.id

                        </span>

                    </li>

                    <li>

                        <i class="bi bi-telephone-fill"></i>

                        <span>

                            (0271) 000000

                        </span>

                    </li>

                </ul>

            </div>

            <!-- ==========================
                 SOCIAL
            =========================== -->

            <div class="col-lg-2">

                <h5 class="footer-title">

                    Ikuti Kami

                </h5>

                <div class="footer-social">

                    <a href="#">

                        <i class="bi bi-instagram"></i>

                    </a>

                    <a href="#">

                        <i class="bi bi-facebook"></i>

                    </a>

                    <a href="#">

                        <i class="bi bi-globe"></i>

                    </a>

                    <a href="#">

                        <i class="bi bi-youtube"></i>

                    </a>

                </div>

            </div>

        </div>

        <hr class="footer-divider">

        <div class="footer-bottom">

            © <?= date('Y'); ?>

            SISARPRAS Politeknik Nest.

            All Rights Reserved.

        </div>

    </div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="<?= $baseUrl; ?>assets/js/user/main.js"></script>

</body>

</html>