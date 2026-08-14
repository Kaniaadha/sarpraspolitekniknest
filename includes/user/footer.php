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

                        <a href="<?= $baseUrl; ?>user/lapor.php">

                            Lapor 

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
                                href="mailto:marketing@politekniknest.ac.id"
                                
                                style="color: inherit; text-decoration: none;">

                                marketing@politekniknest.ac.id

                            </a>

                        </span>

                    </li>


                    <!-- TELEPON -->

                    <li>

                        <i class="bi bi-telephone-fill"></i>

                        <span>

                            <a href="tel:08112951003"
                            
                                style="color: inherit; text-decoration: none;">

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
                                rel="noopener noreferrer"
                                
                                style="color: inherit; text-decoration: none;">

                                politekniknest.ac.id

                            </a>

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