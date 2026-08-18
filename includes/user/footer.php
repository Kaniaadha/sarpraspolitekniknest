<footer class="footer">

    <div class="container">

        <div class="row gy-5">


            <!-- ==========================
                 BRAND
            =========================== -->

            <div class="col-lg-4">

                <div class="footer-brand">

                    <!-- Logo sebagai akses Admin -->

                    <a
                        href="<?= $baseUrl; ?>admin/dashboard.php"
                        class="footer-admin-logo"
                        title="Admin">

                        <img
                            src="<?= $baseUrl; ?>assets/img/logo/logo-polnest.png"
                            alt="Logo Politeknik Nest"
                            class="footer-logo">

                    </a>


                    <div>

                        <h3>
                            SISARPRAS
                        </h3>

                        <span>
                            Politeknik Nest
                        </span>

                    </div>

                </div>


                <p class="footer-desc">

                    Sistem Informasi Sarana dan Prasarana
                    Politeknik Nest yang digunakan untuk
                    memudahkan pengelolaan gedung,
                    ruangan, public space, inventaris,
                    proses peminjaman, serta pelaporan
                    kerusakan dan kehilangan secara digital.

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


                    <!-- BERANDA -->

                    <li>

                        <a
                            href="<?= $baseUrl; ?>index.php">

                            Beranda

                        </a>

                    </li>


                    <!-- GEDUNG -->

                    <li>

                        <a
                            href="<?= $baseUrl; ?>user/gedung.php">

                            Gedung

                        </a>

                    </li>


                    <!-- RUANGAN -->

                    <li>

                        <a
                            href="<?= $baseUrl; ?>user/ruangan.php">

                            Ruangan

                        </a>

                    </li>


                    <!-- PUBLIC SPACE -->

                    <li>

                        <a
                            href="<?= $baseUrl; ?>user/public_space.php">

                            Public Space

                        </a>

                    </li>


                    <!-- INVENTARIS -->

                    <li>

                        <a
                            href="<?= $baseUrl; ?>user/inventaris.php">

                            Inventaris

                        </a>

                    </li>


                    <!-- PEMINJAMAN -->

                    <li>

                        <a
                            href="<?= $baseUrl; ?>user/peminjaman/index.php">

                            Peminjaman

                        </a>

                    </li>


                    <!-- LAPOR KERUSAKAN -->

                    <li>

                        <a
                            href="<?= $baseUrl; ?>user/lapor_kerusakan.php">

                            Lapor Kerusakan

                        </a>

                    </li>


                    <!-- LAPOR KEHILANGAN -->

                    <li>

                        <a
                            href="<?= $baseUrl; ?>user/lapor/laporan_kehilangan/">

                            Lapor Kehilangan

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


                    <!-- ALAMAT -->

                    <li>

                        <i class="bi bi-geo-alt-fill"></i>

                        <span>

                            Jl. Raya Telukan - Cuplik Km.1,
                            Dkh. Padas Mas Sudimoro
                            Rt 003/010, Kel. Parangjoro,
                            Kec. Grogol ~ Indonesia,
                            Sawah, Parangjoro,
                            Kec. Sukoharjo,
                            Prop, Jawa Tengah 57552

                        </span>

                    </li>


                    <!-- EMAIL -->

                    <li>

                        <i class="bi bi-envelope-fill"></i>

                        <span>

                            <a
                                href="mailto:marketing@politekniknest.ac.id">

                                marketing@politekniknest.ac.id

                            </a>

                        </span>

                    </li>


                    <!-- TELEPON -->

                    <li>

                        <i class="bi bi-telephone-fill"></i>

                        <span>

                            <a
                                href="tel:08112951003">

                                0811 2951003

                            </a>

                        </span>

                    </li>


                    <!-- WEBSITE -->

                    <li>

                        <i class="bi bi-globe"></i>

                        <span>

                            <a
                                href="https://politekniknest.ac.id"
                                target="_blank"
                                rel="noopener noreferrer">

                                politekniknest.ac.id

                            </a>

                        </span>

                    </li>


                </ul>

            </div>



            <!-- ==========================
                 SOCIAL MEDIA
            =========================== -->

            <div class="col-lg-2">

                <h5 class="footer-title">

                    Ikuti Kami

                </h5>


                <div class="footer-social">


                    <!-- INSTAGRAM -->

                    <a
                        href="https://www.instagram.com/politekniknest?igsh=ZXAwdW5rcGljOTFl"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Instagram Politeknik NEST">

                        <i class="bi bi-instagram"></i>

                    </a>


                    <!-- FACEBOOK -->

                    <a
                        href="https://www.facebook.com/share/1GnkpXruPT/?mibextid=wwXIfr"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Facebook Politeknik NEST">

                        <i class="bi bi-facebook"></i>

                    </a>


                    <!-- WEBSITE -->

                    <a
                        href="https://politekniknest.ac.id"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Website Politeknik NEST">

                        <i class="bi bi-globe"></i>

                    </a>


                    <!-- YOUTUBE -->

                    <a
                        href="https://youtube.com/@politekniknest.official?si=MzuuWgkdX3AnxxPq"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="YouTube Politeknik NEST">

                        <i class="bi bi-youtube"></i>

                    </a>


                </div>

            </div>


        </div>



        <!-- ==========================
             DIVIDER
        =========================== -->

        <hr class="footer-divider">



        <!-- ==========================
             COPYRIGHT
        =========================== -->

        <div class="footer-bottom">

            © <?= date('Y'); ?>

            SISARPRAS Politeknik Nest.

            All Rights Reserved.

        </div>


    </div>

</footer>



<!-- ==========================
     BOOTSTRAP
=========================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>



<!-- ==========================
     USER JAVASCRIPT
=========================== -->

<script
    src="<?= $baseUrl; ?>assets/js/user/main.js">
</script>

<style>
    /* Footer - semua link putih */
    .footer a {
        color: #ffffff !important;
        text-decoration: none;
    }

    .footer a:hover {
        color: #ffffff !important;
        text-decoration: none;
    }
</style>
</body>

</html>