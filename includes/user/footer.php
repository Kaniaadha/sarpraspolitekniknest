<footer class="footer">

    <div class="container">

        <div class="row g-5">

            <div class="col-lg-5">

                <h5>SISARPRAS Politeknik Nest</h5>

                <p>
                    Sistem Informasi Sarana dan Prasarana
                    yang digunakan untuk memudahkan
                    pengelolaan data fasilitas kampus,
                    inventaris, serta proses peminjaman
                    secara digital.
                </p>

            </div>

            <div class="col-lg-3">

                <h5>Menu</h5>

                <ul>

                    <li><a href="<?= $baseUrl; ?>index.php">Beranda</a></li>

                    <li><a href="<?= $baseUrl; ?>user/gedung.php">Gedung</a></li>

                    <li><a href="<?= $baseUrl; ?>user/ruangan.php">Ruangan</a></li>

                    <li><a href="<?= $baseUrl; ?>user/public_space.php">Public Space</a></li>

                    <li><a href="<?= $baseUrl; ?>user/inventaris.php">Inventaris</a></li>

                </ul>

            </div>

            <div class="col-lg-4">

                <h5>Kontak</h5>

                <p><i class="bi bi-geo-alt-fill me-2"></i>Politeknik Nest</p>

                <p><i class="bi bi-envelope-fill me-2"></i>info@polinest.ac.id</p>

                <p><i class="bi bi-telephone-fill me-2"></i>(0271) 000000</p>

            </div>

        </div>

        <hr>

        <div class="text-center">

            <p class="mb-0">

                © <?= date('Y'); ?>

                SISARPRAS Politeknik Nest.

                All Rights Reserved.

            </p>

        </div>

    </div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="<?= $baseUrl; ?>assets/js/user/main.js"></script>

</body>

</html>