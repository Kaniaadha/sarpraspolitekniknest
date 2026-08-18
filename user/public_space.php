<?php

session_start();

require_once '../config/database.php';

$baseUrl = "../";
$currentPage = "public_space";


/* ==========================================================
   DATA PUBLIC SPACE
========================================================== */

$sql = "
    SELECT
        l.id_lantai,
        l.id_lokasi,
        l.kode_lantai,
        l.nama_lantai,
        l.nomor_lantai,

        lok.kode_lokasi,
        lok.nama_lokasi,

        p.id_public_space,
        p.kode_public_space,
        p.nama_public_space,
        p.luas,
        p.deskripsi,
        p.status AS status_public_space,

        fp.nama_file AS foto_public_space

    FROM lantai l

    INNER JOIN lokasi lok
        ON lok.id_lokasi = l.id_lokasi

    LEFT JOIN public_space p
        ON p.id_lantai = l.id_lantai
        AND p.status = 'Aktif'

    LEFT JOIN foto_public_space fp
        ON fp.id_public_space = p.id_public_space
        AND fp.is_cover = 1

    WHERE
        l.status = 'Aktif'
        AND lok.status = 'Aktif'

    ORDER BY
        lok.id_lokasi ASC,
        l.nomor_lantai ASC,
        p.nama_public_space ASC
";

$query = mysqli_query($conn, $sql);

$lantaiList = [];

if ($query) {

    while ($row = mysqli_fetch_assoc($query)) {

        $idLantai = $row['id_lantai'];

        if (!isset($lantaiList[$idLantai])) {

            $lantaiList[$idLantai] = [
                'id_lantai'   => $row['id_lantai'],
                'id_lokasi'   => $row['id_lokasi'],
                'kode_lantai' => $row['kode_lantai'],
                'nama_lantai' => $row['nama_lantai'],
                'nomor_lantai'=> $row['nomor_lantai'],
                'kode_lokasi' => $row['kode_lokasi'],
                'nama_lokasi' => $row['nama_lokasi'],
                'public_space'=> []
            ];
        }

        if (!empty($row['id_public_space'])) {

            $lantaiList[$idLantai]['public_space'][] = [
                'id_public_space'    => $row['id_public_space'],
                'kode_public_space'  => $row['kode_public_space'],
                'nama_public_space'  => $row['nama_public_space'],
                'luas'               => $row['luas'],
                'deskripsi'          => $row['deskripsi'],
                'foto_public_space'  => $row['foto_public_space']
            ];
        }
    }
}


$totalPublicSpace = 0;

foreach ($lantaiList as $lantai) {
    $totalPublicSpace += count($lantai['public_space']);
}

?>

<!DOCTYPE html>
<html lang="id">

<?php include '../includes/user/header.php'; ?>

<body>

<?php include '../includes/user/navbar.php'; ?>


<style>

/* ==========================================================
   PAGE
========================================================== */

.public-page {
    background: #fff8fc;
    min-height: 100vh;
}


/* ==========================================================
   HERO
========================================================== */

.page-hero {
    background: linear-gradient(
        135deg,
        #EC4899 0%,
        #FF7A45 100%
    );

    padding: 65px 0 110px;
    color: #fff;
}

.breadcrumb-custom {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

.breadcrumb-custom a {
    color: #fff;
    text-decoration: none;
}

.hero-badge {
    display: inline-flex;
    gap: 8px;
    padding: 9px 17px;
    margin-bottom: 16px;
    border-radius: 50px;
    background: rgba(255,255,255,.18);
    color: #fff;
    font-size: 14px;
    font-weight: 600;
}

.hero-title {
    margin: 0 0 12px;
    font-size: 3rem;
    font-weight: 800;
}

.hero-description {
    max-width: 650px;
    margin-bottom: 28px;
    color: rgba(255,255,255,.92);
    line-height: 1.7;
}


/* ==========================================================
   SEARCH
========================================================== */

.search-box {
    position: relative;
    max-width: 560px;
}

.search-box i {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #EC4899;
}

.search-box input {
    width: 100%;
    height: 55px;
    padding: 0 20px 0 50px;
    border: none;
    outline: none;
    border-radius: 50px;
}/* ==========================================================
   TOTAL PUBLIC SPACE
========================================================== */

.public-total {
    margin-top: -55px;
    margin-bottom: 45px;
    position: relative;
    z-index: 5;
}

.public-total-card {
    display: flex;
    align-items: center;
    gap: 18px;
    background: #fff;
    border-radius: 22px;
    padding: 22px 26px;
    box-shadow: 0 15px 35px rgba(0,0,0,.08);
}

.public-total-icon {
    width: 58px;
    height: 58px;
    flex-shrink: 0;
    border-radius: 50%;
    background: linear-gradient(
        135deg,
        #EC4899,
        #FF7A45
    );
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    font-size: 22px;
}

.public-total-card h3 {
    margin: 0;
    font-size: 28px;
    font-weight: 800;
    color: #2d3748;
}

.public-total-card p {
    margin: 3px 0 0;
    color: #6b7280;
    font-size: 14px;
}

/* ==========================================================
   CONTENT
========================================================== */

.public-content {
    padding: 65px 0 90px;
}
.section-header {
    margin-bottom: 40px;
}
.section-badge {
    color: #EC4899;
    font-size: 14px;
    font-weight: 700;
}
.section-header h2 {
    margin: 8px 0;
    color: #243653;
    font-size: 30px;
    font-weight: 800;
}
.section-header p {
    margin: 0;
    color: #718096;
}
.section-label {
    color: #EC4899 !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    letter-spacing: 1.5px !important;
}

/* ==========================================================
   LANTAI
========================================================== */

.lantai-section {
    margin-bottom: 45px;
}

.lantai-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}

.lantai-title-wrapper {
    display: flex;
    align-items: center;
    gap: 14px;
}

.lantai-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    background: linear-gradient(
        135deg,
        #EC4899,
        #FF7A45
    );
    color: #fff;
}

.lantai-title-wrapper h2 {
    margin: 0;
    color: #243653;
    font-size: 23px;
    font-weight: 800;
}

.lantai-title-wrapper p {
    margin: 3px 0 0;
    color: #718096;
    font-size: 13px;
}

.lantai-count {
    padding: 8px 14px;
    border-radius: 50px;
    background: #fce7f3;
    color: #EC4899;
    font-size: 12px;
    font-weight: 700;
}


/* ==========================================================
   CARD
========================================================== */

.public-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

.public-card {
    overflow: hidden;
    background: #fff;
    border-radius: 22px;
    box-shadow: 0 12px 30px rgba(0,0,0,.07);
    transition: .3s;
}

.public-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(236,72,153,.14);
}


/* FOTO */

.public-image {
    position: relative;
    height: 175px;
    overflow: hidden;
    background: #fce7f3;
}

.public-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.public-image-placeholder {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #EC4899;
    font-size: 45px;
}

.public-code {
    position: absolute;
    top: 14px;
    left: 14px;
    padding: 6px 12px;
    border-radius: 50px;
    background: #fff;
    color: #EC4899;
    font-size: 11px;
    font-weight: 700;
}


/* BODY */

.public-body {
    padding: 18px;
}

.public-body h5 {
    margin: 0 0 6px;
    color: #2d3748;
    font-size: 18px;
    font-weight: 700;
}

.public-location {
    margin-bottom: 15px;
    color: #718096;
    font-size: 12px;
}

.public-location i {
    color: #EC4899;
}

.public-info {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}

.public-info div {
    color: #4b5563;
    font-size: 13px;
}

.public-info i {
    margin-right: 5px;
    color: #EC4899;
}

.public-desc {
    min-height: 42px;
    margin-bottom: 16px;
    color: #718096;
    font-size: 13px;
    line-height: 1.6;
}

.btn-detail {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 42px;
    border-radius: 50px;
    background: linear-gradient(
        135deg,
        #EC4899,
        #FF7A45
    );
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
}


/* ==========================================================
   RESPONSIVE
========================================================== */

@media (max-width: 992px) {
    .public-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .public-grid {
        grid-template-columns: 1fr;
    }

    .lantai-header {
        align-items: flex-start;
        gap: 15px;
        flex-direction: column;
    }

    .hero-title {
        font-size: 2.2rem;
    }
}

/* ==================================================
   RESPONSIVE MOBILE - 2 CARD SEBARIS
================================================== */

@media (max-width: 576px) {

    .public-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .public-card {
        border-radius: 16px;
    }

    .public-image {
        height: 120px;
    }

    .public-code {
        top: 6px;
        left: 6px;
        padding: 4px 8px;
        font-size: 9px;
    }

    .public-body {
        padding: 12px;
    }

    .public-body h5 {
        font-size: 14px;
        line-height: 1.2;
        margin-bottom: 5px;
    }

    .public-location {
        font-size: 10px;
        margin-bottom: 9px;
    }

    .public-info {
        gap: 6px;
        margin-bottom: 10px;
    }

    .public-info div {
        font-size: 10px;
    }

    .public-desc {
        font-size: 10px;
        line-height: 1.4;
        margin-bottom: 10px;
    }

    .btn-detail {
        height: 34px;
        font-size: 11px;
        gap: 4px;
    }

}
</style>


<main class="public-page">


<!-- ==========================================================
     HERO
========================================================== -->

<section class="page-hero">

    <div class="container">

        <div class="breadcrumb-custom">

            <a href="<?= $baseUrl; ?>index.php">
                Beranda
            </a>

            <span>/</span>

            <span>
                Public Space
            </span>

        </div>


        <span class="hero-badge">

            <i class="bi bi-buildings-fill"></i>

            Data Public Space

        </span>


        <h1 class="hero-title">
            Semua Public Space
        </h1>


        <p class="hero-description">

            Jelajahi berbagai public space yang tersedia
            di lingkungan Politeknik Nest.

        </p>


        <div class="search-box">

            <i class="bi bi-search"></i>

            <input
                type="text"
                id="searchPublic"
                placeholder="Cari public space...">

        </div>

    </div>

</section>



<!-- ==========================================================
     TOTAL
========================================================== -->

<section class="public-total">

    <div class="container">

        <div class="public-total-card">

            <div class="public-total-icon">
                <i class="bi bi-buildings-fill"></i>
            </div>

            <div>

                <h3>
                    <?= $totalPublicSpace; ?>
                </h3>

                <p>
                    Total public space aktif
                </p>

            </div>

        </div>

    </div>

</section>



<!-- ==========================================================
     DAFTAR PUBLIC SPACE
========================================================== -->

<section class="public-content">

    <div class="container">

        <div class="section-header">

            <span class="section-label">
                DATA PUBLIC SPACE

            </span>

            <h2>
                Daftar Public Space
            </h2>

            <p>
                Pilih public space berdasarkan lantai
                untuk melihat informasi lengkap.
            </p>

        </div>


        <div id="lantaiContainer">


            <?php foreach ($lantaiList as $lantai): ?>


                <?php
                $jumlahPublic = count(
                    $lantai['public_space']
                );
                ?>


                <?php if ($jumlahPublic > 0): ?>


                    <div
                        class="lantai-section"
                        data-lokasi="<?= strtolower(
                            htmlspecialchars(
                                $lantai['nama_lokasi']
                            )
                        ); ?>"
                        data-lantai="<?= strtolower(
                            htmlspecialchars(
                                $lantai['nama_lantai']
                            )
                        ); ?>">


                        <!-- HEADER LANTAI -->

                        <div class="lantai-header">

                            <div class="lantai-title-wrapper">

                                <div class="lantai-icon">

                                    <i class="bi bi-layers-fill"></i>

                                </div>

                                <div>

                                    <h2>

                                        <?= htmlspecialchars(
                                            $lantai['nama_lantai']
                                        ); ?>

                                    </h2>

                                    <p>

                                        <?= htmlspecialchars(
                                            $lantai['nama_lokasi']
                                        ); ?>

                                        •

                                        <?= htmlspecialchars(
                                            $lantai['kode_lantai']
                                        ); ?>

                                    </p>

                                </div>

                            </div>


                            <span class="lantai-count">

                                <?= $jumlahPublic; ?>

                                Public Space

                            </span>

                        </div>


                        <!-- CARD -->

                        <div class="public-grid">


                            <?php foreach (
                                $lantai['public_space']
                                as $public
                            ): ?>


                                <?php

                                $foto = !empty(
                                    $public[
                                        'foto_public_space'
                                    ]
                                )

                                ? $baseUrl .
                                  'assets/uploads/public_space/' .
                                  $public[
                                      'foto_public_space'
                                  ]

                                : $baseUrl .
                                  'assets/img/no-image.png';

                                ?>


                                <div
                                    class="public-card"
                                    data-search="<?= strtolower(
                                        htmlspecialchars(
                                            $public[
                                                'nama_public_space'
                                            ] .
                                            ' ' .
                                            $public[
                                                'kode_public_space'
                                            ] .
                                            ' ' .
                                            $lantai[
                                                'nama_lantai'
                                            ] .
                                            ' ' .
                                            $lantai[
                                                'nama_lokasi'
                                            ]
                                        )
                                    ); ?>">


                                    <!-- FOTO -->

                                    <div class="public-image">

                                        <?php if (
                                            !empty(
                                                $public[
                                                    'foto_public_space'
                                                ]
                                            )
                                        ): ?>

                                            <img
                                                src="<?= htmlspecialchars(
                                                    $foto
                                                ); ?>"
                                                alt="<?= htmlspecialchars(
                                                    $public[
                                                        'nama_public_space'
                                                    ]
                                                ); ?>">

                                        <?php else: ?>

                                            <div
                                                class="public-image-placeholder">

                                                <i class="bi bi-tree-fill"></i>

                                            </div>

                                        <?php endif; ?>


                                        <span class="public-code">

                                            <?= htmlspecialchars(
                                                $public[
                                                    'kode_public_space'
                                                ]
                                            ); ?>

                                        </span>

                                    </div>


                                    <!-- BODY -->

                                    <div class="public-body">

                                        <h5>

                                            <?= htmlspecialchars(
                                                $public[
                                                    'nama_public_space'
                                                ]
                                            ); ?>

                                        </h5>


                                        <div class="public-location">

                                            <i class="bi bi-geo-alt-fill"></i>

                                            <?= htmlspecialchars(
                                                $lantai[
                                                    'nama_lokasi'
                                                ]
                                            ); ?>

                                            •

                                            <?= htmlspecialchars(
                                                $lantai[
                                                    'nama_lantai'
                                                ]
                                            ); ?>

                                        </div>


                                        <div class="public-info">

                                            <div>

                                                <i class="bi bi-rulers"></i>

                                                <?= htmlspecialchars(
                                                    $public['luas']
                                                ); ?>

                                                m²

                                            </div>


                                            <div>

                                                <i class="bi bi-hash"></i>

                                                <?= htmlspecialchars(
                                                    $public[
                                                        'kode_public_space'
                                                    ]
                                                ); ?>

                                            </div>

                                        </div>


                                        <?php if (
                                            !empty(
                                                $public['deskripsi']
                                            )
                                        ): ?>

                                            <p class="public-desc">

                                                <?= htmlspecialchars(
                                                    mb_strimwidth(
                                                        strip_tags(
                                                            $public[
                                                                'deskripsi'
                                                            ]
                                                        ),
                                                        0,
                                                        90,
                                                        "..."
                                                    )
                                                ); ?>

                                            </p>

                                        <?php endif; ?>


                                        <a
                                            href="detail_public_space.php?id=<?= $public['id_public_space']; ?>"
                                            class="btn-detail">

                                            Lihat Detail

                                            <i class="bi bi-arrow-right"></i>

                                        </a>

                                    </div>

                                </div>


                            <?php endforeach; ?>


                        </div>

                    </div>


                <?php endif; ?>


            <?php endforeach; ?>


        </div>

    </div>

</section>


</main>


<script>

const search = document.getElementById('searchPublic');

search.addEventListener('input', function () {

    const keyword = this.value.toLowerCase();

    document.querySelectorAll('.lantai-section')
        .forEach(function (lantai) {

            let ada = false;

            lantai.querySelectorAll('.public-card')
                .forEach(function (card) {

                    const cocok =
                        card.dataset.search
                        .includes(keyword);

                    card.style.display =
                        cocok ? '' : 'none';

                    if (cocok) ada = true;

                });

            lantai.style.display =
                ada ? '' : 'none';

        });

});

</script>


<?php include '../includes/user/footer.php'; ?>

</body>
</html>